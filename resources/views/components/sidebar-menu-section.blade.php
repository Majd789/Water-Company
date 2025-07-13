{{-- resources/views/components/sidebar-menu-section.blade.php --}}

@props(['title', 'icon', 'permissions' => [], 'routes' => []])

@canany($permissions)
    <li class="nav-item has-treeview {{ Request::routeIs($routes) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::routeIs($routes) ? 'active' : '' }}">
            <i class="nav-icon {{ $icon }}"></i>
            <p>{{ $title }} <i class="right fas fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">
            {{ $slot }}
        </ul>
    </li>
@endcanany
