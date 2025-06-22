<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h1>إضافة منهل جديد</h1>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <form action="{{ route('manholes.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="file">اختر ملف المنهولات (Excel أو CSV)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">استيراد</button>
                </form>
            @endif
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

            <!-- نموذج إضافة منهل جديد -->
            <form action="{{ route('manholes.store') }}" method="POST" class="login-form">
                @csrf

          
                <!-- حاوية الكروت -->
                <div class="cards-container">

                    <!-- الكرت 1: المعلومات الأساسية -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-primary">
                                المعلومات الأساسية
                            </div>
                            <div class="card-body">
                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- عرض اسم الوحدة الحالية فقط -->

                                <label>الوحدة:</label>
                                <input type="text" class="form-control" value="{{ $unit ? $unit->unit_name : '' }}"
                                    readonly>


                                <!-- حقل البلدة -->

                                <label for="town_id">اختر البلدة</label>
                                <select name="town_id" id="town_id"
                                    class="form-control @error('town_id') is-invalid @enderror" required>
                                    <option value="">-- اختر البلدة --</option>
                                    @foreach ($towns as $town)
                                        <option value="{{ $town->id }}"
                                            {{ old('town_id') == $town->id ? 'selected' : '' }}>
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
                                    value="{{ old('manhole_name') }}" placeholder="أدخل اسم المنهل">
                                @error('manhole_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- حقل الحالة -->

                                <label for="status">حالة المنهل</label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="يعمل" {{ old('status') == 'يعمل' ? 'selected' : '' }}>يعمل</option>
                                    <option value="متوقف" {{ old('status') == 'متوقف' ? 'selected' : '' }}>متوقف</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- حقل سبب التوقف -->

                                <label for="stop_reason">أدخل سبب التوقف</label>
                                <input type="text" name="stop_reason" id="stop_reason"
                                    class="form-control @error('stop_reason') is-invalid @enderror"
                                    value="{{ old('stop_reason') }}" placeholder="أدخل سبب التوقف">
                                @error('stop_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                    </div>

                    <!-- الكرت 2: بيانات الحفر -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-success">
                                بيانات عداد المنهل
                            </div>
                            <div class="card-body">
                                <!-- حقل عداد الغزارة -->

                                <label for="has_flow_meter">هل يوجد عداد غزارة؟</label>
                                <select name="has_flow_meter" id="has_flow_meter"
                                    class="form-control @error('has_flow_meter') is-invalid @enderror" required>
                                    <option value="1" {{ old('has_flow_meter') == '1' ? 'selected' : '' }}>نعم
                                    </option>
                                    <option value="0" {{ old('has_flow_meter') == '0' ? 'selected' : '' }}>لا</option>
                                </select>
                                @error('has_flow_meter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- حقل رقم الشاسيه -->

                                <label for="chassis_number">أدخل رقم الشاسيه</label>
                                <input type="text" name="chassis_number" id="chassis_number"
                                    class="form-control @error('chassis_number') is-invalid @enderror"
                                    value="{{ old('chassis_number') }}" placeholder="أدخل رقم الشاسيه">
                                @error('chassis_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- حقل قطر العداد -->

                                <label for="meter_diameter">أدخل قطر العداد</label>
                                <input type="text" name="meter_diameter" id="meter_diameter"
                                    class="form-control @error('meter_diameter') is-invalid @enderror"
                                    value="{{ old('meter_diameter') }}" placeholder="أدخل قطر العداد">
                                @error('meter_diameter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- حقل حالة العداد -->

                                <label for="meter_status">حالة العداد</label>
                                <select name="meter_status" id="meter_status"
                                    class="form-control @error('meter_status') is-invalid @enderror" required>
                                    <option value="يعمل" {{ old('meter_status') == 'يعمل' ? 'selected' : '' }}>يعمل
                                    </option>
                                    <option value="متوقف" {{ old('meter_status') == 'متوقف' ? 'selected' : '' }}>متوقف
                                    </option>
                                </select>
                                @error('meter_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- حقل طريقة عمل العداد بالمتر -->

                                <label for="meter_operation_method_in_meter">طريقة عمل العداد بالمتر</label>
                                <input type="text" name="meter_operation_method_in_meter"
                                    id="meter_operation_method_in_meter"
                                    class="form-control @error('meter_operation_method_in_meter') is-invalid @enderror"
                                    value="{{ old('meter_operation_method_in_meter') }}"
                                    placeholder="أدخل طريقة عمل العداد بالمتر">
                                @error('meter_operation_method_in_meter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                    </div>
                    <!-- الكرت 3: بيانات المضخة -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-info">
                                بيانات الخزان
                            </div>
                            <div class="card-body">
                                <!-- حقل خزان تجميعي -->

                                <label for="has_storage_tank">هل يوجد خزان تجميعي؟</label>
                                <select name="has_storage_tank" id="has_storage_tank"
                                    class="form-control @error('has_storage_tank') is-invalid @enderror" required>
                                    <option value="1" {{ old('has_storage_tank') == '1' ? 'selected' : '' }}>نعم
                                    </option>
                                    <option value="0" {{ old('has_storage_tank') == '0' ? 'selected' : '' }}>لا
                                    </option>
                                </select>
                                @error('has_storage_tank')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- حقل سعة الخزان -->

                                <label for="tank_capacity">أدخل سعة الخزان</label>
                                <input type="text" name="tank_capacity" id="tank_capacity"
                                    class="form-control @error('tank_capacity') is-invalid @enderror"
                                    value="{{ old('tank_capacity') }}" placeholder="أدخل سعة الخزان">
                                @error('tank_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- حقل الملاحظات -->

                                <label for="general_notes">أدخل الملاحظات</label>
                                <textarea name="general_notes" id="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل الملاحظات">{{ old('general_notes') }}</textarea>
                                @error('general_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <!-- زر الإرسال -->
                    <button type="submit" class="btn btn-primary">إضافة المنهل</button>
            </form>
        </div>
    </div>

@endsection
