<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <h1 style="text-align: center">تفاصيل مضخة التعقيم</h1>

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
                            <th>رقم المضخة</th>
                            <td>{{ $disinfectionPump->id }}</td>
                        </tr>
                        <tr>
                            <th>اسم المحطة</th>
                            <td>{{ $disinfectionPump->station->station_name }}</td>
                        </tr>
                        <tr>
                            <th>الوضع التشغيلي</th>
                            <td>{{ $disinfectionPump->disinfection_pump_status }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: بيانات المضخة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    بيانات المضخة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>ماركة وطراز المضخة</th>
                            <td>{{ $disinfectionPump->pump_brand_model }}</td>
                        </tr>
                        <tr>
                            <th>غزارة المضخة</th>
                            <td>{{ $disinfectionPump->pump_flow_rate }} لتر/ساعة</td>
                        </tr>
                        <tr>
                            <th>ضغط العمل</th>
                            <td>{{ $disinfectionPump->operating_pressure }} بار</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: الحالة الفنية والملاحظات -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-warning">
                    الحالة الفنية والملاحظات
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>الحالة الفنية</th>
                            <td>{{ $disinfectionPump->technical_condition }}</td>
                        </tr>
                        <tr>
                            <th>ملاحظات</th>
                            <td>{{ $disinfectionPump->notes ?? 'لا توجد ملاحظات' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 4: مصدر الطاقة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    مصدر الطاقة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>نوع مصدر الطاقة</th>
                            <td>{{ $disinfectionPump->power_source }}</td>
                        </tr>
                        <tr>
                            <th>القدرة الكهربائية</th>
                            <td>{{ $disinfectionPump->power_capacity }} كيلوواط</td>
                        </tr>
                        <tr>
                            <th>ملاحظات حول الطاقة</th>
                            <td>{{ $disinfectionPump->power_notes ?? 'لا توجد ملاحظات' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align: center" class="text-center">
        <a href="{{ route('disinfection_pumps.index') }}" class="btn btn-primary">عودة إلى القائمة</a>
    </div>
@endsection
