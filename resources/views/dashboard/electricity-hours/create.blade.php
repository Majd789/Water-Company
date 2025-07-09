@extends('layouts.app')

@section('title', 'إضافة عداد كهرباء جديد')

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
                <h1 class="m-0">إضافة عداد كهرباء جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.electricity-hours.index') }}">عدادات الكهرباء</a>
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
            <div class="col-lg-10 col-xl-8">

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

                <!-- قسم استيراد بيانات العدادات -->
              
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
                            <form action="{{ route('dashboard.electricity_hours.import') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <p class="text-muted">يمكنك استيراد قائمة بعدادات الكهرباء دفعة واحدة.</p>
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
                            <i class="fas fa-bolt ml-1"></i>
                            بيانات عداد الكهرباء
                        </h3>
                    </div>
                    <!-- /.card-header -->

                    <!-- بدء الفورم -->
                    <form action="{{ route('dashboard.electricity-hours.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة<span class="text-danger">*</span></label>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="electricity_hour_number">رقم العداد<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-barcode"></i></span></div>
                                            <input type="text" name="electricity_hour_number"
                                                id="electricity_hour_number" class="form-control"
                                                value="{{ old('electricity_hour_number') }}" placeholder="أدخل رقم العداد"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="electricity_hours">عدد ساعات التغذية<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-clock"></i></span></div>
                                            <input type="number" name="electricity_hours" id="electricity_hours"
                                                class="form-control" value="{{ old('electricity_hours') }}"
                                                placeholder="أدخل عدد ساعات التغذية اليومية" required min="0"
                                                max="24">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meter_type">نوع العداد<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-tachometer-alt"></i></span></div>
                                            <select name="meter_type" id="meter_type" class="form-control" required>
                                                <option value="" disabled selected>-- اختر نوع العداد --</option>
                                                <option value="مسبق الدفع"
                                                    {{ old('meter_type') == 'مسبق الدفع' ? 'selected' : '' }}>مسبق الدفع
                                                </option>
                                                <option value="لاحق الدفع"
                                                    {{ old('meter_type') == 'لاحق الدفع' ? 'selected' : '' }}>لاحق الدفع
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="operating_entity">الجهة المشغلة<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-building"></i></span></div>
                                            <input type="text" name="operating_entity" id="operating_entity"
                                                class="form-control" value="{{ old('operating_entity') }}"
                                                placeholder="أدخل اسم الجهة المسؤولة" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">الملاحظات</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-sticky-note"></i></span></div>
                                            <textarea name="notes" id="notes" class="form-control" rows="3"
                                                placeholder="أدخل أي ملاحظات إضافية هنا">{{ old('notes') }}</textarea>
                                        </div>
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
                            <a href="{{ route('dashboard.electricity-hours.index') }}" class="btn btn-secondary btn-lg">
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
        });
    </script>
@endpush
