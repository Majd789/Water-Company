<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة قسم جديد</h2>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <form action="{{ route('pumping_sectors.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="file">اختر ملف قطاعات الضخ (Excel أو CSV)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">استيراد</button>
                </form>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pumping-sectors.store') }}" method="POST" class="login-form">
                @csrf
                <!-- حاوية الكروت -->
                <div class="cards-container">

                    <!-- الكرت 1: المعلومات الأساسية -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                قطاع الضخ
                            </div>
                            <div class="card-body">

                                <label for="station_id">اختر محطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر محطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}">{{ $station->station_name }}</option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror



                                <label for="sector_name">أدخل اسم القسم</label>
                                <input type="text" name="sector_name" id="sector_name"
                                    class="form-control @error('sector_name') is-invalid @enderror"
                                    placeholder="أدخل اسم القسم" required>
                                @error('sector_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror



                                <label for="town_id">اختر بلدة</label>
                                <select name="town_id" id="town_id"
                                    class="form-control @error('town_id') is-invalid @enderror" required>
                                    <option value="">اختر بلدة</option>
                                    @foreach ($towns as $town)
                                        <option value="{{ $town->id }}">{{ $town->town_name }}</option>
                                    @endforeach
                                </select>
                                @error('town_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror



                                <label for="notes">أدخل الملاحظات</label>
                                <input name="notes" id="notes"
                                    class="form-control @error('notes') is-invalid @enderror" placeholder="أدخل الملاحظات">
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">إضافة القسم</button>

            </form>
        </div>
    </div>

@endsection
