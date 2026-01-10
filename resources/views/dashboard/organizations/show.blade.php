@extends('layouts.app')
@section('title', 'تفاصيل المنظمة: ' . $organization->name)

@push('styles')
    <style>
        /* تنسيق الهيدر الموحد */
        .widget-user .widget-user-header {
            height: 220px;
            /* صورة خلفية تعبر عن المؤسسات أو الشركات */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('assets/img/org-header.jpg') }}');
            background-size: cover;
            background-position: center center;
            background-color: #6f42c1; /* لون بنفسجي احتياطي */
        }
        .widget-user .widget-user-image > .icon-circle {
            width: 100px; height: 100px; border: 3px solid #fff;
            background-color: #6610f2; /* لون أيقونة مميز للمنظمات */
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 45px; color: #fff;
        }
        .card-footer { padding-top: 60px; }
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
            background-color: #6610f2;
        }
        .nav-pills .nav-link:not(.active):hover {
            color: #6610f2;
        }
        .text-purple { color: #6610f2; }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">ملف المنظمة: <span class="text-purple">{{ $organization->code }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.organizations.index') }}">المنظمات</a></li>
                    <li class="breadcrumb-item active">{{ $organization->name }}</li>
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
                        <h3 class="widget-user-username display-4" style="font-weight: bold;">{{ $organization->name }}</h3>
                        <h5 class="widget-user-desc">الكود: {{ $organization->code }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <div class="icon-circle img-circle elevation-2"><i class="fas fa-sitemap"></i></div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $organization->projects->count() }}</h5>
                                    <span class="description-text">عدد المشاريع</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    {{-- حساب إجمالي تكلفة المشاريع التابعة للمنظمة --}}
                                    <h5 class="description-header text-success">
                                        ${{ number_format($organization->projects->sum('total_value') ?? 0, 2) }}
                                    </h5>
                                    <span class="description-text">إجمالي التمويل/الميزانية</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">
                                        {{ $organization->projects->pluck('activities')->flatten()->count() }}
                                    </h5>
                                    <span class="description-text">إجمالي الأنشطة</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. التبويبات (مشاريع - أنشطة - مقاولين) --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tabs-projects-tab" data-toggle="pill" href="#tabs-projects" role="tab" aria-controls="tabs-projects" aria-selected="true">
                                    <i class="fas fa-project-diagram mr-1"></i> المشاريع التابعة
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabs-activities-tab" data-toggle="pill" href="#tabs-activities" role="tab" aria-controls="tabs-activities" aria-selected="false">
                                    <i class="fas fa-tasks mr-1"></i> كافة الأنشطة
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabs-contractors-tab" data-toggle="pill" href="#tabs-contractors" role="tab" aria-controls="tabs-contractors" aria-selected="false">
                                    <i class="fas fa-hard-hat mr-1"></i> المقاولون المتعاقدون
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">

                            {{-- تبويب المشاريع --}}
                            <div class="tab-pane fade show active" id="tabs-projects" role="tabpanel" aria-labelledby="tabs-projects-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>كود المشروع</th>
                                                <th>اسم المشروع</th>
                                                <th>الحالة</th>
                                                <th>الميزانية</th>
                                                <th>تاريخ البدء</th>
                                                <th>عرض</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($organization->projects as $project)
                                                <tr>
                                                    <td>{{ $project->project_code }}</td>
                                                    <td>{{ $project->name }}</td>
                                                    <td><span class="badge badge-info">{{ $project->generalStatus->name ?? '-' }}</span></td>
                                                    <td>${{ number_format($project->total_value, 2) }}</td>
                                                    <td>{{ $project->start_date ?? '-' }}</td>
                                                    <td>
                                                        <a href="{{ route('dashboard.projects.show', $project->id) }}" class="btn btn-xs btn-outline-primary">
                                                            <i class="fas fa-eye"></i> التفاصيل
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">لا توجد مشاريع مسجلة لهذه المنظمة.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- تبويب الأنشطة --}}
                            <div class="tab-pane fade" id="tabs-activities" role="tabpanel" aria-labelledby="tabs-activities-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>كود النشاط</th>
                                                <th>النوع</th>
                                                <th>المشروع التابع له</th>
                                                <th>الموقع</th>
                                                <th>الحالة</th>
                                                <th>التكلفة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- نقوم بجمع كل الأنشطة من كل المشاريع --}}
                                            @php
                                                $allActivities = $organization->projects->pluck('activities')->flatten();
                                            @endphp

                                            @forelse($allActivities as $activity)
                                                <tr>
                                                    <td><a href="{{ route('dashboard.project-activities.show', $activity->id) }}">{{ $activity->activity_code }}</a></td>
                                                    <td>{{ $activity->masterActivity->name ?? '-' }}</td>
                                                    <td>{{ $activity->project->name ?? '-' }}</td>
                                                    <td>{{ $activity->town->town_name ?? '-' }}</td>
                                                    <td>
                                                        @if($activity->status == 'منفذ')
                                                            <span class="badge badge-success">منفذ</span>
                                                        @elseif($activity->status == 'قيد التنفيذ')
                                                            <span class="badge badge-warning">قيد التنفيذ</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ $activity->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>${{ number_format($activity->cost, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">لا توجد أنشطة مرتبطة بمشاريع هذه المنظمة.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- تبويب المقاولين --}}
                            <div class="tab-pane fade" id="tabs-contractors" role="tabpanel" aria-labelledby="tabs-contractors-tab">
                                <div class="alert alert-light border">
                                    <i class="fas fa-info-circle text-info"></i> يعرض هذا الجدول المقاولين الذين لديهم عقود ضمن مشاريع تابعة لهذه المنظمة.
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>اسم المقاول</th>
                                                <th>رقم العقد</th>
                                                <th>المشروع</th>
                                                <th>قيمة العقد</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- الوصول للمقاولين عبر عقود المشاريع --}}
                                            @php
                                                // الحصول على العقود من المشاريع
                                                $projectContracts = $organization->projects->pluck('projectContracts')->flatten();
                                            @endphp

                                            @forelse($projectContracts as $contract)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $contract->contractor->name ?? 'غير معروف' }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $contract->contractor->phone ?? '' }}</small>
                                                    </td>
                                                    <td>{{ $contract->contract_code }}</td>
                                                    <td>{{ $contract->project->name ?? '-' }}</td>
                                                    <td>${{ number_format($contract->amount ?? 0, 2) }}</td>
                                                    <td>
                                                        <a href="{{ route('dashboard.project-contractors.show', $contract->id) }}" class="btn btn-xs btn-info">
                                                            <i class="fas fa-file-contract"></i> العقد
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">لا يوجد مقاولون متعاقدون حالياً في مشاريع هذه المنظمة.</td>
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
                @can('organizations.edit')
                    <a href="{{ route('dashboard.organizations.edit', $organization->id) }}" class="btn btn-lg btn-warning">
                        <i class="fas fa-edit ml-1"></i> تعديل بيانات المنظمة
                    </a>
                @endcan
                <a href="{{ route('dashboard.organizations.index') }}" class="btn btn-lg btn-secondary">
                    <i class="fas fa-arrow-left ml-1"></i> العودة للقائمة
                </a>
            </div>
        </div>

    </div>
@endsection
