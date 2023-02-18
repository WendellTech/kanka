@php
$usages = [
    '' => __('campaigns.invites.usages.no_limit'),
    '1' => __('campaigns.invites.usages.once'),
    '5' => __('campaigns.invites.usages.five'),
    '10' => __('campaigns.invites.usages.ten'),
];
@endphp

{{ csrf_field() }}
<div class="form-group required">
    <label>{{ __('campaigns.invites.fields.usage') }}</label>
    {!! Form::select('validity', $usages, null, ['class' => 'form-control']) !!}
</div>
<div class="form-group required">
    <label>{{ __('campaigns.invites.fields.role') }}</label>
    {!! Form::select('role_id', $roles, null, ['class' => 'select form-control']) !!}
</div>

