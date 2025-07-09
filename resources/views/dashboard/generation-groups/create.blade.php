@extends('layouts.app')

{{-- CSS لتلوين الحقول بشكل تفاعلي عند الإدخال --}}
@push('styles')
    <style>
        /* لا يتم تطبيق الألوان إلا بعد أن يبدأ المستخدم بالكتابة */
        .form-control:not(:placeholder-shown):invalid {
            border-color: #dc3545 !important;
        }

        .form-control:not(:placeholder-shown):valid {
            border-color: #28a745 !important;
        }

        /* استهداف خاص لـ Select2 */
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
                <h1 class="m-0">إضافة مجموعة توليد جديدة</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.generation-groups.index') }}">مجموعات التوليد</a>
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

                <!-- قسم استيراد مجموعات التوليد -->
              
                    <div class="card card-success collapsed-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-file-excel ml-1"></i>
                                استيراد من ملف Excel
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.generation_groups.import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted">يمكنك استيراد قائمة بمجموعات التوليد دفعة واحدة من ملف إكسل.</p>
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
              

                <!-- الفورم الرئيسي لإضافة مجموعة توليد جديدة -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات مجموعة التوليد
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('dashboard.generation-groups.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            {{-- 1. المعلومات الأساسية --}}
                            <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-info-circle text-primary ml-2"></i>المعلومات الأساسية</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة التابعة لها<span class="text-danger">*</span></label>
                                        <select name="station_id" class="form-control select2" required>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="generator_name">اسم المولدة<span class="text-danger">*</span></label>
                                        <select name="generator_name" id="generator_name" class="form-control select2"
                                            required>
                                            <option value="" disabled selected>-- اختر اسم المولدة --</option>
                                            @foreach (['DOOSAN', 'PERKINS', 'SCANIA', 'VOLVO', 'CATERPILLAR', 'TEKSAN', 'CUMMINS', 'DAEWOO', 'JOHN DEERE', 'IVECO', 'FIAT', 'EMSA GENERATOR', 'AKSA', 'FPT', 'DORMAN', 'RICARDO (صيني)', 'BAUDOUIN', 'MARKON', 'KJPOWER', 'GENPOWER', 'DODS', 'AIFO', 'DOTZ', 'MAK', 'MITSUBISHI / MITORELA', 'IDEA', 'COELMO', 'غير معروف'] as $generator)
                                                <option value="{{ $generator }}"
                                                    {{ old('generator_name') == $generator ? 'selected' : '' }}>
                                                    {{ $generator }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="generation_capacity">استطاعة التوليد (KVA)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-bolt"></i></span></div>
                                            <input type="number" step="0.01" name="generation_capacity"
                                                class="form-control" value="{{ old('generation_capacity') }}"
                                                placeholder="أدخل استطاعة التوليد" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="actual_operating_capacity">استطاعة العمل الفعلية (KVA)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <input type="number" step="0.01" name="actual_operating_capacity"
                                                class="form-control" value="{{ old('actual_operating_capacity') }}"
                                                placeholder="أدخل استطاعة العمل الفعلية" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="generation_group_readiness_percentage">نسبة الجاهزية (%)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-percentage"></i></span></div>
                                            <input type="number" step="0.01"
                                                name="generation_group_readiness_percentage" class="form-control"
                                                value="{{ old('generation_group_readiness_percentage') }}"
                                                placeholder="أدخل نسبة الجاهزية (0-100)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. بيانات التشغيل والاستهلاك --}}
                            <h5 class="mt-4 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><i
                                    class="fas fa-cogs text-success ml-2"></i>بيانات التشغيل والاستهلاك</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fuel_consumption">استهلاك الوقود (لتر/ساعة)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-gas-pump"></i></span></div>
                                            <input type="number" step="0.01" name="fuel_consumption"
                                                class="form-control" value="{{ old('fuel_consumption') }}"
                                                placeholder="أدخل استهلاك الوقود" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="oil_usage_duration">مدة استخدام الزيت (ساعة)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-hourglass-half"></i></span></div>
                                            <input type="number" name="oil_usage_duration" class="form-control"
                                                value="{{ old('oil_usage_duration') }}"
                                                placeholder="أدخل مدة استخدام الزيت" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="oil_quantity_for_replacement">كمية الزيت للتبديل (لتر)<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-oil-can"></i></span></div>
                                            <input type="number" step="0.01" name="oil_quantity_for_replacement"
                                                class="form-control" value="{{ old('oil_quantity_for_replacement') }}"
                                                placeholder="أدخل كمية الزيت للتبديل" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="operational_status">الوضع التشغيلي<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-power-off"></i></span></div>
                                            <select name="operational_status" id="operational_status"
                                                class="form-control" required>
                                                <option value="عاملة"
                                                    {{ old('operational_status') == 'عاملة' ? 'selected' : '' }}>عاملة
                                                </option>
                                                <option value="متوقفة"
                                                    {{ old('operational_status') == 'متوقفة' ? 'selected' : '' }}>متوقفة
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="stop_reason_container" style="display: none;">
                                    <div class="form-group">
                                        <label for="stop_reason">سبب التوقف</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-exclamation-triangle"></i></span></div>
                                            <textarea name="stop_reason" class="form-control" placeholder="أدخل سبب التوقف">{{ old('stop_reason') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-pencil-alt"></i></span></div>
                                            <textarea name="notes" class="form-control" placeholder="أدخل أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ المجموعة
                            </button>
                            <a href="{{ route('dashboard.generation-groups.index') }}" class="btn btn-secondary btn-lg">
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
            // تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl"
            });

            // تفعيل bs-custom-file-input
            bsCustomFileInput.init();

            // منطق إظهار/إخفاء حقل سبب التوقف
            const operationalStatusSelect = document.getElementById('operational_status');
            const stopReasonContainer = document.getElementById('stop_reason_container');

            function toggleStopReason() {
                if (operationalStatusSelect.value === 'متوقفة') {
                    stopReasonContainer.style.display = 'block';
                } else {
                    stopReasonContainer.style.display = 'none';
                }
            }

            // استدعاء الوظيفة عند تغيير القيمة
            operationalStatusSelect.addEventListener('change', toggleStopReason);

            // استدعاء الوظيفة عند تحميل الصفحة لضبط الحالة الأولية بناءً على old()
            toggleStopReason();
        });
    </script>
@endpush
