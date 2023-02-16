<?php
$datagridOptions = [
    $campaign,
    $model,
    'init' => 1
];
$datagridOptions = Datagrid::initOptions($datagridOptions);
?>

<div class="box box-solid item-subitems" id="subitems">
    <div class="box-header with-border">
        <h3 class="box-title">{{ __('items.fields.items') }}</h3>
        <div class="box-tools">
            <a href="#" class="btn btn-box-tool" data-toggle="dialog" data-target="help-modal">
                <i class="fa-solid fa-question-circle"></i> {{ __('crud.actions.help') }}
            </a>
        </div>
    </div>
    <div id="datagrid-parent" class="table-responsive">
        @include('layouts.datagrid._table', ['datagridUrl' => route('items.items', $datagridOptions)])
    </div>
</div>

@section('modals')
    @parent
    @include('partials.helper-modal', [
        'id' => 'help-modal',
        'title' => __('crud.actions.help'),
        'textes' => [
            __('items.hints.items')
        ]
    ])
@endsection
