@extends('layouts.app')

@section('content')

<div class="recent-orders" style="text-align: center">
    <h1>تفاصيل الوحدة</h1>

    <table class="table table-bordered">
        <tr>
            <th>اسم الوحدة</th>
            <td>{{ $unit->unit_name }}</td>
        </tr>
        <tr>
            <th>ملاحظات عامة</th>
            <td>{{ $unit->general_notes ?? 'لا توجد ملاحظات.' }}</td>
        </tr>
        <tr>
            <th>المحافظة</th>
            <td>{{ $unit->governorate->name ?? 'لا توجد محافظة.' }}</td> <!-- عرض اسم المحافظة -->
        </tr>
    </table>

    <a href="{{ route('units.index') }}" class="btn btn-primary">الرجوع إلى القائمة</a>
</div>
@endsection
