@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
<!-- Data Antrian -->
<div class="block block-bordered" id="my-block">
    <div class="block-content">
        @asyncWidget('\App\Modules\Patient\Widgets\QueuesDisplay', ['id' => 0])                                 
    </div>
</div>
<!-- END Antrian -->
<script type="text/javascript">
jQuery(function(){ 
    Codebase.layout('sidebar_mini_on');
    Codebase.blocks('#my-block', 'fullscreen_on');
});
</script>
@endsection
