{{-- resources/views/dashboard/assessments/index.blade.php --}}
@extends('layouts.app')
@section('title', 'قائمة التقييمات النوعية')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>قائمة التقييمات النوعية</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">التقييمات</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('dashboard.partials.alerts') {{-- <-- إضافة مهمة --}}
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-tasks mr-1"></i> عرض جميع التقييمات <span class="badge badge-primary ml-2">{{ $assessments->total() }}</span></h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>مفتاح التقييم (Key)</th>
                                        <th>القيمة</th>
                                        <th>العنصر التابع له</th>
                                        <th>تاريخ الإضافة</th>
                                        <th class="text-center no-export">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assessments as $assessment)
                                        <tr>
                                            <td>{{ $assessment->id }}</td>
                                            <td><code>{{ $assessment->assessment_key }}</code></td>
                                            <td><span class="badge badge-info">{{ $assessment->value }}</span></td>
                                            <td>
                                                @if($assessment->assessmentable)
                                                    {{-- استخدام دالة __(...) للترجمة المستقبلية --}}
                                                    {{ __(class_basename($assessment->assessmentable_type)) }}:
                                                    {{-- افتراض وجود رابط لعرض العنصر الأب --}}
                                                    <a href="#">
                                                        {{ $assessment->assessmentable->name ?? $assessment->assessmentable->id }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">عنصر محذوف</span>
                                                @endif
                                            </td>
                                            <td>{{ $assessment->created_at->format('Y-m-d') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('dashboard.assessments.edit', $assessment->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('dashboard.assessments.destroy', $assessment->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center">لا توجد تقييمات مسجلة حالياً.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $assessments->withQueryString()->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
