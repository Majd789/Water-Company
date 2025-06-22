<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <h1 style="text-align: center"> {{ $groundTank->tank_name ?? 'غير متوفر' }}</h1>

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
                            <td>{{ $groundTank->tank_name ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>الجهة المنشئة</th>
                            <td>{{ $groundTank->building_entity ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>نوع البناء</th>
                            <td>{{ $groundTank->construction_type ?? 'غير متوفر' }}</td>
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
                            <td>{{ $groundTank->capacity ?? 'غير متوفر' }} م³</td>
                        </tr>
                        <tr>
                            <th>نسبة الجاهزية</th>
                            <td>{{ $groundTank->readiness_percentage ?? 'غير متوفر' }}%</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: المحطة والبلدة -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    المحطة والبلدة
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>المحطة التي تعبئه</th>
                            <td>{{ $groundTank->feeding_station ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>البلدة التي تشرب منه</th>
                            <td>{{ $groundTank->town_supply ?? 'غير متوفر' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 4: الموقع و الارتفاع -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-warning">
                    الموقع و الارتفاع
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>قطر البوري</th>
                            <td>
                                @php
                                    $diameter = $groundTank->pipe_diameter ?? null;
                                    if ($diameter !== null) {
                                        if ($diameter <= 15) {
                                            $diameter .= ' إنش';
                                        } else {
                                            $diameter = round($diameter / 25.4, 2) . ' مم';
                                        }
                                    } else {
                                        $diameter = 'غير متوفر';
                                    }
                                @endphp
                                {{ $diameter }}
                            </td>
                        </tr>

                        <tr>
                            <th>موقع الخزان (خط العرض)</th>
                            <td>{{ $groundTank->latitude ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>موقع الخزان (خط الطول)</th>
                            <td>{{ $groundTank->longitude ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>الارتفاع</th>
                            <td>{{ $groundTank->altitude ?? 'غير متوفر' }} متر</td>
                        </tr>
                        <tr>
                            <th>دقة الموقع</th>
                            <td>{{ $groundTank->precision ?? 'غير متوفر' }} متر</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align: center" class="text-center">
        <!-- زر العودة -->
        <a href="{{ route('ground-tanks.index') }}" class="btn btn-primary">العودة إلى القائمة</a>
    </div>
@endsection
