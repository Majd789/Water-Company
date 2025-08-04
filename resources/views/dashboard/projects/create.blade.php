@extends('layouts.app')

@section('title', 'إضافة مشروع جديد')

@push('styles')
    {{-- CSS لتلوين الحقول بشكل تفاعلي عند الإدخال --}}
    <style>
        .form-control:not(:placeholder-shown):invalid {
            border-color: #dc3545;
        }

        .form-control:not(:placeholder-shown):valid {
            border-color: #28a745;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">إضافة مشروع جديد</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/dashboard">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.index') }}">المشاريع</a></li>
                    <li class="breadcrumb-item active">إضافة</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <form action="{{ route('dashboard.projects.store') }}" method="POST" novalidate>
            @csrf

            {{-- عرض الأخطاء --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban ml-1"></i> خطأ!</h5>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- =================================================== -->
            <!-- بطاقة بيانات المشروع الرئيسية (بدون include) -->
            <!-- =================================================== -->
            <div class="card card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">بيانات المشروع الأساسية</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- اسم المشروع --}}
                        <div class="col-md-6 form-group">
                            <label for="name">اسم المشروع</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name') }}" placeholder="أدخل اسم المشروع" required>
                        </div>

                        {{-- الرقم المرجعي للمؤسسة --}}
                        <div class="col-md-6 form-group">
                            <label for="institution_ref_number">الرقم المرجعي للمؤسسة</label>
                            <input type="text" id="institution_ref_number" name="institution_ref_number"
                                class="form-control" value="{{ old('institution_ref_number') }}" placeholder="مثال: 52"
                                required>
                        </div>

                        {{-- تاريخ كتاب المؤسسة --}}
                        <div class="col-md-6 form-group">
                            <label for="institution_ref_date">تاريخ كتاب المؤسسة</label>
                            <input type="date" id="institution_ref_date" name="institution_ref_date" class="form-control"
                                value="{{ old('institution_ref_date') }}" required>
                        </div>

                        {{-- الرقم المرجعي لمكتب العمل الانساني --}}
                        <div class="col-md-6 form-group">
                            <label for="hac_ref_number">رقم كتاب مكتب العمل الانساني</label>
                            <input type="text" id="hac_ref_number" name="hac_ref_number" class="form-control"
                                value="{{ old('hac_ref_number') }}" placeholder="مثال: 8524 (اختياري)">
                        </div>

                        {{-- تاريخ كتاب مكتب العمل الانساني --}}
                        <div class="col-md-6 form-group">
                            <label for="hac_ref_date">تاريخ كتاب مكتب العمل الانساني</label>
                            <input type="date" id="hac_ref_date" name="hac_ref_date" class="form-control"
                                value="{{ old('hac_ref_date') }}">
                        </div>

                        {{-- نوع المشروع --}}
                        <div class="col-md-6 form-group">
                            <label for="type">نوع المشروع</label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="تنفيذ" @if (old('type') == 'تنفيذ') selected @endif>تنفيذ</option>
                                <option value="تقييم احتياج" @if (old('type') == 'تقييم احتياج') selected @endif>تقييم احتياج
                                </option>
                                <option value="أخرى" @if (old('type') == 'أخرى') selected @endif>أخرى</option>
                            </select>
                        </div>

                        {{-- المنظمة --}}
                        <div class="col-md-6 form-group">
                            <label for="organization">المنظمة</label>
                            <input type="text" id="organization" name="organization" class="form-control"
                                value="{{ old('organization') }}" placeholder="اسم المنظمة الشريكة" required>
                        </div>

                        {{-- الجهة المانحة --}}
                        <div class="col-md-6 form-group">
                            <label for="donor">الجهة المانحة</label>
                            <input type="text" id="donor" name="donor" class="form-control"
                                value="{{ old('donor') }}" placeholder="اسم الجهة المانحة" required>
                        </div>

                        {{-- الكلفة الإجمالية --}}
                        <div class="col-md-6 form-group">
                            <label for="total_cost">الكلفة الإجمالية ($)</label>
                            <input type="number" id="total_cost" name="total_cost" class="form-control" step="0.01"
                                value="{{ old('total_cost', 0) }}" required>
                        </div>

                        {{-- المدة بالأيام --}}
                        <div class="col-md-6 form-group">
                            <label for="duration_days">مدة المشروع (يوم)</label>
                            <input type="number" id="duration_days" name="duration_days" class="form-control"
                                value="{{ old('duration_days') }}" placeholder="عدد أيام التنفيذ" required>
                        </div>

                        {{-- تاريخ البدء --}}
                        <div class="col-md-6 form-group">
                            <label for="start_date">تاريخ بداية المشروع</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                value="{{ old('start_date') }}" required>
                        </div>

                        {{-- تاريخ الانتهاء --}}
                        <div class="col-md-6 form-group">
                            <label for="end_date">تاريخ نهاية المشروع</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                value="{{ old('end_date') }}" required>
                        </div>

                        {{-- اسم المشرف --}}
                        <div class="col-md-6 form-group">
                            <label for="supervisor_name">اسم المشرف</label>
                            <input type="text" id="supervisor_name" name="supervisor_name" class="form-control"
                                value="{{ old('supervisor_name') }}" placeholder="اسم المشرف المسؤول" required>
                        </div>

                        {{-- رقم تواصل المشرف --}}
                        <div class="col-md-6 form-group">
                            <label for="supervisor_contact">رقم تواصل المشرف</label>
                            <input type="text" id="supervisor_contact" name="supervisor_contact" class="form-control"
                                value="{{ old('supervisor_contact') }}" placeholder="رقم الهاتف (اختياري)">
                        </div>

                        {{-- حالة المشروع --}}
                        <div class="col-md-6 form-group">
                            <label for="status">حالة المشروع</label>
                            <input type="text" id="status" name="status" class="form-control"
                                value="{{ old('status', 'موافقة') }}" placeholder="مثال: موافقة، قيد التنفيذ" required>
                        </div>

                    </div>
                </div>
            </div>

            <!-- =================================================== -->
            <!-- بطاقة الأنشطة المرتبطة -->
            <!-- =================================================== -->
            <div class="card card-secondary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">أنشطة المشروع</h3>
                    <div class="card-tools">
                        <button type="button" id="add_activity_btn" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> إضافة نشاط
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="activities_container">
                        {{-- الأنشطة التي يتم إضافتها بواسطة JS ستظهر هنا --}}
                    </div>
                </div>
            </div>

            <div class="card-footer text-left">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save ml-1"></i> حفظ المشروع</button>
                <a href="{{ route('dashboard.projects.index') }}" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>

    <!-- =================================================== -->
    <!-- قالب صف النشاط (مخفي وسيتم استخدامه بواسطة JS) -->
    <!-- =================================================== -->
    <div id="activity_template" style="display: none;">
        <div class="activity-row border p-3 mb-3 rounded position-relative">
            <button type="button" class="btn btn-danger btn-sm remove_activity_btn"
                style="position: absolute; top: 10px; left: 10px;"><i class="fas fa-trash"></i></button>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>اسم النشاط</label>
                    <input type="text" name="activities[__INDEX__][activity_name]" class="form-control"
                        placeholder="مثال: صيانة شبكة" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>القيمة ($)</label>
                    <input type="number" name="activities[__INDEX__][value]" class="form-control" step="0.01"
                        placeholder="قيمة هذا النشاط فقط" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>حالة التنفيذ</label>
                    <input type="text" name="activities[__INDEX__][execution_status]" class="form-control"
                        placeholder="مثال: قيد التنفيذ" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>الوحدة</label>
                    <select name="activities[__INDEX__][unit_id]" class="form-control">
                        <option value="">-- اختر وحدة (اختياري) --</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>البلدة</label>
                    <select name="activities[__INDEX__][town_id]" class="form-control">
                        <option value="">-- اختر بلدة (اختياري) --</option>
                        @foreach ($towns as $town)
                            <option value="{{ $town->id }}">{{ $town->town_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>المحطة</label>
                    <select name="activities[__INDEX__][station_id]" class="form-control">
                        <option value="">-- اختر محطة (اختياري) --</option>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}">{{ $station->station_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let activityIndex = 0;

            // عند النقر على زر "إضافة نشاط"
            $('#add_activity_btn').click(function() {
                // احصل على محتوى القالب واستبدل المؤشر __INDEX__ بالرقم الحالي
                let template = $('#activity_template').html().replace(/__INDEX__/g, activityIndex);

                // أضف القالب إلى حاوية الأنشطة
                $('#activities_container').append(template);

                // زد المؤشر للنشاط التالي
                activityIndex++;
            });

            // عند النقر على زر الحذف داخل حاوية الأنشطة (استخدام التفويض للتعامل مع العناصر المضافة ديناميكياً)
            $('#activities_container').on('click', '.remove_activity_btn', function() {
                // احذف أقرب عنصر أب له الكلاس 'activity-row'
                $(this).closest('.activity-row').remove();
            });
        });
    </script>
@endpush
