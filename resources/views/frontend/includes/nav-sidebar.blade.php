<ul class="nav-main">
	<li>
            <a class="{{ active_class(Active::checkRoute('frontend.index')) }}" href="{{ route('frontend.index') }}"><i class="si si-compass"></i>@lang('navs.general.home')</a>
        </li>
	@auth
        <li>
            <a class="{{ active_class(Active::checkRoute('frontend.user.dashboard')) }}" href="{{ route('frontend.user.dashboard') }}"><i class="si si-compass"></i>@lang('navs.frontend.dashboard')</a>
        </li>
        @else
            <li>
                <a class="{{ active_class(Active::checkRoute('frontend.auth.login')) }}" href="{{ route('frontend.auth.login') }}"><i class="si si-compass"></i>@lang('navs.frontend.login')</a>
            </li>
            @if(config('access.registration'))
                <li>
                    <a class="{{ active_class(Active::checkRoute('frontend.auth.register')) }}" href="{{ route('frontend.auth.register') }}"><i class="si si-compass"></i>@lang('navs.frontend.register')</a>
                </li>
            @endif
        @endauth
        <li><a href="{{route('frontend.contact')}}" class="{{ active_class(Active::checkRoute('frontend.contact')) }}"><i class="si si-compass"></i>@lang('navs.frontend.contact')</a></li>
</ul>