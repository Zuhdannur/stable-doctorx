@if(Route::currentRouteName() == 'frontend.index')
<div class="bg-image bg-image-bottom" style="background-image: url({{ asset('media/photos/photo20@2x.jpg') }});">
    <div class="bg-primary-dark-op">
        <div class="content text-center">
            <div class="pt-50 pb-20">
                <h1 class="font-w700 text-white mb-10">@lang('navs.general.home')</h1>
                <h2 class="h4 font-w400 text-white-op">@lang('strings.frontend.welcome_to', ['place' => app_name()])</h2>
            </div>
        </div>
    </div>
</div>
@endif