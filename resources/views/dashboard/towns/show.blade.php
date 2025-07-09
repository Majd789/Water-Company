@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <h1>تفاصيل البلدة: {{ $town->town_name }}</h1>

        <table class="table table-bordered">
            <tr>
                <th>اسم البلدة</th>
                <td>{{ $town->town_name }}</td>
            </tr>
            <tr>
                <th>كود البلدة</th>
                <td>{{ $town->town_code }}</td>
            </tr>
            <tr>
                <th>الوحدة</th>
                <td>{{ $town->unit->unit_name }}</td>
            </tr>
            <tr>
                <th>الملاحظات العامة</th>
                <td>{{ $town->general_notes }}</td>
            </tr>
            <tr>
                <th>خط العرض</th>
                <td>{{ $town->latitude }}</td>
            </tr>
            <tr>
                <th>خط الطول</th>
                <td>{{ $town->longitude }}</td>
            </tr>

        </table>

        <a href="{{ route('dashboard.towns.index') }}" class="btn btn-primary">الرجوع إلى قائمة البلدات</a>
    </div>
@endsection
