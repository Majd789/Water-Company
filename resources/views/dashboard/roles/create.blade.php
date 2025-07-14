@extends('layouts.app')

@section('title', 'إضافة دور جديد')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>إضافة دور جديد</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.roles.index') }}">الأدوار والصلاحيات</a></li>
                        <li class="breadcrumb-item active">إضافة دور</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-plus-circle mr-1"></i>
                                بيانات الدور الجديد
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.roles.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">اسم الدور (باللغة الإنجليزية، بدون مسافات)</label>
                                            <input type="text" name="name" id="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" placeholder="e.g., data_entry">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="display_name">الاسم المعروض (للعرض في الواجهة)</label>
                                            <input type="text" name="display_name" id="display_name"
                                                class="form-control @error('display_name') is-invalid @enderror"
                                                value="{{ old('display_name') }}" placeholder="e.g., مدخل بيانات">
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                {{-- أزرار تحديد الكل / إلغاء التحديد --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>الصلاحيات</h5>
                                    <div>
                                        <a href="#" id="select-all-permissions" class="btn btn-sm btn-info mr-2">تحديد
                                            كل الصلاحيات</a>
                                        <a href="#" id="deselect-all-permissions"
                                            class="btn btn-sm btn-secondary">إلغاء تحديد الكل</a>
                                    </div>
                                </div>

                                @error('permissions')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="row">
                                    @foreach ($permissions as $group => $groupPermissions)
                                        <div class="col-md-4 mb-3">
                                            <div class="card h-100">
                                                <div
                                                    class="card-header bg-light d-flex justify-content-between align-items-center">
                                                    <strong>{{ $group }}</strong>
                                                    {{-- زر تحديد الكل الخاص بالقسم --}}
                                                    <div class="form-check">
                                                        <input class="form-check-input select-all-group" type="checkbox"
                                                            id="select-all-{{ $loop->iteration }}">
                                                        <label class="form-check-label"
                                                            for="select-all-{{ $loop->iteration }}">
                                                            تحديد الكل
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    @foreach ($groupPermissions as $permission)
                                                        <div class="form-check">
                                                            {{-- إضافة كلاس 'permission-checkbox' لتسهيل التحديد --}}
                                                            <input class="form-check-input permission-checkbox"
                                                                type="checkbox" name="permissions[]"
                                                                value="{{ $permission->id }}"
                                                                id="perm-{{ $permission->id }}"
                                                                {{ is_array(old('permissions')) && in_array($permission->id, old('permissions')) ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="perm-{{ $permission->id }}">
                                                                {{ $permission->display_name ?? $permission->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> حفظ الدور
                                    </button>
                                    <a href="{{ route('dashboard.roles.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-1"></i> إلغاء
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function() {
            // 1. زر تحديد الكل / إلغاء تحديد الكل (الرئيسي)
            $('#select-all-permissions').on('click', function(e) {
                e.preventDefault();
                $('.permission-checkbox, .select-all-group').prop('checked', true);
            });

            $('#deselect-all-permissions').on('click', function(e) {
                e.preventDefault();
                $('.permission-checkbox, .select-all-group').prop('checked', false);
            });

            // 2. زر تحديد الكل الخاص بكل قسم (مجموعة)
            $('.select-all-group').on('change', function() {
                var isChecked = $(this).is(':checked');
                // البحث عن جميع مربعات الاختيار داخل نفس الكارد وتغيير حالتها
                $(this).closest('.card').find('.permission-checkbox').prop('checked', isChecked);
            });

            // 3. تحديث حالة زر "تحديد الكل" الخاص بالقسم عند تغيير أي صلاحية
            $('.permission-checkbox').on('change', function() {
                var card = $(this).closest('.card');
                var allPermissionsInGroup = card.find('.permission-checkbox');
                var checkedPermissionsInGroup = card.find('.permission-checkbox:checked');

                // إذا كان عدد الصلاحيات المحددة يساوي العدد الكلي، حدد زر "تحديد الكل" للقسم
                if (allPermissionsInGroup.length === checkedPermissionsInGroup.length) {
                    card.find('.select-all-group').prop('checked', true);
                } else {
                    card.find('.select-all-group').prop('checked', false);
                }
            });

            // تشغيل التحقق عند تحميل الصفحة للتأكد من حالة الأزرار بناءً على old()
            $('.select-all-group').each(function() {
                var card = $(this).closest('.card');
                var allPermissionsInGroup = card.find('.permission-checkbox');
                var checkedPermissionsInGroup = card.find('.permission-checkbox:checked');
                if (allPermissionsInGroup.length > 0 && allPermissionsInGroup.length ===
                    checkedPermissionsInGroup.length) {
                    $(this).prop('checked', true);
                }
            });
        });
    </script>
@endpush
