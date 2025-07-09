@extends('layouts.app')
@section('title', 'إضافة بئر جديد')

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
                <h1 class="m-0">إضافة بئر جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    {{-- تم تعديل الرابط ليتناسب مع الآبار --}}
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.private-wells.index') }}">الآبار الخاصة</a></li>
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

                <!-- قسم استيراد الآبار (بنفس التصميم الأخضر) -->
               
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
                            {{-- تم تغيير الرابط ليتوافق مع استيراد الآبار --}}
                            <form action="{{ route('dashboard.private_wells.import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted">يمكنك استيراد قائمة بالآبار دفعة واحدة من ملف إكسل.</p>
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
               

                <!-- الفورم الرئيسي لإضافة بئر جديد -->
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات البئر الجديد
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('dashboard.private-wells.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>البيانات الأساسية للبئر</h5>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_name">اسم البئر<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tag"></i></span></div>
                                            <input type="text" name="well_name" id="well_name" class="form-control"
                                                value="{{ old('well_name') }}" placeholder="أدخل اسم البئر" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة التابع لها<span class="text-danger">*</span></label>
                                        {{-- تم إضافة أيقونة وتفعيل select2 --}}
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-sitemap"></i></span></div>
                                            <select name="station_id" id="station_id" class="form-control select2" required>
                                                <option value="" disabled selected>-- اختر محطة --</option>
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
                                        <label for="well_type">نوع البئر</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-water"></i></span></div>
                                            <input type="text" name="well_type" id="well_type" class="form-control"
                                                value="{{ old('well_type') }}" placeholder="مثال: ارتوازي، سطحي...">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_count">عدد الآبار</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-layer-group"></i></span></div>
                                            <input type="number" name="well_count" id="well_count" class="form-control"
                                                value="{{ old('well_count') }}" placeholder="أدخل عدد الآبار">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="distance_from_nearest_well">المسافة من أقرب بئر (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-horizontal"></i></span></div>
                                            <input type="number" name="distance_from_nearest_well"
                                                id="distance_from_nearest_well" class="form-control"
                                                value="{{ old('distance_from_nearest_well') }}"
                                                placeholder="أدخل المسافة بالأمتار">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="أدخل أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ البئر
                            </button>
                            <a href="{{ route('dashboard.private-wells.index') }}" class="btn btn-secondary btn-lg">
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
                placeholder: "-- اختر محطة --",
                allowClear: true // لإضافة زر المسح
            });

            // تفعيل bs-custom-file-input لإظهار اسم الملف المختار
            bsCustomFileInput.init();
        });
    </script>
@endpush
