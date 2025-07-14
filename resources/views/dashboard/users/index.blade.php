@extends('layouts.app')

@section('content')
    <div class="container" dir="rtl">
        <div class="row">
            <div class="col-12">
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>إدارة المستخدمين</h5>
                        <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary">إضافة مستخدم جديد</a>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الدور</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            {{-- Spatie HasRoles trait gives you this handy method --}}
                                            <span
                                                class="badge bg-info">{{ $user->getRoleNames()->first() ?? 'لا يوجد دور' }}</span>
                                        </td>
                                        <td>
                                            {{-- Using the Enum cast we added to the model --}}
                                            <span class="badge bg-{{ $user->status->getColor() }}">
                                                {{ $user->status->getLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('dashboard.users.edit', $user->id) }}"
                                                class="btn btn-sm btn-warning">تعديل</a>
                                            <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المستخدم؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">لا يوجد مستخدمين لعرضهم.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
