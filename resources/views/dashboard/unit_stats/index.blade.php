@extends('layouts.app')

@section('title', 'الإحصائيات الشهرية للوحدات')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">سجل الإحصائيات الشهرية</h3>
                    @can('unit_stats.create')
                    <a href="{{ route('dashboard.unit-stats.create') }}" class="btn btn-primary float-right">إضافة سجل جديد</a>
                    @endcan
                </div>

                <!-- فلترة البحث -->
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard.unit-stats.index') }}">
                        <div class="row">
                            @if(Auth::user()->unit_id === null)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>فلترة حسب الوحدة</label>
                                    <select name="unit_id" class="form-control select2bs4">
                                        <option value="">جميع الوحدات</option>
                                        @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>السنة</label>
                                    <input type="number" name="year" class="form-control" placeholder="مثال: 2024" value="{{ request('year') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                     <label>الشهر</label>
                                     <select name="month" class="form-control">
                                        <option value="">كل الشهور</option>
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">بحث</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>الوحدة</th>
                                <th>الشهر / السنة</th>
                                <th>حالة القسم التقني</th>
                                <th>حالة قسم المشتركين</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stats as $stat)
                            <tr>
                                <td>{{ $stat->unit->unit_name }}</td>
                                <td>{{ $stat->month }} / {{ $stat->year }}</td>
                                <td>
                                    @if($stat->is_technical_complete)
                                        <span class="badge badge-success">مكتمل</span>
                                    @else
                                        <span class="badge badge-warning">بانتظار الإدخال</span>
                                    @endif
                                </td>
                                <td>
                                    @if($stat->is_subscribers_complete)
                                        <span class="badge badge-success">مكتمل</span>
                                    @else
                                        <span class="badge badge-warning">بانتظار الإدخال</span>
                                    @endif
                                </td>
                                <td>
                                    @can('unit_stats.view')
                                    <a href="{{ route('dashboard.unit-stats.show', $stat->id) }}" class="btn btn-sm btn-info" title="عرض التفاصيل"><i class="fas fa-eye"></i></a>
                                    @endcan

                                    @can('unit_stats.edit_technical')
                                    <a href="{{ route('dashboard.unit-stats.edit_technical', $stat->id) }}" class="btn btn-sm btn-primary" title="تعبئة/تعديل البيانات التقنية"><i class="fas fa-cogs"></i></a>
                                    @endcan

                                    @can('unit_stats.edit_subscribers')
                                    <a href="{{ route('dashboard.unit-stats.edit_subscribers', $stat->id) }}" class="btn btn-sm btn-success" title="تعبئة/تعديل بيانات المشتركين"><i class="fas fa-users"></i></a>
                                    @endcan

                                    @can('unit_stats.delete')
                                    <form action="{{ route('dashboard.unit-stats.destroy', $stat->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من رغبتك في الحذف؟')" title="حذف السجل"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">لا توجد سجلات لعرضها.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $stats->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
