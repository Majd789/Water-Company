<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>

    <table style="border-collapse: collapse; width: 100%; font-family: 'DejaVu Sans', sans-serif;">
        <thead>
            <tr>
                {{-- العرض الآن ثابت على 9 أعمدة: 2 للمواصفات + 7 للبيانات --}}
                <th colspan="9"
                    style="background-color: #0d47a1; color: #ffffff; font-weight: bold; text-align: center; font-size: 18px; padding: 12px; border: 1px solid #cccccc;">
                    Detailed assessment for water pump stations <br> تقييم مفصل لمحطات ضخ المياه
                </th>
            </tr>
        </thead>
        <tbody>
            <tr style="height: 20px;">
                <td colspan="9" style="border: none;"></td>
            </tr>

            <!-- 1. General Information -->
            <tr>
                <th colspan="2"
                    style="background-color: #e3f2fd; color: #0d47a1; font-weight: bold; text-align: center; font-size: 14px; border: 1px solid #cccccc;">
                    المعلومات العامة / General Information
                </th>
            </tr>
            @php
                $stationInfo = [
                    'كود المحطة' => $station->station_code,
                    'اسم المحطة' => $station->station_name,
                    'البلدة' => $station->town->town_name ?? 'غير محدد',
                    'الوحدة' => $station->town->unit->unit_name ?? 'غير محدد',
                    'حالة التشغيل' => $station->operational_status,
                    'سبب التوقف' => $station->stop_reason,
                    'مصدر الطاقة' => $station->energy_source,
                    'جهة التشغيل' => $station->operator_entity,
                    'جاهزية الشبكة (%)' => $station->network_readiness_percentage,
                    'عدد الأسر المستفيدة' => $station->beneficiary_families_count,
                    'التدفق الفعلي' => $station->actual_flow_rate,
                    'الموقع الجغرافي' => "{$station->latitude}, {$station->longitude}",
                ];
            @endphp
            @foreach ($stationInfo as $label => $value)
                <tr>
                    <td
                        style="font-weight: bold; background-color: #f5f5f5; text-align: right; border: 1px solid #cccccc; padding: 8px;">
                        {{ $label }}</td>
                    <td style="text-align: center; border: 1px solid #cccccc; padding: 8px;">{{ $value ?? '-' }}</td>
                </tr>
            @endforeach

            {{-- استدعاء الأقسام المختلفة باستخدام القالب المحدث --}}
            @include('dashboard.exports._section_template', [
                'title' => 'الآبار والمصادر المائية',
                'relation' => $station->wells,
                'itemName' => 'بئر',
                'specs' => [
                    ['label' => 'الوضع التشغيلي', 'key' => 'well_status'],
                    ['label' => 'المسافة من المحطة (متر)', 'key' => 'distance_from_station'],
                    ['label' => 'نوع البئر', 'key' => 'well_type'],
                    ['label' => 'تدفق البئر (م³/ساعة)', 'key' => 'well_flow'],
                    ['label' => 'العمق الساكن (متر)', 'key' => 'static_depth'],
                    ['label' => 'العمق الديناميكي (متر)', 'key' => 'dynamic_depth'],
                    ['label' => 'عمق الحفر (متر)', 'key' => 'drilling_depth'],
                    ['label' => 'قطر البئر (إنش)', 'key' => 'well_diameter'],
                    ['label' => 'عمق تركيب المضخة (متر)', 'key' => 'pump_installation_depth'],
                    ['label' => 'استطاعة المضخة (حصان)', 'key' => 'pump_capacity'],
                    ['label' => 'التدفق الفعلي للمضخة (م³/ساعة)', 'key' => 'actual_pump_flow'],
                    ['label' => 'رفع المضخة', 'key' => 'pump_lifting'],
                    ['label' => 'ماركة ونموذج المضخة', 'key' => 'pump_brand_model'],
                    ['label' => 'مصدر الطاقة', 'key' => 'energy_source'],
                    ['label' => 'عنوان البئر', 'key' => 'well_address'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'مجموعات التوليد',
                'relation' => $station->generationGroups,
                'itemName' => 'مجموعة',
                'specs' => [
                    ['label' => 'اسم المولد', 'key' => 'generator_name'],
                    ['label' => 'حالة التشغيل', 'key' => 'operational_status'],
                    ['label' => 'استطاعة التوليد (KVA)', 'key' => 'generation_capacity'],
                    ['label' => 'استطاعة التشغيل الفعلية (KVA)', 'key' => 'actual_operating_capacity'],
                    ['label' => 'صرف الوقود (لتر/ساعة)', 'key' => 'fuel_consumption'],
                    ['label' => 'مدة استخدام الزيت (ساعة)', 'key' => 'oil_usage_duration'],
                    ['label' => 'كمية الزيت للتبديل (لتر)', 'key' => 'oil_quantity_for_replacement'],
                    ['label' => 'سبب التوقف', 'key' => 'stop_reason'],
                    ['label' => 'ملاحظات', 'key' => 'notes'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'المضخات الأفقية',
                'relation' => $station->horizontalPumps,
                'itemName' => 'مضخة',
                'specs' => [
                    ['label' => 'الوضع التشغيلي', 'key' => 'pump_status'],
                    ['label' => 'استطاعة المضخة (حصان)', 'key' => 'pump_capacity_hp'],
                    ['label' => 'تدفق المضخة (م³/ساعة)', 'key' => 'pump_flow_rate_m3h'],
                    ['label' => 'ارتفاع الضخ (متر)', 'key' => 'pump_head'],
                    ['label' => 'ماركة وطراز المضخة', 'key' => 'pump_brand_model'],
                    ['label' => 'الحالة الفنية', 'key' => 'technical_condition'],
                    ['label' => 'مصدر الطاقة', 'key' => 'energy_source'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'الخزانات الأرضية',
                'relation' => $station->groundTanks,
                'itemName' => 'خزان',
                'specs' => [
                    ['label' => 'الجهة المنفذة', 'key' => 'building_entity'],
                    ['label' => 'نوع البناء', 'key' => 'construction_type'],
                    ['label' => 'المحطة المغذية', 'key' => 'feeding_station'],
                    ['label' => 'البلدة المزودة', 'key' => 'town_supply'],
                    ['label' => 'قطر الأنبوب الداخلي (مم)', 'key' => 'pipe_diameter_inside'],
                    ['label' => 'قطر الأنبوب الخارجي (مم)', 'key' => 'pipe_diameter_outside'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'الخزانات العالية',
                'relation' => $station->elevatedTanks,
                'itemName' => 'خزان',
                'specs' => [
                    ['label' => 'اسم الخزان', 'key' => 'tank_name'],
                    ['label' => 'الجهة المنشئة', 'key' => 'building_entity'],
                    ['label' => 'تاريخ البناء (قديم-جديد)', 'key' => 'construction_date'],
                    ['label' => 'سعة الخزان (م³)', 'key' => 'capacity'],
                    ['label' => 'نسبة الجاهزية (%)', 'key' => 'readiness_percentage'],
                    ['label' => 'ارتفاع الخزان (متر)', 'key' => 'height'],
                    ['label' => 'شكل الخزان', 'key' => 'tank_shape'],
                    ['label' => 'المحطة المغذية', 'key' => 'feeding_station'],
                    ['label' => 'البلدة المزودة', 'key' => 'town_supply'],
                    ['label' => 'قطر البوري (مم)', 'key' => 'in_pipe_diameter'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'الطاقة الشمسية',
                'relation' => $station->solarEnergies,
                'itemName' => 'نظام',
                'specs' => [
                    ['label' => 'استطاعة اللوح (واط)', 'key' => 'panel_size'],
                    ['label' => 'عدد الألواح', 'key' => 'panel_count'],
                    ['label' => 'الجهة المنشئة', 'key' => 'manufacturer'],
                    ['label' => 'نوع القاعدة', 'key' => 'base_type'],
                    ['label' => 'الحالة الفنية', 'key' => 'technical_condition'],
                    ['label' => 'عدد التجهيزات المزودة (غاطس-افقية)', 'key' => 'wells_supplied_count'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'مرشحات المياه',
                'relation' => $station->filters,
                'itemName' => 'مرشح',
                'specs' => [
                    ['label' => 'استطاعة المرشح (m³/h)', 'key' => 'filter_capacity'],
                    ['label' => 'جاهزية (%)', 'key' => 'readiness_status'],
                    ['label' => 'نوع المرشح', 'key' => 'filter_type'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'الانفلترات',
                'relation' => $station->infiltrator,
                'itemName' => 'انفلتر',
                'specs' => [
                    ['label' => 'استطاعة الانفلتر (kW)', 'key' => 'infiltrator_capacity'],
                    ['label' => 'جاهزية (%)', 'key' => 'readiness_status'],
                    ['label' => 'نوع الانفلتر', 'key' => 'infiltrator_type'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'خزانات الديزل',
                'relation' => $station->dieselTank,
                'itemName' => 'خزان',
                'specs' => [
                    ['label' => 'اسم الخزان', 'key' => 'tank_name'],
                    ['label' => 'سعة الخزان (لتر)', 'key' => 'tank_capacity'],
                    ['label' => 'نسبة الجاهزية (%)', 'key' => 'readiness_percentage'],
                    ['label' => 'نوع الخزان', 'key' => 'type'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'مضخات التعقيم',
                'relation' => $station->disinfectionPump,
                'itemName' => 'مضخة',
                'specs' => [
                    ['label' => 'الوضع التشغيلي', 'key' => 'disinfection_pump_status'],
                    ['label' => 'ماركة وطراز المضخة', 'key' => 'pump_brand_model'],
                    ['label' => 'غزارة المضخة (م³/ساعة)', 'key' => 'pump_flow_rate'],
                    ['label' => 'ضغط العمل (بار)', 'key' => 'operating_pressure'],
                    ['label' => 'الحالة الفنية', 'key' => 'technical_condition'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'محولات الكهرباء',
                'relation' => $station->electricityTransformer,
                'itemName' => 'محولة',
                'specs' => [
                    ['label' => 'استطاعة المحولة (KVA)', 'key' => 'transformer_capacity'],
                    ['label' => 'بعد المحولة عن المحطة (متر)', 'key' => 'distance_from_station'],
                    [
                        'label' => 'هل المحولة خاصة بالمحطة',
                        'key' => 'is_station_transformer',
                        'type' => 'boolean',
                    ],
                    ['label' => 'وصف المحولة', 'key' => 'talk_about_station_transformer'],
                    ['label' => 'هل الاستطاعة كافية', 'key' => 'is_capacity_sufficient', 'type' => 'boolean'],
                    ['label' => 'كم الاستطاعة المحتاجة', 'key' => 'how_mush_capacity_need'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'ساعات الكهرباء',
                'relation' => $station->electricityHours,
                'itemName' => 'عداد',
                'specs' => [
                    ['label' => 'عدد ساعات الكهرباء', 'key' => 'electricity_hours'],
                    ['label' => 'رقم ساعة الكهرباء', 'key' => 'electricity_hour_number'],
                    ['label' => 'نوع العداد', 'key' => 'meter_type'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'المناهل',
                'relation' => $station->manholes,
                'itemName' => 'منهل',
                'specs' => [
                    ['label' => 'اسم المنهل', 'key' => 'manhole_name'],
                    ['label' => 'الوضع التشغيلي', 'key' => 'status'],
                    ['label' => 'هل يوجد عداد', 'key' => 'has_flow_meter', 'type' => 'boolean'],
                    ['label' => 'رقم شاسيه', 'key' => 'chassis_number'],
                    ['label' => 'قطر العداد (إنش)', 'key' => 'meter_diameter'],
                    ['label' => 'وضع العداد', 'key' => 'meter_status'],
                    ['label' => 'هل يوجد خزان تجميعي', 'key' => 'has_storage_tank', 'type' => 'boolean'],
                    ['label' => 'سعة الخزان (م³)', 'key' => 'tank_capacity'],
                ],
            ])
            @include('dashboard.exports._section_template', [
                'title' => 'قطاعات الضخ',
                'relation' => $station->pumpingSectors,
                'itemName' => 'قطاع',
                'specs' => [
                    ['label' => 'اسم القطاع', 'key' => 'sector_name'],
                    ['label' => 'البلدة التابعة', 'key' => 'town.town_name'],
                ],
            ])

        </tbody>
    </table>

</body>

</html>
