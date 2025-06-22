<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <!-- حاوية الكروت -->
    <div class="cards-container">
        <!-- الكرت 1: معلومات القسم -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary">
                    معلومات القسم
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>المحطة</th>
                            <td>{{ $PumpingSector->station->station_name }}</td>
                        </tr>
                        <tr>
                            <th>البلدة</th>
                            <td>{{ $PumpingSector->town->town_name }}</td>
                        </tr>
                        <tr>
                            <th>اسم القسم</th>
                            <td>{{ $PumpingSector->sector_name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- الكرت 2: الملاحظات -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    الملاحظات
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>ملاحظات</th>
                            <td>{{ $PumpingSector->notes ? $PumpingSector->notes : 'لا توجد ملاحظات' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="text-align: center" class="text-center">
                <a href="{{ route('pumping-sectors.index') }}" class="btn btn-primary">العودة إلى القائمة</a>
            </div>
        </div>

    </div>


    <!-- زر العودة -->
@endsection
