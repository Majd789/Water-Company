<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل الوحدة</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('units.update', $unit->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل الوحدة
                            </div>
                            <div class="card-body">
                                <!-- اسم الوحدة -->
                                <label for="unit_name">اسم الوحدة</label>
                                <input type="text" class="form-control @error('unit_name') is-invalid @enderror"
                                    name="unit_name" value="{{ old('unit_name', $unit->unit_name) }}"
                                    placeholder="أدخل اسم الوحدة" required>
                                @error('unit_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ملاحظات عامة -->
                                <label for="general_notes">ملاحظات عامة</label>
                                <textarea name="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل ملاحظات عامة">{{ old('general_notes', $unit->general_notes) }}</textarea>
                                @error('general_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اختيار المحافظة -->
                                <label for="governorate_id">المحافظة</label>
                                <select name="governorate_id"
                                    class="form-control @error('governorate_id') is-invalid @enderror">
                                    <option value="">اختر المحافظة</option>
                                    @foreach ($governorates as $governorate)
                                        <option value="{{ $governorate->id }}"
                                            @if ($governorate->id == old('governorate_id', $unit->governorate_id)) selected @endif>
                                            {{ $governorate->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('governorate_id')
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
