<footer id="page-footer" class="opacity-0">
    <div class="content py-20 font-size-xs clearfix">
        <div class="float-right">
            @lang('labels.general.copyright') &copy; {{ date('Y') }} <i class="fa fa-heart text-pulse"></i> by <a class="font-w600" href="{{ setting()->get('copyright_link') }}" title="{{ setting()->get('copyright') }}" target="_blank">{{ setting()->get('copyright') }}</a> <span>@lang('strings.backend.general.all_rights_reserved')</span>
        </div>
        <div class="float-left">
            <a class="font-w600" href="{{ route('admin.dashboard') }}">{{ app_name() }}</a> {{ config('app.version') }}
        </div>
    </div>
</footer>