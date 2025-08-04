@extends('layouts.app')

@section('title', 'قائمة المشاريع')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>قائمة المشاريع</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">الرئيسية</a></li>
                        <li class="breadcrumb-item active">المشاريع</li>
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
                            <h3 class="card-title">جميع المشاريع المسجلة</h3>
                            <div class="card-tools">
                                <a href="{{ route('dashboard.projects.create') }}" class="btn btn-primary ml-2">
                                    <i class="fas fa-plus"></i> إضافة مشروع جديد
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> نجاح!</h5>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>اسم المشروع</th>
                                        <th>المنظمة</th>
                                        <th>الكلفة الإجمالية</th>
                                        <th>تاريخ البدء</th>
                                        <th>الحالة</th>
                                        <th style="width: 150px;">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($projects as $project)
                                        <tr>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $project->organization }}</td>
                                            <td>${{ number_format($project->total_cost, 2) }}</td>
                                            <td>{{ $project->start_date }}</td>
                                            <td><span class="badge bg-info">{{ $project->status }}</span></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('dashboard.projects.show', $project->id) }}">
                                                        <i class="fas fa-eye"></i> عرض
                                                    </a>
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ route('dashboard.projects.edit', $project->id) }}">
                                                        <i class="fas fa-edit"></i> تعديل
                                                    </a>
                                                    <form action="{{ route('dashboard.projects.destroy', $project->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('هل أنت متأكد من رغبتك في الحذف؟ سيتم حذف جميع الأنشطة المرتبطة.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i> حذف
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">لا توجد مشاريع لعرضها.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            {{ $projects->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
