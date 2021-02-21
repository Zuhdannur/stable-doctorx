<li class="{{ active_class(Active::checkUriPattern('admin/calendar/booking*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/calendar//booking*')) }}" data-toggle="nav-submenu" href="#">Booking</a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/calendar/booking/docter')) }}" href="{{ route('admin.booking.docter') }}">By Docter</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/calendar/booking/therapist')) }}" href="{{ route('admin.booking.therapist') }}">By Therapist</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/calendar/booking/room')) }}" href="{{ route('admin.booking.room') }}">By Room</a>
        </li>
    </ul>
</li>