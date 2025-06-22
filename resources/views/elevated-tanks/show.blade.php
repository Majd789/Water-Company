<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <h1 style="text-align: center"> {{ $elevatedTank->tank_name ?? 'غير متوفر' }}</h1>

    <!-- حاوية الكروت -->
    <div class="cards-container">
        <!-- الكرت 1: معلومات الخزان -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary">
                    معلومات الخزان
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>اسم الخزان</th>
                            <td>{{ $elevatedTank->tank_name ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>الجهة المنشئة</th>
                            <td>{{ $elevatedTank->building_entity ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ البناء</th>
                            <td>{{ $elevatedTank->construction_date ?? 'غير متوفر' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: سعة الخزان و الجاهزية -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    سعة الخزان و الجاهزية
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>سعة الخزان</th>
                            <td>{{ $elevatedTank->capacity ?? 'غير متوفر' }} م³</td>
                        </tr>
                        <tr>
                            <th>نسبة الجاهزية</th>
                            <td>{{ $elevatedTank->readiness_percentage ?? 'غير متوفر' }}%</td>
                        </tr>
                        <tr>
                            <th>ارتفاع الخزان</th>
                            <td>{{ $elevatedTank->height ?? 'غير متوفر' }} متر</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: شكل الخزان و المحطة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    شكل الخزان و المحطة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>شكل الخزان</th>
                            <td>{{ $elevatedTank->tank_shape ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>المحطة التي تعبئه</th>
                            <td>{{ $elevatedTank->feeding_station ?? 'غير متوفر' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 4: البلدة و الأبعاد -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-warning">
                    البلدة و الأبعاد
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>البلدة التي تشرب منه</th>
                            <td>{{ $elevatedTank->town_supply ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>قطر البوري (الدخول)</th>
                            <td>{{ $elevatedTank->in_pipe_diameter ?? 'غير متوفر' }} مم</td>
                        </tr>
                        <tr>
                            <th>قطر البوري (الخروج)</th>
                            <td>{{ $elevatedTank->out_pipe_diameter ?? 'غير متوفر' }} مم</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 5: الموقع و الملاحظات -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-secondary">
                    الموقع و الملاحظات
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>موقع الخزان (خط العرض)</th>
                            <td>{{ $elevatedTank->latitude ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>موقع الخزان (خط الطول)</th>
                            <td>{{ $elevatedTank->longitude ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>الارتفاع</th>
                            <td>{{ $elevatedTank->altitude ?? 'غير متوفر' }} متر</td>
                        </tr>
                        <tr>
                            <th>دقة الموقع</th>
                            <td>{{ $elevatedTank->precision ?? 'غير متوفر' }} متر</td>
                        </tr>
                        <tr>
                            <th>ملاحظات</th>
                            <td>{{ $elevatedTank->notes ?? 'غير متوفر' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align: center" class="text-center">
        <!-- زر العودة -->
        <a href="{{ route('elevated-tanks.index') }}" class="btn btn-primary">العودة إلى القائمة</a>
    </div>

@endsection
