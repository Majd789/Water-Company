<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل مجموعة توليد: {{ $generationGroup->generator_name }}</h2>

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

            <!-- نموذج تعديل مجموعة التوليد -->
            <form action="{{ route('generation-groups.update', $generationGroup->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')

                <!-- المحطة -->
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل مجموعة توليد
                            </div>
                            <div class="card-body">
                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ $generationGroup->station_id == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- اسم المولدة -->

                                <!-- في ملف resources/views/generation-groups/edit.blade.php -->
                                <!-- ابحث عن حقل اسم المولدة واستبدله بهذا الكود: -->

                                <label for="generator_name">اسم المولدة</label>
                                <select name="generator_name" id="generator_name"
                                    class="form-control @error('generator_name') is-invalid @enderror" required>
                                    <option value="">-- اختر اسم المولدة --</option>
                                    @php
                                        $generators = [
                                            'DOOSAN',
                                            'PERKINS',
                                            'SCANIA',
                                            'VOLVO',
                                            'CATERPILLAR',
                                            'TEKSAN',
                                            'CUMMINS',
                                            'DAEWOO',
                                            'JOHN DEERE',
                                            'IVECO',
                                            'FIAT',
                                            'EMSA GENERATOR',
                                            'AKSA',
                                            'FPT',
                                            'DORMAN',
                                            'RICARDO (صيني)',
                                            'BAUDOUIN',
                                            'MARKON',
                                            'KJPOWER',
                                            'GENPOWER',
                                            'DODS',
                                            'AIFO',
                                            'DOTZ',
                                            'MAK',
                                            'MITSUBISHI / MITORELA',
                                            'IDEA',
                                            'COELMO',
                                            'غير معروف',
                                        ];
                                    @endphp
                                    @foreach ($generators as $generator)
                                        <option value="{{ $generator }}"
                                            {{ old('generator_name', $generationGroup->generator_name) == $generator ? 'selected' : '' }}>
                                            {{ $generator }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('generator_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <!-- استطاعة التوليد -->

                                <label for="generation_capacity">استطاعة التوليد</label>
                                <input type="number" step="0.01" name="generation_capacity" id="generation_capacity"
                                    class="form-control @error('generation_capacity') is-invalid @enderror"
                                    value="{{ old('generation_capacity', $generationGroup->generation_capacity) }}"
                                    placeholder="استطاعة التوليد" required>
                                @error('generation_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- استطاعة العمل الفعلية -->

                                <label for="actual_operating_capacity">استطاعة العمل الفعلية</label>
                                <input type="number" step="0.01" name="actual_operating_capacity"
                                    id="actual_operating_capacity"
                                    class="form-control @error('actual_operating_capacity') is-invalid @enderror"
                                    value="{{ old('actual_operating_capacity', $generationGroup->actual_operating_capacity) }}"
                                    placeholder="استطاعة العمل الفعلية" required>
                                @error('actual_operating_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- نسبة الجاهزية -->

                                <label for="generation_group_readiness_percentage">نسبة الجاهزية (0-100)</label>
                                <input type="number" step="0.01" name="generation_group_readiness_percentage"
                                    id="generation_group_readiness_percentage"
                                    class="form-control @error('generation_group_readiness_percentage') is-invalid @enderror"
                                    value="{{ old('generation_group_readiness_percentage', $generationGroup->generation_group_readiness_percentage) }}"
                                    placeholder="نسبة الجاهزية">
                                @error('generation_group_readiness_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- استهلاك الوقود -->

                                <label for="fuel_consumption">استهلاك الوقود</label>
                                <input type="number" step="0.01" name="fuel_consumption" id="fuel_consumption"
                                    class="form-control @error('fuel_consumption') is-invalid @enderror"
                                    value="{{ old('fuel_consumption', $generationGroup->fuel_consumption) }}"
                                    placeholder="استهلاك الوقود" required>
                                @error('fuel_consumption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- مدة استخدام الزيت -->

                                <label for="oil_usage_duration">مدة استخدام الزيت</label>
                                <input type="number" name="oil_usage_duration" id="oil_usage_duration"
                                    class="form-control @error('oil_usage_duration') is-invalid @enderror"
                                    value="{{ old('oil_usage_duration', $generationGroup->oil_usage_duration) }}"
                                    placeholder="مدة استخدام الزيت" required>
                                @error('oil_usage_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- كمية الزيت للتبديل -->

                                <label for="oil_quantity_for_replacement">كمية الزيت للتبديل</label>
                                <input type="number" step="0.01" name="oil_quantity_for_replacement"
                                    id="oil_quantity_for_replacement"
                                    class="form-control @error('oil_quantity_for_replacement') is-invalid @enderror"
                                    value="{{ old('oil_quantity_for_replacement', $generationGroup->oil_quantity_for_replacement) }}"
                                    placeholder="كمية الزيت للتبديل" required>
                                @error('oil_quantity_for_replacement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- الوضع التشغيلي -->

                                <label for="operational_status">الوضع التشغيلي</label>
                                <select name="operational_status" id="operational_status"
                                    class="form-control @error('operational_status') is-invalid @enderror" required>
                                    <option value="عاملة"
                                        {{ old('operational_status', $generationGroup->operational_status) == 'عاملة' ? 'selected' : '' }}>
                                        يعمل</option>
                                    <option value="متوقفة"
                                        {{ old('operational_status', $generationGroup->operational_status) == 'متوقفة' ? 'selected' : '' }}>
                                        متوقف</option>
                                </select>
                                @error('operational_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- سبب التوقف -->

                                <label for="stop_reason">سبب التوقف</label>
                                <textarea name="stop_reason" id="stop_reason" class="form-control @error('stop_reason') is-invalid @enderror"
                                    placeholder="سبب التوقف">{{ old('stop_reason', $generationGroup->stop_reason) }}</textarea>
                                @error('stop_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- ملاحظات -->

                                <label for="notes">ملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="ملاحظات">{{ old('notes', $generationGroup->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- زر التحديث -->
                                <button type="submit" class="btn btn-primary mt-3">تحديث</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
