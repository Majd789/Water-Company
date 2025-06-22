<!-- resources/views/waterwells/create.blade.php -->
<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
<style>
  /* createoffice.css */


.card {
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    font-size: 1.5rem;
    padding: 1.5rem;
    border-bottom: 2px solid #ddd;
}

.card-body {
    padding: 2rem;
}

.form-label {
    font-weight: bold;
    color: #333;
}

input[type="file"] {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 8px;
}

button.btn {
    font-size: 1.1rem;
    padding: 10px;
    border-radius: 5px;
}

    </style>
<div class="recent-orders text-center" style="text-align: center">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h3>رفع ملف Excel للمناهل</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('waterwells2.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="file" class="form-label">اختيار ملف Excel</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">استيراد</button>
            </form>
        </div>
    </div>
</div>
@endsection
