@extends('layouts.app')
@section('title', 'ملف المقاول: ' . $contractor->name)

@push('styles')
    <style>
        .widget-user .widget-user-header {
            height: 220px;
            /* صورة خلفية تعبر عن القوى العاملة أو البناء */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('assets/img/contractor-header.jpg') }}');
            background-size: cover;
            background-position: center center;
            background-color: #343a40;
        }
        .widget-user .widget-user-image > .icon-circle {
            width: 100px; height: 100px; border: 3px solid #fff;
            background-color: #28a745; /* لون أخضر للمقاولين */
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 45px; color: #fff;
        }
        .card-footer { padding-top: 60px; }
        .description-block { margin-bottom: 1.5rem; text-align: center; padding: 0 10px; }
        .description-text { display: block; color: #6c757d; font-weight: 500; text-transform: uppercase; font-size: 0.85rem; margin-bottom: 5px; }
        .description-header { font-size: 1.1rem; font-weight: 600; color: #343a40; display: block; }

        /* تنسيق التبويبات */
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
            background-color: #28a745;
        }
        .nav-pills .nav-link:not(.active):hover {
            color: #28a745;
        }
        .text-green { color: #28a745; }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">ملف المقاول: <span class="text-green">{{ $contractor->code }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.contractors.index') }}">المقاولون</a></li>
                    <li class="breadcrumb-item active">{{ $contractor->name }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">

        {{-- 1. بطاقة المعلومات الرئيسية والإحصائيات --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-widget widget-user shadow">
                    <div class="widget-user-header text-white">
                        <h3 class="widget-user-username display-4" style="font-weight: bold;">{{ $contractor->name }}</h3>
                        <h5 class="widget-user-desc"><i class="fas fa-phone ml-2"></i> {{ $contractor->phone_number ?? 'رقم الهاتف غير متوفر' }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2"><i class="fas fa-user-hard-hat"></i></div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $contractor->projectContracts->count() }}</h5>
                                    <span class="description-text">عدد العقود</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    {{-- حساب إجمالي قيمة العقود لهذا المقاول --}}
                                    <h5 class="description-header text-success">
                                        ${{ number_format($contractor->projectContracts->sum('value') ?? 0, 2) }}
                                    </h5>
                                    <span class="description-text">إجمالي قيمة التعاقدات</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">
                                        {{-- عدد المشاريع الفريدة التي عمل بها --}}
                                        {{ $contractor->projectContracts->pluck('project_id')->unique()->count() }}
                                    </h5>
                                    <span class="description-text">مشاريع مشارك بها</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. التبويبات (العقود - المشاريع) --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-success card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tabs-contracts-tab" data-toggle="pill" href="#tabs-contracts" role="tab" aria-controls="tabs-contracts" aria-selected="true">
                                    <i class="fas fa-file-signature mr-1"></i> العقود المسجلة
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabs-projects-tab" data-toggle="pill" href="#tabs-projects" role="tab" aria-controls="tabs-projects" aria-selected="false">
                                    <i class="fas fa-project-diagram mr-1"></i> المشاريع المرتبطة
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">

                            {{-- تبويب العقود --}}
                            <div class="tab-pane fade show active" id="tabs-contracts" role="tabpanel" aria-labelledby="tabs-contracts-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>كود العقد</th>
                                                <th>المشروع</th>
                                                <th>القيمة</th>
                                                <th>تاريخ التوقيع</th>
                                                <th>الحالة</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($contractor->projectContracts as $contract)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('dashboard.project-contractors.show', $contract->id) }}" class="font-weight-bold">
                                                            {{ $contract->contract_code }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $contract->project->name ?? 'غير محدد' }}</td>
                                                    <td>${{ number_format($contract->value, 2) }}</td>
                                                    <td>{{ $contract->contract_date ? \Carbon\Carbon::parse($contract->contract_date)->format('Y-m-d') : '-' }}</td>
                                                    <td>
                                                        <span class="badge badge-info">{{ $contract->contractorStatus->name ?? 'غير محدد' }}</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('dashboard.project-contractors.show', $contract->id) }}" class="btn btn-xs btn-outline-primary">
                                                            <i class="fas fa-eye"></i> عرض
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">لا توجد عقود مسجلة لهذا المقاول.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- تبويب المشاريع (عرض المشاريع الفريدة المستخلصة من العقود) --}}
                            <div class="tab-pane fade" id="tabs-projects" role="tabpanel" aria-labelledby="tabs-projects-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>كود المشروع</th>
                                                <th>اسم المشروع</th>
                                                <th>حالة المشروع</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- استخلاص المشاريع الفريدة --}}
                                            @php
                                                $uniqueProjects = $contractor->projectContracts->map(function($contract) {
                                                    return $contract->project;
                                                })->unique('id');
                                            @endphp

                                            @forelse($uniqueProjects as $project)
                                                @if($project) {{-- التأكد من أن المشروع غير محذوف --}}
                                                    <tr>
                                                        <td>{{ $project->project_code }}</td>
                                                        <td>{{ $project->name }}</td>
                                                        <td>
                                                            <span class="badge badge-secondary">{{ $project->generalStatus->name ?? '-' }}</span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('dashboard.projects.show', $project->id) }}" class="btn btn-xs btn-outline-info">
                                                                <i class="fas fa-eye"></i> تفاصيل المشروع
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">لا توجد مشاريع مرتبطة.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>

        {{-- أزرار التحكم --}}
        <div class="row mt-3 mb-4">
            <div class="col-12 text-center">
                @can('contractors.edit')
                    <a href="{{ route('dashboard.contractors.edit', $contractor->id) }}" class="btn btn-lg btn-warning">
                        <i class="fas fa-edit ml-1"></i> تعديل بيانات المقاول
                    </a>
                @endcan
                <a href="{{ route('dashboard.contractors.index') }}" class="btn btn-lg btn-secondary">
                    <i class="fas fa-arrow-left ml-1"></i> العودة للقائمة
                </a>
            </div>
        </div>

    </div>
@endsection
