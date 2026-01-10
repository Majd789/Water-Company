@extends('layouts.app')
@section('title', 'تفاصيل العقد: ' . $projectContractor->contract_code)

@push('styles')
    <style>
        .widget-user .widget-user-header {
            height: 220px;
            /* صورة خلفية تعبر عن التوقيعات والعقود */
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('assets/img/contract-header.jpg') }}');
            background-size: cover;
            background-position: center center;
            background-color: #343a40;
        }
        .widget-user .widget-user-image > .icon-circle {
            width: 100px; height: 100px; border: 3px solid #fff;
            background-color: #fd7e14; /* لون برتقالي للعقود */
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 45px; color: #fff;
        }
        .card-footer { padding-top: 60px; }
        .description-block { margin-bottom: 1.5rem; text-align: center; padding: 0 10px; }
        .description-text { display: block; color: #6c757d; font-weight: 500; text-transform: uppercase; font-size: 0.85rem; margin-bottom: 5px; }
        .description-header { font-size: 1.1rem; font-weight: 600; color: #343a40; display: block; }
        .section-divider { border-top: 1px solid #dee2e6; margin: 2rem 0; }
        .list-group-item { border: none; padding: .75rem 0; }
        .list-group-item strong { min-width: 160px; display: inline-block; color: #555; }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تفاصيل العقد: <span class="text-orange">{{ $projectContractor->contract_code }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.project-contractors.index') }}">عقود المقاولين</a></li>
                    <li class="breadcrumb-item active">{{ $projectContractor->contract_code }}</li>
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
                            {{ $projectContractor->contractor->name ?? 'تنفيذ مباشر' }}
                        </h3>
                        <h5 class="widget-user-desc mt-2">
                            المشروع: {{ $projectContractor->project->name ?? 'غير محدد' }}
                        </h5>
                    </div>

                    {{-- الأيقونة --}}
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2"><i class="fas fa-file-signature"></i></div>
                    </div>

                    <div class="card-footer">
                        {{-- إحصائيات سريعة --}}
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <span class="description-text">قيمة العقد</span>
                                    <h5 class="description-header text-success">
                                        ${{ number_format($projectContractor->value, 2) }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <span class="description-text">مدة التنفيذ</span>
                                    <h5 class="description-header">{{ $projectContractor->duration_days }} يوم</h5>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <span class="description-text">حالة العقد</span>
                                    <h5 class="description-header">
                                        <span class="badge badge-info">{{ $projectContractor->contractorStatus->name ?? 'غير محدد' }}</span>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">

                        {{-- التفاصيل الكاملة --}}
                        <div class="card-body pt-0">
                            <h5 class="mb-3 text-primary"><i class="fas fa-info-circle mr-1"></i> المعلومات الأساسية والمالية</h5>
                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item">
                                    <strong><i class="fas fa-barcode mr-1 text-muted"></i> كود العقد:</strong>
                                    {{ $projectContractor->contract_code }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-project-diagram mr-1 text-muted"></i> المشروع:</strong>
                                    <a href="{{ route('dashboard.projects.show', $projectContractor->project_id) }}">
                                        {{ $projectContractor->project->name ?? 'N/A' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-hard-hat mr-1 text-muted"></i> المقاول:</strong>
                                    @if($projectContractor->contractor)
                                        {{ $projectContractor->contractor->name }}
                                        <span class="text-muted text-sm mr-2">({{ $projectContractor->contractor->phone ?? 'لا يوجد هاتف' }})</span>
                                    @else
                                        <span class="badge badge-secondary">تنفيذ مباشر</span>
                                    @endif
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="far fa-calendar-alt mr-1 text-muted"></i> تاريخ توقيع العقد:</strong>
                                    {{ $projectContractor->contract_date ? \Carbon\Carbon::parse($projectContractor->contract_date)->format('Y-m-d') : '-' }}
                                </li>
                            </ul>

                            <h5 class="mb-3 text-primary"><i class="far fa-clock mr-1"></i> الجدول الزمني</h5>
                            <ul class="list-group list-group-flush mb-4">
                                <li class="list-group-item">
                                    <strong><i class="fas fa-hourglass-start mr-1 text-muted"></i> تاريخ البداية المخطط:</strong>
                                    {{ $projectContractor->start_date ? \Carbon\Carbon::parse($projectContractor->start_date)->format('Y-m-d') : '-' }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-hourglass-end mr-1 text-muted"></i> تاريخ النهاية المخطط:</strong>
                                    {{ $projectContractor->end_date ? \Carbon\Carbon::parse($projectContractor->end_date)->format('Y-m-d') : '-' }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-play mr-1 text-muted"></i> البداية الفعلية:</strong>
                                    {{ $projectContractor->actual_start_date ? \Carbon\Carbon::parse($projectContractor->actual_start_date)->format('Y-m-d') : 'لم يبدأ بعد' }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-check-circle mr-1 text-muted"></i> النهاية الفعلية:</strong>
                                    {{ $projectContractor->actual_end_date ? \Carbon\Carbon::parse($projectContractor->actual_end_date)->format('Y-m-d') : '-' }}
                                </li>
                            </ul>

                            <h5 class="mb-3 text-primary"><i class="fas fa-stamp mr-1"></i> الموافقات الرسمية</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong><i class="fas fa-hashtag mr-1 text-muted"></i> رقم موافقة المنظمة:</strong>
                                    {{ $projectContractor->org_approval_number ?? 'غير متوفر' }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="far fa-calendar-check mr-1 text-muted"></i> تاريخ الموافقة:</strong>
                                    {{ $projectContractor->org_approval_date ? \Carbon\Carbon::parse($projectContractor->org_approval_date)->format('Y-m-d') : '-' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- العمود الجانبي --}}
            <div class="col-md-4">
                {{-- بطاقة المهام المسندة --}}
                <div class="card card-outline card-orange">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-tasks ml-1"></i> المهام/الأنشطة المسندة</h3>
                        <div class="card-tools">
                             <span class="badge badge-warning">{{ $projectContractor->tasks->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($projectContractor->tasks as $task)
                                <li class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 text-bold text-dark">
                                            <a href="{{ route('dashboard.project-activities.show', $task->project_activity_id) }}">
                                                {{ $task->projectActivity->masterActivity->name ?? 'نشاط غير معروف' }}
                                            </a>
                                        </h6>
                                        <small>{{ $task->created_at->format('Y-m-d') }}</small>
                                    </div>
                                    <p class="mb-1 text-sm text-muted">
                                        كود النشاط: {{ $task->projectActivity->activity_code ?? '-' }}
                                    </p>
                                    <small class="badge badge-light border">
                                        {{ $task->projectActivity->town->town_name ?? '' }}
                                    </small>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">
                                    <i class="fas fa-clipboard-check mb-2 d-block" style="font-size: 24px; opacity: 0.5;"></i>
                                    لا توجد مهام مسندة لهذا العقد حالياً.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- بطاقة بيانات إضافية (Metadata) --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-database ml-1"></i> بيانات السجل</h3>
                    </div>
                    <div class="card-body">
                         <ul class="list-unstyled mb-0">
                             <li class="mb-2"><strong><i class="fas fa-plus-circle text-success ml-1"></i> تاريخ الإنشاء:</strong> <br> {{ $projectContractor->created_at->format('Y-m-d h:i A') }}</li>
                             <li><strong><i class="fas fa-edit text-info ml-1"></i> آخر تحديث:</strong> <br> {{ $projectContractor->updated_at->format('Y-m-d h:i A') }}</li>
                         </ul>
                    </div>
                 </div>
            </div>
        </div>

        {{-- قسم الأزرار --}}
        <div class="row mt-3 mb-4">
            <div class="col-12 text-center">
                @can('project_contractors.edit')
                    <a href="{{ route('dashboard.project-contractors.edit', $projectContractor->id) }}" class="btn btn-lg btn-warning text-white">
                        <i class="fas fa-edit ml-1"></i> تعديل العقد
                    </a>
                @endcan
                <a href="{{ route('dashboard.project-contractors.index') }}" class="btn btn-lg btn-secondary">
                    <i class="fas fa-arrow-left ml-1"></i> العودة للقائمة
                </a>
            </div>
        </div>
    </div>
@endsection
