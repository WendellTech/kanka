<div class="box box-solid mt-5" id="map-markers">
    @if(Datagrid::hasBulks()) {!! Form::open(['route' => ['maps.markers.bulk', ['campaign' => $campaign, 'map' => $model]]]) !!} @endif
    <div class="box-header with-border">
        <h3 class="box-title">
            {{ __('maps.panels.markers') }}
        </h3>
    </div>
    <div id="datagrid-parent" class="table-responsive">
        @include('layouts.datagrid._table')
    </div>
    @if(Datagrid::hasBulks()) {!! Form::close() !!} @endif

</div>

@section('modals')
    @parent
    @include('layouts.datagrid.delete-forms', ['models' => Datagrid::deleteForms(), 'params' => []])
@endsection
