@extends('layouts.app')

@section('content')
<div class="recent-orders" style="text-align: center">
    <h2>قائمة المناهل</h2>
    
    <!-- زر إضافة منهل جديد -->
    <a id="btnb" href="{{ route('waterwells.create') }}" class="btn btn-primary mb-3">إضافة تقرير للمناهل </a>
    <!-- فلتر عرض البيانات -->
    <form method="GET" action="{{ route('waterwells.index') }}" class="mb-3">
       
        <select name="filter" id="filter" class="form-control d-inline-block w-auto">
            <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>جميع البيانات</option>
            <option value="incorrect" {{ request('filter') == 'incorrect' ? 'selected' : '' }}>البيانات الخاطئة فقط</option>
        </select>
      
        <button style="margin: 10px" id="btnb" type="submit" class="btn btn-secondary">تحديث</button>
    </form>

    <!-- عرض البيانات في جدول -->
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
               
                <th>اسم المنهل</th>
                <th>الكمية المباعة</th>
                <th>السعر</th>
                <th>المبلغ</th>
                <th>التحقق من الكمية</th>
                <th>التحقق من السعر</th>
                <th> تسلسل العداد</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            @foreach($filteredWells as $well)
                <tr>
                    <td>{{ $well->id }}</td>
                    <td>{{ $well->well_name }}</td>
                    <td>{{ $well->water_sold_quantity }}</td>
                    <td>{{ $well->water_price }}</td>
                    <td>{{ $well->total_amount }}</td>
                    <td>{{ $well->quantity_check }}</td>
                    <td>{{ $well->price_check }}</td>
                    <td>{{ $well->meter_sequence_check }}</td>
                    <td>
                        <a href="{{ route('waterwells.show', $well->id) }}" class="btn btn-info btn-sm">عرض</a>
                        <a href="{{ route('waterwells.edit', $well->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="{{ route('waterwells.destroy', $well->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button id="btnd" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
