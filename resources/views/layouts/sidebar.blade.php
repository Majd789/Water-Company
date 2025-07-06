<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/dashboard" class="brand-link">
        <img src="{{ asset('assets/img/favicon.png') }}" alt="Water Db Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Water Db</span>
    </a>

    <div class="sidebar">
        @php
            // Your existing logic to get user info
            $userRole = auth()->check() ? auth()->user()->role_id : 'guest';
            $imgSrc = asset('assets/img/favicon.png'); // Default image
            $roleText = $userRole;
            $userName = auth()->check() ? auth()->user()->name : 'Guest';

            switch ($userRole) {
                case 'superA':
                    $imgSrc = asset('assets/img/favicon.png');
                    $roleText = 'المدير العام';
                    break;
                case 'super':
                    $imgSrc = asset('assets/img/favicon.png');
                    $roleText = 'مشرف وحدة';
                    break;
                case 'admin':
                    $imgSrc = asset('assets/img/favicon.png');
                    $roleText = 'القسم التقني';
                    break;
            }
        @endphp

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ $imgSrc }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ $userName }}</a>
                <span class="d-block text-muted">{{ $roleText }}</span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>الرئيسية</p>
                    </a>
                </li>

                @if (auth()->check() && in_array(auth()->user()->role_id, ['admin', 'super']))
                    @php
                        $technicalRoutes = [
                            'station_reports*',
                            'waterwells2*',
                            'units*',
                            'towns*',
                            'stations*',
                            'wells*',
                            'generation-groups*',
                            'solar_energy*',
                            'disinfection_pumps*',
                            'horizontal-pumps*',
                            'ground-tanks*',
                            'elevated-tanks*',
                            'pumping-sectors*',
                            'electricity-hours*',
                            'electricity-transformers*',
                            'private-wells*',
                            'infiltrators*',
                            'filters*',
                            'manholes*',
                            'diesel_tanks*',
                        ];
                    @endphp
                    <li class="nav-item has-treeview {{ Request::is($technicalRoutes) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is($technicalRoutes) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-desktop"></i>
                            <p>
                                القسم التقني
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('station_reports.index') }}"
                                    class="nav-link {{ Request::is('station_reports*') ? 'active' : '' }}">
                                    <i class="fas fa-chart-bar nav-icon"></i>
                                    <p>تقارير المحطات</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('waterwells2.index') }}"
                                    class="nav-link {{ Request::is('waterwells2*') ? 'active' : '' }}">
                                    <i class="fas fa-clipboard-check nav-icon"></i>
                                    <p>تدقيق تقارير</p>
                                </a>
                            </li>
                            @if (auth()->user()->role_id == 'admin')
                                <li class="nav-item">
                                    <a href="{{ route('units.index') }}"
                                        class="nav-link {{ Request::is('units*') ? 'active' : '' }}">
                                        <i class="fas fa-building nav-icon"></i>
                                        <p>استعراض الوحدات</p>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('towns.index') }}"
                                    class="nav-link {{ Request::is('towns*') ? 'active' : '' }}">
                                    <i class="fas fa-city nav-icon"></i>
                                    <p>استعراض البلدات</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('stations.index') }}"
                                    class="nav-link {{ Request::is('stations*') ? 'active' : '' }}">
                                    <i class="fas fa-broadcast-tower nav-icon"></i>
                                    <p>المحطات</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('wells.index') }}"
                                    class="nav-link {{ Request::is('wells*') ? 'active' : '' }}">
                                    <i class="fas fa-water nav-icon"></i>
                                    <p>الآبار</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('generation-groups.index') }}"
                                    class="nav-link {{ Request::is('generation-groups*') ? 'active' : '' }}">
                                    <i class="fas fa-bolt nav-icon"></i>
                                    <p>مجموعات التوليد</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('solar_energy.index') }}"
                                    class="nav-link {{ Request::is('solar_energy*') ? 'active' : '' }}">
                                    <i class="fas fa-solar-panel nav-icon"></i>
                                    <p>الطاقة الشمسية</p>
                                </a>
                            </li>
                            <li class="nav-item"><a href="{{ route('disinfection_pumps.index') }}"
                                    class="nav-link {{ Request::is('disinfection_pumps*') ? 'active' : '' }}"><i
                                        class="fas fa-shield-virus nav-icon"></i>
                                    <p>التعقيم</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('horizontal-pumps.index') }}"
                                    class="nav-link {{ Request::is('horizontal-pumps*') ? 'active' : '' }}"><i
                                        class="fas fa-exchange-alt nav-icon"></i>
                                    <p>المضخات الأفقية</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('ground-tanks.index') }}"
                                    class="nav-link {{ Request::is('ground-tanks*') ? 'active' : '' }}"><i
                                        class="fas fa-box-open nav-icon"></i>
                                    <p>الخزانات الأرضية</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('elevated-tanks.index') }}"
                                    class="nav-link {{ Request::is('elevated-tanks*') ? 'active' : '' }}"><i
                                        class="fas fa-archway nav-icon"></i>
                                    <p>الخزانات العالية</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('pumping-sectors.index') }}"
                                    class="nav-link {{ Request::is('pumping-sectors*') ? 'active' : '' }}"><i
                                        class="fas fa-network-wired nav-icon"></i>
                                    <p>قطاع الضخ</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('electricity-hours.index') }}"
                                    class="nav-link {{ Request::is('electricity-hours*') ? 'active' : '' }}"><i
                                        class="fas fa-hourglass-half nav-icon"></i>
                                    <p>ساعات الكهرباء</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('electricity-transformers.index') }}"
                                    class="nav-link {{ Request::is('electricity-transformers*') ? 'active' : '' }}"><i
                                        class="fas fa-plug nav-icon"></i>
                                    <p>محولات الكهرباء</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('private-wells.index') }}"
                                    class="nav-link {{ Request::is('private-wells*') ? 'active' : '' }}"><i
                                        class="fas fa-user-lock nav-icon"></i>
                                    <p>الآبار الخاصة</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('infiltrators.index') }}"
                                    class="nav-link {{ Request::is('infiltrators*') ? 'active' : '' }}"><i
                                        class="fas fa-vial nav-icon"></i>
                                    <p>الانفلترات</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('filters.index') }}"
                                    class="nav-link {{ Request::is('filters*') ? 'active' : '' }}"><i
                                        class="fas fa-filter nav-icon"></i>
                                    <p>المرشحات</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('manholes.index') }}"
                                    class="nav-link {{ Request::is('manholes*') ? 'active' : '' }}"><i
                                        class="fas fa-dungeon nav-icon"></i>
                                    <p>المناهل</p>
                                </a></li>
                            <li class="nav-item"><a href="{{ route('diesel_tanks.index') }}"
                                    class="nav-link {{ Request::is('diesel_tanks*') ? 'active' : '' }}"><i
                                        class="fas fa-gas-pump nav-icon"></i>
                                    <p>خزانات الديزل</p>
                                </a></li>
                        </ul>
                    </li>
                @endif

                <li class="nav-header">الإعدادات</li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                        class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p>تسجيل خروج</p>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
