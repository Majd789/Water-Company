{{-- resources/views/dashboard/station_teams/index.blade.php --}}
@extends('layouts.app')
@section('title', 'قائمة فرق المحطات')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>قائمة فرق المحطات</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">فرق المحطات</li>
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
                        <div class="card-tools"><button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button></div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('dashboard.station-teams.index') }}">
                            <div class="row align-items-end">
                                <div class="col-md-5">
                                    <label>فلترة حسب الوحدة:</label>
                                    <select name="unit_id" class="form-control select2">
                                        <option value="">عرض جميع الوحدات</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label>بحث باسم المحطة:</label>
                                    <input type="text" name="search" class="form-control" placeholder="اكتب اسم المحطة..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">تطبيق</button></div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- قسم جدول البيانات --}}
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            عرض فرق المحطات <span class="badge badge-primary ml-2">{{ $stations->total() }}</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم المحطة</th>
                                        <th>فريق الصيانة</th>
                                        <th>فريق جودة المياه</th>
                                        <th>الفريق الإداري</th>
                                        <th class="text-center no-export">خيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stations as $station)
                                        <tr>
                                            <td>{{ $loop->iteration + ($stations->currentPage() - 1) * $stations->perPage() }}</td>
                                            <td>{{ $station->station_name }}</td>
                                            <td>{{ $station->team->maintenance_team_count ?? 'N/A' }}</td>
                                            <td>{{ $station->team->water_quality_team_count ?? 'N/A' }}</td>
                                            <td>{{ $station->team->admin_team_count ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('dashboard.station-teams.edit', $station->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل بيانات الفريق">
                                                    <i class="fas fa-edit"></i> تعديل الفريق
                                                </a>
                                                {{-- زر الحذف يظهر فقط إذا كان هناك فريق مسجل --}}
                                                @if($station->team)
                                                <form action="{{ route('dashboard.station-teams.destroy', $station->team->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف بيانات الفريق"><i class="fas fa-trash"></i></button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center">لا توجد محطات لعرضها.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $stations->withQueryString()->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
