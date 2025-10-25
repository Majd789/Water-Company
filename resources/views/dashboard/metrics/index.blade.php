{{-- resources/views/dashboard/metrics/index.blade.php --}}
@extends('layouts.app')
@section('title', 'قائمة القياسات الرقمية')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>قائمة القياسات الرقمية</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">القياسات الرقمية</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> عرض جميع القياسات <span class="badge badge-primary ml-2">{{ $metrics->total() }}</span></h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>نوع المقياس (Key)</th>
                                        <th>القيمة</th>
                                        <th>العنصر التابع له</th>
                                        <th>تاريخ القياس</th>
                                        <th class="text-center no-export">خيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($metrics as $metric)
                                        <tr>
                                            <td>{{ $metric->id }}</td>
                                            <td><code>{{ $metric->metric_key }}</code></td>
                                            <td><strong>{{ number_format($metric->value, 2) }}</strong> {{ $metric->unit }}</td>
                                            <td>
                                                @if($metric->metricable)
                                                    {{ class_basename($metric->metricable_type) }}: {{ $metric->metricable->name ?? $metric->metricable->id }}
                                                @else
                                                    عنصر محذوف
                                                @endif
                                            </td>
                                            <td>{{ $metric->measured_at->format('Y-m-d') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('dashboard.metrics.edit', $metric->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('dashboard.metrics.destroy', $metric->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center">لا توجد قياسات مسجلة.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $metrics->withQueryString()->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
