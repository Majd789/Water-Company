@extends('layouts.app')

@section('title', 'تفاصيل المشروع: ' . $project->name)

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>تفاصيل المشروع</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.projects.index') }}">المشاريع</a></li>
                        <li class="breadcrumb-item active">عرض</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- بطاقة بيانات المشروع الرئيسية -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $project->name }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>الرقم المرجعي</th>
                            <td>{{ $project->institution_ref_number }}</td>
                        </tr>
                        <tr>
                            <th>المنظمة / المانح</th>
                            <td>{{ $project->organization }} / {{ $project->donor }}</td>
                        </tr>
                        <tr>
                            <th>الكلفة الإجمالية</th>
                            <td>${{ number_format($project->total_cost, 2) }}</td>
                        </tr>
                        <tr>
                            <th>المدة وتواريخ التنفيذ</th>
                            <td>{{ $project->duration_days }} يوم (من {{ $project->start_date }} إلى
                                {{ $project->end_date }})</td>
                        </tr>
                        <tr>
                            <th>المشرف</th>
                            <td>{{ $project->supervisor_name }} ({{ $project->supervisor_contact }})</td>
                        </tr>
                        <tr>
                            <th>الحالة</th>
                            <td><span class="badge bg-success">{{ $project->status }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- بطاقة الأنشطة المرتبطة -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">الأنشطة المرتبطة بالمشروع</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>اسم النشاط</th>
                                <th>الموقع (المحطة)</th>
                                <th>القيمة</th>
                                <th>حالة التنفيذ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($project->activities as $activity)
                                <tr>
                                    <td>{{ $activity->activity_name }}</td>
                                    <td>
                                        {{ $activity->station->station_name ?? ($activity->town->town_name ?? ($activity->unit->unit_name ?? 'غير محدد')) }}
                                    </td>
                                    <td>${{ number_format($activity->value, 2) }}</td>
                                    <td>{{ $activity->execution_status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">لا توجد أنشطة مرتبطة بهذا المشروع.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.projects.edit', $project->id) }}" class="btn btn-primary">تعديل
                        المشروع</a>
                    <a href="{{ route('dashboard.projects.index') }}" class="btn btn-secondary">العودة للقائمة</a>
                </div>
            </div>
        </div>
    </section>
@endsection
