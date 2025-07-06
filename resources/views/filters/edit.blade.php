@extends('layouts.app')
@section('title', 'تعديل بيانات مرشح')

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
                <h1 class="m-0">تعديل بيانات مرشح</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('filters.index') }}">المرشحات</a></li>
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
                            بيانات المرشح (الفلتر)
                        </h3>
                    </div>

                    <form action="{{ route('filters.update', $filter->id) }}" method="POST" novalidate>
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
                                                    {{ old('station_id', $filter->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filter_type">نوع المرشح<span class="text-danger">*</span></label>
                                        <select name="filter_type" id="filter_type" class="form-control select2" required>
                                            <option value="">-- اختر نوع المرشح --</option>
                                            <option value="OSM-SF"
                                                {{ old('filter_type', $filter->filter_type) == 'OSM-SF' ? 'selected' : '' }}>
                                                OSM-SF</option>
                                            <option value="OSM-CF"
                                                {{ old('filter_type', $filter->filter_type) == 'OSM-CF' ? 'selected' : '' }}>
                                                OSM-CF</option>
                                            <option value="RO System"
                                                {{ old('filter_type', $filter->filter_type) == 'RO System' ? 'selected' : '' }}>
                                                RO System</option>
                                            <option value="Sand Filter (رملي)"
                                                {{ old('filter_type', $filter->filter_type) == 'Sand Filter (رملي)' ? 'selected' : '' }}>
                                                Sand Filter (رملي)</option>
                                            <option value="Carbon Filter (كربوني)"
                                                {{ old('filter_type', $filter->filter_type) == 'Carbon Filter (كربوني)' ? 'selected' : '' }}>
                                                Carbon Filter (كربوني)</option>
                                            <option value="Cartridge Filter (كارتريدج)"
                                                {{ old('filter_type', $filter->filter_type) == 'Cartridge Filter (كارتريدج)' ? 'selected' : '' }}>
                                                Cartridge Filter (كارتريدج)</option>
                                            <option value="Bag Filter"
                                                {{ old('filter_type', $filter->filter_type) == 'Bag Filter' ? 'selected' : '' }}>
                                                Bag Filter</option>
                                            <option value="UV Sterilizer"
                                                {{ old('filter_type', $filter->filter_type) == 'UV Sterilizer' ? 'selected' : '' }}>
                                                UV Sterilizer</option>
                                            <option value="Multi-media Filter"
                                                {{ old('filter_type', $filter->filter_type) == 'Multi-media Filter' ? 'selected' : '' }}>
                                                Multi-media Filter</option>
                                            <option value="Micron Filter (مايكروني)"
                                                {{ old('filter_type', $filter->filter_type) == 'Micron Filter (مايكروني)' ? 'selected' : '' }}>
                                                Micron Filter (مايكروني)</option>
                                            <option value="OSM-X Series"
                                                {{ old('filter_type', $filter->filter_type) == 'OSM-X Series' ? 'selected' : '' }}>
                                                OSM-X Series</option>
                                            <option value="غير معروف"
                                                {{ old('filter_type', $filter->filter_type) == 'غير معروف' ? 'selected' : '' }}>
                                                غير معروف</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filter_capacity">استطاعة المرشح (م³/ساعة)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <input type="number" step="0.01" name="filter_capacity" id="filter_capacity"
                                                class="form-control"
                                                value="{{ old('filter_capacity', $filter->filter_capacity) }}"
                                                placeholder="أدخل استطاعة المرشح">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="readiness_status">نسبة الجاهزية (%)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-percentage"></i></span></div>
                                            <input type="number" name="readiness_status" id="readiness_status"
                                                class="form-control"
                                                value="{{ old('readiness_status', $filter->readiness_status) }}"
                                                placeholder="أدخل نسبة الجاهزية (0-100)" min="0" max="100">
                                        </div>
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
                            <a href="{{ route('filters.index') }}" class="btn btn-secondary btn-lg">
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
