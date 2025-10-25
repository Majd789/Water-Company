{{-- resources/views/dashboard/safety_profiles/index.blade.php --}}
@extends('layouts.app')
@section('title', 'قائمة ملفات السلامة')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>قائمة ملفات السلامة</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">ملفات السلامة</li>
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
                        {{-- نفس كود الفلترة من المتحكم السابق --}}
                        <form method="GET" action="{{ route('dashboard.safety-profiles.index') }}">
                            <div class="row align-items-end">
                                <div class="col-md-5"><label>فلترة حسب الوحدة:</label><select name="unit_id" class="form-control select2"><option value="">عرض الكل</option>@foreach ($units as $unit)<option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>@endforeach</select></div>
                                <div class="col-md-5"><label>بحث باسم المحطة:</label><input type="text" name="search" class="form-control" placeholder="اكتب اسم المحطة..." value="{{ request('search') }}"></div>
                                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">تطبيق</button></div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- قسم جدول البيانات --}}
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-shield-alt mr-1"></i> عرض ملفات السلامة <span class="badge badge-primary ml-2">{{ $stations->total() }}</span></h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم المحطة</th>
                                        <th class="text-center">معدات وقاية (PPE)</th>
                                        <th class="text-center">طفايات حريق</th>
                                        <th class="text-center">خطة إخلاء</th>
                                        <th class="text-center">إسعافات أولية</th>
                                        <th class="text-center no-export">خيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stations as $station)
                                        <tr>
                                            <td>{{ $loop->iteration + ($stations->currentPage() - 1) * $stations->perPage() }}</td>
                                            <td>
                                             <a href="{{ route('dashboard.stations.show', $station->id) }}">{{ $station->station_name }}</a>
                                            </td>
                                            <td class="text-center">@if($station->safetyProfile && $station->safetyProfile->has_ppe)<i class="fas fa-check-circle text-success"></i>@else<i class="fas fa-times-circle text-danger"></i>@endif</td>
                                            <td class="text-center">@if($station->safetyProfile && $station->safetyProfile->has_fire_extinguishers)<i class="fas fa-check-circle text-success"></i>@else<i class="fas fa-times-circle text-danger"></i>@endif</td>
                                            <td class="text-center">@if($station->safetyProfile && $station->safetyProfile->has_evacuation_plan)<i class="fas fa-check-circle text-success"></i>@else<i class="fas fa-times-circle text-danger"></i>@endif</td>
                                            <td class="text-center">@if($station->safetyProfile && $station->safetyProfile->has_first_aid_kit)<i class="fas fa-check-circle text-success"></i>@else<i class="fas fa-times-circle text-danger"></i>@endif</td>
                                          <td class="text-center">
                                            <div class="btn-group">
                                                {{-- زر العرض الجديد --}}
                                                <a href="{{ route('dashboard.safety-profiles.show', $station->id) }}" class="btn btn-sm btn-outline-info" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                {{-- زر التعديل --}}
                                                <a href="{{ route('dashboard.safety-profiles.edit', $station->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل ملف السلامة">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- زر الحذف --}}
                                                @if($station->safetyProfile)
                                                <form action="{{ route('dashboard.safety-profiles.destroy', $station->safetyProfile->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف الملف"><i class="fas fa-trash"></i></button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="text-center">لا توجد محطات لعرضها.</td></tr>
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
