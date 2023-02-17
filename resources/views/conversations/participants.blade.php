@extends('layouts.' . (request()->ajax() ? 'ajax' : 'app'), [
    'title' => __('conversations.participants.title', ['name' => $model->name]),
    'breadcrumbs' => [
        ['url' => $model->entity->url('index'), 'label' => __('entities.conversations')],
        ['url' => $model->getLink(), 'label' => $model->name],
        __('crud.update'),
    ]
])

@section('content')
    <div class="panel panel-default">
        @if (request()->ajax())
            <div class="panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('crud.delete_modal.close') }}"><span aria-hidden="true">&times;</span></button>
                <h4>{{ __('conversations.participants.modal', ['name' => $model->name]) }}</h4>
            </div>
        @endif
        <div class="panel-body">
            <ul class="list-group list-group-unbordered mb-5">
                @foreach ($model->participants as $participant)
                    @if ($participant->isMember() || (auth()->check() && auth()->user()->can('view', $participant->entity())))
                    <li class="list-group-item">
                        @can('update', $model)
                            {!! Form::open(['method' => 'DELETE', 'route' => ['conversations.conversation_participants.destroy', $campaign, $model, $participant], 'style'=>'display:inline']) !!}
                        @endcan

                        @if ($participant->isMember())
                            {{ $participant->entity()->name }}
                        @else
                            <a href="{{ $participant->entity()->getLink() }}">{{ $participant->entity()->name }}</a>
                        @endif

                        @can('update', $model)
                            <button class="btn btn-xs btn-danger pull-right">
                                <i class="fa-solid fa-trash" aria-hidden="true"></i> {{ __('crud.remove') }}
                            </button>
                            {!! Form::close() !!}
                        @endcan
                    </li>
                    @endif
                @endforeach
            </ul>

            @can('update', $model)
                @include('partials.errors')
                <?php $memberList = $campaign->membersList($model->participantsList(false)); ?>
                @if($model->forCharacters() || count($memberList) > 0)
                {!! Form::open(['route' => ['conversations.conversation_participants.store', $campaign, $model], 'method'=>'POST', 'data-shortcut' => "1"]) !!}
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group required">
                            @if ($model->forCharacters())
                            {!! Form::select2(
                                'character_id',
                                null,
                                App\Models\Character::class,
                                false
                            ) !!}
                            @else
                                {!! Form::select(
                                    'user_id',
                                    $memberList,
                                    null,
                                    ['class' => 'form-control']
                                ) !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        @if ($model->target ==  \App\Models\Conversation::TARGET_CHARACTERS)
                            <label></label>
                        @endif
                        <button class="btn btn-primary btn-info btn-flat btn-block">
                            <i class="fa-solid fa-plus"></i> {{ __('crud.add') }}
                        </button>
                    </div>
                </div>
                {!! Form::hidden('conversation_id', $model->id) !!}
                {!! Form::close() !!}
                @endif
            @endcan
        </div>
    </div>
@endsection
