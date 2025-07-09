@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تفاصيل تقرير المحطة</h2>
        <table class="table table-bordered">
            <tr>
                <th>كود المحطة</th>
                <td>{{ $report->station_code }}</td>
            </tr>
            <tr>
                <th>التاريخ</th>
                <td>{{ $report->date }}</td>
            </tr>
            <tr>
                <th>جهة التشغيل</th>
                <td>{{ $report->operator_entity }}</td>
            </tr>
            <tr>
                <th>مصدر الطاقة</th>
                <td>{{ $report->energy_source }}</td>
            </tr>
        </table>
        <a href="{{ route('dashboard.station_reports.index') }}" class="btn btn-secondary">العودة</a>
    </div>
@endsection
