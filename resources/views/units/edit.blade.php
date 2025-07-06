@extends('layouts.app')
@section('title', 'تعديل الوحدة')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تعديل الوحدة: <span class="text-primary">{{ $unit->unit_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('units.index') }}">الوحدات</a></li>
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
                {{-- بطاقة تعديل بيانات الوحدة --}}
                <div class="card card-primary shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">بيانات الوحدة</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="{{ route('units.update', $unit->id) }}" method="POST">
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

                            {{-- حقل اسم الوحدة --}}
                            <div class="form-group">
                                <label for="unit_name">اسم الوحدة</label>
                                <input type="text" id="unit_name"
                                    class="form-control @error('unit_name') is-invalid @enderror" name="unit_name"
                                    value="{{ old('unit_name', $unit->unit_name) }}" placeholder="أدخل اسم الوحدة" required>
                                @error('unit_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- حقل اختيار المحافظة --}}
                            <div class="form-group">
                                <label for="governorate_id">المحافظة</label>
                                <select id="governorate_id" name="governorate_id"
                                    class="form-control @error('governorate_id') is-invalid @enderror">
                                    <option value="">-- اختر المحافظة --</option>
                                    @foreach ($governorates as $governorate)
                                        <option value="{{ $governorate->id }}"
                                            @if (old('governorate_id', $unit->governorate_id) == $governorate->id) selected @endif>
                                            {{ $governorate->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('governorate_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- حقل ملاحظات عامة --}}
                            <div class="form-group">
                                <label for="general_notes">ملاحظات عامة</label>
                                <textarea id="general_notes" name="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل ملاحظات عامة (اختياري)">{{ old('general_notes', $unit->general_notes) }}</textarea>
                                @error('general_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save ml-1"></i> تحديث</button>
                            <a href="{{ route('units.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection
