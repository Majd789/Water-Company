@extends('layouts.app')

@section('title', 'إضافة مهمة صيانة جديدة')

{{-- CSS لتلوين الحقول بشكل تفاعلي عند الإدخال --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
                <h1 class="m-0">إضافة مهمة صيانة جديدة</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.maintenance_tasks.index') }}">مهام الصيانة</a>
                    </li>
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
                            @if(session('import_errors'))
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-ban"></i> حدثت أخطاء أثناء الاستيراد:</h5>
                                    <ul class="mb-0">
                                        @foreach(session('import_errors') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('dashboard.maintenance_tasks.import') }}" method="POST" enctype="multipart/form-data">
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
                <!-- الفورم الرئيسي لإضافة البيانات -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle ml-1"></i>
                            بيانات مهمة الصيانة
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('dashboard.maintenance_tasks.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="unit_id">الوحدة<span class="text-danger">*</span></label>
                                        <select name="unit_id" id="unit_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- اختر الوحدة --</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->unit_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="technician_name">الفني المسؤول<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-user-cog"></i></span></div>
                                            <input type="text" name="technician_name" id="technician_name"
                                                class="form-control" value="{{ old('technician_name') }}"
                                                placeholder="اسم الفني" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="maintenance_date">تاريخ الصيانة<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-calendar-alt"></i></span></div>
                                            <input type="date" name="maintenance_date" id="maintenance_date"
                                                class="form-control" value="{{ old('maintenance_date', date('Y-m-d')) }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. تفاصيل العطل --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-exclamation-triangle text-danger ml-2"></i>تفاصيل العطل</h5>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="location">مكان العطل<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-map-marker-alt"></i></span></div>
                                            <input type="text" name="location" id="location" class="form-control"
                                                value="{{ old('location') }}" placeholder="مثال: محطة حيش - بئر رقم 2"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fault_description">وصف العطل<span class="text-danger">*</span></label>
                                        <textarea name="fault_description" id="fault_description" class="form-control" rows="3"
                                            placeholder="اكتب وصفاً دقيقاً للعطل" required>{{ old('fault_description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fault_cause">سبب العطل (إن وجد)</label>
                                        <textarea name="fault_cause" id="fault_cause" class="form-control" rows="3"
                                            placeholder="اكتب السبب الذي تم تحديده للعطل">{{ old('fault_cause') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. إجراءات الصيانة والحالة --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-tools text-success ml-2"></i>إجراءات الصيانة والحالة</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="maintenance_actions">وصف إجراءات الصيانة<span
                                                class="text-danger">*</span></label>
                                        <textarea name="maintenance_actions" id="maintenance_actions" class="form-control" rows="4"
                                            placeholder="اكتب بالتفصيل الإجراءات التي تم اتخاذها" required>{{ old('maintenance_actions') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>هل تم إصلاح العطل؟<span class="text-danger">*</span></label>
                                        <div class="d-flex pt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_fixed"
                                                    id="is_fixed_yes" value="1"
                                                    {{ old('is_fixed', 1) == 1 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="is_fixed_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_fixed"
                                                    id="is_fixed_no" value="0"
                                                    {{ old('is_fixed') == 0 ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="is_fixed_no">لا</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="reason_not_fixed_container"
                                        style="display: {{ old('is_fixed', 1) == 0 ? 'block' : 'none' }};">
                                        <label for="reason_not_fixed">لماذا لم يتم الإصلاح؟</label>
                                        <textarea name="reason_not_fixed" id="reason_not_fixed" class="form-control" rows="2"
                                            placeholder="اكتب سبب عدم اكتمال الإصلاح">{{ old('reason_not_fixed') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            {{-- 4. ملاحظات إضافية --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-file-alt text-info ml-2"></i>ملاحظات إضافية</h5>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3"
                                            placeholder="أي ملاحظات إضافية حول المهمة">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ البيانات
                            </button>
                            <a href="{{ route('dashboard.maintenance_tasks.index') }}" class="btn btn-secondary btn-lg">
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
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            // تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl"
            });

            // إظهار أو إخفاء حقل "لماذا لم يتم الإصلاح" بناءً على الاختيار
            $('input[name="is_fixed"]').on('change', function() {
                if ($(this).val() == '0') {
                    $('#reason_not_fixed_container').slideDown();
                } else {
                    $('#reason_not_fixed_container').slideUp();
                }
            });

            // تشغيل الدالة عند تحميل الصفحة للتأكد من الحالة الصحيحة عند وجود old data
            $('input[name="is_fixed"]:checked').trigger('change');
        });
    </script>
@endpush
