<li class="{{ active_class(Active::checkUriPattern('admin/billing*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/billing*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-calculator"></i><span class="sidebar-mini-hide">Billing</span></a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/billing/create*')) }}" href="{{ route('admin.billing.create', [0,0]) }}">Pembayaran</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/billing/list*')) }}" href="{{ route('admin.billing.index') }}">Data Pembayaran/Invoice</a>
        </li>
    </ul>
</li>