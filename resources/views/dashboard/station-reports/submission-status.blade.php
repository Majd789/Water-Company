@extends('layouts.app')

@section('title', 'لوحة مراقبة حالة التقارير')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>لوحة مراقبة حالة التقارير</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.station-reports.index') }}">تقارير
                                المحطات</a></li>
                        <li class="breadcrumb-item active">حالة التقديم</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            {{-- بطاقة الفلترة --}}
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">فلترة البيانات</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.reports.submission-status') }}" method="GET" class="form-inline">

                        {{-- فلتر الوحدة (يظهر فقط إذا كان المستخدم مديرًا ولديه وحدات لعرضها) --}}
                        @if ($units->isNotEmpty())
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="unit_id" class="mr-2">الوحدة:</label>
                                <select name="unit_id" id="unit_id" class="form-control">
                                    <option value="">كل الوحدات</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ $unit->id == $selectedUnitId ? 'selected' : '' }}>
                                            {{ $unit->unit_name }} {{-- <-- تم التصحيح هنا --}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- فلتر السنة --}}
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="year" class="mr-2">السنة:</label>
                            <select name="year" id="year" class="form-control">
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- فلتر الشهر --}}
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="month" class="mr-2">الشهر:</label>
                            <select name="month" id="month" class="form-control">
                                @foreach ($months as $num => $name)
                                    <option value="{{ $num }}" {{ $num == $month ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- أزرار التحكم --}}
                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fas fa-search"></i> عرض
                        </button>
                        <a href="{{ route('dashboard.reports.submission-status') }}" class="btn btn-secondary mb-2 mx-2">
                            <i class="fas fa-sync-alt"></i> إعادة تعيين
                        </a>
                    </form>
                </div>
            </div>

            {{-- بطاقة جدول البيانات --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">حالة تقديم التقارير لشهر {{ $months[$month] }} {{ $year }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th
                                        style="min-width: 150px; background-color: #f8f9fa; position: sticky; left: 0; z-index: 1;">
                                        اسم المحطة</th>
                                    @for ($day = 1; $day <= $daysInMonth; $day++)
                                        <th style="min-width: 40px;">{{ $day }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stations as $station)
                                    <tr>
                                        <td style="background-color: #f8f9fa; position: sticky; left: 0; z-index: 1;">
                                            {{ $station->station_name }}</td>
                                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                            <td>
                                                @if (isset($reportMatrix[$station->id . '-' . $day]))
                                                    <span class="badge badge-success" style="font-size: 1.1em;">✔</span>
                                                @else
                                                    <span class="badge badge-danger" style="font-size: 1.1em;">✖</span>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $daysInMonth + 1 }}" class="text-center">
                                            لا توجد محطات لعرضها. (قد تحتاج لاختيار وحدة من الفلتر)
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        /* لضمان عمل الـ sticky header بشكل صحيح مع قالب AdminLTE */
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endpush
