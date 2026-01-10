@extends('layouts.app')
@section('title', 'مركز التقارير والتحليل')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>مركز التقارير</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">التقارير</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> استخراج تقارير شاملة</h3>
            </div>
            <form action="{{ route('dashboard.reports.generate') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        {{-- اختيار نوع التقرير --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>نوع التقرير <span class="text-danger">*</span></label>
                                <select name="report_type" class="form-control" required>
                                    <option value="" disabled selected>-- اختر التقرير المطلوب --</option>
                                    <option value="financial">📊 التقرير المالي (تمويل وميزانيات)</option>
                                    <option value="geographical">🌍 التوزيع الجغرافي (وحدات وقرى)</option>
                                    <option value="contractors">👷 أداء المقاولين (عقود ومهام)</option>
                                    <option value="projects_comprehensive">📂 سجل المشاريع الشامل</option>
                                </select>
                            </div>
                        </div>

                        {{-- فلاتر التاريخ --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>من تاريخ (بداية المشروع)</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>إلى تاريخ</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>ملاحظة:</strong> التقارير يتم توليدها بناءً على البيانات الحية في النظام، وترك التواريخ فارغة سيجلب كافة البيانات.
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="fas fa-file-download mr-2"></i> توليد وتحميل التقرير
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>
@endsection
