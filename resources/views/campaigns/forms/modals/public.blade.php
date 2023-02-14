{!! Form::model($campaign, ['route' => ['campaign-visibility.save', $campaign], 'method' => 'POST']) !!}

<div class="modal-body">

    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title  text-center mb-5">
        {!! __('campaigns/public.title') !!}
    </h4>

    <div class="alert alert-info">
        <p>{!! __('campaigns/public.helpers.main', [
    'public-campaigns' => link_to_route('front.public_campaigns', __('front.menu.campaigns'), null, ['target' => '_blank']),
    'public-role' => link_to_route('campaign_roles.public', __('campaigns.members.roles.public'), $campaign, ['target' => '_blank'])
]) !!}</p>
        <p>
            <a href="https://www.youtube.com/watch?v=VpY_D2PAguM" target="_blank"><i class="fa-solid fa-external-link-alt"></i> {{ __('helpers.public') }}</a>
        </p>
    </div>


    <div class="form-group">
        <label>
            {{ __('campaigns.fields.public') }}
        </label>
        {!! Form::select('is_public', [0 => __('campaigns.visibilities.private'), 1 => __('campaigns.visibilities.public')], null, ['class' => 'form-control']) !!}
    </div>

    <div class="my-5 text-center">
        <button type="button" class="btn btn-default mr-5 rounded-full px-8" data-dismiss="modal">
            {{ __('crud.cancel') }}
        </button>

        <button class="btn btn-success ml-5 rounded-full px-8">{{ __('crud.actions.apply') }}</button>

    </div>
</div>
@if (isset($from) && $from === 'overview')
    <input type="hidden" name="from" value="{{ $from }}" />
@endif
{!! Form::close() !!}
