@extends('layouts.app')

@section('content')
<div class="recent-orders" style="text-align: center">
    <h1>تفاصيل المرشح</h1>

    <table class="table table-bordered">
        <tr>
            <th>المحطة</th>
            <td>{{ $filter->station->station_name }}</td>
        </tr>
        <tr>
            <th>استطاعة المرشح</th>
            <td>{{ $filter->filter_capacity }}</td>
        </tr>
        <tr>
            <th>حالة الجاهزية</th>
            <td>{{ $filter->readiness_status }}</td>
        </tr>
        <tr>
            <th>نوع المرشح</th>
            <td>{{ $filter->filter_type }}</td>
        </tr>
        <tr>
            <th>الملاحظات</th>
            <td>{{ $filter->notes ?? 'لا توجد ملاحظات' }}</td>
        </tr>
    </table>

    <a href="{{ route('filters.index') }}" class="btn btn-primary">العودة إلى القائمة</a>
</div>
@endsection