@extends('layouts.app')
@section('title', 'تعديل البلدة')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تعديل البلدة: <span class="text-primary">{{ $town->town_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('towns.index') }}">البلدات</a></li>
                    <li class="breadcrumb-item active">تعديل</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                {{-- بطاقة تعديل بيانات البلدة --}}
                <div class="card card-primary shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">بيانات البلدة</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="{{ route('towns.update', $town->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- حقل اسم البلدة --}}
                            <div class="form-group">
                                <label for="town_name">اسم البلدة</label>
                                <input type="text" id="town_name"
                                    class="form-control @error('town_name') is-invalid @enderror" name="town_name"
                                    value="{{ old('town_name', $town->town_name) }}" placeholder="أدخل اسم البلدة" required>
                                @error('town_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- حقل كود البلدة --}}
                            <div class="form-group">
                                <label for="town_code">كود البلدة</label>
                                <input type="text" id="town_code"
                                    class="form-control @error('town_code') is-invalid @enderror" name="town_code"
                                    value="{{ old('town_code', $town->town_code) }}" placeholder="أدخل كود البلدة" required>
                                @error('town_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- حقل اختيار الوحدة --}}
                            <div class="form-group">
                                <label for="unit_id">الوحدة</label>
                                <select id="unit_id" name="unit_id"
                                    class="form-control @error('unit_id') is-invalid @enderror" required>
                                    <option value="">-- اختر الوحدة --</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            @if (old('unit_id', $town->unit_id) == $unit->id) selected @endif>
                                            {{ $unit->unit_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- حقل ملاحظات عامة --}}
                            <div class="form-group">
                                <label for="general_notes">ملاحظات عامة</label>
                                <textarea id="general_notes" name="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل ملاحظات عامة (اختياري)">{{ old('general_notes', $town->general_notes) }}</textarea>
                                @error('general_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save ml-1"></i> تحديث</button>
                            <a href="{{ route('towns.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection
