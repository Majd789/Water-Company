<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <h1 style="text-align: center">تفاصيل ساعة الكهرباء</h1>

    <!-- حاوية الكروت -->
    <div class="cards-container">
        <!-- الكرت 1: اسم المحطة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    اسم المحطة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>المحطة</th>
                            <td>{{ $electricityHour->station->station_name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: بيانات العداد -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success text-white">
                    بيانات العداد
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>رقم ساعة الكهرباء</th>
                            <td>{{ $electricityHour->electricity_hour_number }}</td>
                        </tr>
                        <tr>
                            <th>نوع العداد</th>
                            <td>{{ $electricityHour->meter_type }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: عدد ساعات الكهرباء -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    عدد ساعات الكهرباء
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>عدد ساعات الكهرباء</th>
                            <td>{{ $electricityHour->electricity_hours }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 4: الجهة المشغلة والملاحظات -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info text-white">
                    الجهة المشغلة والملاحظات
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>الجهة المشغلة</th>
                            <td>{{ $electricityHour->operating_entity }}</td>
                        </tr>
                        <tr>
                            <th>الملاحظات</th>
                            <td>{{ $electricityHour->notes ?? 'لا توجد ملاحظات' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
