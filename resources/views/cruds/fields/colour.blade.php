<x-forms.field field="colour" :required="true" :label="__('crud.fields.colour')">
    {!! Form::select('colour', FormCopy::colours(), FormCopy::field('colour')->string(), ['class' => 'form-control select2-colour']) !!}
</x-forms.field>
