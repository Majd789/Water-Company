<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة بلدة جديدة</h2>

            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <form action="{{ route('towns.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" required>
                    <button type="submit" class="btn btn-primary">استيراد البلدات</button>
                </form>
            @endif

            @if (session('success'))
                <p style="color: green;">{{ session('success') }}</p>
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

            <form action="{{ route('towns.store') }}" method="POST" class="login-form">
                @csrf

                <!-- حاوية الكروت -->
                <div class="cards-container">

                    <!-- الكرت 1: معلومات البلدة -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                معلومات البلدة
                            </div>
                            <div class="card-body">
                                <label for="town_name">اسم البلدة</label>
                                <input type="text" class="form-control" id="town_name" name="town_name"
                                    placeholder="أدخل اسم البلدة" required>

                                <label for="town_code">كود البلدة</label>
                                <input type="text" class="form-control" id="town_code" name="town_code"
                                    placeholder="أدخل كود البلدة" required>

                                <label for="unit_id">الوحدة</label>
                                @if (auth()->user()->unit_id)
                                    <input type="text" class="form-control" id="unit_id" name="unit_id"
                                        value="{{ auth()->user()->unit->unit_name }}" readonly required>
                                @else
                                    <select name="unit_id" class="form-control" id="unit_id" required>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                        @endforeach
                                    </select>
                                @endif

                                <label for="general_notes">الملاحظات العامة</label>
                                <textarea name="general_notes" class="form-control" id="general_notes" placeholder="أدخل ملاحظات عامة"></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">حفظ</button>
            </form>
        </div>
    </div>

@endsection
