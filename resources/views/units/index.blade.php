@extends('layouts.app')

@section('content')
 <div class="recent-orders" style="text-align: center">  
        <h2>قائمة الوحدات</h2>
        
        <a id="btnb" href="{{ route('units.create') }}" class="btn btn-primary mb-3">إضافة وحدة جديدة</a>
        <form action="{{ route('units.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <input  type="file" name="file" class="form-control" required>
            <button id="btnb" type="submit" class="btn btn-success">استيراد</button>
        </form>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th scope="col" style="margin: 10">اسم الوحدة</th>
                    <th scope="col" style="margin: 10">المحافظة</th>  <!-- إضافة عمود المحافظة -->
                    <th scope="col" style="margin: 10">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($units as $unit)
                <tr>
                    <td>{{ $unit->unit_name }}</td>
                    <td>{{ $unit->governorate->name ?? 'لا توجد محافظة' }}</td>  <!-- عرض اسم المحافظة -->
                    <td>
                        <a id="btnb" href="{{ route('units.show', $unit->id) }}" class="btn btn-info btn-sm">عرض</a>
                        <a id="btnb" href="{{ route('units.edit', $unit->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                        @if(auth()->check() && auth()->user()->role_id == 'admin') 
                        <form action="{{ route('units.destroy', $unit->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button id="btnd" type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
