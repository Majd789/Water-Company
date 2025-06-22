<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل بيانات البئر</h2>

            <form action="{{ route('private-wells.update', $privateWell) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل بيانات البئر
                            </div>
                            <div class="card-body">
                                <label for="station_id">المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر محطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $privateWell->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="well_name">اسم البئر</label>
                                <input type="text" name="well_name" id="well_name"
                                    class="form-control @error('well_name') is-invalid @enderror"
                                    value="{{ old('well_name', $privateWell->well_name) }}">
                                @error('well_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="well_count">عدد الآبار</label>
                                <input type="number" name="well_count" id="well_count"
                                    class="form-control @error('well_count') is-invalid @enderror"
                                    value="{{ old('well_count', $privateWell->well_count) }}">
                                @error('well_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="distance_from_nearest_well">المسافة من أقرب بئر</label>
                                <input type="number" name="distance_from_nearest_well" id="distance_from_nearest_well"
                                    class="form-control @error('distance_from_nearest_well') is-invalid @enderror"
                                    value="{{ old('distance_from_nearest_well', $privateWell->distance_from_nearest_well) }}">
                                @error('distance_from_nearest_well')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <label for="well_type">اسم البئر</label>
                                <input type="text" name="well_type" id="well_type"
                                    class="form-control @error('well_type') is-invalid @enderror"
                                    value="{{ old('well_type', $privateWell->well_type) }}">
                                @error('well_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror


                                <label for="notes">ملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $privateWell->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <button type="submit" class="btn btn-warning mt-3">تحديث</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
