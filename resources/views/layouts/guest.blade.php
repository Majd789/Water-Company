<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>بوابة الدخول - مؤسسة المياه</title>
    <meta content="بوابة الدخول الرسمية لموظفي وعملاء مؤسسة المياه" name="description">
    <meta content="login, auth, laravel, bootstrap, design, water, corporation, portal" name="keywords">

    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-water-blue: #0077b6;
            /* أزرق مائي عميق */
            --secondary-water-teal: #00b4d8;
            /* تركواز مائي فاتح */
            --background-light: #f0f8ff;
            /* أزرق سماوي شاحب جداً للخلفية */
            --text-dark-blue: #03045e;
            /* كحلي داكن للنصوص الرئيسية */
            --text-light-gray: #6c757d;
            --white: #ffffff;
        }

        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            text-align: right;
            background-color: var(--background-light);
            color: var(--text-dark-blue);
            overflow-x: hidden;
            /* لمنع ظهور شريط تمرير أفقي بسبب الموجات */
        }

        .input-group {
            position: relative;
        }

        /* تنسيق أيقونة العين لتكون عائمة داخل الحقل */
        .password-toggle-icon {
            position: absolute;
            top: 50%;
            /* بما أن التصميم RTL، سنضعها في اليسار */
            left: 15px;
            transform: translateY(-50%);
            z-index: 3;
            /* للتأكد من أنها فوق الحقل */
            cursor: pointer;
            color: var(--secondary-water-teal);
            /* للحفاظ على لون الأيقونة */
        }

        /* إضافة حشوة داخلية لحقل كلمة المرور حتى لا يكتب المستخدم فوق الأيقونة */
        #password {
            padding-left: 45px !important;
            /* مساحة كافية للأيقونة */
        }

        main {
            position: relative;
            z-index: 2;
            animation: fadeIn 1.2s ease-out;
        }

        .logo img {
            max-width: 90px;
            /* تحديد عرض أقصى للصورة */
            height: auto;
            /* الحفاظ على نسبة العرض إلى الارتفاع */
            margin-bottom: 0.5rem;
            /* إضافة هامش أسفل الصورة */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            background-color: var(--white);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 118, 182, 0.15);
            border: 2px solid transparent;
            background-image: linear-gradient(var(--white), var(--white)),
                linear-gradient(45deg, var(--primary-water-blue), var(--secondary-water-teal));
            background-origin: border-box;
            background-clip: padding-box, border-box;
            transition: all 0.3s ease;
        }

        .card-body {
            padding: 40px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo .icon {
            font-size: 50px;
            color: var(--primary-water-blue);
            margin-bottom: 0.5rem;
            display: inline-block;
        }

        .logo span {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-dark-blue);
            display: block;
        }

        .card-title {
            color: var(--text-dark-blue);
            font-weight: 700;
            font-size: 1.75rem;
        }

        .form-label {
            color: var(--text-dark-blue);
            font-weight: 600;
            margin-bottom: 0.25rem;
            /* تقليل المسافة بين العنوان والحقل */
        }

        .form-control {
            background-color: #f7faff;
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: all 0.3s ease;
            padding: 12px 20px;
        }

        .form-control:focus {
            background-color: var(--white);
            border-color: var(--primary-water-blue);
            box-shadow: 0 0 0 0.25rem rgba(0, 119, 182, 0.2);
        }

        /* أيقونات داخل حقول الإدخال */
        .input-group-text {
            background-color: transparent;
            border: none;
            border-left: 1px solid #ddd;
            /* RTL Support */
            padding-left: 15px;
            color: var(--secondary-water-teal);
        }

        .input-group .form-control {
            border-right: 0;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-water-blue), var(--secondary-water-teal));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.4s ease;
            box-shadow: 0 8px 20px rgba(0, 180, 216, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 180, 216, 0.4);
        }

        .form-check .form-check-input:checked {
            background-color: var(--primary-water-blue);
            border-color: var(--primary-water-blue);
        }

        .small a {
            color: var(--primary-water-blue);
            font-weight: 700;
            text-decoration: none;
        }

        .small a:hover {
            color: var(--secondary-water-teal);
        }

        .credits a {
            color: var(--primary-water-blue);
        }

        /* --- خلفية الموجات المتحركة --- */
        .wave-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px;
            overflow: hidden;
            z-index: 1;
        }

        .waves {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 100%;
            background-size: 50% 100%;
        }

        .wave-1 {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120' preserveAspectRatio='none'%3e%3cpath d='M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z' style='fill: rgba(0,180,216,0.5)'%3e%3c/path%3e%3c/svg%3e");
            animation: wave-flow 20s linear infinite;
        }

        .wave-2 {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120' preserveAspectRatio='none'%3e%3cpath d='M985.66,92.83C906.67,72,823.78,31,741.22,14.7c-82.39-16.72-168.19-17.73-250.45-.39C408.4,29.5,344.24,53.8,279.79,81.39,211,108.38,142.25,116.82,72.58,111.45V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z' style='fill: rgba(0,119,182,0.6)'%3e%3c/path%3e%3c/svg%3e");
            animation: wave-flow 15s linear infinite reverse;
            opacity: 0.8;
        }

        @keyframes wave-flow {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }
    </style>
</head>

<body>

    <main>
        <div class="container">
            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-8 col-sm-10">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="logo-container">
                                        {{-- تغيير: استخدام أيقونة بدلاً من صورة، يمكنك استبدالها بشعارك الفعلي --}}
                                        <a href="/" class="logo">
                                            <img src="{{ asset('assets/img/logo.png') }}" alt="شعار المؤسسة">
                                        </a>
                                    </div>

                                    {{-- هنا سيتم حقن محتوى صفحة تسجيل الدخول --}}
                                    {{ $slot }}

                                </div>

                            </div>
                            <div class="credits text-center">
                                مصمم بواسطة <a href="#">الفريق التقني إدلب</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    {{-- إضافة حاوية الموجات --}}
    <div class="wave-container">
        <div class="waves wave-1"></div>
        <div class="waves wave-2"></div>
    </div>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
