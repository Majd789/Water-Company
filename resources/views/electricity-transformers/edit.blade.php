<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل المحولة الكهربائية</h2>

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

            <!-- نموذج تعديل المحولة الكهربائية -->
            <form action="{{ route('electricity-transformers.update', $electricityTransformer) }}" method="POST"
                class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل المحولة الكهربائية
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->
                                <label for="station_id">المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر محطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $electricityTransformer->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- الوضع التشغيلي -->
                                <label for="operational_status">الوضع التشغيلي</label>
                                <select name="operational_status" id="operational_status"
                                    class="form-control @error('operational_status') is-invalid @enderror" required>
                                    <option value="تعمل"
                                        {{ old('operational_status', $electricityTransformer->operational_status) == 'تعمل' ? 'selected' : '' }}>
                                        تعمل</option>
                                    <option value="متوقفة"
                                        {{ old('operational_status', $electricityTransformer->operational_status) == 'متوقفة' ? 'selected' : '' }}>
                                        متوقفة</option>
                                </select>
                                @error('operational_status')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- استطاعة المحولة -->
                                <label for="transformer_capacity">استطاعة المحولة (KVA)</label>
                                <input type="number" step="0.01" name="transformer_capacity" id="transformer_capacity"
                                    class="form-control @error('transformer_capacity') is-invalid @enderror"
                                    value="{{ old('transformer_capacity', $electricityTransformer->transformer_capacity) }}"
                                    placeholder="استطاعة المحولة (KVA)" required>
                                @error('transformer_capacity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- بعد المحولة عن المحطة -->
                                <label for="distance_from_station">البعد عن المحطة (م)</label>
                                <input type="number" step="0.01" name="distance_from_station" id="distance_from_station"
                                    class="form-control @error('distance_from_station') is-invalid @enderror"
                                    value="{{ old('distance_from_station', $electricityTransformer->distance_from_station) }}"
                                    placeholder="البعد عن المحطة (م)" required>
                                @error('distance_from_station')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- هل المحولة خاصة بالمحطة -->
                                <label for="is_station_transformer">هل المحولة خاصة بالمحطة</label>
                                <select name="is_station_transformer" id="is_station_transformer"
                                    class="form-control @error('is_station_transformer') is-invalid @enderror" required>
                                    <option value="1"
                                        {{ old('is_station_transformer', $electricityTransformer->is_station_transformer) == '1' ? 'selected' : '' }}>
                                        نعم</option>
                                    <option value="0"
                                        {{ old('is_station_transformer', $electricityTransformer->is_station_transformer) == '0' ? 'selected' : '' }}>
                                        لا</option>
                                </select>
                                @error('is_station_transformer')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- تكلم سردًا -->
                                <label for="talk_about_station_transformer">اذكر سردًا الجهة التي تشترك بالمحولة مع
                                    المحطة</label>
                                <textarea name="talk_about_station_transformer" id="talk_about_station_transformer"
                                    class="form-control @error('talk_about_station_transformer') is-invalid @enderror"
                                    placeholder="اذكر سردًا الجهة التي تشترك بالمحولة مع المحطة">{{ old('talk_about_station_transformer', $electricityTransformer->talk_about_station_transformer) }}</textarea>
                                @error('talk_about_station_transformer')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- هل الاستطاعة كافية -->
                                <label for="is_capacity_sufficient">هل الاستطاعة كافية</label>
                                <select name="is_capacity_sufficient" id="is_capacity_sufficient"
                                    class="form-control @error('is_capacity_sufficient') is-invalid @enderror" required>
                                    <option value="1"
                                        {{ old('is_capacity_sufficient', $electricityTransformer->is_capacity_sufficient) == '1' ? 'selected' : '' }}>
                                        نعم</option>
                                    <option value="0"
                                        {{ old('is_capacity_sufficient', $electricityTransformer->is_capacity_sufficient) == '0' ? 'selected' : '' }}>
                                        لا</option>
                                </select>
                                @error('is_capacity_sufficient')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- كم تحتاج من الاستطاعة -->
                                <label for="how_mush_capacity_need">كم تحتاج من الاستطاعة</label>
                                <input type="number" step="0.01" name="how_mush_capacity_need"
                                    id="how_mush_capacity_need"
                                    class="form-control @error('how_mush_capacity_need') is-invalid @enderror"
                                    value="{{ old('how_mush_capacity_need', $electricityTransformer->how_mush_capacity_need) }}"
                                    placeholder="كم تحتاج من الاستطاعة" required>
                                @error('how_mush_capacity_need')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- ملاحظات -->
                                <label for="notes">الملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="أدخل أي ملاحظات إضافية">{{ old('notes', $electricityTransformer->notes) }}</textarea>
                                @error('notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- زر الإرسال -->
                                <button type="submit" class="btn btn-success">تحديث المحولة</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

@endsection
