<?php

namespace App\Http\Controllers;

use App\Datagrids\Actions\DeprecatedDatagridActions;
use App\Datagrids\Filters\ConversationFilter;
use App\Facades\CampaignLocalization;
use App\Http\Requests\StoreConversation;
use App\Models\Conversation;

class ConversationController extends CrudController
{
    /**
     * @var string
     */
    protected string $view = 'conversations';
    protected string $route = 'conversations';
    protected $module = 'conversations';

    /** @var string Model */
    protected $model = \App\Models\Conversation::class;

    /** @var string Filter */
    protected $filter = ConversationFilter::class;

    /** @var string  */
    protected $datagridActions = DeprecatedDatagridActions::class;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addNavAction(
            '//docs.kanka.io/en/latest/entities/conversations.html',
            '<i class="fa-solid fa-question-circle"></i> ' . __('crud.actions.help'),
            'default',
            true
        );
    }

    /**
     */
    public function store(StoreConversation $request)
    {
        return $this->crudStore($request);
    }

    /**
     */
    public function show(Conversation $conversation)
    {
        // Policies will always fail if they can't resolve the user.
        if (auth()->check()) {
            $this->authorize('view', $conversation);
        } else {
            $this->authorizeForGuest(\App\Models\CampaignPermission::ACTION_READ, $conversation);
        }
        $campaign = CampaignLocalization::getCampaign();
        $name = $this->view;
        $ajax = request()->ajax();
        $model = $conversation;

        return view(
            'cruds.show',
            compact('campaign', 'model', 'name', 'ajax')
        );
    }

    /**
     */
    public function edit(Conversation $conversation)
    {
        return $this->crudEdit($conversation);
    }

    /**
     */
    public function update(StoreConversation $request, Conversation $conversation)
    {
        return $this->crudUpdate($request, $conversation);
    }

    /**
     */
    public function destroy(Conversation $conversation)
    {
        return $this->crudDestroy($conversation);
    }
}
