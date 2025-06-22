<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <h1 style="text-align: center">{{ $privateWell->well_name }}</h1>

    <!-- حاوية الكروت -->
    <div class="cards-container">
        <!-- الكرت 1: تفاصيل البئر -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary">
                    تفاصيل البئر
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>اسم البئر</th>
                            <td>{{ $privateWell->well_name }}</td>
                        </tr>
                        <tr>
                            <th>عدد الآبار</th>
                            <td>{{ $privateWell->well_count }}</td>
                        </tr>
                        <tr>
                            <th>المسافة من أقرب بئر</th>
                            <td>{{ $privateWell->distance_from_nearest_well }}</td>
                        </tr>
                        <tr>
                            <th>نوع البئر</th>
                            <td>{{ $privateWell->well_type }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: المحطة المرتبطة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    المحطة المرتبطة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>المحطة</th>
                            <td>{{ $privateWell->station->station_name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: الموقع الجغرافي -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    الموقع الجغرافي
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>الإحداثيات</th>
                            <td>
                                @if ($privateWell->latitude && $privateWell->longitude)
                                    <a href="https://www.google.com/maps?q={{ $privateWell->latitude }},{{ $privateWell->longitude }}"
                                        target="_blank">عرض الموقع على الخريطة</a>
                                @else
                                    لا يوجد موقع متاح
                                @endif
                            </td>
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
                            <th>ملاحظات</th>
                            <td>{{ $privateWell->notes ?? 'لا توجد ملاحظات' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align: center" class="text-center mt-4">
        <a href="{{ route('private-wells.index') }}" class="btn btn-primary">عودة إلى القائمة</a>
    </div>
@endsection
