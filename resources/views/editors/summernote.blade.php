@section('scripts')
    @parent
    <script src="/vendor/summernote/summernote.min.js?v={{ config('app.version') }}" defer></script>
    <script src="{{ mix('js/editors/summernote.js') }}" defer></script>
    <script src="/vendor/summernote/plugin/embed/summernote-embed-plugin.js" defer></script>
    <script src="/vendor/summernote/plugin/summernote-table-headers/summernote-table-headers.js" defer></script>
    <script src="/vendor/summernote/plugin/summernote-gallery-kanka.min.js" defer></script>
    <script src="/vendor/summernote/plugin/summernote-toc-kanka/summernote-toc.js" defer></script>
    <script src="/vendor/summernote/plugin/summernote-aroba-kanka/summernote-aroba.js" defer></script>
    <script src="/vendor/summernote/plugin/summernote-table-ext.js" defer></script>
    <script src="/vendor/summernote/plugin/spoiler/summernote-spoiler.js" defer></script>
    <script src="/vendor/summernote/plugin/summernote-image-attribute.js" defer></script>
    <script src="/vendor/summernote/plugin/kanka/summernote-bragi-kanka.min.js" defer></script>
    <script src="/vendor/summernote/plugin/kanka/summernote-prettify-kanka.min.js" defer></script>
{{--    <script src="/vendor/summernote/plugin/rtl/summernote-ext-rtl.js" defer></script>--}}

    @if (app()->getLocale() == 'ca')
        <script src="/vendor/summernote/lang/summernote-ca-ES.js" defer></script>
    @elseif (!in_array(app()->getLocale(), ['en-US', 'en']))
        <script src="/vendor/summernote/lang/summernote-{{ app()->getLocale() }}-{{ strtoupper(app()->getLocale()) }}.js" defer></script>
    @endif
@endsection

@section('styles')
@parent
<link href="/vendor/summernote/summernote.min.css" rel="stylesheet">
@endsection

@section('modals')
    @parent

    <div
        id="summernote-config"
        data-mention="{{ route('search.live') }}"
        data-advanced-mention="{{ auth()->user()->alwaysAdvancedMentions() }}"
        data-months="{{ route('search.calendar-months') }}"
        data-gallery-title="Superboosted Gallery"
        data-gallery-close="{{ __('crud.click_modal.close') }}"
        data-gallery-add="{{ __('crud.add') }}"
        data-gallery-select-all="{{ __('general.select_all') }}"
        data-gallery-deselect-all="{{ __('general.deselect_all') }}"
        data-gallery-error="generic.gallery.error"
        data-filesize="{{ auth()->user()->maxUploadSize() }}"
        data-placeholder="{{ __('crud.placeholders.entry') }}"
        data-dialogs="{{ isset($dialogsInBody) ? '1' : '0' }}"
@if (isset($name) && $name == 'characters')        data-bragi="{{ route('bragi') }}"@endif
@if(isset($campaignService) && $campaignService->campaign() !== null)
        data-gallery="{{ $campaignService->campaign()->superboosted() ? route('gallery.summernote', [$campaignService->campaign()]) : null }}"
    @if($campaignService->campaign()->superboosted()) data-gallery-upload="{{ route('gallery.ajax-upload', [$campaignService->campaign()]) }}" @endif
@endif
@if (!empty($model) && !($model instanceof \App\Models\Campaign) && $model->entity)        data-attributes="{{ route('search.attributes', $model->entity) }}"
@elseif (!empty($entity))        data-attributes="{{ route('search.attributes', $entity) }}"

@endif
        data-locale="{{ app()->getLocale() }}"></div>

@if(isset($campaignService) && $campaignService instanceof \App\Services\CampaignService && $campaignService->campaign() !== null)
    <div class="modal fade" id="campaign-imageupload-modal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-2xl">
                <div class="modal-body  text-center">
                    <div id="campaign-imageupload-boosted">
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('crud.delete_modal.close') }}"><span aria-hidden="true">&times;</span></button>

                        @include('layouts.callouts.boost-modal', ['texts' => [__('campaigns/gallery.pitch')], 'superboost' => true, 'campaign' => $campaignService->campaign()])
                    </div>
                    <p class="alert alert-danger" id="campaign-imageupload-error" style="display:none"></p>
                    <p class="alert alert-danger" id="campaign-imageupload-permission" style="display:none">
                        {!! __('campaigns/gallery.errors.permissions', [
    'permission' => '<code>' . __('campaigns.roles.permissions.actions.gallery') . '</code>']
    ) !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
