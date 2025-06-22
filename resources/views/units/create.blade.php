<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة وحدة جديدة</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- نموذج إضافة الوحدة -->
            <form action="{{ route('units.store') }}" method="POST" class="login-form">
                @csrf
                <div class="cards-container">
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-primary">
                                المعلومات الأساسية
                            </div>
                            <!-- كود المحطة -->
                            <div class="card-body">
                                <!-- إدخال اسم الوحدة -->
                                <input type="text" class="form-control @error('unit_name') is-invalid @enderror"
                                    name="unit_name" value="{{ old('unit_name') }}" placeholder="أدخل اسم الوحدة" required>
                                @error('unit_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- إدخال ملاحظات عامة -->
                                <textarea name="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل ملاحظات عامة">{{ old('general_notes') }}</textarea>
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
                                            @if (old('governorate_id') == $governorate->id) selected @endif>
                                            {{ $governorate->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('governorate_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">حفظ</button>
            </form>

        </div>
    </div>

@endsection
