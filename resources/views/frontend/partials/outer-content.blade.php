@if(Route::currentRouteName() == 'frontend.index')
    <!-- Info -->
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.7781331636534!2d106.91460751477906!3d-6.91710786962288!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68482be8370981%3A0xe7ce1bf920a084c5!2sRumah+Cantik+Irna!5e0!3m2!1sen!2sid!4v1552619788868" width="100%" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
@endif
    <div class="bg-image" style="background-image: url({{ asset('media/photos/photo34@2x.jpg') }});">
        <div class="bg-white-op-90">
            <div class="content">
                <div class="py-50 nice-copy text-center">
                    <h3 class="font-w700 mb-10">{{ app_name() }}</h3>
                    <h4 class="font-w400 text-muted mb-30">{{ config('app.version') }}</h4>
                </div>
            </div>
        </div>
    </div>
