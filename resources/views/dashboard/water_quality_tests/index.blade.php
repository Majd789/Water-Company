{{-- resources/views/dashboard/water_quality_tests/index.blade.php --}}
@extends('layouts.app')
@section('title', 'قائمة فحوصات جودة المياه')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>قائمة فحوصات جودة المياه</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">فحوصات جودة المياه</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">


                {{-- قسم الفلترة --}}
                <div class="card card-default collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter mr-1"></i> فلترة النتائج</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('dashboard.water-quality-tests.index') }}" id="filterForm">
                            <div class="row align-items-end">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>فلترة حسب الوحدة:</label>
                                        <select name="unit_id" class="form-control select2">
                                            <option value="">عرض جميع الوحدات</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->unit_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>بحث باسم المحطة:</label>
                                        <input type="text" name="search" class="form-control" placeholder="اكتب اسم المحطة..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary w-100">تطبيق</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- قسم جدول البيانات --}}
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-vial mr-1"></i>
                            عرض السجلات <span class="badge badge-primary ml-2">{{ $waterQualityTests->total() }}</span>
                        </h3>
                        <div class="card-tools d-flex align-items-center">
                            {{-- أزرار التصدير والاستيراد هنا إذا لزم الأمر --}}
                            <a href="{{ route('dashboard.water-quality-tests.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i> إضافة فحص
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>المحطة</th>
                                        <th>تاريخ الفحص</th>
                                        <th>العكارة (NTU)</th>
                                        <th>pH</th>
                                        <th>التحليل الجرثومي</th>
                                        <th class="text-center no-export">خيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($waterQualityTests as $test)
                                        <tr>
                                            <td>{{ $loop->iteration + ($waterQualityTests->currentPage() - 1) * $waterQualityTests->perPage() }}</td>
                                            <td>{{ $test->station->station_name ?? 'غير محددة' }}</td>
                                            <td>{{ $test->test_date->format('Y-m-d') }}</td>
                                            <td>{{ $test->turbidity ?? '-' }}</td>
                                            <td>{{ $test->ph_level ?? '-' }}</td>
                                            <td>{{ $test->microbial_analysis ?? '-' }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('dashboard.water-quality-tests.show', $test->id) }}" class="btn btn-sm btn-outline-info" title="عرض"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('dashboard.water-quality-tests.edit', $test->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('dashboard.water-quality-tests.destroy', $test->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">لا توجد سجلات مطابقة للبحث.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $waterQualityTests->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
