@extends('layouts.app')

@section('content')
    <div class="container" dir="rtl">
        <div class="row">
            <div class="col-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>إضافة مستخدم جديد</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.users.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">الاسم الكامل</label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">كلمة المرور</label>
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">الدور (الصلاحية)</label>
                                    <select name="role" id="role"
                                        class="form-select @error('role') is-invalid @enderror">
                                        <option value="">اختر دوراً</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}"
                                                {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">الحالة</label>
                                    <select name="status" id="status"
                                        class="form-select @error('status') is-invalid @enderror">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->value }}"
                                                {{ old('status', 'active') == $status->value ? 'selected' : '' }}>
                                                {{ $status->getLabel() }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">حفظ المستخدم</button>
                            <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary mt-3">إلغاء</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
