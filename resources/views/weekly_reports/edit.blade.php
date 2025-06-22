<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل التقرير الأسبوعي</h2>
            <form action="{{ route('weekly_reports.update', $report) }}" method="POST" class="login-form"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 600px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل البيانات الأساسية
                            </div>
                            <div class="card-body">
                                <label for="unit_id">الوحدة</label>
                                <select name="unit_id" id="unit_id"
                                    class="form-control @error('unit_id') is-invalid @enderror" required>
                                    <option value="">اختر وحدة</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id', $report->unit_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->unit_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="report_date">تاريخ التقرير</label>
                                <input type="date" name="report_date" id="report_date"
                                    class="form-control @error('report_date') is-invalid @enderror"
                                    value="{{ old('report_date', $report->report_date) }}" required>
                                @error('report_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="sender_name">اسم المرسل</label>
                                <input type="text" name="sender_name" id="sender_name"
                                    class="form-control @error('sender_name') is-invalid @enderror"
                                    value="{{ old('sender_name', $report->sender_name) }}" required>
                                @error('sender_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="operational_status">الوضع التشغيلي</label>
                                <textarea name="operational_status" id="operational_status"
                                    class="form-control @error('operational_status') is-invalid @enderror" required>{{ old('operational_status', $report->operational_status) }}</textarea>
                                @error('operational_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="stop_reason">سبب توقف المحطة</label>
                                <textarea name="stop_reason" id="stop_reason" class="form-control @error('stop_reason') is-invalid @enderror">{{ old('stop_reason', $report->stop_reason) }}</textarea>
                                @error('stop_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="maintenance_works">أعمال الصيانة (سردي)</label>
                                <textarea name="maintenance_works" id="maintenance_works"
                                    class="form-control @error('maintenance_works') is-invalid @enderror" required>{{ old('maintenance_works', $report->maintenance_works) }}</textarea>
                                @error('maintenance_works')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="maintenance_entity">اسم الجهة المنفذة</label>
                                <input type="text" name="maintenance_entity" id="maintenance_entity"
                                    class="form-control @error('maintenance_entity') is-invalid @enderror"
                                    value="{{ old('maintenance_entity', $report->maintenance_entity) }}">
                                @error('maintenance_entity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="maintenance_image">صورة إثباتية لأعمال الصيانة</label>
                                <input type="file" name="maintenance_image" id="maintenance_image"
                                    class="form-control @error('maintenance_image') is-invalid @enderror">
                                @if ($report->maintenance_image)
                                    <a href="{{ asset('storage/' . $report->maintenance_image) }}" target="_blank">عرض
                                        الصورة الحالية</a>
                                @endif
                                @error('maintenance_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="administrative_works">أعمال إدارية (سردي)</label>
                                <textarea name="administrative_works" id="administrative_works"
                                    class="form-control @error('administrative_works') is-invalid @enderror">{{ old('administrative_works', $report->administrative_works) }}</textarea>
                                @error('administrative_works')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="administrative_image">صورة الأعمال الإدارية</label>
                                <input type="file" name="administrative_image" id="administrative_image"
                                    class="form-control @error('administrative_image') is-invalid @enderror">
                                @if ($report->administrative_image)
                                    <a href="{{ asset('storage/' . $report->administrative_image) }}" target="_blank">عرض
                                        الصورة
                                        الحالية</a>
                                @endif
                                @error('administrative_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <label for="additional_notes">ملاحظات إضافية</label>
                                <textarea name="additional_notes" id="additional_notes"
                                    class="form-control @error('additional_notes') is-invalid @enderror">{{ old('additional_notes', $report->additional_notes) }}</textarea>
                                @error('additional_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning">تحديث</button>
            </form>
        </div>
    </div>
@endsection
