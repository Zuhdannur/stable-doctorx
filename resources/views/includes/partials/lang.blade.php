<div class="dropdown-menu" aria-labelledby="toolbarLanguage" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -2px, 0px); top: 0px; left: 0px; will-change: transform;">
	@foreach(array_keys(config('locale.languages')) as $lang)
        @if($lang != app()->getLocale())
            <small><a href="{{ '/lang/'.$lang }}" class="dropdown-item"><i class="fa fa-flag-o"></i> @lang('menus.language-picker.langs.'.$lang)</a></small>
        @endif
    @endforeach
</div>