<li class="{{ active_class(Active::checkUriPattern('admin/masterdata*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/masterdata*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span class="sidebar-mini-hide">Master Data</span></a>
    <ul>
        <li class="{{ active_class(Active::checkUriPattern('admin/masterdata*'), 'open') }}">
            <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/diagnoseitem/index')) }}" href="{{ route('admin.masterdata.diagnoseitem.index') }}">Data Diagnosa</a>
        </li>
        <li class="{{ active_class(Active::checkUriPattern('admin/masterdata/product*'), 'open') }}">
            <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/masterdata/product*')) }}" data-toggle="nav-submenu" href="#">Produk</a>
            <ul>
                <li class="{{ active_class(Active::checkUriPattern('admin/masterdata/product/product*'), 'open') }}">
                    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/masterdata/product/product*')) }}" data-toggle="nav-submenu" href="#">Data Obat</a>
                    <ul>
                        <li>
                            <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/product/productcategory*')) }}" href="{{ route('admin.product.category.index') }}">Kategori Obat</a>
                        </li>
                        <li>
                            <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/product/productdata*')) }}" href="{{ route('admin.product.index') }}">Data Obat</a>
                        </li>
                        <li>
                            <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/product/productpackage*')) }}" href="{{ route('admin.product.productpackage.index') }}">Paket Obat</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ active_class(Active::checkUriPattern('admin/masterdata/product/service*'), 'open') }}">
                    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/masterdata/product/servicecategory*')) }}" data-toggle="nav-submenu" href="#">Data Service</a>
                    <ul>
                        <li>
                            <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/product/servicecategory*')) }}" href="{{ route('admin.product.servicecategory.index') }}">Kategori Service</a>
                        </li>
                        <li>
                            <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/product/services*')) }}" href="{{ route('admin.product.service.index') }}">Data Service</a>
                        </li>
                        <li>
                            <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/product/services-packages*')) }}" href="{{ route('admin.product.services-packages.index') }}">Data Paket Service</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/product/supplier*')) }}" href="{{ route('admin.product.supplier') }}">Data Supplier</a>
                </li>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/product/stok*')) }}" href="{{ route('stok.index') }}">Stok Obat</a>
                </li>
            </ul>
        </li>
        <li class="{{ active_class(Active::checkUriPattern('admin/masterdata/room*'), 'open') }}">
            <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/masterdata/room*')) }}" data-toggle="nav-submenu" href="#">Ruangan</a>
            <ul>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/room/group*')) }}" href="{{ route('admin.room.group.index') }}">Grup</a>
                </li>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/room/floor*')) }}" href="{{ route('admin.room.floor.index') }}">Lantai</a>
                </li>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/room/room*')) }}" href="{{ route('admin.room.index') }}">Ruangan</a>
                </li>
            </ul>
        </li>
        <li class="{{ active_class(Active::checkUriPattern('admin/masterdata/klinik*'), 'open') }}">
            <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/klinik*')) }}" href="{{ route('klinik.index') }}">Data Klinik</a>
        </li>
        <li class="{{ active_class(Active::checkUriPattern('admin/masterdata/setting-modul*'), 'open') }}">
            <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/setting-modul*')) }}" href="{{ route('setting-modul.index') }}">Setting Modul</a>
        </li>
        <li class="{{ active_class(Active::checkUriPattern('admin/masterdata/setting-modul-urutan*'), 'open') }}">
            <a class="{{ active_class(Active::checkUriPattern('admin/masterdata/setting-modul-urutan*')) }}" href="{{ route('setting-modul-urutan.index') }}">Setting Modul Urutan</a>
        </li>
    </ul>
</li>
