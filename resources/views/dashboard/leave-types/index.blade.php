@extends('layouts.app')

@section('title', 'أنواع الإجازات')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>إدارة أنواع الإجازات</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('dashboard.leave-types.create') }}" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus"></i> إضافة نوع جديد
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">قائمة أنواع الإجازات المعرفة</h3>
                <div class="card-tools">
                    <form action="{{ route('dashboard.leave-types.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="بحث عن نوع..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10%">#</th>
                            <th>مسمى نوع الإجازة</th>
                            <th class="text-center">تأثير الرصيد</th>
                            <th class="text-center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveTypes as $type)
                        <tr>
                            <td>{{ $type->id }}</td>
                            <td><strong>{{ $type->type_name }}</strong></td>
                            <td class="text-center">
                                @if($type->affects_balance)
                                    <span class="badge badge-warning"><i class="fas fa-minus-circle"></i> تخصم من الرصيد</span>
                                @else
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> لا تؤثر على الرصيد</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('dashboard.leave-types.edit', $type->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dashboard.leave-types.destroy', $type->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟ سيتم التحقق من عدم وجود إجازات مرتبطة أولاً.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">لا توجد أنواع إجازات معرفة حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $leaveTypes->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
