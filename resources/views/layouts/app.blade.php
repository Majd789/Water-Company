<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    {{-- Other meta tags and links --}}
    @stack('head_meta') {{-- A new stack for head content --}}
    @stack('styles')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Water Db</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />

    <link href="{{ asset('css/home.css') }}" rel="stylesheet">

    @stack('styles') {{-- This will include page-specific styles --}}
</head>

<body>
    @stack('dashboard') {{-- Assuming this stack is for dashboard specific layout/elements --}}

    @php
        // Define common image assets
        $imgsuperA = asset('img/superA.png');
        $imgsuper = asset('img/super.png');
        $img1 = asset('img/1.png'); // Consider renaming to a more descriptive name if used
        $img2 = asset('img/2.jpg'); // Consider renaming to a more descriptive name if used
        $img3 = asset('img/3.jpg'); // Consider renaming to a more descriptive name if used
        $plus = asset('img/plus.png'); // Consider renaming to a more descriptive name if used
        $profile2 = asset('img/profile-2.jpg'); // Consider renaming to a more descriptive name if used
        $profile3 = asset('img/profile-3.jpg'); // Consider renaming to a more descriptive name if used
        $profile4 = asset('img/profile-4.jpg'); // Consider renaming to a more descriptive name if used
    @endphp

    @if (auth()->check() && auth()->user()->role_id == 'unknown')
        {{-- Display for 'unknown' role --}}
        <div class="unauthorized-access-message">
            <h1>الانشاء فقط من عند غازي خليل</h1>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <span class="material-icons-sharp">logout</span>
                    تسجيل خروج
                </button>
            </form>
        </div>
    @else
        {{-- Main application layout for authenticated users --}}
        <div class="container">
            <aside>
                @php
                    $userRole = auth()->check() ? auth()->user()->role_id : 'guest';
                    $imgSrc = $img2; // Default image
                    $roleText = $userRole; // Default role text

                    switch ($userRole) {
                        case 'superA':
                            $imgSrc = $imgsuperA;
                            $roleText = 'المدير العام';
                            break;
                        case 'super':
                            $imgSrc = $imgsuper;
                            $roleText = 'مشرف وحدة';
                            break;
                        case 'admin':
                            $imgSrc = $imgsuper; // Assuming 'super' image for 'admin'
                            $roleText = 'القسم التقني';
                            break;
                        // Add more cases for other roles if needed
                    }
                @endphp

                <div class="toggle">
                    <div class="logo">
                        <img src="{{ $imgSrc }}" alt="شعار">
                        <h2><span class="danger">{{ $roleText }}</span></h2>
                    </div>
                    <div class="close" id="close-btn">
                        <span class="material-icons-sharp">close</span>
                    </div>
                </div>

                <div class="sidebar">
                    <a href="/dashboard" class="{{ Request::is('dashboard') ? 'active' : '' }}">
                        <span class="material-icons-sharp">dashboard</span>
                        <h3>الرئيسية</h3>
                    </a>
                    {{-- <a href="{{ route('weekly_reports.index') }}"
                        class="{{ Request::is('weekly_reports*') ? 'active' : '' }}">
                        <span class="material-icons-sharp">people</span>
                        <h3>التقرير الاسبوعي</h3>
                    </a>}}

                    <a href="{{ route('weekly_reports.news') }}"
                        class="{{ Request::is('weekly_reports/news') ? 'active' : '' }}">
                        <span class="material-icons-sharp">article</span> {{-- Changed icon for clarity
                        <h3>استعراض التقارير</h3>
                    </a> --}}
                    <a href="{{ route('station_reports.index') }}"
                        class="{{ Request::is('station_reports*') ? 'active' : '' }}">
                        <span class="material-icons-sharp">description</span>
                        <h3>تقارير المحطات</h3>
                    </a>

                    @if (auth()->check() && in_array(auth()->user()->role_id, ['admin', 'super']))
                        <a href="{{ route('waterwells2.index') }}"
                            class="{{ Request::is('waterwells2*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">fact_check</span>
                            <h3>تدقيق تقارير</h3>
                        </a>
                        {{-- <a href="{{ route('maintenances.index') }}"
                            class="{{ Request::is('maintenances*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">build</span>
                            <h3>صيانة للمحطات</h3>
                        </a> --}}

                        @if (auth()->user()->role_id == 'admin')
                            <a href="{{ route('units.index') }}" class="{{ Request::is('units*') ? 'active' : '' }}">
                                <span class="material-icons-sharp">apartment</span>
                                <h3>استعراض الوحدات</h3>
                            </a>
                        @endif

                        <a href="{{ route('towns.index') }}" class="{{ Request::is('towns*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">location_city</span>
                            <h3>استعراض البلدات</h3>
                        </a>
                        <a href="{{ route('stations.index') }}"
                            class="{{ Request::is('stations*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">place</span>
                            <h3>المحطات</h3>
                        </a>
                        <a href="{{ route('wells.index') }}" class="{{ Request::is('wells*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">water_drop</span>
                            <h3>الآبار</h3>
                        </a>
                        <a href="{{ route('generation-groups.index') }}"
                            class="{{ Request::is('generation-groups*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">bolt</span>
                            <h3>مجموعات التوليد</h3>
                        </a>
                        <a href="{{ route('disinfection_pumps.index') }}"
                            class="{{ Request::is('disinfection_pumps*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">sanitizer</span>
                            <h3>التعقيم</h3>
                        </a>
                        <a href="{{ route('horizontal-pumps.index') }}"
                            class="{{ Request::is('horizontal-pumps*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">swap_vert</span>
                            <h3>المضخات الأفقية</h3>
                        </a>
                        <a href="{{ route('ground-tanks.index') }}"
                            class="{{ Request::is('ground-tanks*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">inventory_2</span>
                            <h3>الخزانات الأرضية</h3>
                        </a>
                        <a href="{{ route('elevated-tanks.index') }}"
                            class="{{ Request::is('elevated-tanks*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">inventory_2</span>
                            <h3>الخزانات العالية</h3>
                        </a>
                        <a href="{{ route('pumping-sectors.index') }}"
                            class="{{ Request::is('pumping-sectors*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">sync_alt</span>
                            <h3>قطاع الضخ</h3>
                        </a>
                        <a href="{{ route('electricity-hours.index') }}"
                            class="{{ Request::is('electricity-hours*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">schedule</span>
                            <h3>ساعات الكهرباء</h3>
                        </a>
                        <a href="{{ route('electricity-transformers.index') }}"
                            class="{{ Request::is('electricity-transformers*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">transform</span>
                            <h3>محولات الكهرباء</h3>
                        </a>
                        <a href="{{ route('private-wells.index') }}"
                            class="{{ Request::is('private-wells*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">lock</span>
                            <h3>الآبار الخاصة</h3>
                        </a>
                        <a href="{{ route('infiltrators.index') }}"
                            class="{{ Request::is('infiltrators*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">opacity</span>
                            <h3>الانفلترات</h3>
                        </a>
                        <a href="{{ route('filters.index') }}" class="{{ Request::is('filters*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">filter_list</span>
                            <h3>المرشحات</h3>
                        </a>
                        <a href="{{ route('manholes.index') }}"
                            class="{{ Request::is('manholes*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">construction</span>
                            <h3>المناهل</h3>
                        </a>
                        <a href="{{ route('solar_energy.index') }}"
                            class="{{ Request::is('solar_energy*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">solar_power</span>
                            <h3>الطاقة الشمسية</h3>
                        </a>
                        <a href="{{ route('diesel_tanks.index') }}"
                            class="{{ Request::is('diesel_tanks*') ? 'active' : '' }}">
                            <span class="material-icons-sharp">local_gas_station</span>
                            <h3>خزانات الديزل</h3>
                        </a>
                        {{--   <a href="{{ route('stations.map') }}"
                            class="{{ Request::is('stations/map') ? 'active' : '' }}">
                            <span class="material-icons-sharp">map</span>
                            <h3>مرجع المواقع</h3>
                        </a> --}}

                    @endif

                    {{-- Logout link --}}
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                        class="logout-link">
                        <span class="material-icons-sharp">logout</span>
                        <h3>تسجيل خروج</h3>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                </div>
            </aside>
            <main class="@stack('main-class')">
                @yield('content')
            </main>
            <div class="right-section">
                <div class="nav">
                    <button id="menu-btn">
                        <span class="material-icons-sharp">menu</span>
                    </button>
                    <div class="dark-mode">
                        <span class="material-icons-sharp active">light_mode</span>
                        <span class="material-icons-sharp">dark_mode</span>
                    </div>
                </div>
                <div class="user-profile">
                    <a href="{{ route('activity-log.index') }}">
                        <div class="logo">
                            <img src="{{ $img3 }}" alt="الفريق التقني">
                            <h2>الفريق التقني</h2>
                        </div>
                    </a>
                </div>

                <div class="reminders">
                    <div class="header">
                        <h2>اتصل بنا</h2>
                        <span class="material-icons-sharp">notifications_none</span>
                    </div>
                    <div class="notification deactive">
                        <div class="icon">
                            <span class="material-icons-sharp">edit</span>
                        </div>
                        <div class="content">
                            <div class="info">
                                <a href="{{ route('notes.index') }}">
                                    <h2>قسم الملاحظات</h2>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="notification add-reminder">
                        <a href="{{ route('notes.create') }}">
                            <div>
                                <span class="material-icons-sharp">add</span>
                                <h2>ارسل ملاحظة</h2>
                            </div>
                        </a>
                    </div>

                    <a href="{{ route('export.all') }}" class="btn btn-success export-all-btn">
                        <div class="notification add-reminder">
                            <div>
                                <h2 style="text-align: center">📥 تصدير جميع البيانات بنقرة </h2>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>

    <script src="{{ asset('js/home.js') }}" defer></script>
    <script src="{{ asset('js/index.js') }}" defer></script>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js" defer></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js" defer></script>

</body>

</html>
