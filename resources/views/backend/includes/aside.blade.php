<aside id="side-overlay">
    <!-- Side Header -->
    <div class="content-header content-header-fullrow">
        <div class="content-header-section align-parent">
            <!-- Close Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout" data-action="side_overlay_close">
                <i class="fa fa-times text-danger"></i>
            </button>
            <!-- END Close Side Overlay -->

            <!-- User Info -->
            <div class="content-header-item">
                <a class="img-link mr-5" href="javascript:void(0)">
                    <img class="img-avatar img-avatar32" src="{{ $logged_in_user->picture }}" alt="">
                </a>
                <a class="align-middle link-effect text-body-color-dark font-w600" href="javascript:void(0)">{{ $logged_in_user->name }}</a>
            </div>
            <!-- END User Info -->
        </div>
    </div>
    <!-- END Side Header -->

    <!-- Emergency Notification -->
    <div class="content-side content-side-full bg-danger-light text-center">
        <i class="fa fa-exclamation-triangle fa-2x text-danger animated swing infinite"></i>
        <p class="font-size-h5 font-w700 text-danger mt-10 mb-0">
            There is an emergency, please proceed to surgery immediately!
        </p>
    </div>
    <!-- END Emergency Notification -->

    <!-- Side Content -->
    <div class="content-side">
        <!-- Mini Stats -->
        <div class="block pull-t pull-r-l">
            <div class="block-content block-content-full block-content-sm bg-body-light">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Konsultasi</div>
                        <a class="link-effect font-w600 font-size-h4" href="javascript:void(0)">5</a>
                    </div>
                    <div class="col-4">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Treatment</div>
                        <a class="link-effect font-w600 font-size-h4" href="javascript:void(0)">6</a>
                    </div>
                    <div class="col-4">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Kasir</div>
                        <a class="link-effect font-w600 font-size-h4" href="javascript:void(0)">6</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Mini Stats -->

        <!-- Notifications -->
        <div class="block pull-r-l">
            <div class="block-header bg-body-light">
                <h3 class="block-title">Pemberitahuan Terbaru</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <ul class="list list-activity">
                    @foreach(auth()->user()->notifications as $notification)
                    <li>
                        <i class="fa fa-check text-success"></i>
                        <div class="font-w600">{{ $notification['data']['header'] ?? $notification['data']['header'] }}</div>
                        <div>
                            <a class="font-w600 text-info" href="javascript:void(0)">{{ $notification['data']['message'] ?? $notification['data']['message'] }}</a>
                        </div>
                        <div class="font-size-xs text-muted">{{ $notification['created_at']->diffForHumans() }}</div>
                    </li>
                    @endforeach
                </ul>
                <a class="btn btn-block btn-alt-secondary" href="javascript:void(0)">
                    Load more..
                </a>
                <a class="btn btn-block btn-hero btn-alt-primary" href="javascript:void(0)">
                    <i class="fa fa-flag mr-5"></i> View All Notifications
                </a>
            </div>
        </div>
        <!-- END Notifications -->
    </div>
    <!-- END Side Content -->
</aside>