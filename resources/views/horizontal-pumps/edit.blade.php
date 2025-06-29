<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل بيانات المضخة الأفقية</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>عفوًا! هناك بعض الأخطاء في الإدخال:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('horizontal-pumps.update', $horizontalPump->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')

                <!-- حاوية الكروت -->
                <div class="cards-container">

                    <!-- الكرت 1: المعلومات الأساسية -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-primary">المعلومات الأساسية</div>
                            <div class="card-body">
                                <label for="station_id">اختر محطة<span class="text-danger">*</span></label>
                                <select name="station_id" class="form-control" required>
                                    <option value="">-- اختر محطة --</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $horizontalPump->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <label for="pump_name">اسم المضخة</label>
                                <input type="text" name="pump_name" class="form-control"
                                    value="{{ old('pump_name', $horizontalPump->pump_name) }}">

                                <label for="pump_status">الحالة التشغيلية</label>
                                <select name="pump_status" class="form-control">
                                    <option value="يعمل"
                                        {{ old('pump_status', $horizontalPump->pump_status) == 'يعمل' ? 'selected' : '' }}>
                                        تعمل</option>
                                    <option value="متوقفة"
                                        {{ old('pump_status', $horizontalPump->pump_status) == 'متوقفة' ? 'selected' : '' }}>
                                        متوقفة</option>
                                </select>

                                <label for="technical_condition">الحالة الفنية</label>
                                <input type="text" name="technical_condition" class="form-control"
                                    value="{{ old('technical_condition', $horizontalPump->technical_condition) }}">
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 2: المواصفات الفنية -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-success">المواصفات الفنية</div>
                            <div class="card-body">
                                <label for="pump_capacity_hp">الاستطاعة (حصان)</label>
                                <input type="number" name="pump_capacity_hp" class="form-control" step="0.01"
                                    value="{{ old('pump_capacity_hp', $horizontalPump->pump_capacity_hp) }}">

                                <label for="pump_flow_rate_m3h">تدفق المضخة (م³/ساعة)</label>
                                <input type="number" name="pump_flow_rate_m3h" class="form-control" step="0.01"
                                    value="{{ old('pump_flow_rate_m3h', $horizontalPump->pump_flow_rate_m3h) }}">

                                <label for="pump_head">ارتفاع الضخ (متر)</label>
                                <input type="number" name="pump_head" class="form-control" step="0.01"
                                    value="{{ old('pump_head', $horizontalPump->pump_head) }}">

                                <label for="pump_brand_model">ماركة وطراز المضخة</label>
                                <select name="pump_brand_model" class="form-control">
                                    <option value="">-- اختر الماركة --</option>
                                    @php
                                        $brands = [
                                            'HALLER & SCHNEIDER',
                                            'German Made',
                                            'Italian Made',
                                            'Turkish Made',
                                            'STANDART',
                                            'MEZ',
                                            'CAPRARI',
                                            'GAMAK',
                                            'SEMPA',
                                            'SEVER',
                                            'Czech Made',
                                            'WATT',
                                            'European',
                                            'SKM',
                                            'GRUNDFOS',
                                            'MAS',
                                            'SIEMENS',
                                            'ROVATTI',
                                            'Spanish Made',
                                            'DEMAK',
                                            'Iranian Made',
                                            'KLN',
                                            'ELK',
                                            'PENTAX',
                                            'Chinese Made',
                                            'LOWARA',
                                            'JET',
                                            'FLOWSERVE',
                                            'KSB',
                                            'ATURIA',
                                            'غير معروف',
                                        ];
                                    @endphp
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand }}"
                                            {{ old('pump_brand_model', $horizontalPump->pump_brand_model) == $brand ? 'selected' : '' }}>
                                            {{ $brand }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 3: الطاقة والملاحظات -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-info">الطاقة والملاحظات</div>
                            <div class="card-body">
                                <label for="energy_source">مصدر الطاقة</label>
                                <select name="energy_source" class="form-control">
                                    <option value="">-- اختر مصدر الطاقة --</option>
                                    <option value="لا يوجد"
                                        {{ old('energy_source', $horizontalPump->energy_source) == 'لا يوجد' ? 'selected' : '' }}>
                                        لا يوجد</option>
                                    <option value="كهرباء"
                                        {{ old('energy_source', $horizontalPump->energy_source) == 'كهرباء' ? 'selected' : '' }}>
                                        كهرباء</option>
                                    <option value="مولدة"
                                        {{ old('energy_source', $horizontalPump->energy_source) == 'مولدة' ? 'selected' : '' }}>
                                        مولدة</option>
                                    <option value="طاقة شمسية"
                                        {{ old('energy_source', $horizontalPump->energy_source) == 'طاقة شمسية' ? 'selected' : '' }}>
                                        طاقة شمسية</option>
                                    <option value="كهرباء و مولدة"
                                        {{ old('energy_source', $horizontalPump->energy_source) == 'كهرباء و مولدة' ? 'selected' : '' }}>
                                        كهرباء و مولدة</option>
                                    <option value="كهرباء و طاقة شمسية"
                                        {{ old('energy_source', $horizontalPump->energy_source) == 'كهرباء و طاقة شمسية' ? 'selected' : '' }}>
                                        كهرباء و طاقة شمسية</option>
                                    <option value="مولدة و طاقة شمسية"
                                        {{ old('energy_source', $horizontalPump->energy_source) == 'مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                        مولدة و طاقة شمسية</option>
                                    <option value="كهرباء و مولدة و طاقة شمسية"
                                        {{ old('energy_source', $horizontalPump->energy_source) == 'كهرباء و مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                        كهرباء و مولدة و طاقة شمسية</option>
                                </select>

                                <label for="notes">ملاحظات</label>
                                <textarea name="notes" class="form-control" placeholder="ملاحظات">{{ old('notes', $horizontalPump->notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                </div> <!-- نهاية حاوية الكروت -->

                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">تحديث
                    البيانات</button>
            </form>
        </div>
    </div>

@endsection
