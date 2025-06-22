@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <h1>قائمة العقارات المؤسسية</h1>

        <!-- رسالة نجاح -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- زر إضافة عقار جديد -->
        <div class="mb-3">
            <a href="{{ route('institution_properties.create') }}" class="btn btn-primary">إضافة عقار جديد</a>
        </div>

        <!-- جدول عرض العقارات -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم القسم</th>
                    <th>نوع العقار</th>
                    <th>عمل العقار</th>
                    <th>طبيعة العقار</th>
                    <th>قيمة الإيجار</th>
                    <th>المحطة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($institutionProperties as $property)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $property->department_name }}</td>
                        <td>{{ $property->property_type }}</td>
                        <td>{{ $property->property_use }}</td>
                        <td>{{ $property->property_nature }}</td>
                        <td>{{ $property->rental_value }}</td>
                        <td>{{ $property->station->station_name ?? 'غير محدد' }}</td>
                        <td>
                            <!-- عرض -->
                            <a id="btnb" href="{{ route('institution_properties.show', $property->id) }}"
                                class="btn btn-info btn-sm">عرض</a>

                            <!-- تعديل -->
                            <a id="btnb" href="{{ route('institution_properties.edit', $property->id) }}"
                                class="btn btn-warning btn-sm">تعديل</a>
                            @if (auth()->check() && auth()->user()->role_id == 'admin')
                                <!-- حذف -->
                                <form action="{{ route('institution_properties.destroy', $property->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button id="btnd" type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('هل أنت متأكد من حذف هذا العقار؟')">حذف</button>
                            @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">لا توجد بيانات.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <script>
        function selectUnit(unitId) {
            document.getElementById('selectedUnit').value = unitId;
            document.getElementById('unitFilterForm').submit();
        }
    </script>
@endsection
