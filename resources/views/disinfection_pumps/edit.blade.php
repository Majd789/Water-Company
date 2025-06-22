<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل بيانات مضخة التعقيم</h2>

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

            <!-- نموذج تعديل مضخة التعقيم -->
            <form action="{{ route('disinfection_pumps.update', $disinfectionPump->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل مضخة التعقيم
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->
                                <label for="station_id">اختيار المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $disinfectionPump->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الوضع التشغيلي -->
                                <label for="disinfection_pump_status">الوضع التشغيلي</label>
                                <select name="disinfection_pump_status" id="disinfection_pump_status"
                                    class="form-control @error('disinfection_pump_status') is-invalid @enderror">
                                    <option value="">اختر الوضع التشغيلي</option>
                                    <option value="يعمل"
                                        {{ old('disinfection_pump_status', $disinfectionPump->disinfection_pump_status) == 'يعمل' ? 'selected' : '' }}>
                                        يعمل</option>
                                    <option value="متوقف"
                                        {{ old('disinfection_pump_status', $disinfectionPump->disinfection_pump_status) == 'متوقف' ? 'selected' : '' }}>
                                        متوقف</option>
                                </select>
                                @error('disinfection_pump_status')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- ماركة وطراز المضخة -->
                                <label for="pump_brand_model">ماركة وطراز المضخة</label>
                                <input type="text" name="pump_brand_model" id="pump_brand_model"
                                    class="form-control @error('pump_brand_model') is-invalid @enderror"
                                    value="{{ old('pump_brand_model', $disinfectionPump->pump_brand_model) }}"
                                    placeholder="ماركة وطراز المضخة">
                                @error('pump_brand_model')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- غزارة المضخة -->
                                <label for="pump_flow_rate">غزارة المضخة (لتر/ساعة)</label>
                                <input type="number" name="pump_flow_rate" id="pump_flow_rate"
                                    class="form-control @error('pump_flow_rate') is-invalid @enderror"
                                    value="{{ old('pump_flow_rate', $disinfectionPump->pump_flow_rate) }}" step="0.01"
                                    placeholder="غزارة المضخة">
                                @error('pump_flow_rate')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- ضغط العمل -->
                                <label for="operating_pressure">ضغط العمل</label>
                                <input type="number" name="operating_pressure" id="operating_pressure"
                                    class="form-control @error('operating_pressure') is-invalid @enderror"
                                    value="{{ old('operating_pressure', $disinfectionPump->operating_pressure) }}"
                                    step="0.01" placeholder="ضغط العمل">
                                @error('operating_pressure')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الحالة الفنية -->
                                <label for="technical_condition">الحالة الفنية</label>
                                <input type="text" name="technical_condition" id="technical_condition"
                                    class="form-control @error('technical_condition') is-invalid @enderror"
                                    value="{{ old('technical_condition', $disinfectionPump->technical_condition) }}"
                                    placeholder="الحالة الفنية">
                                @error('technical_condition')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الملاحظات -->
                                <label for="notes">الملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="الملاحظات">{{ old('notes', $disinfectionPump->notes) }}</textarea>
                                @error('notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- زر الإرسال -->
                                <button type="submit" class="btn btn-primary">تحديث البيانات</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
