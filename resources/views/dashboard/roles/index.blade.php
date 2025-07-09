@extends('layouts.app')

@section('content')
    <div class="container" dir="rtl">
        <div class="row">
            <div class="col-12">
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>إدارة الأدوار والصلاحيات</h5>
                        @can('roles.create')
                            <a href="{{ route('dashboard.roles.create') }}" class="btn btn-primary">إضافة دور جديد</a>
                        @endcan
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>اسم الدور</th>
                                    <th>الصلاحيات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                    <tr>
                                        <td>{{ $role->display_name ?? $role->name }}</td>
                                        <td>
                                            @foreach ($role->permissions->take(5) as $permission)
                                                <span class="badge bg-success">{{ $permission->display_name }}</span>
                                            @endforeach
                                            @if ($role->permissions->count() > 5)
                                                <span class="badge bg-secondary">... و {{ $role->permissions->count() - 5 }}
                                                    أخرى</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('roles.edit')
                                                <a href="{{ route('dashboard.roles.edit', $role->id) }}"
                                                    class="btn btn-sm btn-warning">تعديل</a>
                                            @endcan

                                            @can('roles.delete')
                                                <form action="{{ route('dashboard.roles.destroy', $role->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('هل أنت متأكد؟ سيؤثر حذف الدور على المستخدمين المرتبطين به.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">لا توجد أدوار لعرضها.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
