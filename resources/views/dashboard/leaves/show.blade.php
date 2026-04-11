@extends('layouts.app')

@section('title', 'تفاصيل الإجازة')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>بيانات الإجازة #{{ $leave->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.leaves.index') }}">سجل الإجازات</a></li>
                        <li class="breadcrumb-item active">عرض التفاصيل</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- العمود الأيمن: معلومات الموظف --}}
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset('dist/img/avatar5.png') }}"
                                     alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center">{{ $leave->employee->full_name }}</h3>
                            <p class="text-muted text-center">{{ $leave->employee->unit->name ?? 'بدون وحدة' }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>الرصيد المتبقي حالياً</b> <a class="float-left badge badge-success text-sm">{{ $leave->employee->remaining_days }} يوم</a>
                                </li>
                                <li class="list-group-item">
                                    <b>كود الموظف</b> <a class="float-left text-primary">{{ $leave->employee->employee_code ?? 'N/A' }}</a>
                                </li>
                            </ul>
                            <a href="{{ route('dashboard.leaves.index') }}" class="btn btn-primary btn-block"><b>عودة للسجل</b></a>
                        </div>
                    </div>
                </div>

                {{-- العمود الأيسر: تفاصيل الإجازة --}}
                <div class="col-md-8">
                    <div class="card card-primary card-outline">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">بيانات الطلب</a></li>
                                <li class="nav-item"><a class="nav-link" href="#attachment" data-toggle="tab">المرفقات</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="details">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <strong><i class="fas fa-calendar-alt mr-1"></i> نوع الإجازة</strong>
                                            <p class="text-muted">{{ $leave->type->type_name }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <strong><i class="fas fa-hourglass-half mr-1"></i> مدة الإجازة</strong>
                                            <p class="text-muted">{{ $leave->duration }} يوم</p>
                                        </div>
                                        <div class="col-sm-12"><hr></div>
                                        <div class="col-sm-6">
                                            <strong><i class="far fa-calendar-check mr-1"></i> تاريخ البدء</strong>
                                            <p class="text-muted">{{ $leave->start_date }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <strong><i class="far fa-calendar-times mr-1"></i> تاريخ الانتهاء</strong>
                                            <p class="text-muted">{{ $leave->end_date }}</p>
                                        </div>
                                        <div class="col-sm-12"><hr></div>
                                        <div class="col-sm-12">
                                            <strong><i class="fas fa-info-circle mr-1"></i> السبب / ملاحظات</strong>
                                            <p class="text-muted bg-light p-3 rounded border">
                                                {{ $leave->reason ?? 'لا توجد ملاحظات مسجلة لهذه الإجازة.' }}
                                            </p>
                                        </div>
                                        <div class="col-sm-12 mt-4">
                                            <small class="text-muted">
                                                <i class="fas fa-user-edit"></i> سجلت بواسطة: {{ $leave->creator->name ?? 'غير معروف' }}
                                                | بتاريخ: {{ $leave->created_at->format('Y-m-d H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="attachment">
                                    @if($leave->attachment_path)
                                        <div class="text-center py-4">
                                            <i class="far fa-file-pdf fa-4x text-danger mb-3"></i>
                                            <h5>مستند مرفق متوفر</h5>
                                            <a href="{{ asset('storage/' . $leave->attachment_path) }}" target="_blank" class="btn btn-info mt-3">
                                                <i class="fas fa-download"></i> تحميل أو عرض المرفق
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-paperclip fa-3x text-gray-300 mb-3"></i>
                                            <p class="text-muted">لا يوجد ملف مرفق لهذا الطلب.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-left">
                            <a href="{{ route('dashboard.leaves.edit', $leave->id) }}" class="btn btn-warning text-white">
                                <i class="fas fa-edit"></i> تعديل البيانات
                            </a>
                            <form action="{{ route('dashboard.leaves.destroy', $leave->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الإجازة وإعادة الرصيد للموظف؟')">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
