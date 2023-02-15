@if (!$campaign->enabled('organisations'))
    <?php return ?>
@endif

<input type="hidden" name="save_organisations" value="1">
<div class="form-group">
    @include('components.form.organisations', ['options' => [
        'model' => $model ?? FormCopy::model(),
        'source' => $source ?? null,
    ]])
</div>
