<?php /** @var \App\Models\Entity $entity
 * @var \App\Models\Relation $relation
 */

$options = [
    '' => __('entities/relations.options.relations'),
    'only_relations' => __('entities/relations.options.only_relations'),
    'related' => __('entities/relations.options.related'),
    'mentions' => __('entities/relations.options.mentions'),
];

?>
@if(!$campaign->boosted())
    @include('layouts.callouts.boost', ['texts' => [__('entities/relations.call-to-action')]])
    <?php return ?>
@endif

{!! Form::open([
    'route' => ['entities.relations.index', [$campaign, $entity]],
    'method' => 'GET',
]) !!}
<div class="box box-solid">
    <div class="box-body">
        <div class="input-group">
            {!! Form::select('option', $options, $option, ['class' => 'form-control']) !!}
            <div class="input-group-btn">

                <input type="submit" value="{{ __('entities/relations.options.show') }}" class="btn btn-primary" />
            </div>
        </div>
    </div>
</div>
{!! Form::hidden('mode', 'map') !!}
{!! Form::close() !!}

<div class="box box-solid box-entity-relations box-entity-relations-explorer">
    <div class="box-body">
        <div class="loading text-center" id="spinner">
            <i class="fa-solid fa-spinner fa-spin fa-4x"></i>
        </div>
        <div id="cy" class="cy" style="display: none;" data-url="{{ route('entities.relations_map', [$campaign, $entity, 'option' => $option]) }}"></div>
    </div>
</div>
