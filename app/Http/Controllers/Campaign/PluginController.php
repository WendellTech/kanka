<?php

namespace App\Http\Controllers\Campaign;

use App\Facades\CampaignCache;
use App\Facades\Datagrid;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignPlugin;
use App\Models\Plugin;
use App\Services\Campaign\CampaignPluginService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PluginController extends Controller
{
    protected CampaignPluginService $service;

    public function __construct(CampaignPluginService $service)
    {
        $this->middleware('auth', ['except' => ['css', 'index']]);
        $this->middleware('campaign.boosted', ['except' => 'index']);

        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index(Campaign $campaign)
    {
        $this->authorize('access', $campaign);

        Datagrid::layout(\App\Renderers\Layouts\Campaign\Plugin::class)
            ->route('campaign_plugins.index', ['campaign' => $campaign]);

        $highlight = request()->get('highlight');
        if (!empty($highlight)) {
            Datagrid::highlight(function () use ($highlight) {
                return $this->uuid === $highlight;
            });
        }

        $plugins = $campaign->plugins()
            ->preparedSelect()
            ->sort(request()->only(['o', 'k']))
            ->highlighted($highlight)
            ->has('user')
            ->with('versions');

        if (!auth()->check() || !$campaign->userIsMember()) {
            $plugins->where('campaign_plugins.is_active', true);
        }
        $rows = $plugins->paginate();

        // Ajax Datagrid
        if (request()->ajax()) {
            $html = view('campaigns.plugins._table')->with('rows', $rows)->render();
            $deletes = view('layouts.datagrid.delete-forms')->with('models', Datagrid::deleteForms())->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'deletes' => $deletes,
            ]);
        }

        return view('campaigns.plugins', compact('campaign', 'rows', 'highlight'));
    }

    /**
     * @param Plugin $plugin
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function enable(Campaign $campaign, Plugin $plugin)
    {
        $this->authorize('recover', $campaign);

        $this->service
            ->campaign($campaign)
            ->plugin($plugin)
            ->enable();

        return redirect()->route('campaign_plugins.index', $campaign)
            ->with(
                'success',
                __('campaigns/plugins.enabled.success', ['plugin' => $plugin->name])
            );
    }

    /**
     * @param Plugin $plugin
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function disable(Campaign $campaign, Plugin $plugin)
    {
        $this->authorize('recover', $campaign);

        $this->service->campaign($campaign)
            ->plugin($plugin)
            ->disable();

        return redirect()->route('campaign_plugins.index', $campaign)
            ->with(
                'success',
                __('campaigns/plugins.disabled.success', ['plugin' => $plugin->name])
            );
    }


    /**
     * @param Request $request
     * @param Plugin $plugin
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Request $request, Campaign $campaign, Plugin $plugin)
    {
        $this->authorize('recover', $campaign);

        try {
            $this->service
                ->campaign($campaign)
                ->plugin($plugin)
                ->remove();

            return redirect()->route('campaign_plugins.index', $campaign)
                ->with(
                    'success',
                    __('campaigns/plugins.destroy.success', ['plugin' => $plugin->name])
                );
        } catch (\Exception $e) {
            return redirect()->route('campaign_plugins.index', $campaign)
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function css(Campaign $campaign)
    {
        $this->authorize('access', $campaign);

        $themes = CampaignCache::themes();

        $response = \Illuminate\Support\Facades\Response::make($themes);
        $response->header('Content-Type', 'text/css');
        $response->header('Expires', Carbon::now()->addMonth()->toDateTimeString());
        $month = 2592000;
        $response->header('Cache-Control', 'public, max_age=' . $month);

        return $response;
    }

    /**
     * @param Plugin $plugin
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateInfo(Campaign $campaign, Plugin $plugin)
    {
        $this->authorize('recover', $campaign);

        $ajax = request()->ajax();
        $versions = $plugin
            ->versions()
            ->publishedVersions($plugin->created_by)
            ->orderBy('id', 'desc')
            ->paginate();

        $plugin = $campaign->plugins->where('id', $plugin->id)->first();

        return view('campaigns.plugins.info', compact('campaign', 'plugin', 'ajax', 'versions'));
    }

    /**
     * @param Plugin $plugin
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Campaign $campaign, Plugin $plugin)
    {
        $this->authorize('recover', $campaign);

        $this->service->plugin($plugin)->campaign($campaign)->update();
        return redirect()->route('campaign_plugins.index', $campaign)
            ->with(
                'success',
                __('campaigns/plugins.update.success', ['plugin' => $plugin->name])
            );
    }

    public function confirmImport(Campaign $campaign, Plugin $plugin)
    {
        $this->authorize('recover', $campaign);

        $version = CampaignPlugin::where('campaign_id', $campaign->id)
            ->where('plugin_id', $plugin->id)
            ->firstOrFail();

        return view('campaigns.plugins.confirm')
            ->with('plugin', $plugin)
            ->with('version', $version)
            ->with('campaign', $campaign)
        ;
    }

    /**
     * @param Plugin $plugin
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function import(Request $request, Campaign $campaign, Plugin $plugin)
    {
        $this->authorize('recover', $campaign);

        try {
            $count = $this->service
                ->plugin($plugin)
                ->campaign($campaign)
                ->options($request->only(['force_private', 'only_new']))
                ->import();

            return redirect()->route('campaign_plugins.index', $campaign)
                ->with(
                    'success',
                    trans_choice('campaigns/plugins.import.success', $count, ['plugin' => $plugin->name, 'count' => $count])
                )
                ->with('plugin_entities_created', $this->service->created())
                ->with('plugin_entities_updated', $this->service->updated())
                ->with('plugin_only_new', $request->get('only_new'))
            ;
        } catch (\Exception $e) {
            return redirect()->route('campaign_plugins.index', $campaign)
                ->withError(__('campaigns/plugins.import.errors.' . $e->getMessage(), ['plugin' => $plugin->name]));
        }
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function bulk(Campaign $campaign)
    {
        $this->authorize('recover', $campaign);

        $action = request()->get('action');
        $models = request()->get('model');
        if (!in_array($action, ['enable', 'disable', 'update', 'delete']) || empty($models)) {
            return redirect()
                ->route('campaign_plugins.index');
        }

        $this->service->campaign($campaign);
        $count = 0;
        foreach ($models as $id) {
            /** @var Plugin|null $plugin */
            $plugin = Plugin::find($id);
            if (empty($plugin)) {
                continue;
            }
            if ($action === 'enable') {
                if ($this->service->plugin($plugin)->enable()) {
                    $count++;
                }
            } elseif ($action === 'disable') {
                if ($this->service->plugin($plugin)->disable()) {
                    $count++;
                }
            } elseif ($action === 'update') {
                if ($this->service->plugin($plugin)->update()) {
                    $count++;
                }
            } elseif ($action === 'delete') {
                $this->service->plugin($plugin)->remove();
                $count++;
            }
        }
        CampaignCache::clearTheme();

        return redirect()
            ->route('campaign_plugins.index', $campaign)
            ->with('success', trans_choice('campaigns/plugins.bulks.' . $action, $count, ['count' => $count]))
        ;
    }
}
