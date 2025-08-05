<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @php
        // 1. تحديد الصورة الافتراضية
        $profileImage = asset('assets/img/favicon.png');

        // 2. التحقق من دور المستخدم وتغيير الصورة بناءً عليه
        if (!auth()->user()->roles->isEmpty()) {
            $roleName = auth()->user()->roles->first()->display_name;

            switch ($roleName) {
                case 'مشرف وحدة':
                    $profileImage = asset('assets/img/AdminUnit.png');
                    break;
                case 'القسم التقني':
                    $profileImage = asset('assets/img/it.png');
                    break;
                case 'قسم الصيانة':
                    $profileImage = asset('assets/img/maintenance_tasks.jpg');
                    break;
            }
        }
    @endphp
    <a href="{{ route('dashboard.home') }}" class="brand-link">
        <img src="{{ asset('assets/img/favicon.png') }}" alt="Water Db Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Water Db</span>
    </a>

    <div class="sidebar">
        @auth
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ $profileImage }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                    @if (!auth()->user()->roles->isEmpty())
                        <span class="d-block text-muted">
                            {{ auth()->user()->roles->first()->display_name }}
                        </span>
                    @else
                        <span class="d-block text-muted">مستخدم</span>
                    @endif
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">

                    {{-- 1. الرئيسية --}}
                    <li class="nav-item">
                        <a href="{{ route('dashboard.home') }}"
                            class="nav-link {{ Request::routeIs('dashboard.home') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>الرئيسية</p>
                        </a>
                    </li>

                    {{-- 2. القسم التقني --}}
                    @php
                        $technicalPermissions = [
                            'station_reports.view',
                            'units.view',
                            'towns.view',
                            'stations.view',
                            'wells.view',
                            'generation_groups.view',
                            'solar_energies.view',
                            'horizontal_pumps.view',
                            'ground_tanks.view',
                            'elevated_tanks.view',
                            'pumping_sectors.view',
                            'electricity_hours.view',
                            'electricity_transformers.view',
                            'privet_wells.view',
                            'infiltrators.view',
                            'filters.view',
                            'manholes.view',
                            'diesel_tanks.view',
                        ];
                        $technicalRoutes = [
                            'dashboard.station_reports.*',
                            'dashboard.waterwells2.*',
                            'dashboard.units.*',
                            'dashboard.towns.*',
                            'dashboard.stations.*',
                            'dashboard.wells.*',
                            'dashboard.generation-groups.*',
                            'dashboard.solar-energies.*',
                            'dashboard.disinfection-pumps.*',
                            'dashboard.horizontal-pumps.*',
                            'dashboard.ground-tanks.*',
                            'dashboard.elevated-tanks.*',
                            'dashboard.pumping-sectors.*',
                            'dashboard.electricity-hours.*',
                            'dashboard.electricity-transformers.*',
                            'dashboard.private-wells.*',
                            'dashboard.infiltrators.*',
                            'dashboard.filters.*',
                            'dashboard.manholes.*',
                            'dashboard.diesel-tanks.*',
                        ];
                    @endphp
                    <x-sidebar-menu-section title="القسم التقني" icon="fas fa-desktop" :permissions="$technicalPermissions" :routes="$technicalRoutes">
                        @can('station_reports.view')
                            <li class="nav-item"><a href="{{ route('dashboard.station_reports.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.station_reports.*') ? 'active' : '' }}"><i
                                        class="fas fa-chart-bar nav-icon"></i>
                                    <p>تقارير المحطات</p>
                                </a></li>
                        @endcan
                        @can('waterwells2.view')
                            <li class="nav-item"><a href="{{ route('dashboard.waterwells2.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.waterwells2.*') ? 'active' : '' }}"><i
                                        class="fas fa-clipboard-check nav-icon"></i>
                                    <p>تدقيق تقارير</p>
                                </a></li>
                        @endcan
                        @can('units.view')
                            <li class="nav-item"><a href="{{ route('dashboard.units.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.units.*') ? 'active' : '' }}"><i
                                        class="fas fa-building nav-icon"></i>
                                    <p>استعراض الوحدات</p>
                                </a></li>
                        @endcan
                        @can('towns.view')
                            <li class="nav-item"><a href="{{ route('dashboard.towns.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.towns.*') ? 'active' : '' }}"><i
                                        class="fas fa-city nav-icon"></i>
                                    <p>استعراض البلدات</p>
                                </a></li>
                        @endcan
                        @can('stations.view')
                            <li class="nav-item"><a href="{{ route('dashboard.stations.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.stations.*') ? 'active' : '' }}"><i
                                        class="fas fa-broadcast-tower nav-icon"></i>
                                    <p>المحطات</p>
                                </a></li>
                        @endcan
                        @can('wells.view')
                            <li class="nav-item"><a href="{{ route('dashboard.wells.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.wells.*') ? 'active' : '' }}"><i
                                        class="fas fa-water nav-icon"></i>
                                    <p>الآبار</p>
                                </a></li>
                        @endcan
                        @can('generation_groups.view')
                            <li class="nav-item"><a href="{{ route('dashboard.generation-groups.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.generation-groups.*') ? 'active' : '' }}"><i
                                        class="fas fa-bolt nav-icon"></i>
                                    <p>مجموعات التوليد</p>
                                </a></li>
                        @endcan
                        @can('solar_energies.view')
                            <li class="nav-item"><a href="{{ route('dashboard.solar_energy.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.solar_energy.*') ? 'active' : '' }}"><i
                                        class="fas fa-solar-panel nav-icon"></i>
                                    <p>الطاقة الشمسية</p>
                                </a></li>
                        @endcan
                        @can('disinfection_pumps.view')
                            <li class="nav-item"><a href="{{ route('dashboard.disinfection_pumps.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.disinfection_pumps.*') ? 'active' : '' }}"><i
                                        class="fas fa-shield-virus nav-icon"></i>
                                    <p>التعقيم</p>
                                </a></li>
                        @endcan
                        @can('horizontal_pumps.view')
                            <li class="nav-item"><a href="{{ route('dashboard.horizontal-pumps.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.horizontal-pumps.*') ? 'active' : '' }}"><i
                                        class="fas fa-exchange-alt nav-icon"></i>
                                    <p>المضخات الأفقية</p>
                                </a></li>
                        @endcan
                        @can('ground_tanks.view')
                            <li class="nav-item"><a href="{{ route('dashboard.ground-tanks.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.ground-tanks.*') ? 'active' : '' }}"><i
                                        class="fas fa-box-open nav-icon"></i>
                                    <p>الخزانات الأرضية</p>
                                </a></li>
                        @endcan
                        @can('elevated_tanks.view')
                            <li class="nav-item"><a href="{{ route('dashboard.elevated-tanks.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.elevated-tanks.*') ? 'active' : '' }}"><i
                                        class="fas fa-archway nav-icon"></i>
                                    <p>الخزانات العالية</p>
                                </a></li>
                        @endcan
                        @can('pumping_sectors.view')
                            <li class="nav-item"><a href="{{ route('dashboard.pumping-sectors.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.pumping-sectors.*') ? 'active' : '' }}"><i
                                        class="fas fa-network-wired nav-icon"></i>
                                    <p>قطاع الضخ</p>
                                </a></li>
                        @endcan
                        @can('electricity_hours.view')
                            <li class="nav-item"><a href="{{ route('dashboard.electricity-hours.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.electricity-hours.*') ? 'active' : '' }}"><i
                                        class="fas fa-hourglass-half nav-icon"></i>
                                    <p>ساعات الكهرباء</p>
                                </a></li>
                        @endcan
                        @can('electricity_transformers.view')
                            <li class="nav-item"><a href="{{ route('dashboard.electricity-transformers.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.electricity-transformers.*') ? 'active' : '' }}"><i
                                        class="fas fa-plug nav-icon"></i>
                                    <p>محولات الكهرباء</p>
                                </a></li>
                        @endcan
                        @can('privet_wells.view')
                            <li class="nav-item"><a href="{{ route('dashboard.private-wells.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.private-wells.*') ? 'active' : '' }}"><i
                                        class="fas fa-user-lock nav-icon"></i>
                                    <p>الآبار الخاصة</p>
                                </a></li>
                        @endcan
                        @can('infiltrators.view')
                            <li class="nav-item"><a href="{{ route('dashboard.infiltrators.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.infiltrators.*') ? 'active' : '' }}"><i
                                        class="fas fa-vial nav-icon"></i>
                                    <p>الانفلترات</p>
                                </a></li>
                        @endcan
                        @can('filters.view')
                            <li class="nav-item"><a href="{{ route('dashboard.filters.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.filters.*') ? 'active' : '' }}"><i
                                        class="fas fa-filter nav-icon"></i>
                                    <p>المرشحات</p>
                                </a></li>
                        @endcan
                        @can('manholes.view')
                            <li class="nav-item"><a href="{{ route('dashboard.manholes.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.manholes.*') ? 'active' : '' }}"><i
                                        class="fas fa-dungeon nav-icon"></i>
                                    <p>المناهل</p>
                                </a></li>
                        @endcan
                        @can('diesel_tanks.view')
                            <li class="nav-item"><a href="{{ route('dashboard.diesel_tanks.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.diesel_tanks.*') ? 'active' : '' }}"><i
                                        class="fas fa-gas-pump nav-icon"></i>
                                    <p>خزانات الديزل</p>
                                </a></li>
                        @endcan
                    </x-sidebar-menu-section>

                    {{-- 3. قسم الصيانة --}}
                    @php
                        $maintenancePermissions = ['maintenance_tasks.view'];
                        $maintenanceRoutes = ['dashboard.maintenance_tasks.*'];
                    @endphp
                    <x-sidebar-menu-section title="قسم الصيانة" icon="fas fa-wrench" :permissions="$maintenancePermissions" :routes="$maintenanceRoutes">
                        @can('maintenance_tasks.view')
                            <li class="nav-item">
                                <a href="{{ route('dashboard.maintenance_tasks.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.maintenance_tasks.*') ? 'active' : '' }}">
                                    <i class="fas fa-tools nav-icon"></i>
                                    <p>مهام الصيانة</p>
                                </a>
                            </li>
                        @endcan
                    </x-sidebar-menu-section>
                    @php
                        $projectManagementPermissions = ['projects.view'];
                        $projectManagementRoutes = ['dashboard.projects.*'];
                    @endphp
                    <x-sidebar-menu-section title="دائرة المشاريع" icon="fas fa-project-diagram" :permissions="$projectManagementPermissions"
                        :routes="$projectManagementRoutes">
                        @can('projects.view')
                            <li class="nav-item">
                                <a href="{{ route('dashboard.projects.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.projects.*') ? 'active' : '' }}">
                                    <i class="fas fa-folder-open nav-icon"></i>
                                    <p>مشاريع منظمات</p>
                                </a>
                            </li>
                        @endcan
                        {{-- يمكنك إضافة روابط أخرى متعلقة بالمشاريع هنا مستقبلاً --}}
                    </x-sidebar-menu-section>
                    <li class="nav-header">الإعدادات</li>

                    {{-- 4. إدارة النظام --}}
                    @php
                        $settingsPermissions = ['users.view', 'roles.view'];
                        $settingsRoutes = ['dashboard.users.*', 'dashboard.roles.*'];
                    @endphp
                    <x-sidebar-menu-section title="إدارة النظام" icon="fas fa-cogs" :permissions="$settingsPermissions" :routes="$settingsRoutes">
                        @can('users.view')
                            <li class="nav-item"><a href="{{ route('dashboard.users.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.users.*') ? 'active' : '' }}"><i
                                        class="fas fa-users nav-icon"></i>
                                    <p>إدارة المستخدمين</p>
                                </a></li>
                        @endcan
                        @can('roles.view')
                            <li class="nav-item"><a href="{{ route('dashboard.roles.index') }}"
                                    class="nav-link {{ Request::routeIs('dashboard.roles.*') ? 'active' : '' }}"><i
                                        class="fas fa-user-shield nav-icon"></i>
                                    <p>الأدوار والصلاحيات</p>
                                </a></li>
                        @endcan
                    </x-sidebar-menu-section>

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
        @endauth
    </div>
</aside>
