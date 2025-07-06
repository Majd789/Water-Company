@extends('layouts.app')
@section('title', 'تعديل بيانات انفلتر')

{{-- استيراد مكتبة Select2 و CSS مخصص --}}
@push('styles')
    <style>
        .form-control:not(:placeholder-shown):invalid {
            border-color: #dc3545 !important;
        }

        .form-control:not(:placeholder-shown):valid {
            border-color: #28a745 !important;
        }

        .select2-container--bootstrap4 .select2-selection {
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تعديل بيانات انفلتر</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('infiltrators.index') }}">الانفلترات</a></li>
                    <li class="breadcrumb-item active">تعديل بيانات</li>
                </ol>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban ml-1"></i> خطأ!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات الانفلتر (العاكس)
                        </h3>
                    </div>

                    <form action="{{ route('infiltrators.update', $infiltrator->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة<span class="text-danger">*</span></label>
                                        <select name="station_id" id="station_id" class="form-control select2" required>
                                            <option value="">-- اختر المحطة --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}"
                                                    {{ old('station_id', $infiltrator->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="infiltrator_type">نوع / ماركة الانفلتر<span
                                                class="text-danger">*</span></label>
                                        <select name="infiltrator_type" id="infiltrator_type" class="form-control select2"
                                            required>
                                            <option value="">-- اختر النوع --</option>
                                            @php
                                                $types = [
                                                    'VEIKONG',
                                                    'USFULL',
                                                    'LS',
                                                    'ABB',
                                                    'GROWATT',
                                                    'SMA',
                                                    'HUAWEI',
                                                    'DANFOSS',
                                                    'FRECON',
                                                    'BAISON',
                                                    'GMTCNT',
                                                    'CELIK',
                                                    'TREST',
                                                    'TRUST',
                                                    'STAR POWER',
                                                    'STAR NEW',
                                                    'WINGS INTERNATIONAL',
                                                    'ORIGINAL COLD',
                                                    'NGGRID',
                                                    'POWER MAX PRO',
                                                    'FREKON',
                                                    'GELEK',
                                                    'INVT',
                                                    'ENPHASE',
                                                    'SOLAREDGE',
                                                    'GOODWE',
                                                    'VICTRON ENERGY',
                                                    'DELTA',
                                                    'SUNGROW',
                                                    'YASKAWA',
                                                    'KACO',
                                                    'FRONIUS',
                                                    'SOLAX',
                                                    'SOLIS',
                                                    'VFD-LS',
                                                    'RUST',
                                                    'COM',
                                                    'SHIRE',
                                                    'CLICK',
                                                    'HLUX',
                                                    'MOLTO',
                                                    'ON-GRID',
                                                    'OFF-GRID',
                                                    'HYBRID',
                                                    'غير معروف',
                                                ];
                                            @endphp
                                            @foreach ($types as $type)
                                                <option value="{{ $type }}"
                                                    {{ old('infiltrator_type', $infiltrator->infiltrator_type) == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="infiltrator_capacity">استطاعة الانفلتر (KVA)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bolt"></i></span></div>
                                            <input type="number" step="0.01" name="infiltrator_capacity"
                                                id="infiltrator_capacity" class="form-control"
                                                value="{{ old('infiltrator_capacity', $infiltrator->infiltrator_capacity) }}"
                                                placeholder="أدخل استطاعة الانفلتر" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="readiness_status">نسبة الجاهزية (%)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-percentage"></i></span></div>
                                            <input type="number" name="readiness_status" id="readiness_status"
                                                class="form-control"
                                                value="{{ old('readiness_status', $infiltrator->readiness_status) }}"
                                                placeholder="أدخل نسبة الجاهزية (0-100)" required min="0"
                                                max="100">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">الملاحظات</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="أدخل أي ملاحظات إضافية">{{ old('notes', $infiltrator->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ التعديلات
                            </button>
                            <a href="{{ route('infiltrators.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times ml-1"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: "-- اختر --"
            });
        });
    </script>
@endpush
