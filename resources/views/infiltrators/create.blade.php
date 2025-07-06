@extends('layouts.app')
@section('title', 'إضافة انفلتر جديد')

{{-- استيراد نفس الأنماط التفاعلية من التصميم المحفوظ --}}
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

        .form-control.is-valid~.select2-container--bootstrap4 .select2-selection {
            border-color: #28a745 !important;
        }

        .form-control.is-invalid~.select2-container--bootstrap4 .select2-selection {
            border-color: #dc3545 !important;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">إضافة انفلتر جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('infiltrators.index') }}">الانفلترات</a></li>
                    <li class="breadcrumb-item active">إضافة جديدة</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">

                <!-- رسائل الحالة والأخطاء -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check ml-1"></i> نجاح!</h5>
                        {{ session('success') }}
                    </div>
                @endif

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

                <!-- قسم استيراد الانفلترات (بنفس التصميم الأخضر) -->
                @if (auth()->check() && auth()->user()->role_id == 'admin')
                    <div class="card card-success collapsed-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-file-excel ml-1"></i>
                                استيراد من ملف Excel
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus "></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('infiltrators.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted">يمكنك استيراد قائمة بالانفلترات دفعة واحدة من ملف إكسل.</p>
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="importFile" name="file"
                                            required>
                                        <label class="custom-file-label" for="importFile">اختر ملف Excel</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success"><i class="fas fa-upload ml-1"></i> بدء
                                    الاستيراد</button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- الفورم الرئيسي لإضافة انفلتر جديد -->
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات الانفلتر الجديد
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('infiltrators.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>البيانات الأساسية للانفلتر</h5>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="infiltrator_type">نوع الانفلتر<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-cogs"></i></span></div>
                                            <select name="infiltrator_type" id="infiltrator_type"
                                                class="form-control select2" required>
                                                <option value="" disabled selected>-- اختر نوع الانفلتر --</option>
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
                                                        {{ old('infiltrator_type') == $type ? 'selected' : '' }}>
                                                        {{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة التابع لها<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-sitemap"></i></span></div>
                                            <select name="station_id" id="station_id" class="form-control select2" required>
                                                <option value="" disabled selected>-- اختر المحطة --</option>
                                                @foreach ($stations as $station)
                                                    <option value="{{ $station->id }}"
                                                        {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                                        {{ $station->station_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="infiltrator_capacity">استطاعة الانفلتر (kW)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bolt"></i></span></div>
                                            <input type="number" step="0.1" name="infiltrator_capacity"
                                                id="infiltrator_capacity" class="form-control"
                                                value="{{ old('infiltrator_capacity') }}"
                                                placeholder="أدخل استطاعة الانفلتر بالكيلو واط" required>
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
                                            <input type="number" step="1" min="0" max="100"
                                                name="readiness_status" id="readiness_status" class="form-control"
                                                value="{{ old('readiness_status') }}"
                                                placeholder="أدخل نسبة الجاهزية من 0 إلى 100" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        {{-- تم تحويله إلى textarea --}}
                                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="أدخل أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ الانفلتر
                            </button>
                            <a href="{{ route('infiltrators.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times ml-1"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                    <!-- نهاية الفورم -->
                </div>
                <!-- /.card -->

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

@push('scripts')
    <script>
        $(function() {
            // تفعيل Select2 مع دعم كامل للغة العربية ومطابقة شكل bootstrap
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: "-- اختر --", // placeholder عام
                allowClear: true
            });

            // تفعيل bs-custom-file-input لإظهار اسم الملف المختار
            bsCustomFileInput.init();
        });
    </script>
@endpush
