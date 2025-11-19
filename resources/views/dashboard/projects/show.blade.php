@extends('layouts.app')
@section('title', 'تفاصيل المشروع: ' . $project->name)

@push('styles')
    <style>
        .widget-user .widget-user-header {
            height: 220px;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('assets/img/project-header.jpg') }}'); /* Add a default header image */
            background-size: cover;
            background-position: center center;
        }
        .widget-user .widget-user-image > .icon-circle {
            width: 100px; height: 100px; border: 3px solid #fff;
            background-color: #007bff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 45px; color: #fff;
        }
        .card-footer { padding-top: 60px; }
        .description-block { margin-bottom: 1.5rem; text-align: center; padding: 0 10px; }
        .description-text { display: block; color: #6c757d; font-weight: 500; text-transform: uppercase; font-size: 0.85rem; margin-bottom: 5px; }
        .description-header { font-size: 1.1rem; font-weight: 600; color: #343a40; display: block; }
        .section-divider { border-top: 1px solid #dee2e6; margin: 2rem 0; }
        .list-group-item { border: none; padding: .75rem 0; }
        .list-group-item strong { min-width: 150px; display: inline-block; }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تفاصيل: <span class="text-primary">{{ $project->name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.index') }}">المشاريع</a></li>
                    <li class="breadcrumb-item active">{{ $project->name }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- البطاقة الرئيسية لعرض تفاصيل المشروع --}}
            <div class="col-md-8">
                <div class="card card-widget widget-user shadow-lg rounded">
                    <div class="widget-user-header">
                        <h3 class="widget-user-username display-4 mb-0" style="font-weight: bold;">{{ $project->name }}</h3>
                        <h5 class="widget-user-desc mt-2">{{ $project->project_code }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2"><i class="fas fa-project-diagram"></i></div>
                    </div>
                    <div class="card-footer">
                        {{-- الملخص الرئيسي --}}
                        <div class="row">
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">المنظمة الداعمة</span>
                                    <h5 class="description-header">{{ $project->organization->name ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block">
                                    <span class="description-text">الحالة العامة</span>
                                    <h5 class="description-header"><span class="badge badge-lg badge-success">{{ $project->generalStatus->name ?? 'N/A' }}</span></h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="description-block">
                                    <span class="description-text">القيمة الإجمالية</span>
                                    <h5 class="description-header">${{ number_format($project->total_value ?? 0, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                        <hr class="section-divider">

                        {{-- تفاصيل المشروع في قائمة --}}
                        <div class="card-body pt-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>نوع المشروع:</strong> {{ $project->projectType->name ?? 'N/A' }}</li>
                                <li class="list-group-item"><strong>الحالة الرئيسية:</strong> {{ $project->mainStatus->name ?? 'N/A' }}</li>
                                <li class="list-group-item"><strong>محضر التسليم:</strong> {{ $project->handoverStatus->name ?? 'N/A' }}</li>
                                <li class="list-group-item"><strong>الجهة المانحة:</strong> {{ $project->donor_name ?? 'غير محدد' }}</li>
                                <li class="list-group-item"><strong>اسم المشرف:</strong> {{ $project->supervisor_name ?? 'غير محدد' }}</li>
                                <li class="list-group-item"><strong>رقم المشرف:</strong> {{ $project->supervisor_phone ?? 'غير محدد' }}</li>
                                <li class="list-group-item"><strong>تاريخ البدء:</strong> {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : 'N/A' }}</li>
                                <li class="list-group-item"><strong>تاريخ النهاية:</strong> {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : 'N/A' }}</li>
                                <li class="list-group-item"><strong>ملاحظات:</strong> <p class="mt-2">{{ $project->notes ?? 'لا يوجد' }}</p></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- البطاقات الجانبية للأنشطة والمقاولين --}}
            <div class="col-md-4">
                {{-- بطاقة المقاولين --}}
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-hard-hat ml-1"></i> المقاولون</h3>
                        <div class="card-tools">
                             <span class="badge badge-success">{{ $project->projectContracts->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($project->projectContracts as $contract)
                                <li class="list-group-item">
                                    <a href="{{ route('dashboard.project-contractors.show', $contract->id) }}">
                                        {{ $contract->contractor->name ?? 'N/A' }}
                                    </a>
                                    <span class="float-right text-muted">{{ $contract->contract_code }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">لا يوجد مقاولون مرتبطون.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- بطاقة الأنشطة --}}
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-tasks ml-1"></i> الأنشطة</h3>
                         <div class="card-tools">
                             <span class="badge badge-info">{{ $project->activities->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                         <ul class="list-group list-group-flush">
                            @forelse($project->activities as $activity)
                                <li class="list-group-item">
                                    <a href="{{ route('dashboard.project-activities.show', $activity->id) }}">
                                        {{ $activity->masterActivity->name ?? 'N/A' }}
                                    </a>
                                    <span class="float-right text-muted">{{ $activity->activity_code }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">لا توجد أنشطة مرتبطة.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- قسم الأزرار --}}
        <div class="row mt-3 mb-4">
            <div class="col-12 text-center">
                @can('projects.edit')
                    <a href="{{ route('dashboard.projects.edit', $project->id) }}" class="btn btn-lg btn-warning"><i class="fas fa-edit ml-1"></i> تعديل المشروع</a>
                @endcan
                <a href="{{ route('dashboard.projects.index') }}" class="btn btn-lg btn-secondary"><i class="fas fa-arrow-left ml-1"></i> العودة للقائمة</a>
            </div>
        </div>
    </div>
@endsection
