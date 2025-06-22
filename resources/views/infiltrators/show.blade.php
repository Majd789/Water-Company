@extends('layouts.app')

@section('content')
<div class="recent-orders" style="text-align: center">
        <h1>تفاصيل الانفلتر</h1>

        <table class="table">
            <tr>
                <th>المحطة</th>
                <td>{{ $infiltrator->station->station_name }}</td>
            </tr>
            <tr>
                <th>استطاعة الانفلتر</th>
                <td>{{ $infiltrator->infiltrator_capacity }}</td>
            </tr>
            <tr>
                <th>حالة الجاهزية</th>
                <td>{{ $infiltrator->readiness_status }}</td>
            </tr>
            <tr>
                <th>نوع الانفلتر</th>
                <td>{{ $infiltrator->infiltrator_type }}</td>
            </tr>
            <tr>
                <th>الملاحظات</th>
                <td>{{ $infiltrator->notes }}</td>
            </tr>
        </table>

        <a href="{{ route('infiltrators.index') }}" class="btn btn-primary">العودة إلى القائمة</a>
    </div>
@endsection
