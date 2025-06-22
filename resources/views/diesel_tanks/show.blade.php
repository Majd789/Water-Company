@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/show.css') }}" rel="stylesheet">



    <!-- حاوية الكروت -->
    <div class="cards-container">
        <h1 style="text-align: center"> {{ $dieselTank->station->station_name }} </h1>
        <!-- الكرت 1: تفاصيل الخزان -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary">
                    بيانات الخزان
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>المحطة:</th>
                            <td>{{ $dieselTank->station->station_name }}</td>
                        </tr>
                        <tr>
                            <th>اسم الخزان:</th>
                            <td>{{ $dieselTank->tank_name }}</td>
                        </tr>
                        <tr>
                            <th>سعة الخزان (لتر):</th>
                            <td>{{ $dieselTank->tank_capacity }}</td>
                        </tr>
                        <tr>
                            <th>نسبة الجاهزية (%):</th>
                            <td>{{ $dieselTank->readiness_percentage }}</td>
                        </tr>
                        <tr>
                            <th>خط العرض:</th>
                            <td>{{ $dieselTank->latitude ?? 'غير متوفر' }}</td>
                        </tr>
                        <tr>
                            <th>الملاحظات:</th>
                            <td>{{ $dieselTank->general_notes ?? 'لا توجد ملاحظات' }}</td>
                        </tr>
                    </table>
                </div>
                <a href="{{ route('diesel_tanks.index') }}" class="btn btn-primary mt-3">رجوع إلى القائمة</a>
            </div>
        </div>
    </div> <!-- زر الرجوع إلى قائمة الخزانات -->
@endsection
