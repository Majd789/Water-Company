<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <h1 style="text-align: center">{{ $well->well_name }}</h1>

    <!-- حاوية الكروت -->
    <div class="cards-container">
        <!-- الكرت 1: المعلومات الأساسية -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary">
                    المعلومات الأساسية
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>اسم البئر</th>
                            <td>{{ $well->well_name }}</td>
                        </tr>
                        <tr>
                            <th>المحطة</th>
                            <td>{{ $well->station->station_name }}</td>
                        </tr>
                        <tr>
                            <th>كود البلدة</th>
                            <td>{{ $well->town_code }}</td>
                        </tr>
                        <tr>
                            <th>الوضع التشغيلي</th>
                            <td>{{ $well->well_status == 'يعمل' ? 'تشغيل' : 'توقف' }}</td>
                        </tr>
                        <tr>
                            <th>سبب التوقف</th>
                            <td>{{ $well->stop_reason ?? 'غير محدد' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: بيانات الحفر -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    بيانات الحفر
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>العمق الستاتيكي</th>
                            <td>{{ $well->static_depth }} متر</td>
                        </tr>
                        <tr>
                            <th>العمق الديناميكي</th>
                            <td>{{ $well->dynamic_depth }} متر</td>
                        </tr>
                        <tr>
                            <th>العمق الحفر</th>
                            <td>{{ $well->drilling_depth }} متر</td>
                        </tr>
                        <tr>
                            <th>قطر البئر</th>
                            <td>{{ $well->well_diameter }} انش</td>
                        </tr>
                        <tr>
                            <th>نوع البئر</th>
                            <td>{{ $well->well_type }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: بيانات المضخة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    بيانات المضخة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>عمق تركيب المضخة</th>
                            <td>{{ $well->pump_installation_depth }} متر</td>
                        </tr>
                        <tr>
                            <th>استطاعة المضخة</th>
                            <td>{{ $well->pump_capacity }} حصان</td>
                        </tr>
                        <tr>
                            <th>تدفق المضخة الفعلي</th>
                            <td>{{ $well->actual_pump_flow }} متر مكعب/ساعة</td>
                        </tr>
                        <tr>
                            <th>رفع المضخة</th>
                            <td>{{ $well->pump_lifting }} متر</td>
                        </tr>
                        <tr>
                            <th>ماركة وموديل المضخة</th>
                            <td>{{ $well->pump_brand_model }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 4: تدفق البئر -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-warning">
                    غزارة البئر
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>تدفق البئر</th>
                            <td>{{ $well->well_flow }} متر مكعب/ساعة</td>
                        </tr>
                        <tr>
                            <th>بعده عن المحطة (م)</th>
                            <td>{{ $well->distance_from_station }} متر</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 5: الموقع والملاحظات -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-secondary">
                    الموقع والملاحظات
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>مصدر الطاقة</th>
                            <td>{{ $well->energy_source }}</td>
                        </tr>
                        <tr>
                            <th>عنوان البئر</th>
                            <td>{{ $well->well_address }}</td>
                        </tr>
                        <tr>
                            <th>الموقع</th>
                            <td>{{ $well->well_location ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <th>الملاحظات العامة</th>
                            <td>{{ $well->general_notes ?? 'لا توجد ملاحظات' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align: center" class="text-center">
        <a href="{{ route('wells.index') }}" class="btn btn-primary">الرجوع إلى قائمة الآبار</a>
    </div>
@endsection
