@php $currentCampaign = isset($campaign) && $campaign instanceof \App\Models\Campaign ? $campaign : null @endphp
<div class="@if (!isset($skipImage))grid gap-5 grid-cols-1 lg:grid-cols-2 booster-block mb-5"@endif>
    <div class="">
        <div class="booster-callout">
            <div class="booster-icon">
                <i class="fa-solid fa-rocket fa-2x" aria-hidden="true"></i>
            </div>

            <h4>{{ __('campaigns/limits.title') }}</h4>

            @foreach ([__('campaigns/limits.' . $key)] as $text)
                <p class="mb-5">{!! $text !!}</p>
            @endforeach

            @subscriber()
            @if (isset($superboost))
                <a href="{{ route('settings.boost', ['campaign' => $currentCampaign, 'superboost' => true]) }}" class="btn bg-maroon btn-lg">
                    {!! __('callouts.booster.actions.superboost', ['campaign' => $currentCampaign->name]) !!}
                </a>
            @else
                <a href="{{ route('settings.boost', ['campaign' => $currentCampaign]) }}" class="btn bg-maroon btn-lg">
                    {!! __('callouts.booster.actions.boost', ['campaign' => $currentCampaign->name]) !!}
                </a>
            @endif
            @else
                <a href="{{ route('front.boosters') }}" target="_blank" class="btn bg-maroon btn-lg">
                    {!! __('callouts.booster.learn-more') !!}
                </a>
            @endif
        </div>
    </div>
    @if (!isset($skipImage))
    <div class="">
        @include('partials.images.boosted-image')
    </div>
    @endif
</div>
