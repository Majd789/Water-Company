<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- روابط الشريط العلوي اليمنى -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/') }}" class="nav-link">الرئيسية</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">اتصل بنا</a>
        </li>
    </ul>

    <!-- نموذج البحث (اختياري، يمكن إبقاؤه أو حذفه) -->
    <form class="form-inline mr-auto">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="بحث..." aria-label="بحث">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- روابط الشريط العلوي اليسرى -->
    <ul class="navbar-nav ml-auto">

        <!-- قائمة الرسائل المنسدلة -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
                <a href="#" class="dropdown-item">
                    <!-- بداية الرسالة -->
                    <div class="media">
                        <img src="{{ asset('dist/img/user1-128x128.jpg') }}" alt="صورة المستخدم" class="img-size-50 ml-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                فريق الدعم الفني
                                <span class="float-left text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">تم حل مشكلة التقرير...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock ml-1"></i> منذ 4 ساعات</p>
                        </div>
                    </div>
                    <!-- نهاية الرسالة -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- بداية الرسالة -->
                    <div class="media">
                        <img src="{{ asset('dist/img/user8-128x128.jpg') }}" alt="صورة المستخدم" class="img-size-50 ml-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                أحمد علي
                                <span class="float-left text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">شكرًا على المساعدة.</p>
                            <p class="text-sm text-muted"><i class="far fa-clock ml-1"></i> منذ 8 ساعات</p>
                        </div>
                    </div>
                    <!-- نهاية الرسالة -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">عرض كل الرسائل</a>
            </div>
        </li>

        <!-- قائمة الإشعارات المنسدلة -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">10</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
                <span class="dropdown-item dropdown-header">10 إشعارات جديدة</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file-invoice ml-2 text-primary"></i> 3 تقارير جديدة جاهزة
                    <span class="float-left text-muted text-sm">منذ 5 دقائق</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-exclamation-triangle ml-2 text-danger"></i> تنبيه جاهزية منخفضة لخزان
                    <span class="float-left text-muted text-sm">منذ 30 دقيقة</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users ml-2 text-info"></i> تمت إضافة 5 مستخدمين جدد
                    <span class="float-left text-muted text-sm">منذ 12 ساعة</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">عرض كل الإشعارات</a>
            </div>
        </li>

        <!-- أيقونة وضع ملء الشاشة -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- أيقونة الشريط الجانبي للإعدادات -->
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>

        <!-- قائمة المستخدم المنسدلة -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <div class="user-panel d-flex">
                    <div class="image">
                        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                    </div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
                <div class="dropdown-header d-flex flex-column align-items-center">
                    <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image" width="80">
                    <h5 class="mt-2 mb-0">{{ Auth::user()->name ?? 'اسم المستخدم' }}</h5>
                    <small>{{ Auth::user()->email ?? 'user@example.com' }}</small>
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user-cog ml-2"></i> الملف الشخصي
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cogs ml-2"></i> الإعدادات
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item text-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>

    </ul>
</nav>
<!-- /.navbar -->
