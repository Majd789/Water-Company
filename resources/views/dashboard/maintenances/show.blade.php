@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/show.css') }}" rel="stylesheet">

    <!-- حاوية الكروت -->
    <div class="cards-container">
        <!-- الكرت 1: معلومات المحطة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary">
                    معلومات المحطة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>المحطة</th>
                            <td>{{ $maintenance->station->station_name }}</td>
                        </tr>
                        <tr>
                            <th>نوع الصيانة</th>
                            <td>{{ $maintenance->maintenanceType->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: تفاصيل الصيانة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    تفاصيل الصيانة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>كمية الصيانة الإجمالية</th>
                            <td>{{ $maintenance->total_quantity }}</td>
                        </tr>
                        <tr>
                            <th>مواقع التنفيذ</th>
                            <td>{{ $maintenance->execution_sites }}</td>
                        </tr>
                        <tr>
                            <th>التكلفة الإجمالية</th>
                            <td>{{ $maintenance->total_cost }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الصيانة</th>
                            <td>{{ $maintenance->maintenance_date }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: الجهة المنفذة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    الجهة المنفذة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>اسم المقاول</th>
                            <td>{{ $maintenance->contractor_name }}</td>
                        </tr>
                        <tr>
                            <th>اسم الفني</th>
                            <td>{{ $maintenance->technician_name }}</td>
                        </tr>
                        <tr>
                            <th>حالة الصيانة</th>
                            <td>{{ $maintenance->status }}</td>
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
                            <td>{{ $maintenance->notes }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
