@extends('layouts.app')

@section('title', 'التقارير اليومية للمحطات')

@push('main-class', 'main-content-class-for-reports') {{-- يمكنك إضافة كلاس خاص هنا إذا أردت --}}

@section('content')
    {{-- رسائل النجاح والخطأ --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert"> {{-- افترض وجود تنسيق .alert-success --}}
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert"> {{-- افترض وجود تنسيق .alert-danger --}}
            {{ session('error') }}
        </div>
    @endif

    <div class="page-header"> {{-- افترض وجود تنسيق .page-header --}}
        <h1>@yield('title')</h1>
        <a href="{{ route('daily-station-reports.create') }}" class="btn btn-primary"> {{-- افترض وجود تنسيق .btn .btn-primary --}}
            <span class="material-icons-sharp">add_circle_outline</span>
            إضافة تقرير جديد
        </a>
    </div>

    <div class="card-container"> {{-- افترض وجود تنسيق .card-container أو .card --}}
        @if ($reports->isEmpty())
            <div class="alert alert-info"> {{-- افترض وجود تنسيق .alert-info --}}
                لا توجد تقارير لعرضها حالياً.
            </div>
        @else
            <div class="table-responsive"> {{-- افترض وجود تنسيق .table-responsive --}}
                <table class="table styled-table"> {{-- افترض وجود تنسيق .table .styled-table --}}
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>تاريخ التقرير</th>
                            <th>المحطة</th>
                            <th>المشغل المناوب</th>
                            <th>الوضع التشغيلي</th>
                            <th>كمية المياه (م³)</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td>{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
                                <td>{{ $report->report_date->format('Y-m-d') }}</td>
                                <td>{{ $report->station->station_name ?? 'غير محدد' }}</td>
                                <td>{{ $report->operator->name ?? 'غير محدد' }}</td>
                                <td>
                                    {{-- يمكنك استخدام ألوان أو شارات هنا بناءً على CSS الخاص بك --}}
                                    {{ $report->daily_operational_status }}
                                </td>
                                <td>{{ $report->water_pumped_to_network_m3 ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('daily-station-reports.show', $report) }}" class="btn-action btn-info"
                                        title="عرض">
                                        <span class="material-icons-sharp">visibility</span>
                                    </a>
                                    <a href="{{ route('daily-station-reports.edit', $report) }}"
                                        class="btn-action btn-warning" title="تعديل">
                                        <span class="material-icons-sharp">edit</span>
                                    </a>
                                    <form action="{{ route('daily-station-reports.destroy', $report) }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا التقرير؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-danger" title="حذف">
                                            <span class="material-icons-sharp">delete_outline</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-container mt-3"> {{-- افترض وجود تنسيق .pagination-container .mt-3 --}}
                {{ $reports->links() }} {{-- قد تحتاج لتخصيص عرض الـ pagination ليناسب تصميمك --}}
            </div>
        @endif
    </div>
@endsection

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('css/reports-table.css') }}"> --}}
    <style>
        /* يمكنك إضافة تنسيقات خاصة هنا أو في ملف CSS منفصل */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-primary {
            /* مثال بسيط */
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-primary .material-icons-sharp {
            margin-right: 5px;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
        }

        .styled-table th,
        .styled-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }

        .styled-table th {
            background-color: #f2f2f2;
        }

        .btn-action {
            padding: 5px 8px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            margin: 0 2px;
            border: none;
            cursor: pointer;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
    </style>
@endpush
