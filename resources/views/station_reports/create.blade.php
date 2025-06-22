<!-- resources/views/waterwells/create.blade.php -->
<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <style>
        /* createoffice.css */
    </style>
    <div class="recent-orders text-center" style="text-align: center">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h3>رفع ملف Excel للمناهل</h3>
            </div>
            <div class="card-body">
                @if (auth()->check() && auth()->user()->role_id == 'admin')
                    <form action="{{ route('station_reports.import') }}" method="POST" enctype="multipart/form-data"
                        class="mb-3">
                        @csrf

                        <div class="mb-4">
                            <label for="file" class="form-label">اختيار ملف Excel</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">استيراد</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
