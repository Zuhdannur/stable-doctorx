@if($breadcrumbs && Route::currentRouteName() !== 'admin.dashboard')
<div class="bg-body-light border-b">
    <div class="content py-5 text-center">
        <nav class="breadcrumb bg-body-light mb-0">
            <span class="breadcrumb-item">Home</span>

            @foreach($breadcrumbs as $breadcrumb)
                @if($breadcrumb->url && !$loop->last)
                    <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                @else
                    <span class="breadcrumb-item active">{{ $breadcrumb->title }}</span>
                @endif
            @endforeach

            @yield('breadcrumb-links')
        </nav>
    </div>
</div>
<nav class="breadcrumb bg-white push d-none">
    @foreach($breadcrumbs as $breadcrumb)
        @if($breadcrumb->url && !$loop->last)
            <a class="breadcrumb-item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
        @else
            <span class="breadcrumb-item active">{{ $breadcrumb->title }}</span>
        @endif
    @endforeach

    @yield('breadcrumb-links')
</nav>
@endif