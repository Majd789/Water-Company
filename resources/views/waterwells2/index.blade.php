@extends('layouts.app')

@section('content')
<div class="recent-orders text-center" style="text-align: center">
    <h2>قائمة المناهل</h2>
    
    <!-- زر إضافة منهل جديد -->
    <a id="btnb" href="{{ route('waterwells2.create') }}" class="btn btn-primary mb-3">إضافة تقرير للمناهل</a>
    <a id="btnb" href="{{ route('waterwells2.aggregated') }}" class="btn btn-primary mb-3">تدقيق تقرير المناهل الاجمالي</a>
    <form method="GET" action="{{ route('waterwells2.index') }}" class="mb-3">
        <select name="filter" id="filter" class="form-control d-inline-block w-auto">
            <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>جميع البيانات</option>
            <option value="incorrect" {{ request('filter') == 'incorrect' ? 'selected' : '' }}>البيانات الخاطئة فقط</option>
        </select>
    
        <!-- حقل اختيار التاريخ -->
        <input type="text" id="search" name="date_filter" value="{{ request('date_filter') }}"  placeholder="أدخل (مثال: 2025-01-08)" />
    
        <button style="margin: 10px" id="btnb" type="submit" class="btn btn-secondary">تحديث</button>
    </form>
    
    <form action="{{ route('waterwells2.destroy') }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button id="btnd" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف جميع التقارير الخاصة بوحدتك؟')">حذف انتهى التدقيق</button>
    </form>
    
    
    <!-- عرض البيانات في جدول -->
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>التاريخ</th>
                <th>اسم المنهل</th>           
                <th>التحقق من الكمية</th>
                <th>التحقق من السعر</th>
                <th>تسلسل العداد</th>
                <th>التحكم</th>
            </tr>
        </thead>
        <tbody>
            @foreach($filteredWells as $well)
                <tr>
                    <td>{{ $well->id }}</td>
                    <td>
                        @php
                            $formattedDate = null;
                            // التحقق إذا كان التاريخ رقماً (عدد الأيام)
                            if (is_numeric($well->date) && strlen($well->date) > 3) {
                                // تحويل الرقم إلى تاريخ بناءً على 1 يناير 1970
                                // 1970-01-01 هو البداية (epoch)
                                $formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d', '1970-01-01')
                                                               ->addDays($well->date - 25569)  // طرح 25569 لتحويل الأيام من 1900 إلى 1970
                                                               ->format('d/m/Y');
                            } else {
                                // إذا لم يكن رقمًا، نحاول تحويله باستخدام Carbon
                                try {
                                    $formattedDate = \Carbon\Carbon::parse($well->date)->format('d/m/Y');
                                } catch (\Exception $e) {
                                    $formattedDate = $well->date; // إذا فشل التحويل، عرض التاريخ كما هو
                                }
                            }
                        @endphp
                        {{ $formattedDate }}
                    </td>
                    <td>{{ $well->well_name }}</td>
                    <td>{{ $well->quantity_check }}</td>
                    <td>{{ $well->price_check }}</td>
                    <td>{{ $well->meter_sequence_check }}</td>
                    <td>
                        <a href="{{ route('waterwells2.show', $well->id) }}" class="btn btn-info btn-sm">عرض</a>
                        <a href="{{ route('waterwells2.edit', $well->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="{{ route('waterwells2.destroy', $well->id) }}" method="POST" style="display:inline-block;">
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
