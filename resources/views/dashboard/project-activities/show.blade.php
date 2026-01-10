@extends('layouts.app')

{{-- عنوان الصفحة --}}
@section('title', 'تفاصيل النشاط: ' . ($projectActivity->masterActivity->name ?? 'غير محدد'))

@push('styles')
    <style>
        .widget-user .widget-user-header {
            height: 220px;
            /* يمكنك تغيير الصورة هنا لتكون معبرة عن الأنشطة */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('assets/img/activity-header.jpg') }}');
            background-size: cover;
            background-position: center center;
            background-color: #343a40; /* لون احتياطي في حال عدم وجود صورة */
        }
        .widget-user .widget-user-image > .icon-circle {
            width: 100px; height: 100px; border: 3px solid #fff;
            background-color: #17a2b8; /* لون مختلف عن المشاريع للتمييز */
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
        .list-group-item strong { min-width: 150px; display: inline-block; color: #555; }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تفاصيل النشاط: <span class="text-info">{{ $projectActivity->activity_code }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.project-activities.index') }}">أنشطة المشاريع</a></li>
                    <li class="breadcrumb-item active">{{ $projectActivity->activity_code }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- البطاقة الرئيسية لعرض تفاصيل النشاط --}}
            <div class="col-md-8">
                <div class="card card-widget widget-user shadow-lg rounded">
                    {{-- رأس البطاقة --}}
                    <div class="widget-user-header text-white">
                        <h3 class="widget-user-username display-4 mb-0" style="font-weight: bold;">
                            {{ $projectActivity->masterActivity->name ?? 'غير محدد' }}
                        </h3>
                        <h5 class="widget-user-desc mt-2">{{ $projectActivity->project->name ?? 'مشروع غير محدد' }}</h5>
                    </div>

                    {{-- الأيقونة الدائرية --}}
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2"><i class="fas fa-clipboard-list"></i></div>
                    </div>

                    <div class="card-footer">
                        {{-- الملخص الرئيسي (Header Summary) --}}
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <span class="description-text">الموقع / القرية</span>
                                    <h5 class="description-header">{{ $projectActivity->town->town_name ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <span class="description-text">التكلفة التقديرية</span>
                                    <h5 class="description-header text-success">${{ number_format($projectActivity->cost, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <span class="description-text">حالة النشاط</span>
                                    <h5 class="description-header">
                                        @php
                                            $badgeClass = match($projectActivity->status) {
                                                'منفذ' => 'badge-success',
                                                'قيد التنفيذ' => 'badge-warning',
                                                'ينتظر مقاول' => 'badge-danger',
                                                default => 'badge-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $projectActivity->status ?? 'غير محدد' }}</span>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">

                        {{-- التفاصيل الكاملة (Body) --}}
                        <div class="card-body pt-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong><i class="fas fa-barcode mr-1 text-muted"></i> كود النشاط:</strong>
                                    {{ $projectActivity->activity_code }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-project-diagram mr-1 text-muted"></i> المشروع التابع:</strong>
                                    <a href="{{ route('dashboard.projects.show', $projectActivity->project_id) }}">
                                        {{ $projectActivity->project->name ?? 'N/A' }}
                                    </a>
                                    ({{ $projectActivity->project->project_code ?? '' }})
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-map-marker-alt mr-1 text-muted"></i> اسم المحطة/الموقع:</strong>
                                    {{ $projectActivity->station_name ?? 'لا يوجد' }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-flag mr-1 text-muted"></i> الوحدة الإدارية:</strong>
                                    {{ $projectActivity->town->unit->unit_name ?? 'N/A' }}
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-cubes mr-1 text-muted"></i> الكمية:</strong>
                                    {{ $projectActivity->quantity ?? 0 }} {{ $projectActivity->unit_measure ?? '' }}
                                </li>
                                @if($projectActivity->unit_capacity)
                                <li class="list-group-item">
                                    <strong><i class="fas fa-tachometer-alt mr-1 text-muted"></i> السعة:</strong>
                                    {{ $projectActivity->unit_capacity }}
                                </li>
                                @endif
                                <li class="list-group-item">
                                    <strong><i class="far fa-sticky-note mr-1 text-muted"></i> ملاحظات:</strong>
                                    <p class="text-muted mt-2 mb-0" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                                        {{ $projectActivity->notes ?? 'لا توجد ملاحظات إضافية.' }}
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- العمود الجانبي (المقاولون والمهام) --}}
            <div class="col-md-4">
                {{-- بطاقة المقاولين والمهام --}}
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-hard-hat ml-1"></i> المهام والمقاولون</h3>
                        <div class="card-tools">
                             <span class="badge badge-primary">{{ $projectActivity->tasks->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($projectActivity->tasks as $task)
                                <li class="list-group-item px-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="font-weight-bold">
                                            {{ $task->projectContractor->contractor->name ?? 'مقاول غير معروف' }}
                                        </span>
                                        <small class="text-muted">{{ $task->created_at->format('Y-m-d') }}</small>
                                    </div>
                                    <p class="mb-1 text-sm text-muted">
                                        العقد: {{ $task->projectContractor->contract_code ?? '-' }}
                                    </p>
                                    <div>
                                        {{-- يمكنك إضافة حالة المهمة هنا إذا كانت متوفرة في جدول المهام --}}
                                        <span class="badge badge-light border">مهمة مسندة</span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">
                                    <i class="fas fa-info-circle mb-2 d-block" style="font-size: 24px;"></i>
                                    لا توجد مهام مسندة لمقاولين حتى الآن.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                 {{-- بطاقة معلومات إضافية سريعة (مثال: تواريخ) --}}
                 <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="far fa-clock ml-1"></i> التواريخ</h3>
                    </div>
                    <div class="card-body">
                         <ul class="list-unstyled">
                             <li class="mb-2"><strong>تاريخ الإنشاء:</strong> <span class="float-right">{{ $projectActivity->created_at->format('Y-m-d') }}</span></li>
                             <li><strong>آخر تحديث:</strong> <span class="float-right">{{ $projectActivity->updated_at->format('Y-m-d') }}</span></li>
                         </ul>
                    </div>
                 </div>
            </div>
        </div>

        {{-- قسم الأزرار --}}
        <div class="row mt-3 mb-4">
            <div class="col-12 text-center">
                @can('project_activities.edit')
                    <a href="{{ route('dashboard.project-activities.edit', $projectActivity->id) }}" class="btn btn-lg btn-warning">
                        <i class="fas fa-edit ml-1"></i> تعديل النشاط
                    </a>
                @endcan
                <a href="{{ route('dashboard.project-activities.index') }}" class="btn btn-lg btn-secondary">
                    <i class="fas fa-arrow-left ml-1"></i> العودة للقائمة
                </a>
            </div>
        </div>
    </div>
@endsection
