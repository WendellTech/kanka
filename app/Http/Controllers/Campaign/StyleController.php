<?php

namespace App\Http\Controllers\Campaign;

use App\Facades\CampaignCache;
use App\Facades\Datagrid;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReorderStyles;
use App\Http\Requests\StoreCampaignStyle;
use App\Http\Requests\StoreCampaignTheme;
use App\Models\Campaign;
use App\Models\CampaignStyle;

class StyleController extends Controller
{
    public const MAX_THEMES = 30;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
        $this->middleware('campaign.boosted', ['except' => 'index']);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Campaign $campaign)
    {
        $this->authorize('recover', $campaign);
        $styles = $campaign->styles()
            ->sort(request()->only(['o', 'k']))
            ->take(self::MAX_THEMES)
            ->paginate(10);
        Datagrid::layout(\App\Renderers\Layouts\Campaign\Theme::class)
            ->route('campaign_styles.index', ['campaign' => $campaign])
            ->permissions(false);

        // Ajax Datagrid
        if (request()->ajax()) {
            $html = view('campaigns.styles._table')->with('styles', $styles)->render();
            $deletes = view('layouts.datagrid.delete-forms')->with('models', Datagrid::deleteForms())->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'deletes' => $deletes,
            ]);
        }

        $theme = $campaign->theme;
        $reorderStyles = $campaign->styles()->defaultOrder()->take(self::MAX_THEMES)->get();

        return view('campaigns.styles.index', compact('campaign', 'styles', 'theme', 'reorderStyles'));
    }

    public function show(Campaign $campaign, CampaignStyle $campaignStyle)
    {
        return redirect()
            ->route('campaign_styles.index');
    }

    public function create(Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        if ($campaign->styles()->count() >= self::MAX_THEMES) {
            return redirect()->route('campaign_styles.index')
                ->with('error', __('campaigns/styles.errors.max_reached', ['max' => self::MAX_THEMES]));
        }
        return view('campaigns.styles.create', compact('campaign'));
    }

    public function store(StoreCampaignStyle $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        if ($campaign->styles()->count() >= self::MAX_THEMES) {
            return redirect()->route('campaign_styles.index')
                ->with('error', __('campaigns/styles.errors.max_reached', ['max' => self::MAX_THEMES]));
        }

        $style = new CampaignStyle($request->only('name', 'content', 'is_enabled'));
        $style->campaign_id = $campaign->id;
        $style->save();
        CampaignCache::clearStyles();

        if ($request->has('submit-update')) {
            return redirect()
                ->route('campaign_styles.edit', [$campaign, $style])
                ->with('success', __('campaigns/styles.create.success', ['name' => $style->name]));
        }
        return redirect()
            ->route('campaign_styles.index', $campaign)
            ->with('success', __('campaigns/styles.create.success'));
    }

    public function edit(Campaign $campaign, CampaignStyle $campaignStyle)
    {
        $this->authorize('update', $campaign);

        $style = $campaignStyle;
        return view('campaigns.styles.edit', compact('campaign', 'style'));
    }

    public function update(StoreCampaignStyle $request, Campaign $campaign, CampaignStyle $campaignStyle)
    {
        $this->authorize('update', $campaign);

        $campaignStyle->update($request->only('name', 'content', 'is_enabled'));
        CampaignCache::clearStyles();

        if ($request->has('submit-update')) {
            return redirect()
                ->route('campaign_styles.edit', [$campaign, $campaignStyle])
                ->with('success', __('campaigns/styles.update.success', ['name' => $campaignStyle->name]));
        }
        return redirect()
            ->route('campaign_styles.index', $campaign)
            ->with('success', __('campaigns/styles.update.success', ['name' => $campaignStyle->name]));
    }

    public function destroy(Campaign $campaign, CampaignStyle $campaignStyle)
    {
        $this->authorize('update', $campaign);

        $campaignStyle->delete();
        CampaignCache::clearStyles();

        return redirect()
            ->route('campaign_styles.index', $campaign)
            ->with('success', __('campaigns/styles.delete.success', ['name' => $campaignStyle->name]));
    }

    public function theme(Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $themes = [null => __('campaigns.themes.none')];
        foreach (\App\Models\Theme::all() as $theme) {
            $themes[$theme->id] = $theme->__toString();
        }

        return view('campaigns.styles.theme', compact('campaign', 'themes'));
    }

    public function themeSave(StoreCampaignTheme $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $campaign->update([
            'theme_id' => $request->get('theme_id')
        ]);

        return redirect()
            ->route('campaign_styles.index', $campaign)
            ->with('success', __('campaigns/styles.theme.success'))
        ;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function bulk(Campaign $campaign)
    {
        $action = request()->get('action');
        $models = request()->get('model');
        if (!in_array($action, ['enable', 'disable', 'delete']) || empty($models)) {
            return redirect()
                ->route('campaign_styles.index', $campaign);
        }

        $count = 0;
        foreach ($models as $id) {
            /** @var CampaignStyle|null $style */
            $style = CampaignStyle::find($id);
            if ($style === null) {
                continue;
            }
            if ($action === 'enable' && !$style->is_enabled) {
                $style->is_enabled = true;
                $style->update();
                $count++;
            } elseif ($action === 'disable' && $style->is_enabled) {
                $style->is_enabled = false;
                $style->update();
                $count++;
            } elseif ($action === 'delete') {
                $style->delete();
                $count++;
            }
        }
        CampaignCache::clearStyles();

        return redirect()
            ->route('campaign_styles.index', $campaign)
            ->with('success', trans_choice('campaigns/styles.bulks.' . $action, $count, ['count' => $count]))
        ;
    }

    public function reorder(ReorderStyles $request, Campaign $campaign)
    {
        $order = 1;
        $ids = $request->get('style');
        foreach ($ids as $id) {
            $style = CampaignStyle::find($id);
            if (empty($style)) {
                continue;
            }
            $style->order = $order;
            $style->timestamps = false;
            $style->update();
            $order++;
        }
        CampaignCache::clearStyles();

        $order--;
        return redirect()
            ->route('campaign_styles.index', $campaign)
            ->with('success', trans_choice('campaigns/styles.reorder.success', $order, ['count' => $order]))
        ;
    }
}
