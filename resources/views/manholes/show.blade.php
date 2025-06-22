<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <h1 style="text-align: center">{{ $manhole->manhole_name }}</h1>

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
                            <th>المحطة</th>
                            <td>{{ $manhole->station->station_name }}</td>
                        </tr>
                        <tr>
                            <th>الوحدة</th>
                            <td>{{ $manhole->unit->unit_name }}</td>
                        </tr>
                        <tr>
                            <th>البلدة</th>
                            <td>{{ $manhole->town->town_name }}</td>
                        </tr>
                        <tr>
                            <th>اسم المنهل</th>
                            <td>{{ $manhole->manhole_name }}</td>
                        </tr>
                        <tr>
                            <th>الحالة</th>
                            <td>{{ $manhole->status }}</td>
                        </tr>
                        <tr>
                            <th>سبب التوقف</th>
                            <td>{{ $manhole->stop_reason ?? 'لا يوجد' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: العداد والمعدات -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    العداد والمعدات
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>هل يوجد عداد غزارة</th>
                            <td>{{ $manhole->has_flow_meter ? 'نعم' : 'لا' }}</td>
                        </tr>
                        <tr>
                            <th>رقم الشاسيه</th>
                            <td>{{ $manhole->chassis_number ?? 'لا يوجد' }}</td>
                        </tr>
                        <tr>
                            <th>قطر العداد</th>
                            <td>{{ $manhole->meter_diameter ?? 'لا يوجد' }}</td>
                        </tr>
                        <tr>
                            <th>حالة العداد</th>
                            <td>{{ $manhole->meter_status ?? 'لا يوجد' }}</td>
                        </tr>
                        <tr>
                            <th>طريقة عمل العداد بالمتر</th>
                            <td>{{ $manhole->meter_operation_method_in_meter ?? 'لا يوجد' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: خزان تجميع المياه -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    خزان تجميع المياه
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>هل يوجد خزان تجميعي</th>
                            <td>{{ $manhole->has_storage_tank ? 'نعم' : 'لا' }}</td>
                        </tr>
                        <tr>
                            <th>سعة الخزان</th>
                            <td>{{ $manhole->tank_capacity ?? 'لا يوجد' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 4: الملاحظات -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-warning">
                    الملاحظات
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>الملاحظات</th>
                            <td>{{ $manhole->general_notes ?? 'لا يوجد' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
