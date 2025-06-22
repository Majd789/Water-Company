@extends('layouts.app')

@section('title', 'تفاصيل التقرير اليومي: ' . $dailyStationReport->report_date->format('Y-m-d'))

@push('main-class', 'main-content-show-page')

@section('content')
    {{-- رسائل النجاح والخطأ --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header d-flex justify-content-between align-items-center">
        <h1>@yield('title')</h1>
        <div>
            <a href="{{ route('daily-station-reports.edit', $dailyStationReport) }}" class="btn btn-edit">
                <span class="material-icons-sharp">edit</span> تعديل
            </a>
            <a href="{{ route('daily-station-reports.index') }}" class="btn btn-back">
                <span class="material-icons-sharp">arrow_back</span> العودة إلى القائمة
            </a>
        </div>
    </div>

    <div class="details-container"> {{-- افترض وجود تنسيق .details-container أو .card --}}
        {{-- معلومات التقرير الأساسية --}}
        <div class="detail-section">
            <h4>معلومات التقرير الأساسية</h4>
            <div class="detail-grid">
                <p><strong>تاريخ التقرير:</strong> {{ $dailyStationReport->report_date->format('Y-m-d') }}</p>
                <p><strong>وقت التقرير:</strong>
                    {{ $dailyStationReport->report_time ? $dailyStationReport->report_time->format('H:i:s') : '-' }}</p>
                <p><strong>المحطة:</strong> {{ $dailyStationReport->station->station_name ?? 'غير محدد' }}</p>
                <p><strong>كود المحطة (عند التسجيل):</strong> {{ $dailyStationReport->station_code_snapshot ?? '-' }}</p>
                <p><strong>المُشغل المناوب:</strong> {{ $dailyStationReport->operator->name ?? '-' }}</p>
                <p><strong>وحدة المياه:</strong> {{ $dailyStationReport->unit->unit_name ?? '-' }}</p>
                <p><strong>البلدة:</strong> {{ $dailyStationReport->town->town_name ?? '-' }}</p>
                <p><strong>قطاع الضخ المستهدف:</strong> {{ $dailyStationReport->pumpingSector->sector_name ?? '-' }}</p>
            </div>
        </div>

        {{-- الوضع التشغيلي --}}
        <div class="detail-section">
            <h4>الوضع التشغيلي والمعلومات المرتبطة</h4>
            <div class="detail-grid">
                <p><strong>الوضع التشغيلي اليومي:</strong> {{ $dailyStationReport->daily_operational_status }}</p>
                <p><strong>سبب التوقف (إن وجد):</strong> {{ $dailyStationReport->daily_stop_reason ?? '-' }}</p>
                <p><strong>الجهة المشغلة اليومية:</strong> {{ $dailyStationReport->daily_operator_entity ?? '-' }}</p>
                <p><strong>اسم الجهة المشغلة اليومية:</strong> {{ $dailyStationReport->daily_operator_entity_name ?? '-' }}
                </p>
            </div>
        </div>

        {{-- معلومات تشغيل الآبار والمضخات --}}
        <div class="detail-section">
            <h4>معلومات تشغيل الآبار والمضخات</h4>
            <div class="detail-grid">
                <p><strong>عدد الآبار المشغلة:</strong> {{ $dailyStationReport->active_wells_during_pumping_count ?? '-' }}
                </p>
                <p><strong>إجمالي ساعات ضخ المحطة:</strong> {{ $dailyStationReport->total_station_pumping_hours ?? '-' }}
                    ساعة</p>
                <p><strong>يوجد مضخة أفقية:</strong> {{ $dailyStationReport->has_horizontal_pump ? 'نعم' : 'لا' }}</p>
                @if ($dailyStationReport->has_horizontal_pump)
                    <p><strong>ساعات تشغيل المضخة الأفقية:</strong>
                        {{ $dailyStationReport->horizontal_pump_operating_hours ?? '-' }} ساعة</p>
                @endif
                {{-- يمكنك عرض ساعات تشغيل كل بئر هنا إذا كانت مُسجلة --}}
                {{--
                <p><strong>ساعات تشغيل البئر الأول:</strong> {{ $dailyStationReport->well_1_operating_hours ?? '-' }}</p>
                ... وهكذا لبقية الآبار ...
                --}}
            </div>
        </div>

        {{-- معلومات الطاقة والتشغيل والكميات --}}
        <div class="detail-section">
            <h4>معلومات الطاقة والتشغيل والكميات</h4>
            <div class="detail-grid">
                <p><strong>مصدر الطاقة التشغيلية:</strong> {{ $dailyStationReport->daily_energy_source ?? '-' }}</p>
                <p><strong>ساعات (دمج) كهرباء وطاقة شمسية:</strong>
                    {{ $dailyStationReport->hours_electric_solar_blend ?? '-' }}</p>
                <p><strong>ساعات (دمج) مولدة وطاقة شمسية:</strong>
                    {{ $dailyStationReport->hours_generator_solar_blend ?? '-' }}</p>
                <p><strong>ساعات التشغيل على الطاقة الشمسية:</strong> {{ $dailyStationReport->hours_on_solar ?? '-' }}</p>
                <p><strong>ساعات التشغيل على الكهرباء:</strong> {{ $dailyStationReport->hours_on_electricity ?? '-' }}</p>
                <p><strong>كمية الكهرباء المُستهلكة (كيلوواط/ساعة):</strong>
                    {{ $dailyStationReport->electricity_consumed_kwh ?? '-' }}</p>
                <p><strong>قراءة عداد الكهرباء (قبل):</strong>
                    {{ $dailyStationReport->electric_meter_reading_start ?? '-' }}</p>
                <p><strong>قراءة عداد الكهرباء (بعد):</strong> {{ $dailyStationReport->electric_meter_reading_end ?? '-' }}
                </p>
                <p><strong>ساعات التشغيل على المولدة:</strong> {{ $dailyStationReport->hours_on_generator ?? '-' }}</p>
                <p><strong>كمية الديزل المُستهلكة (لتر):</strong>
                    {{ $dailyStationReport->diesel_consumed_liters_during_operation ?? '-' }}</p>
                <p><strong>كمية المياه المضخة للشبكة (م³):</strong>
                    {{ $dailyStationReport->water_pumped_to_network_m3 ?? '-' }}</p>
                {{-- ... أضف بقية حقول الطاقة والكميات ... --}}
            </div>
        </div>

        @if ($dailyStationReport->shift_operator_notes)
            <div class="detail-section">
                <h4>ملاحظات المُشغل المناوب</h4>
                <p>{{ nl2br(e($dailyStationReport->shift_operator_notes)) }}</p>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('css/show-details.css') }}"> --}}
    <style>
        /* يمكنك إضافة تنسيقات خاصة هنا أو في ملف CSS منفصل */
        .details-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .detail-section {
            margin-bottom: 25px;
        }

        .detail-section h4 {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            /* أعمدة مرنة */
            gap: 10px;
            /* مسافة بين العناصر */
        }

        .detail-grid p {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
            margin: 0;
            /* إزالة الهامش الافتراضي للفقرة */
        }

        .detail-grid p strong {
            display: inline-block;
            /* min-width: 180px; /* لتوحيد عرض العناوين الفرعية */
            color: #555;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #212529;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            border: none;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            border: none;
        }

        .btn-edit .material-icons-sharp,
        .btn-back .material-icons-sharp {
            margin-right: 5px;
        }
    </style>
@endpush
