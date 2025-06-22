<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h1>تعديل المنهل</h1>

            <!-- عرض الأخطاء في المدخلات -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- نموذج تعديل المنهل -->
            <form action="{{ route('manholes.update', $manhole->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل المنهل
                            </div>
                            <div class="card-body">
                                <!-- حقل المحطة -->
                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $manhole->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل الوحدة -->
                                <label for="unit_id">اختر الوحدة</label>
                                <select name="unit_id" class="form-control @error('unit_id') is-invalid @enderror" required>
                                    <option value="">اختر الوحدة</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id', $manhole->unit_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->unit_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل البلدة -->
                                <label for="town_id">-- اختر البلدة --</label>
                                <select name="town_id" class="form-control @error('town_id') is-invalid @enderror" required>
                                    <option value="">-- اختر البلدة --</option>
                                    @foreach ($towns as $town)
                                        <option value="{{ $town->id }}"
                                            {{ old('town_id', $manhole->town_id) == $town->id ? 'selected' : '' }}>
                                            {{ $town->town_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('town_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل اسم المنهل -->
                                <label for="manhole_name">أدخل اسم المنهل</label>
                                <input type="text" name="manhole_name" id="manhole_name"
                                    class="form-control @error('manhole_name') is-invalid @enderror"
                                    value="{{ old('manhole_name', $manhole->manhole_name) }}"
                                    placeholder="أدخل اسم المنهل">
                                @error('manhole_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل الحالة -->
                                <label for="status">الحالة</label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="يعمل"
                                        {{ old('status', $manhole->status) == 'يعمل' ? 'selected' : '' }}>يعمل</option>
                                    <option value="متوقف"
                                        {{ old('status', $manhole->status) == 'متوقف' ? 'selected' : '' }}>متوقف</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل سبب التوقف -->
                                <label for="stop_reason">أدخل سبب التوقف</label>
                                <input type="text" name="stop_reason" id="stop_reason"
                                    class="form-control @error('stop_reason') is-invalid @enderror"
                                    value="{{ old('stop_reason', $manhole->stop_reason) }}" placeholder="أدخل سبب التوقف">
                                @error('stop_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل عداد الغزارة -->
                                <label for="has_flow_meter">هل يوجد عداد للغزارة؟</label>
                                <select name="has_flow_meter" id="has_flow_meter"
                                    class="form-control @error('has_flow_meter') is-invalid @enderror" required>
                                    <option value="1"
                                        {{ old('has_flow_meter', $manhole->has_flow_meter) == '1' ? 'selected' : '' }}>نعم
                                    </option>
                                    <option value="0"
                                        {{ old('has_flow_meter', $manhole->has_flow_meter) == '0' ? 'selected' : '' }}>لا
                                    </option>
                                </select>
                                @error('has_flow_meter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل رقم الشاسيه -->
                                <label for="chassis_number">أدخل رقم الشاسيه</label>
                                <input type="text" name="chassis_number" id="chassis_number"
                                    class="form-control @error('chassis_number') is-invalid @enderror"
                                    value="{{ old('chassis_number', $manhole->chassis_number) }}"
                                    placeholder="أدخل رقم الشاسيه">
                                @error('chassis_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل قطر العداد -->
                                <label for="meter_diameter">أدخل قطر العداد</label>
                                <input type="text" name="meter_diameter" id="meter_diameter"
                                    class="form-control @error('meter_diameter') is-invalid @enderror"
                                    value="{{ old('meter_diameter', $manhole->meter_diameter) }}"
                                    placeholder="أدخل قطر العداد">
                                @error('meter_diameter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل حالة العداد -->
                                <label for="meter_status">حالة العداد</label>
                                <select name="meter_status" id="meter_status"
                                    class="form-control @error('meter_status') is-invalid @enderror" required>
                                    <option value="يعمل"
                                        {{ old('meter_status', $manhole->meter_status) == 'يعمل' ? 'selected' : '' }}>يعمل
                                    </option>
                                    <option value="متوقف"
                                        {{ old('meter_status', $manhole->meter_status) == 'متوقف' ? 'selected' : '' }}>
                                        متوقف</option>
                                </select>
                                @error('meter_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل طريقة عمل العداد بالمتر -->
                                <label for="meter_operation_method_in_meter">أدخل طريقة عمل العداد بالمتر</label>
                                <input type="text" name="meter_operation_method_in_meter"
                                    id="meter_operation_method_in_meter"
                                    class="form-control @error('meter_operation_method_in_meter') is-invalid @enderror"
                                    value="{{ old('meter_operation_method_in_meter', $manhole->meter_operation_method_in_meter) }}"
                                    placeholder="أدخل طريقة عمل العداد بالمتر">
                                @error('meter_operation_method_in_meter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل خزان تجميعي -->
                                <label for="has_storage_tank">هل يوجد خزان تجميعي؟</label>
                                <select name="has_storage_tank" id="has_storage_tank"
                                    class="form-control @error('has_storage_tank') is-invalid @enderror" required>
                                    <option value="1"
                                        {{ old('has_storage_tank', $manhole->has_storage_tank) == '1' ? 'selected' : '' }}>
                                        نعم</option>
                                    <option value="0"
                                        {{ old('has_storage_tank', $manhole->has_storage_tank) == '0' ? 'selected' : '' }}>
                                        لا</option>
                                </select>
                                @error('has_storage_tank')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل سعة الخزان -->
                                <label for="tank_capacity">أدخل سعة الخزان</label>
                                <input type="text" name="tank_capacity" id="tank_capacity"
                                    class="form-control @error('tank_capacity') is-invalid @enderror"
                                    value="{{ old('tank_capacity', $manhole->tank_capacity) }}"
                                    placeholder="أدخل سعة الخزان">
                                @error('tank_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حقل الملاحظات -->
                                <label for="general_notes">أدخل الملاحظات</label>
                                <textarea name="general_notes" id="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل الملاحظات">{{ old('general_notes', $manhole->general_notes) }}</textarea>
                                @error('general_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- زر التحديث -->
                                <button type="submit" class="btn btn-primary">تحديث المنهل</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
