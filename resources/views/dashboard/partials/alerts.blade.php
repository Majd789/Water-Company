{{-- resources/views/dashboard/partials/alerts.blade.php --}}

{{-- 1. رسالة النجاح (Success) --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h5><i class="icon fas fa-check-circle mr-2"></i> نجاح!</h5>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- 2. رسالة الخطأ (Error/Danger) --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5><i class="icon fas fa-ban mr-2"></i> خطأ!</h5>
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- 3. رسالة التحذير (Warning) --}}
@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5><i class="icon fas fa-exclamation-triangle mr-2"></i> تحذير!</h5>
        {{ session('warning') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- 4. رسالة المعلومات (Info) --}}
@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <h5><i class="icon fas fa-info-circle mr-2"></i> معلومة!</h5>
        {{ session('info') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- 5. عرض أخطاء التحقق من صحة النموذج (Validation Errors) --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5><i class="icon fas fa-ban mr-2"></i> توجد أخطاء في الإدخال!</h5>
        <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
