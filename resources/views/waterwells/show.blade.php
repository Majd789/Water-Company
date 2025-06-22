<!-- resources/views/waterwells/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>تفاصيل المنهل</h2>
        <table class="table">
            <tr>
                <th>التاريخ</th>
                <td>{{ $waterWell->date }}</td>
            </tr>
            <tr>
                <th>كود المحطة</th>
                <td>{{ $waterWell->station_code }}</td>
            </tr>
            <tr>
                <th>اسم المنهل</th>
                <td>{{ $waterWell->well_name }}</td>
            </tr>
            <tr>
                <th>هل يوجد عداد غزارة؟</th>
                <td>{{ $waterWell->has_flow_meter }}</td>
            </tr>
            <tr>
                <th>كمية المياه المباعة</th>
                <td>{{ $waterWell->water_sold_quantity }} متر مكعب</td>
            </tr>
            <tr>
                <th>سعر المتر</th>
                <td>{{ $waterWell->water_price }} ليرة</td>
            </tr>
            <tr>
                <th>المبلغ الإجمالي</th>
                <td>{{ $waterWell->total_amount }} ليرة</td>
            </tr>
        </table>
        <a href="{{ route('waterwells.index') }}" class="btn btn-secondary">الرجوع للقائمة</a>
    </div>
@endsection
