@extends('layouts.app')
@section('title', 'تفاصيل المهمة: ' . $contractorTask->task_code)

@push('styles')
    <style>
        .widget-user .widget-user-header {
            height: 220px;
            /* صورة خلفية تعبر عن التنفيذ الميداني */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('assets/img/task-header.jpg') }}');
            background-size: cover;
            background-position: center center;
            background-color: #343a40;
        }
        .widget-user .widget-user-image > .icon-circle {
            width: 100px; height: 100px; border: 3px solid #fff;
            background-color: #6610f2; /* لون بنفسجي للمهام */
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 40px; color: #fff;
        }
        .card-footer { padding-top: 60px; }
        .description-block { margin-bottom: 1.5rem; text-align: center; padding: 0 10px; }
        .description-text { display: block; color: #6c757d; font-weight: 500; text-transform: uppercase; font-size: 0.85rem; margin-bottom: 5px; }
        .description-header { font-size: 1.1rem; font-weight: 600; color: #343a40; display: block; }
        .section-divider { border-top: 1px solid #dee2e6; margin: 2rem 0; }

        /* تنسيق خاص لحالة عدم التطابق */
        .discrepancy-box {
            background-color: #fff5f5;
            border-right: 5px solid #dc3545;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تفاصيل المهمة: <span style="color: #6610f2;">{{ $contractorTask->task_code }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor-tasks.index') }}">مهام المقاولين</a></li>
                    <li class="breadcrumb-item active">{{ $contractorTask->task_code }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- البطاقة الرئيسية --}}
            <div class="col-md-8">
                <div class="card card-widget widget-user shadow-lg rounded">
                    {{-- رأس البطاقة --}}
                    <div class="widget-user-header text-white">
                        <h3 class="widget-user-username display-4 mb-0" style="font-weight: bold;">
                            {{ $contractorTask->projectActivity->masterActivity->name ?? 'نشاط غير معروف' }}
                        </h3>
                        <h5 class="widget-user-desc mt-2">
                            {{ Str::limit($contractorTask->description, 60) ?? 'لا يوجد وصف مختصر' }}
                        </h5>
                    </div>

                    {{-- الأيقونة --}}
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2"><i class="fas fa-hammer"></i></div>
                    </div>

                    <div class="card-footer">
                        {{-- إحصائيات سريعة --}}
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <span class="description-text">التكلفة</span>
                                    <h5 class="description-header text-success">
                                        ${{ number_format($contractorTask->cost, 2) }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <span class="description-text">الكمية المنفذة</span>
                                    <h5 class="description-header">
                                        {{ $contractorTask->quantity }} {{ $contractorTask->unit_measure }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <span class="description-text">حالة التطابق</span>
                                    <h5 class="description-header">
                                        @if($contractorTask->is_discrepant)
                                            <span class="badge badge-danger">غير مطابق</span>
                                        @else
                                            <span class="badge badge-success">مطابق للنشاط</span>
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">

                        {{-- جسم البطاقة والتفاصيل --}}
                        <div class="card-body pt-0">

                            {{-- تنبيه عدم التطابق --}}
                            @if($contractorTask->is_discrepant)
                                <div class="discrepancy-box">
                                    <h5 class="text-danger"><i class="fas fa-exclamation-triangle ml-2"></i> تنبيه: المهمة غير مطابقة!</h5>
                                    <p class="mb-0 mt-2 text-dark">
                                        <strong>سبب عدم التطابق:</strong> {{ $contractorTask->discrepancy_notes }}
                                    </p>
                                </div>
                            @endif

                            {{-- الوصف الكامل --}}
                            <h5 class="mb-3 text-primary"><i class="fas fa-align-justify ml-1"></i> وصف المهمة</h5>
                            <p class="text-muted p-3 bg-light rounded border">
                                {{ $contractorTask->description ?? 'لا يوجد وصف تفصيلي للمهمة.' }}
                            </p>

                            {{-- الملاحظات الإضافية --}}
                            @if($contractorTask->notes)
                                <h5 class="mb-3 mt-4 text-primary"><i class="far fa-sticky-note ml-1"></i> ملاحظات إضافية</h5>
                                <p class="text-muted">
                                    {{ $contractorTask->notes }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- العمود الجانبي (السياق والارتباطات) --}}
            <div class="col-md-4">

                {{-- بطاقة النشاط والمشروع --}}
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-sitemap ml-1"></i> السياق الهيكلي</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            {{-- النشاط --}}
                            <li class="list-group-item">
                                <small class="text-muted d-block">النشاط الرئيسي</small>
                                <a href="{{ route('dashboard.project-activities.show', $contractorTask->project_activity_id) }}" class="font-weight-bold text-info">
                                    {{ $contractorTask->projectActivity->masterActivity->name ?? '-' }}
                                </a>
                                <br>
                                <span class="badge badge-light border mt-1">{{ $contractorTask->projectActivity->activity_code ?? '' }}</span>
                            </li>
                            {{-- المشروع --}}
                            <li class="list-group-item">
                                <small class="text-muted d-block">المشروع</small>
                                <a href="{{ route('dashboard.projects.show', $contractorTask->projectActivity->project_id) }}" class="font-weight-bold text-dark">
                                    {{ $contractorTask->projectActivity->project->name ?? '-' }}
                                </a>
                            </li>
                            {{-- الموقع --}}
                            <li class="list-group-item">
                                <small class="text-muted d-block">الموقع / القرية</small>
                                <span class="text-dark">
                                    {{ $contractorTask->projectActivity->town->town_name ?? '-' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- بطاقة المقاول والعقد --}}
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-hard-hat ml-1"></i> التنفيذ والتعاقد</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <small class="text-muted d-block">المقاول المنفذ</small>
                                <strong class="text-dark">
                                    {{ $contractorTask->projectContractor->contractor->name ?? 'غير محدد' }}
                                </strong>
                            </li>
                            <li class="list-group-item">
                            <small class="text-muted d-block">رقم العقد</small>

                            {{-- التحقق من وجود العقد قبل إنشاء الرابط --}}
                            @if($contractorTask->project_contractor_id)
                                <a href="{{ route('dashboard.project-contractors.show', $contractorTask->project_contractor_id) }}" class="text-orange font-weight-bold">
                                    {{ $contractorTask->projectContractor->contract_code ?? 'غير متوفر' }}
                                </a>
                            @else
                                <span class="text-muted">غير محدد (لا يوجد عقد مرتبط)</span>
                            @endif
                        </li>
                        </ul>
                    </div>
                </div>

                {{-- بطاقة التواريخ --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="far fa-clock ml-1"></i> التواريخ</h3>
                    </div>
                    <div class="card-body">
                         <ul class="list-unstyled mb-0">
                             <li class="mb-2"><strong>تاريخ الإضافة:</strong> <span class="float-right">{{ $contractorTask->created_at->format('Y-m-d') }}</span></li>
                             <li><strong>آخر تحديث:</strong> <span class="float-right">{{ $contractorTask->updated_at->format('Y-m-d') }}</span></li>
                         </ul>
                    </div>
                 </div>

            </div>
        </div>

        {{-- قسم الأزرار --}}
        <div class="row mt-3 mb-4">
            <div class="col-12 text-center">
                @can('contractor_tasks.edit')
                    <a href="{{ route('dashboard.contractor-tasks.edit', $contractorTask->id) }}" class="btn btn-lg btn-warning">
                        <i class="fas fa-edit ml-1"></i> تعديل المهمة
                    </a>
                @endcan
                <a href="{{ route('dashboard.contractor-tasks.index') }}" class="btn btn-lg btn-secondary">
                    <i class="fas fa-arrow-left ml-1"></i> العودة للقائمة
                </a>
            </div>
        </div>
    </div>
@endsection
