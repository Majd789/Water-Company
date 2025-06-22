<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة مجموعة توليد جديدة</h2>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <form action="{{ route('generation_groups.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">اختر ملف مجموعات التوليد (Excel أو CSV)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">استيراد</button>
                </form>
            @endif
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

            <!-- نموذج إضافة مجموعة توليد جديدة -->
            <form action="{{ route('generation-groups.store') }}" method="POST" class="login-form">
                @csrf
                <!-- حاوية الكروت -->
                <div class="cards-container">

                    <!-- الكرت 1: معلومات البلدة -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                معلومات المولدة
                            </div>
                            <!-- المحطة -->
                            <div class="card-body">
                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" class="form-control @error('station_id') is-invalid @enderror"
                                    required>
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

                                <!-- اسم المولدة -->
                                <label for="generator_name">اسم المولدة</label>
                                <input type="text" name="generator_name"
                                    class="form-control @error('generator_name') is-invalid @enderror"
                                    value="{{ old('generator_name') }}" placeholder="اسم المولدة" required>
                                @error('generator_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- استطاعة التوليد -->
                                <label for="generation_capacity">استطاعة التوليد</label>
                                <input type="number" step="0.01" name="generation_capacity"
                                    class="form-control @error('generation_capacity') is-invalid @enderror"
                                    value="{{ old('generation_capacity') }}" placeholder="استطاعة التوليد" required>
                                @error('generation_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <!-- استطاعة العمل الفعلية -->
                                <label for="actual_operating_capacity">استطاعة العمل الفعلية</label>
                                <input type="number" step="0.01" name="actual_operating_capacity"
                                    class="form-control @error('actual_operating_capacity') is-invalid @enderror"
                                    value="{{ old('actual_operating_capacity') }}" placeholder="استطاعة العمل الفعلية"
                                    required>
                                @error('actual_operating_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- نسبة الجاهزية -->
                                <label for="generation_group_readiness_percentage">نسبة الجاهزية (0-100)</label>
                                <input type="number" step="0.01" name="generation_group_readiness_percentage"
                                    class="form-control @error('generation_group_readiness_percentage') is-invalid @enderror"
                                    value="{{ old('generation_group_readiness_percentage') }}"
                                    placeholder="نسبة الجاهزية (0-100)">
                                @error('generation_group_readiness_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                    </div>
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-success">
                                بيانات المولدة
                            </div>
                            <div class="card-body">

                                <!-- استهلاك الوقود -->
                                <label for="fuel_consumption">استهلاك الوقود</label>
                                <input type="number" step="0.01" name="fuel_consumption"
                                    class="form-control @error('fuel_consumption') is-invalid @enderror"
                                    value="{{ old('fuel_consumption') }}" placeholder="استهلاك الوقود" required>
                                @error('fuel_consumption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- مدة استخدام الزيت -->
                                <label for="oil_usage_duration">مدة استخدام الزيت</label>
                                <input type="number" name="oil_usage_duration"
                                    class="form-control @error('oil_usage_duration') is-invalid @enderror"
                                    value="{{ old('oil_usage_duration') }}" placeholder="مدة استخدام الزيت" required>
                                @error('oil_usage_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- كمية الزيت للتبديل -->
                                <label for="oil_quantity_for_replacement">كمية الزيت للتبديل</label>
                                <input type="number" step="0.01" name="oil_quantity_for_replacement"
                                    class="form-control @error('oil_quantity_for_replacement') is-invalid @enderror"
                                    value="{{ old('oil_quantity_for_replacement') }}" placeholder="كمية الزيت للتبديل"
                                    required>
                                @error('oil_quantity_for_replacement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- الوضع التشغيلي -->
                                <label for="operational_status">الوضع التشغيلي</label>
                                <select name="operational_status"
                                    class="form-control @error('operational_status') is-invalid @enderror" required>
                                    <option value="عاملة" {{ old('operational_status') == 'عاملة' ? 'selected' : '' }}>
                                        يعمل
                                    </option>
                                    <option value="متوقفة" {{ old('operational_status') == 'متوقفة' ? 'selected' : '' }}>
                                        متوقف
                                    </option>
                                </select>
                                @error('operational_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- سبب التوقف -->
                                <label for="stop_reason">سبب التوقف</label>
                                <textarea name="stop_reason" class="form-control @error('stop_reason') is-invalid @enderror" placeholder="سبب التوقف">{{ old('stop_reason') }}</textarea>
                                @error('stop_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ملاحظات -->
                                <label for="notes">ملاحظات</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="ملاحظات">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- زر الحفظ -->
                <button type="submit" class="btn btn-primary mt-3">اضافة مجموعة التوليد</button>
            </form>
        </div>
    </div>

@endsection
