@extends('layouts.app')

@section('title', 'قائمة الوحدات')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>قائمة الوحدات</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">الرئيسية</a></li>
                        <li class="breadcrumb-item active">الوحدات</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">جميع الوحدات المسجلة</h3>
                            <div class="card-tools">
                                <a href="{{ route('units.create') }}" class="btn btn-primary ml-2">
                                    <i class="fas fa-plus"></i> إضافة وحدة جديدة
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if (session('success'))
                                <div class="m-3">
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">×</button>
                                        <h5><i class="icon fas fa-check"></i> نجاح!</h5>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>اسم الوحدة</th>
                                        <th>المحافظة</th>
                                        <th>نسبة التقدم</th>
                                        <th style="width: 40px">الحالة</th>
                                        <th style="width: 150px;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($units as $unit)
                                        @php
                                            // Generate a random percentage for demonstration
                                            $progress = rand(10, 100);
                                            $badge_color = 'bg-danger'; // Default color
                                            if ($progress > 75) {
                                                $badge_color = 'bg-success';
                                            } elseif ($progress > 40) {
                                                $badge_color = 'bg-warning';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $unit->unit_name }}</td>
                                            <td>{{ $unit->governorate->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="progress progress-xs">
                                                    <div class="progress-bar {{ $badge_color }}"
                                                        style="width: {{ $progress }}%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge {{ $badge_color }}">{{ $progress }}%</span></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        الإجراءات
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('units.show', $unit->id) }}">
                                                            <i class="fas fa-eye"></i> عرض
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('units.edit', $unit->id) }}">
                                                            <i class="fas fa-edit"></i> تعديل
                                                        </a>
                                                        @if (auth()->check() && auth()->user()->role_id == 'admin')
                                                            <div class="dropdown-divider"></div>
                                                            <form action="{{ route('units.destroy', $unit->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('هل أنت متأكد من رغبتك في الحذف؟');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-trash"></i> حذف
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
