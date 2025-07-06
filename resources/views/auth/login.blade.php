<x-guest-layout>
    {{-- عرض رسائل الحالة، مثل رابط إعادة تعيين كلمة المرور --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="pt-2 pb-2">
        <h5 class="card-title text-center pb-0 fs-4">أهلاً بك مجدداً</h5>
        <p class="text-center small">سجّل الدخول للمتابعة إلى حسابك</p>
    </div>

    {{-- تم الإبقاء على كل خصائص Laravel كما هي --}}
    <form method="POST" action="{{ route('login') }}" class="row g-2 needs-validation" novalidate>
        @csrf

        {{-- حقل البريد الإلكتروني --}}
        <div class="col-12">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <div class="input-group has-validation">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    id="email" value="{{ old('email') }}" required autofocus autocomplete="username">

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">الرجاء إدخال البريد الإلكتروني.</div>
                @enderror
            </div>
        </div>

        {{-- حقل كلمة المرور مع أيقونة العين --}}
        <div class="col-12">
            <label for="password" class="form-label">كلمة المرور</label>
            <div class="input-group has-validation">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    id="password" required autocomplete="current-password">
                {{-- أيقونة إظهار/إخفاء كلمة المرور --}}
                <span id="togglePassword" class="password-toggle-icon">
                    <i class="bi bi-eye-slash-fill" id="eye-icon"></i>
                </span>

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">الرجاء إدخال كلمة المرور!</div>
                @enderror
            </div>
        </div>

        {{-- حقل تذكرني --}}
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label" for="remember_me">تذكرني</label>
            </div>
        </div>

        {{-- زر تسجيل الدخول --}}
        <div class="col-12 mt-4">
            <button class="btn btn-primary w-100" type="submit">تسجيل الدخول</button>
        </div>

        {{-- رابط تسجيل حساب جديد --}}
        @if (Route::has('register'))
            <div class="col-12 text-center" style="margin-top: 1rem">
                <p class="small mb-0">ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a></p>
            </div>
        @endif
    </form>
</x-guest-layout>

{{-- أضف هذا الكود في نهاية ملف Blade أو في ملف JS مخصص --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        togglePassword.addEventListener('click', function() {
            // تحقق من نوع حقل الإدخال
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // تغيير شكل الأيقونة
            if (type === 'text') {
                // إذا كانت كلمة المرور ظاهرة، اعرض أيقونة العين المفتوحة
                eyeIcon.classList.remove('bi-eye-slash-fill');
                eyeIcon.classList.add('bi-eye-fill');
            } else {
                // إذا كانت كلمة المرور مخفية، اعرض أيقونة العين المغلقة
                eyeIcon.classList.remove('bi-eye-fill');
                eyeIcon.classList.add('bi-eye-slash-fill');
            }
        });
    });
</script>
