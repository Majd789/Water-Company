<link href="{{ asset('css/show.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')
    <h1 style="text-align: center">تفاصيل محطة الطاقة الشمسية</h1>

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
                            <td>{{ $solarEnergy->station->station_name }}</td>
                        </tr>
                        <tr>
                            <th>الجهة المنشئة</th>
                            <td>{{ $solarEnergy->manufacturer }}</td>
                        </tr>
                        <tr>
                            <th>الحالة الفنية</th>
                            <td>{{ $solarEnergy->technical_condition }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: تفاصيل الألواح الشمسية -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    تفاصيل الألواح الشمسية
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>حجم اللوح</th>
                            <td>{{ $solarEnergy->panel_size }} متر مربع</td>
                        </tr>
                        <tr>
                            <th>عدد الألواح</th>
                            <td>{{ $solarEnergy->panel_count }}</td>
                        </tr>
                        <tr>
                            <th>نوع القاعدة</th>
                            <td>{{ $solarEnergy->base_type }}</td>
                        </tr>
                        <tr>
                            <th>عدد الآبار المغذاة</th>
                            <td>{{ $solarEnergy->wells_supplied_count }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 3: الموقع -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    الموقع
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>الموقع</th>
                            <td>
                                @if ($solarEnergy->latitude && $solarEnergy->longitude)
                                    <a href="https://www.google.com/maps?q={{ $solarEnergy->latitude }},{{ $solarEnergy->longitude }}"
                                        target="_blank">عرض الموقع على الخريطة</a>
                                @else
                                    لا يوجد موقع
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
                            <th>الملاحظات</th>
                            <td>{{ $solarEnergy->general_notes ?? 'لا توجد ملاحظات' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- زر الرجوع إلى قائمة المحطات -->
@endsection
