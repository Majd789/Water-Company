<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <h1 style="text-align: center"> {{ $horizontalPump->pump_name ?? 'غير متوفر' }}</h1>

    <!-- حاوية الكروت -->
    <div class="cards-container">
        <!-- الكرت 1: معلومات المضخة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary">
                    معلومات المضخة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>اسم المضخة</th>
                            <td>{{ $horizontalPump->pump_name ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>الحالة التشغيلية</th>
                            <td>{{ $horizontalPump->pump_status == 'working' ? 'يعمل' : 'متوقف' }}</td>
                        </tr>
                        <tr>
                            <th>اسم المحطة</th>
                            <td>{{ $horizontalPump->station->station_name ?? 'غير معروف' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: البيانات الفنية -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    البيانات الفنية
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>الاستطاعة (حصان)</th>
                            <td>{{ $horizontalPump->pump_capacity_hp ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>تدفق المضخة (م³/ساعة)</th>
                            <td>{{ $horizontalPump->pump_flow_rate_m3h ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>ارتفاع الضخ</th>
                            <td>{{ $horizontalPump->pump_head ?? 'غير متوفر' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: العلامة التجارية والحالة الفنية -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    العلامة التجارية والحالة الفنية
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>ماركة وطراز المضخة</th>
                            <td>{{ $horizontalPump->pump_brand_model ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>الحالة الفنية</th>
                            <td>{{ $horizontalPump->technical_condition ?? 'غير متوفر' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 4: مصدر الطاقة والملاحظات -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-warning">
                    مصدر الطاقة والملاحظات
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>مصدر الطاقة</th>
                            <td>{{ $horizontalPump->energy_source ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>ملاحظات</th>
                            <td>{{ $horizontalPump->notes ?? 'لا توجد ملاحظات' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div style="text-align: center" class="text-center">
    <!-- زر العودة -->
    <a href="{{ route('horizontal-pumps.index') }}" class="btn btn-primary">العودة إلى القائمة</a>
    </div>
@endsection
