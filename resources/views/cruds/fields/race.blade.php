@if ($campaign->enabled('races'))
    <?php
    $preset = null;
    if (isset($model) && $model->race) {
        $preset = $model->race;
    } elseif (isset($isRandom) && $isRandom) {
        $preset = $random->generateForeign(\App\Models\Race::class);
    } elseif (isset($parent) && $parent) {
        $preset = FormCopy::field('race')->select(true, \App\Models\Race::class);
    } else {
        $preset = FormCopy::field('race')->select();
    }?>
    <div class="form-group">
        {!! Form::foreignSelect(
            'race_id',
            [
                'preset' => $preset,
                'class' => App\Models\Race::class,
                'enableNew' => isset($enableNew) ? $enableNew : true,
                'labelKey' => isset($parent) && $parent ? 'races.fields.race' : null,
                'from' => isset($from) ? $from : null,
            ]
        ) !!}
    </div>
@endif
