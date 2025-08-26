@extends('layouts.app')

@section('title', 'قائمة الشكاوى')

@push('styles')
    {{-- (انسخ نفس محتوى قسم @push('styles') من صفحة مهام الصيانة) --}}
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>قائمة الشكاوى</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active">الشكاوى</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {{-- يمكنك إضافة فلترة هنا لاحقاً حسب البلدة أو نوع الشكوى --}}

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bullhorn ml-1"></i>
                                عرض الشكاوى <span class="badge badge-primary ml-2">{{ $complaints->count() }}</span>
                            </h3>
                            <div class="card-tools d-flex align-items-center">
                                <a href="{{ route('dashboard.complaints.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus ml-1"></i> إضافة شكوى جديدة
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

                            <div class="table-responsive">
                                <table id="complaintsTable" class="table table-bordered table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المشتكي</th>
                                            <th>البلدة</th>
                                            <th>نوع الشكوى</th>
                                            <th>تاريخ التسجيل</th>
                                            <th>الحالة</th>
                                            <th class="text-center no-export">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($complaints as $complaint)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $complaint->complainant_name }}</td>
                                                <td>{{ $complaint->town->town_name ?? 'N/A' }}</td>
                                                <td>{{ $complaint->complaintType->name ?? 'N/A' }}</td>
                                                <td>{{ $complaint->created_at->format('Y-m-d') }}</td>
                                                <td class="text-center">
                                                    {{-- تنسيق الحالة بألوان مختلفة --}}
                                                    @if ($complaint->status == 'new')
                                                        <span class="badge badge-primary">جديدة</span>
                                                    @elseif($complaint->status == 'in_progress')
                                                        <span class="badge badge-warning">قيد المعالجة</span>
                                                    @elseif($complaint->status == 'resolved')
                                                        <span class="badge badge-success">تم الحل</span>
                                                    @else
                                                        <span class="badge badge-secondary">مغلقة</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('dashboard.complaints.show', $complaint->id) }}"
                                                            class="btn btn-sm btn-outline-info" title="عرض"><i
                                                                class="fas fa-eye"></i></a>
                                                        <a href="{{ route('dashboard.complaints.edit', $complaint->id) }}"
                                                            class="btn btn-sm btn-outline-warning" title="تعديل"><i
                                                                class="fas fa-edit"></i></a>
                                                        <form
                                                            action="{{ route('dashboard.complaints.destroy', $complaint->id) }}"
                                                            method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="حذف"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">لا توجد شكاوى لعرضها.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- (انسخ نفس محتوى قسم @push('scripts') من صفحة مهام الصيانة لتفعيل DataTables و SweetAlert2) --}}
@endpush
