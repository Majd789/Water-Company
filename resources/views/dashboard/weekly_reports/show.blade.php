<style>
    /* Container */
    .recent-orders {
        margin: 2rem auto;
        max-width: 900px;
    }

    /* زر العودة */
    #btnb {
        background: #17a2b8;
        color: #fff !important;
        padding: .5rem 1.25rem;
        border-radius: 50px;
        font-weight: 500;
        transition: background .3s, transform .2s;
    }

    #btnb:hover {
        background: #138496;
        transform: scale(1.05);
    }

    /* البطاقات: عرض عمودي بتباعد وأناقة */
    .cards-container {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
        margin-top: 2rem;
    }

    /* كل بطاقة */
    .card-box .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transition: box-shadow .3s, transform .2s;
    }

    .card-box .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    /* ترويسة البطاقة */
    .card-header {
        font-size: 1.3rem;
        font-weight: 600;
        color: #fff;
        padding: .75rem 1.25rem;
    }

    .card-header.bg-primary {
        background: #007bff;
    }

    .card-header.bg-success {
        background: #28a745;
    }

    .card-header.bg-info {
        background: #17a2b8;
    }

    /* جسم البطاقة */
    .card-body {
        background: #fff;
        padding: 1.5rem;
    }

    /* تنسيق الجدول */
    .card-body table {
        width: 100%;
        border-collapse: collapse;
    }

    /* تجاوز تنسيقات الجداول العامة داخل البطاقات */
    .card-body table {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        table-layout: auto !important;
    }

    .card-body th,
    .card-body td {
        padding: .75rem 1rem;
        vertical-align: top;
        text-align: right;
        border-bottom: 1px solid #f1f1f1;
        word-break: break-word;
        line-height: 1.5;
    }

    .card-body th {
        width: 20%;
        color: #555;
        font-weight: 500;
    }

    .card-body td {
        color: #333;
    }

    /* الصور داخل البطاقة */
    .card-body img {
        display: block;
        max-width: 100%;
        max-height: 280px;
        object-fit: cover;
        border-radius: 8px;
        margin: 1rem 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
</style>

@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center ">
        <h1>{{ $report->sender_name }}</h1>
        <div style="text-align: center" class="text-center">
            <a id="btnb" href="{{ route('dashboard.weekly_reports.index') }}">الرجوع إلى قائمة التقارير</a>
        </div>

        <div class="cards-container">

            <!-- الكرت 1: المعلومات الأساسية -->
            <div class="card-box">
                <div class="card">
                    <div class="card-header bg-primary">
                        المعلومات الأساسية
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>التاريخ</th>
                                <td>{{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>الوحدة</th>
                                <td>{{ $report->unit->unit_name }}</td>
                            </tr>
                            <tr>
                                <th>اسم المرسل</th>
                                <td>{{ $report->sender_name }}</td>
                            </tr>
                            <tr>
                                <th>الوضع التشغيلي</th>
                                <td>{{ $report->operational_status }}</td>
                            </tr>
                            <tr>
                                <th>سبب توقف المحطة</th>
                                <td>{{ $report->stop_reason ?? 'لا يوجد' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- الكرت 2: أعمال الصيانة -->
            <div class="card-box">
                <div class="card">
                    <div class="card-header bg-success">
                        أعمال الصيانة
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>الوصف</th>
                                <td>{{ $report->maintenance_works ?? 'لا يوجد' }}</td>
                            </tr>
                            <tr>
                                <th>الجهة المنفذة</th>
                                <td>{{ $report->maintenance_entity ?? 'لا يوجد' }}</td>
                            </tr>
                            <tr>
                                <th>صورة إثباتية</th>
                                <td>
                                    @if ($report->maintenance_image)
                                        <div style="margin-bottom: 20px">
                                            <img src="{{ asset('storage/' . $report->maintenance_image) }}"
                                                alt="صورة أعمال الصيانة" class="image-preview"
                                                style="max-width: 250px; height: 250px; border: 1px solid #ccc; border-radius: 8px;">
                                        </div>
                                    @else
                                        لا يوجد
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- الكرت 3: الأعمال الإدارية والملاحظات الإضافية -->
            <div class="card-box">
                <div class="card">
                    <div class="card-header bg-info">
                        الأعمال الإدارية والملاحظات
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>أعمال إدارية</th>
                                <td>{{ $report->administrative_works ?? 'لا يوجد' }}</td>
                            </tr>
                            <tr>
                                <th>صورة الأعمال الإدارية</th>
                                <td>
                                    @if ($report->administrative_image)
                                        <div style="margin-bottom: 20px">
                                            <img src="{{ asset('storage/' . $report->administrative_image) }}"
                                                alt="صورة الأعمال الإدارية" class="image-preview"
                                                style="max-width: 100%; height: auto; border: 1px solid #ccc; border-radius: 8px;">
                                        </div>
                                    @else
                                        لا يوجد
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>ملاحظات إضافية</th>
                                <td>{{ $report->additional_notes ?? 'لا توجد' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/show.js') }}"></script>
@endsection
