@extends('layouts.app')

@section('content')
    <div class="container" dir="rtl">
        <div class="row">
            <div class="col-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>إضافة دور جديد</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.roles.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">اسم الدور (باللغة الإنجليزية، بدون مسافات)</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="display_name" class="form-label">الاسم المعروض (للعرض في الواجهة)</label>
                                <input type="text" name="display_name" id="display_name"
                                    class="form-control @error('display_name') is-invalid @enderror"
                                    value="{{ old('display_name') }}">
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>
                            <h5>الصلاحيات</h5>
                            @error('permissions')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="row">
                                @foreach ($permissions as $group => $groupPermissions)
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <strong>{{ ucfirst($group) }}</strong>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($groupPermissions as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                                            value="{{ $permission->id }}" id="perm-{{ $permission->id }}">
                                                        <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                            {{ $permission->display_name ?? $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">حفظ الدور</button>
                            <a href="{{ route('dashboard.roles.index') }}" class="btn btn-secondary mt-3">إلغاء</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
