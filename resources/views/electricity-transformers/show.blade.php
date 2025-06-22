<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <!-- حاوية الكروت -->
    <div class="cards-container">
        <!-- الكرت 1: المحطة والمعلومات الأساسية -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary">
                    المحطة والمعلومات الأساسية
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>المحطة</th>
                            <td>{{ $electricityTransformer->station->station_name }}</td>
                        </tr>
                        <tr>
                            <th>الوضع التشغيلي</th>
                            <td>{{ $electricityTransformer->operational_status == 'working' ? 'تعمل' : 'متوقفة' }}</td>
                        </tr>
                        <tr>
                            <th>استطاعة المحولة (KVA)</th>
                            <td>{{ $electricityTransformer->transformer_capacity }}</td>
                        </tr>
                        <tr>
                            <th>بعد المحولة عن المحطة (م)</th>
                            <td>{{ $electricityTransformer->distance_from_station }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: المحولة الخاصة بالمحطة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    المحولة الخاصة بالمحطة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>هل المحولة خاصة بالمحطة</th>
                            <td>{{ $electricityTransformer->is_station_transformer ? 'نعم' : 'لا' }}</td>
                        </tr>
                        <tr>
                            <th>الجهة المشتركة بالمحطة</th>
                            <td>{{ $electricityTransformer->talk_about_station_transformer }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: الاستطاعة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    الاستطاعة والتحقق
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>هل الاستطاعة كافية</th>
                            <td>{{ $electricityTransformer->is_capacity_sufficient ? 'نعم' : 'لا' }}</td>
                        </tr>
                        <tr>
                            <th>كمية الاستطاعة المطلوبة</th>
                            <td>{{ $electricityTransformer->how_mush_capacity_need }} KVA</td>
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
                            <td>{{ $electricityTransformer->notes }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
