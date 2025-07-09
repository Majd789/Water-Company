@extends('layouts.app')

@section('title', 'تعديل التقرير اليومي: ' . $dailyStationReport->report_date->format('Y-m-d'))

@push('main-class', 'main-content-form-page')

@section('content')
    {{-- رسائل النجاح والخطأ --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-header">
        <h1>@yield('title')</h1>
    </div>

    <div class="form-container">
        <form action="{{ route('dashboard.daily-station-reports.update', $dailyStationReport) }}" method="POST">
            @method('PUT')
            {{-- ملف _form.blade.php سيوضع هنا --}}
            @include('daily_station_reports._form', [
                'dailyStationReport' => $dailyStationReport,
                'stations' => $stations,
                'operators' => $operators,
                'units' => $units,
                'towns' => $towns,
                'pumpingSectors' => $pumpingSectors,
            ])

            <div class="form-actions mt-3">
                <button type="submit" class="btn btn-save">
                    <span class="material-icons-sharp">save</span> تحديث التقرير
                </button>
                <a href="{{ route('dashboard.daily-station-reports.index') }}" class="btn btn-cancel">
                    <span class="material-icons-sharp">cancel</span> إلغاء
                </a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    {{-- استخدم نفس ملف التنسيق للنماذج أو أضف تنسيقات خاصة --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/forms.css') }}"> --}}
    <style>
        /* يمكنك إعادة استخدام نفس التنسيقات من create.blade.php أو وضعها في ملف CSS مشترك */
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="time"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group .form-check-input {
            width: auto;
            margin-left: 10px;
        }

        .form-actions button,
        .form-actions a.btn {
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin-left: 10px;
            border: none;
            cursor: pointer;
        }

        .form-actions .material-icons-sharp {
            margin-right: 5px;
        }

        .btn-save {
            background-color: #28a745;
            color: white;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }

        .text-danger {
            color: #dc3545;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-md-4,
        .col-md-6,
        .col-md-8,
        .col-md-3 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        @media (min-width: 768px) {
            .col-md-3 {
                flex: 0 0 25%;
                max-width: 25%;
            }

            .col-md-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }

            .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .col-md-8 {
                flex: 0 0 66.666667%;
                max-width: 66.666667%;
            }
        }

        .form-container h5 {
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
    </style>
@endpush

@push('scripts')
    {{-- أي سكربتات خاصة بالصفحة --}}
@endpush
