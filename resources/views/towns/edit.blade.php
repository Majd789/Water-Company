<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل البلدة</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('towns.update', $town->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل البلدة
                            </div>
                            <div class="card-body">
                                <!-- اسم البلدة -->
                                <label for="town_name">اسم البلدة</label>
                                <input type="text" class="form-control @error('town_name') is-invalid @enderror"
                                    name="town_name" placeholder="أدخل اسم البلدة"
                                    value="{{ old('town_name', $town->town_name) }}" required>
                                @error('town_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- كود البلدة -->
                                <label for="town_code">كود البلدة</label>
                                <input type="text" class="form-control @error('town_code') is-invalid @enderror"
                                    name="town_code" placeholder="أدخل كود البلدة"
                                    value="{{ old('town_code', $town->town_code) }}" required>
                                @error('town_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- الوحدة -->
                                <label for="unit_id">الوحدة</label>
                                <select name="unit_id" class="form-control @error('unit_id') is-invalid @enderror" required>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ $town->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- ملاحظات عامة -->
                                <label for="general_notes">ملاحظات عامة</label>
                                <textarea name="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل ملاحظات عامة">{{ old('general_notes', $town->general_notes) }}</textarea>
                                @error('general_notes')
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
