@extends('layouts.app')
@section('title', 'إضافة خزان ديزل جديد')

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
                <h1 class="m-0">إضافة خزان ديزل جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.diesel_tanks.index') }}">خزانات الديزل</a></li>
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

                <!-- قسم استيراد خزانات الديزل (بنفس التصميم الأخضر) -->

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
                        <form action="{{ route('dashboard.import.diesel_tanks') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <p class="text-muted">يمكنك استيراد قائمة بخزانات الديزل دفعة واحدة من ملف إكسل.</p>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="importFile" name="file" required>
                                    <label class="custom-file-label" for="importFile">اختر ملف Excel</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-upload ml-1"></i> بدء
                                الاستيراد</button>
                        </form>
                    </div>
                </div>


                <!-- الفورم الرئيسي لإضافة خزان جديد -->
                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات الخزان الجديد
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('dashboard.diesel_tanks.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-gas-pump text-primary ml-2"></i>البيانات الأساسية للخزان</h5>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tank_name">اسم الخزان<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tag"></i></span></div>
                                            <input type="text" name="tank_name" id="tank_name" class="form-control"
                                                value="{{ old('tank_name') }}" placeholder="أدخل اسم الخزان" required>
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
                                        <label for="tank_capacity">سعة الخزان (لتر)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-ruler-combined"></i></span></div>
                                            <input type="number" name="tank_capacity" id="tank_capacity"
                                                class="form-control" value="{{ old('tank_capacity') }}"
                                                placeholder="أدخل سعة الخزان باللتر" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">نوع الخزان<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-layer-group"></i></span></div>
                                            {{-- تم تحويل الحقل إلى قائمة منسدلة لتجربة أفضل --}}
                                            <select name="type" id="type" class="form-control select2" required>
                                                <option value="" disabled selected>-- أرضي أم خارجي؟ --</option>
                                                <option value="أرضي" {{ old('type') == 'أرضي' ? 'selected' : '' }}>أرضي
                                                </option>
                                                <option value="خارجي" {{ old('type') == 'خارجي' ? 'selected' : '' }}>
                                                    خارجي</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="readiness_percentage">نسبة الجاهزية (%)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-percentage"></i></span></div>
                                            <input type="number" name="readiness_percentage" id="readiness_percentage"
                                                step="0.01" min="0" max="100" class="form-control"
                                                value="{{ old('readiness_percentage') }}"
                                                placeholder="أدخل نسبة الجاهزية من 0 إلى 100" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="general_notes">الملاحظات العامة</label>
                                        <textarea name="general_notes" id="general_notes" class="form-control" rows="3"
                                            placeholder="أدخل أي ملاحظات إضافية">{{ old('general_notes') }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ الخزان
                            </button>
                            <a href="{{ route('dashboard.diesel_tanks.index') }}" class="btn btn-secondary btn-lg">
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
                placeholder: "-- اختر --",
                allowClear: true
            });

            // تفعيل bs-custom-file-input لإظهار اسم الملف المختار
            bsCustomFileInput.init();
        });
    </script>
@endpush
