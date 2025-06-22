<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل مضخة أفقية</h2>

            <!-- عرض الأخطاء -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- نموذج تعديل مضخة أفقية -->
            <form action="{{ route('horizontal-pumps.update', $horizontalPump->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل مضخة أفقية
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->
                                <label for="station_id">اختر محطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر محطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $horizontalPump->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اسم المضخة -->
                                <label for="pump_name">اسم المضخة</label>
                                <input type="text" id="pump_name" name="pump_name"
                                    class="form-control @error('pump_name') is-invalid @enderror"
                                    value="{{ old('pump_name', $horizontalPump->pump_name) }}" placeholder="اسم المضخة">
                                @error('pump_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- الحالة التشغيلية -->
                                <label for="pump_status">الحالة التشغيلية</label>
                                <select name="pump_status" id="pump_status"
                                    class="form-control @error('pump_status') is-invalid @enderror">
                                    <option value="يعمل"
                                        {{ old('pump_status', $horizontalPump->pump_status) == 'يعمل' ? 'selected' : '' }}>
                                        تعمل</option>
                                    <option value="متوقفة"
                                        {{ old('pump_status', $horizontalPump->pump_status) == 'متوقفة' ? 'selected' : '' }}>
                                        متوقفة</option>
                                </select>
                                @error('pump_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- استطاعة المضخة -->
                                <label for="pump_capacity_hp">الاستطاعة (حصان)</label>
                                <input type="number" id="pump_capacity_hp" name="pump_capacity_hp"
                                    class="form-control @error('pump_capacity_hp') is-invalid @enderror" step="0.01"
                                    value="{{ old('pump_capacity_hp', $horizontalPump->pump_capacity_hp) }}"
                                    placeholder="الاستطاعة (حصان)">
                                @error('pump_capacity_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- تدفق المضخة -->
                                <label for="pump_flow_rate_m3h">تدفق المضخة (م³/ساعة)</label>
                                <input type="number" id="pump_flow_rate_m3h" name="pump_flow_rate_m3h"
                                    class="form-control @error('pump_flow_rate_m3h') is-invalid @enderror" step="0.01"
                                    value="{{ old('pump_flow_rate_m3h', $horizontalPump->pump_flow_rate_m3h) }}"
                                    placeholder="تدفق المضخة (م³/ساعة)">
                                @error('pump_flow_rate_m3h')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ارتفاع الضخ -->
                                <label for="pump_head">ارتفاع الضخ</label>
                                <input type="number" id="pump_head" name="pump_head"
                                    class="form-control @error('pump_head') is-invalid @enderror" step="0.01"
                                    value="{{ old('pump_head', $horizontalPump->pump_head) }}" placeholder="ارتفاع الضخ">
                                @error('pump_head')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ماركة وطراز المضخة -->
                                <label for="pump_brand_model">ماركة وطراز المضخة</label>
                                <input type="text" id="pump_brand_model" name="pump_brand_model"
                                    class="form-control @error('pump_brand_model') is-invalid @enderror"
                                    value="{{ old('pump_brand_model', $horizontalPump->pump_brand_model) }}"
                                    placeholder="ماركة وطراز المضخة">
                                @error('pump_brand_model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- الحالة الفنية -->
                                <label for="technical_condition">الحالة الفنية</label>
                                <input type="text" id="technical_condition" name="technical_condition"
                                    class="form-control @error('technical_condition') is-invalid @enderror"
                                    value="{{ old('technical_condition', $horizontalPump->technical_condition) }}"
                                    placeholder="الحالة الفنية">
                                @error('technical_condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- مصدر الطاقة -->
                                <label for="energy_source">مصدر الطاقة</label>
                                <input type="text" id="energy_source" name="energy_source"
                                    class="form-control @error('energy_source') is-invalid @enderror"
                                    value="{{ old('energy_source', $horizontalPump->energy_source) }}"
                                    placeholder="مصدر الطاقة">
                                @error('energy_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ملاحظات -->
                                <label for="notes">ملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="ملاحظات">{{ old('notes', $horizontalPump->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- زر التحديث -->
                                <button type="submit" class="btn btn-primary">تحديث</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
