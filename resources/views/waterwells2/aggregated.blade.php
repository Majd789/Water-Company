@extends('layouts.app')

@section('content')
<div class="recent-orders text-center" style="text-align: center">
    <h2>تقرير تجميعي للمناهل</h2>
    
    <!-- زر إضافة تقرير منهل جديد -->
    <a id="btnb" href="{{ route('waterwells2.create') }}" class="btn btn-primary mb-3">إضافة تقرير للمناهل</a>
    
   
    <!-- الحاوية التي تحتوي على الجدول -->
    <div id="tableContainer" style="margin-top: 20px;">
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المنهل</th>
                    <th>الكمية المقاسة</th>
                    <th>كمية المياه المباعة</th>
                    <th>المياه المجانية</th>
                    <th>تعبئة المركبات</th>
                    <th>سعر المياه</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aggregatedResults as $result)
                    <tr>
                        <td>{{ $result['well_name'] }}</td>
                        <td>{{ $result['total_measured_qty'] }}</td>
                        <td>{{ $result['total_sold_qty'] }}</td>
                        <td>{{ $result['total_free_qty'] }}</td>
                        <td>{{ $result['total_vehicle_qty'] }}</td>
                        <td>{{ $result['water_price'] }}</td>
                        <td>{{ $result['total_amount'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- كود جافاسكريبت لتبديل عرض الجدول -->
<script>
    document.getElementById('toggleTableButton').addEventListener('click', function() {
        var tableContainer = document.getElementById('tableContainer');
        if (tableContainer.style.display === 'none' || tableContainer.style.display === '') {
            tableContainer.style.display = 'block';
            this.innerText = 'إخفاء الجدول';
        } else {
            tableContainer.style.display = 'none';
            this.innerText = 'عرض الجدول';
        }
    });
</script>
@endsection
