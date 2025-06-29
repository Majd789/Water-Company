<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل مرشح</h2>

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

            <!-- نموذج تعديل مرشح -->
            <form action="{{ route('filters.update', $filter->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل مرشح
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->

                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $filter->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- استطاعة المرشح -->

                                <label for="filter_capacity">استطاعة المرشح</label>
                                <input type="number" name="filter_capacity" id="filter_capacity"
                                    class="form-control @error('filter_capacity') is-invalid @enderror"
                                    value="{{ old('filter_capacity', $filter->filter_capacity) }}"
                                    placeholder="استطاعة المرشح">
                                @error('filter_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- حالة الجاهزية -->

                                <label for="readiness_status">حالة الجاهزية</label>
                                <input type="number" name="readiness_status" id="readiness_status"
                                    class="form-control @error('readiness_status') is-invalid @enderror"
                                    value="{{ old('readiness_status', $filter->readiness_status) }}"
                                    placeholder="حالة الجاهزية">
                                @error('readiness_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <!-- نوع المرشح -->

                                <label for="filter_type">نوع المرشح</label>
                                <select name="filter_type" id="filter_type"
                                    class="form-control @error('filter_type') is-invalid @enderror" required>
                                    <option value="">-- اختر نوع المرشح --</option>
                                    <option value="OSM-SF"
                                        {{ old('filter_type', $filter->filter_type) == 'OSM-SF' ? 'selected' : '' }}>OSM-SF
                                    </option>
                                    <option value="OSM-CF"
                                        {{ old('filter_type', $filter->filter_type) == 'OSM-CF' ? 'selected' : '' }}>OSM-CF
                                    </option>
                                    <option value="RO System"
                                        {{ old('filter_type', $filter->filter_type) == 'RO System' ? 'selected' : '' }}>RO
                                        System</option>
                                    <option value="Sand Filter (رملي)"
                                        {{ old('filter_type', $filter->filter_type) == 'Sand Filter (رملي)' ? 'selected' : '' }}>
                                        Sand Filter (رملي)</option>
                                    <option value="Carbon Filter (كربوني)"
                                        {{ old('filter_type', $filter->filter_type) == 'Carbon Filter (كربوني)' ? 'selected' : '' }}>
                                        Carbon Filter (كربوني)</option>
                                    <option value="Cartridge Filter (كارتريدج)"
                                        {{ old('filter_type', $filter->filter_type) == 'Cartridge Filter (كارتريدج)' ? 'selected' : '' }}>
                                        Cartridge Filter (كارتريدج)</option>
                                    <option value="Bag Filter"
                                        {{ old('filter_type', $filter->filter_type) == 'Bag Filter' ? 'selected' : '' }}>
                                        Bag Filter</option>
                                    <option value="UV Sterilizer"
                                        {{ old('filter_type', $filter->filter_type) == 'UV Sterilizer' ? 'selected' : '' }}>
                                        UV Sterilizer</option>
                                    <option value="Multi-media Filter"
                                        {{ old('filter_type', $filter->filter_type) == 'Multi-media Filter' ? 'selected' : '' }}>
                                        Multi-media Filter</option>
                                    <option value="Micron Filter (مايكروني)"
                                        {{ old('filter_type', $filter->filter_type) == 'Micron Filter (مايكروني)' ? 'selected' : '' }}>
                                        Micron Filter (مايكروني)</option>
                                    <option value="OSM-X Series"
                                        {{ old('filter_type', $filter->filter_type) == 'OSM-X Series' ? 'selected' : '' }}>
                                        OSM-X Series</option>
                                    <option value="غير معروف"
                                        {{ old('filter_type', $filter->filter_type) == 'غير معروف' ? 'selected' : '' }}>غير
                                        معروف</option>
                                </select>
                                @error('filter_type')
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
