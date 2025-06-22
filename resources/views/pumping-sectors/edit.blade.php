<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل قسم</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pumping-sectors.update', $PumpingSector->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل قسم
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->
                                <label for="station_id">المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر محطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ $PumpingSector->station_id == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اسم القسم -->
                                <label for="sector_name">اسم القسم</label>
                                <input type="text" name="sector_name" id="sector_name"
                                    class="form-control @error('sector_name') is-invalid @enderror"
                                    value="{{ old('sector_name', $PumpingSector->sector_name) }}"
                                    placeholder="أدخل اسم القسم" required>
                                @error('sector_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اختيار البلدة -->
                                <label for="town_id">البلدة</label>
                                <select name="town_id" id="town_id"
                                    class="form-control @error('town_id') is-invalid @enderror" required>
                                    <option value="">اختر بلدة</option>
                                    @foreach ($towns as $town)
                                        <option value="{{ $town->id }}"
                                            {{ $PumpingSector->town_id == $town->id ? 'selected' : '' }}>
                                            {{ $town->town_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('town_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ملاحظات -->
                                <label for="notes">الملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="أدخل الملاحظات">{{ old('notes', $PumpingSector->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- زر التحديث -->
                                <button type="submit" class="btn btn-success">تحديث القسم</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
