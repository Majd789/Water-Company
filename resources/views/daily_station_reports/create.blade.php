@extends('layouts.app')

@section('title', 'إضافة تقرير يومي جديد')

@push('main-class', 'main-content-form-page')

@section('content')
    <div class="form-page-container"> {{-- حاوية خارجية جديدة للصفحة --}}
        <div class="form-card-container kobo-style-card"> {{-- كلاس جديد للبطاقة الرئيسية --}}
            <h2 class="form-card-title">@yield('title')</h2>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>الرجاء تصحيح الأخطاء التالية:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('daily-station-reports.store') }}" method="POST" class="main-form" novalidate>
                @include('daily_station_reports._form', [
                    // المتغيرات الخاصة بالبيانات المعبأة مسبقًا
                    'operatorName' => $operatorName,
                    'preselectedStationName' => $preselectedStationName,
                    'preselectedStationCode' => $preselectedStationCode,
                    'preselectedUnitName' => $preselectedUnitName,
                    'preselectedTownName' => $preselectedTownName,
                    'pumpingSectors' => $pumpingSectors, // قطاعات الضخ الخاصة بمحطة المستخدم
                
                    // المتغيرات العامة للقوائم (للاستخدام إذا كان النموذج يسمح بالاختيار)
                    'stations' => $stations,
                    'operators' => $operators,
                    'units' => $units,
                    'towns' => $towns,
                ])

                <div class="form-actions-footer mt-4 pt-3">
                    <button type="submit" class="btn btn-primary btn-submit">
                        <span class="material-icons-sharp align-middle">save</span>
                        حفظ التقرير
                    </button>
                    <a href="{{ route('daily-station-reports.index') }}" class="btn btn-outline-secondary btn-cancel">
                        <span class="material-icons-sharp align-middle">cancel</span>
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        :root {
            --professional-primary-color: #0056b3;
            --professional-secondary-color: #6c757d;
            --professional-light-gray: #f8f9fa;
            --professional-medium-gray: #dee2e6;
            --professional-dark-gray: #495057;
            --professional-text-color: #212529;
            --professional-label-color: #343a40;
            --professional-input-border-color: #ced4da;
            --professional-input-bg: #ffffff;
            --professional-danger-color: #dc3545;
            --professional-success-color: #198754;
            --professional-card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --professional-border-radius: 0.375rem;
            --professional-font-family: 'Nunito', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            --professional-base-font-size: 1rem;
            /* 16px */
            --professional-large-font-size: 1.15rem;
            /* Adjusted for slightly larger section titles */
            --professional-xlarge-font-size: 1.35rem;
            /* Adjusted */
            --professional-input-padding-y: 0.65rem;
            /* Slightly more padding */
            --professional-input-padding-x: 1rem;
            --professional-form-group-margin-bottom: 1.75rem;
            /* Increased spacing */
        }

        body {
            font-family: var(--professional-font-family);
            color: var(--professional-text-color);
            font-size: var(--professional-base-font-size);
            line-height: 1.65;
            /* Slightly increased line height */
        }

        .form-page-container {
            background-color: var(--professional-light-gray);
            padding: 30px 15px;
            min-height: 100vh;
        }

        .form-card-container.kobo-style-card {
            background-color: var(--professional-input-bg);
            border-radius: var(--professional-border-radius);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, .1);
            padding: 2.5rem 3rem;
            margin: 2rem auto;
            max-width: 1140px;
            width: 100%;
        }

        .form-card-title {
            text-align: center;
            font-size: 2.35rem;
            /* Larger title */
            color: var(--professional-primary-color);
            margin-bottom: 2.5rem;
            padding-bottom: 1.25rem;
            /* Increased padding */
            border-bottom: 2px solid var(--professional-primary-color);
            font-weight: 700;
        }

        .main-form .card {
            margin-bottom: 2.5rem;
            border: 1px solid var(--professional-medium-gray);
            border-radius: var(--professional-border-radius);
            box-shadow: var(--professional-card-shadow);
        }

        .main-form .card-header {
            background-color: #eef2f7;
            /* Lighter, slightly bluish header */
            border-bottom: 1px solid var(--professional-medium-gray);
            padding: 1rem 1.5rem;
        }

        .main-form .card-header h5 {
            font-size: var(--professional-large-font-size);
            font-weight: 600;
            color: var(--professional-primary-color);
            margin-bottom: 0;
        }

        .main-form .card-body {
            padding: 2rem;
        }

        .main-form .form-group {
            margin-bottom: var(--professional-form-group-margin-bottom);
        }

        .main-form .form-label {
            font-weight: 600;
            color: var(--professional-label-color);
            margin-bottom: 0.65rem;
            /* More space below label */
            font-size: 1rem;
            /* Larger label text */
            display: flex;
            /* To align asterisk properly */
            align-items: center;
        }

        .main-form .form-label .text-danger {
            font-weight: bold;
            margin-right: 0.3rem;
            /* Adjusted for RTL */
            font-size: 1.1em;
            /* Make asterisk slightly larger */
        }


        .main-form .form-control,
        .main-form .form-select {
            border-radius: var(--professional-border-radius);
            border: 1px solid var(--professional-input-border-color);
            background-color: var(--professional-input-bg);
            padding: var(--professional-input-padding-y) var(--professional-input-padding-x);
            font-size: var(--professional-base-font-size);
            color: var(--professional-text-color);
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            line-height: 1.6;
            height: calc(1.6em + (var(--professional-input-padding-y) * 2) + 2px);
            /* Adjusted height based on line-height */
        }

        .main-form textarea.form-control {
            min-height: calc(1.6em + (var(--professional-input-padding-y) * 2) + 2px) * 3;
            /* Taller textarea */
        }

        .main-form .form-control:focus,
        .main-form .form-select:focus {
            border-color: var(--professional-primary-color);
            box-shadow: 0 0 0 0.25rem rgba(var(--professional-primary-color-rgb, 0, 86, 179), 0.25);
        }

        :root {
            --professional-primary-color-rgb: 0, 86, 179;
        }


        .main-form input[readonly].form-control {
            background-color: #e9ecef;
            opacity: 1;
        }

        .main-form .form-check {
            padding-right: 0;
            /* Removed specific RTL padding, rely on input margin */
            margin-bottom: 0;
            display: flex;
            align-items: center;
            min-height: calc(1.6em + (var(--professional-input-padding-y) * 2) + 2px);
        }

        .main-form .form-check-input {
            margin-left: 0.75rem;
            /* Consistent margin for LTR/RTL if text is after */
            margin-right: 0.25rem;
            /* Small margin before for RTL */
            margin-top: 0.1em;
            width: 1.15em;
            /* Slightly larger */
            height: 1.15em;
            cursor: pointer;
            flex-shrink: 0;
            /* Prevent shrinking */
        }

        .main-form .form-check-label {
            font-size: var(--professional-base-font-size);
            /* Consistent with input text */
            font-weight: normal;
            color: var(--professional-text-color);
            cursor: pointer;
            line-height: 1.5;
            /* Ensure label text aligns well */
        }

        .invalid-feedback {
            display: block;
            font-size: 0.875em;
            color: var(--professional-danger-color);
            margin-top: 0.3rem;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: var(--professional-danger-color);
        }

        .form-control.is-invalid:focus,
        .form-select.is-invalid:focus {
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }

        .alert {
            border-radius: var(--professional-border-radius);
            font-size: 0.95rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.75rem;
            /* Increased margin */
        }

        .alert-success {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
        }


        .form-actions-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid var(--professional-medium-gray);
        }

        .form-actions-footer .btn {
            min-width: 160px;
            /* Wider buttons */
            padding: 0.85rem 1.75rem;
            font-size: var(--professional-base-font-size);
            font-weight: 600;
            border-radius: var(--professional-border-radius);
            transition: all 0.2s ease-in-out;
        }

        .form-actions-footer .btn .material-icons-sharp {
            font-size: 1.3em;
            margin-right: 0.5em;
            margin-left: 0;
            vertical-align: middle;
        }

        .form-actions-footer .btn-primary {
            background-color: var(--professional-primary-color);
            border-color: var(--professional-primary-color);
            color: white;
        }

        .form-actions-footer .btn-primary:hover {
            background-color: #00458a;
            border-color: #00458a;
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
        }

        .form-actions-footer .btn-outline-secondary {
            color: var(--professional-secondary-color);
            border-color: var(--professional-secondary-color);
        }

        .form-actions-footer .btn-outline-secondary:hover {
            background-color: var(--professional-secondary-color);
            color: white;
            border-color: var(--professional-secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        /* Responsive adjustments */
        /* Removing custom column definitions as Bootstrap's grid should handle this if loaded correctly */
        /* If Bootstrap grid isn't loaded via layouts.app, these would be needed: */
        /*
                                .row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; }
                                .col-md-3, .col-md-4, .col-md-6, .col-md-8, .col-md-9, .col-md-12 {
                                    position: relative; width: 100%; padding-right: 15px; padding-left: 15px;
                                }
                                @media (min-width: 768px) {
                                    .col-md-3 { flex: 0 0 auto; width: 25%; }
                                    .col-md-4 { flex: 0 0 auto; width: 33.33333333%; }
                                    .col-md-6 { flex: 0 0 auto; width: 50%; }
                                    .col-md-8 { flex: 0 0 auto; width: 66.66666667%; }
                                    .col-md-9 { flex: 0 0 auto; width: 75%; }
                                    .col-md-12 { flex: 0 0 auto; width: 100%; }
                                }
                                */
        /* Ensuring form groups have bottom margin for spacing when columns stack */
        .main-form .form-group.row>[class*="col-"] {
            margin-bottom: var(--professional-form-group-margin-bottom);
        }

        .main-form .form-group.row>[class*="col-"]:last-child {
            margin-bottom: 0;
            /* Remove margin from last col in a row if it's the only one left or on the same line */
        }

        /* On smaller screens, ensure all columns in a row get bottom margin if they stack */
        @media (max-width: 767.98px) {
            .main-form .form-group.row>[class*="col-"] {
                margin-bottom: var(--professional-form-group-margin-bottom);
            }

            .main-form .form-group.row>[class*="col-"]:last-child {
                margin-bottom: var(--professional-form-group-margin-bottom);
                /* Ensure last item also has margin if stacked */
            }

            .main-form .form-group:last-child .row>[class*="col-"]:last-child {
                margin-bottom: 0;
                /* No margin for the very last field group's last column */
            }
        }


        @media (max-width: 991.98px) {
            .form-card-container.kobo-style-card {
                padding: 2rem 2rem;
                max-width: 95%;
            }

            .main-form .card-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 767.98px) {
            .form-card-title {
                font-size: 2.75rem;
            }

            .form-actions-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .form-actions-footer .btn {
                width: 100%;
            }

            .form-actions-footer .btn:not(:last-child) {
                margin-bottom: 1rem;
            }

            .main-form .card-body {
                padding: 1.25rem;
            }
        }

        @media (max-width: 575.98px) {
            .form-card-container.kobo-style-card {
                padding: 1.5rem 1rem;
                margin: 1rem auto;
            }

            .main-form .card-body {
                padding: 1rem;
            }

            .main-form .card-header {
                padding: 0.75rem 1rem;
            }

            .main-form .card-header h5 {
                font-size: 1.1rem;
            }

            .form-actions-footer .btn {
                font-size: 0.95rem;
                padding: 0.7rem 1.2rem;
            }
        }
    </style>
@endpush

@push('scripts')
    {{-- أي سكربتات خاصة بالصفحة، مثل مُهيئ datepicker إذا كنت تستخدم واحدًا --}}
    {{-- السكربت الخاص بـ _form.blade.php سيتم إضافته عبر @pushOnce هناك --}}
@endpush
