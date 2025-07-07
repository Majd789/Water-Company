<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DailyStationReport;
use App\Models\Station;
use App\Models\User;
use App\Models\Unit;
use App\Models\Town;
use App\Models\PumpingSector;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // لاستخدام Rule::requiredIf

class DailyStationReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $reports = DailyStationReport::with(['station', 'operator', 'pumpingSector'])
                                     ->latest()
                                     ->paginate(15);

        return view('daily_station_reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $loggedInUser = Auth::user();
        $operatorName = $loggedInUser->name;

        $preselectedStationId = null;
        $preselectedStationName = '';
        $preselectedStationCode = '';
        $preselectedTownName = '';
        $preselectedUnitName = '';
        $userStationPumpingSectors = collect();

        $stations = Station::orderBy('station_name')->pluck('station_name', 'id');
        $operators = User::orderBy('name')->pluck('name', 'id');
        $units = Unit::orderBy('unit_name')->pluck('unit_name', 'id');
        $towns = Town::orderBy('town_name')->pluck('town_name', 'id');
        $allPumpingSectors = PumpingSector::orderBy('sector_name')->pluck('sector_name', 'id');


        if ($loggedInUser->station_id) {
            $station = Station::with(['town.unit', 'pumpingSectors'])->find($loggedInUser->station_id);
            if ($station) {
                $preselectedStationId = $station->id;
                $preselectedStationName = $station->station_name;
                $preselectedStationCode = $station->station_code;
                $preselectedTownName = optional($station->town)->town_name ?? '';
                $preselectedUnitName = optional($station->town->unit)->unit_name ?? '';
                $userStationPumpingSectors = $station->pumpingSectors()->orderBy('sector_name')->get();
            }
        }

        return view('daily_station_reports.create', [
            'operatorName' => $operatorName,
            'operators' => $operators,
            'stations' => $stations,
            'units' => $units,
            'towns' => $towns,
            'allPumpingSectors' => $allPumpingSectors,
            'pumpingSectors' => $userStationPumpingSectors,
            'preselectedStationId' => $preselectedStationId,
            'preselectedStationName' => $preselectedStationName,
            'preselectedStationCode' => $preselectedStationCode,
            'preselectedTownName' => $preselectedTownName,
            'preselectedUnitName' => $preselectedUnitName,
            'dailyStationReport' => null,
        ]);
    }

    /**
     * لتخصيص رسائل التحقق من الصحة باللغة العربية:
     * 1. تأكد من أن لغة التطبيق الافتراضية هي 'ar' في `config/app.php`.
     * `'locale' => 'ar',`
     * 2. قم بإنشاء/تعديل ملف الترجمة `lang/ar/validation.php` (أو `resources/lang/ar/validation.php` في الإصدارات الأحدث).
     * يمكنك نسخ محتوى `lang/en/validation.php` وترجمة الرسائل.
     *
     * مثال لبعض الترجمات في `lang/ar/validation.php`:
     *
     * return [
     * // ...
     * 'required' => 'حقل :attribute مطلوب.',
     * 'date'     => 'حقل :attribute يجب أن يكون تاريخًا صالحًا.',
     * 'numeric'  => 'حقل :attribute يجب أن يكون رقمًا.',
     * 'min'      => [
     * 'numeric' => 'حقل :attribute يجب أن يكون على الأقل :min.',
     * 'string'  => 'حقل :attribute يجب أن يكون على الأقل :min حرفًا.',
     * ],
     * 'max'      => [
     * 'numeric' => 'حقل :attribute يجب ألا يزيد عن :max.',
     * 'string'  => 'حقل :attribute يجب ألا يزيد عن :max حرفًا.',
     * ],
     * 'in'       => 'قيمة :attribute المختارة غير صالحة.',
     * 'exists'   => 'قيمة :attribute المختارة غير موجودة.',
     * 'date_format' => 'حقل :attribute لا يطابق الصيغة :format.',
     * 'boolean'  => 'حقل :attribute يجب أن يكون صحيحًا أو خاطئًا.',
     *
     * // يمكنك أيضًا تخصيص أسماء الحقول (attributes)
     * 'attributes' => [
     * 'report_date' => 'تاريخ التقرير',
     * 'report_time' => 'وقت التقرير',
     * 'daily_operational_status' => 'الوضع التشغيلي',
     * 'daily_stop_reason' => 'سبب التوقف',
     * // ... وهكذا لبقية الحقول
     * ],
     * // ...
     * ];
     *
     * بعد إعداد ملف الترجمة، سيقوم Laravel تلقائيًا باستخدام هذه الرسائل المعربة.
     */
    private function getValidationRules(Request $request, bool $isUpdate = false, ?DailyStationReport $report = null): array
    {
        $operationalStatus = $request->input('daily_operational_status');
        $isOperational = $operationalStatus === 'عاملة';

        $rules = [
            'report_date' => 'required|date',
            'report_time' => 'nullable|date_format:H:i:s',
            'daily_operational_status' => 'required|in:عاملة,متوقفة,خارج الخدمة',
            'daily_stop_reason' => [
                Rule::requiredIf(fn() => in_array($operationalStatus, ['متوقفة', 'خارج الخدمة'])),
                'nullable', 'string', 'max:1000'
            ],
            'daily_operator_entity' => [
                Rule::requiredIf($isOperational),
                'nullable',
                'in:تشغيل تشاركي,المؤسسة العامة لمياه الشرب'
            ],
            'daily_operator_entity_name' => [
                Rule::requiredIf(fn() => $isOperational && $request->input('daily_operator_entity') === 'تشغيل تشاركي'),
                'nullable', 'string', 'max:255'
            ],
            'pumping_sector_id' => [
                Rule::requiredIf($isOperational),
                'nullable',
                'exists:pumping_sectors,id'
            ],
            'shift_operator_notes' => 'nullable|string',
        ];

        $operationalSpecificRules = [
            'active_wells_during_pumping_count' => ['required', 'integer', 'min:0', 'max:7'],
            'total_station_pumping_hours' => ['required', 'numeric', 'min:0'],
            'has_horizontal_pump' => ['required', 'boolean'],
            'station_operation_method_notes' => ['required', 'string', 'max:1000'],
            'daily_has_disinfection' => ['required', 'boolean'],
            'daily_energy_source' => ['required', 'string', Rule::in(['كهرباء', 'مولدة', 'طاقة شمسية', 'دمج كهرباء وطاقة شمسية', 'دمج مولدة وطاقة شمسية', 'كهرباء ومولدة', 'كهرباء ومولدة وطاقة شمسية'])],
            'water_pumped_to_network_m3' => ['required', 'numeric', 'min:0'],
            'diesel_in_station_total_liters' => ['required', 'numeric', 'min:0'],
            'new_diesel_shipment_received' => ['required', 'boolean'],
            'station_equipment_modified_today' => ['required', 'boolean'],
            'electricity_meter_recharged_today' => ['required', 'boolean'],
        ];

        if ($isOperational) {
            $rules = array_merge($rules, $operationalSpecificRules);

            $activeWells = (int) $request->input('active_wells_during_pumping_count', 0);
            for ($i = 1; $i <= 7; $i++) {
                $rules['well_' . $i . '_operating_hours'] = [Rule::requiredIf($i <= $activeWells), 'nullable', 'numeric', 'min:0', 'max:24'];
            }

            $rules['horizontal_pump_operating_hours'] = [Rule::requiredIf($request->input('has_horizontal_pump') == '1'), 'nullable', 'numeric', 'min:0', 'max:24'];
            $rules['daily_no_disinfection_reason'] = [Rule::requiredIf($request->input('daily_has_disinfection') == '0'), 'nullable', 'string', 'max:1000'];

            $energySource = $request->input('daily_energy_source', '');
            $showElectricity = str_contains($energySource, 'كهرباء');
            $showGenerator = str_contains($energySource, 'مولدة');
            $showSolar = str_contains($energySource, 'طاقة شمسية');

            $rules['hours_electric_solar_blend'] = [Rule::requiredIf($energySource === 'دمج كهرباء وطاقة شمسية'), 'nullable', 'numeric', 'min:0', 'max:24'];
            $rules['hours_generator_solar_blend'] = [Rule::requiredIf($energySource === 'دمج مولدة وطاقة شمسية'), 'nullable', 'numeric', 'min:0', 'max:24'];
            $rules['hours_on_solar'] = [Rule::requiredIf($showSolar), 'nullable', 'numeric', 'min:0', 'max:24'];

            if ($showElectricity) {
                $rules['hours_on_electricity'] = ['required', 'numeric', 'min:0', 'max:24'];
                $rules['electricity_consumed_kwh'] = ['required', 'numeric', 'min:0'];
                $rules['electric_meter_reading_start'] = ['required', 'string', 'max:255'];
                $rules['electric_meter_reading_end'] = ['required', 'string', 'max:255'];
                $rules['electricity_recharged_amount_kwh'] = [Rule::requiredIf($request->input('electricity_meter_recharged_today') == '1'), 'nullable', 'numeric', 'min:0'];
            } else {
                $rules['hours_on_electricity'] = ['nullable', 'numeric', 'min:0', 'max:24'];
                $rules['electricity_consumed_kwh'] = ['nullable', 'numeric', 'min:0'];
                $rules['electric_meter_reading_start'] = ['nullable', 'string', 'max:255'];
                $rules['electric_meter_reading_end'] = ['nullable', 'string', 'max:255'];
                $rules['electricity_recharged_amount_kwh'] = ['nullable', 'numeric', 'min:0'];
            }

            if ($showGenerator) {
                $rules['hours_on_generator'] = ['required', 'numeric', 'min:0', 'max:24'];
                $rules['diesel_consumed_liters_during_operation'] = ['required', 'numeric', 'min:0'];
                $rules['generator_oil_changed_status'] = ['required', Rule::in(['لا يوجد', 'زيادة الزيت', 'استبدال الزيت بالكامل'])];
                $rules['oil_added_to_generator_liters'] = [
                    Rule::requiredIf(fn() => in_array($request->input('generator_oil_changed_status'), ['زيادة الزيت', 'استبدال الزيت بالكامل'])),
                    'nullable', 'numeric', 'min:0'
                ];
            } else {
                $rules['hours_on_generator'] = ['nullable', 'numeric', 'min:0', 'max:24'];
                $rules['diesel_consumed_liters_during_operation'] = ['nullable', 'numeric', 'min:0'];
                $rules['generator_oil_changed_status'] = ['nullable', Rule::in(['لا يوجد', 'زيادة الزيت', 'استبدال الزيت بالكامل'])];
                $rules['oil_added_to_generator_liters'] = ['nullable', 'numeric', 'min:0'];
            }

            $rules['new_diesel_shipment_quantity_liters'] = [Rule::requiredIf($request->input('new_diesel_shipment_received') == '1'), 'nullable', 'numeric', 'min:0'];
            $rules['diesel_shipment_supplier'] = [Rule::requiredIf($request->input('new_diesel_shipment_received') == '1'), 'nullable', 'string', 'max:255'];
            $rules['equipment_modification_location_type'] = [Rule::requiredIf($request->input('station_equipment_modified_today') == '1'), 'nullable', 'string', 'max:255'];
            $rules['equipment_modification_description_reason'] = [Rule::requiredIf($request->input('station_equipment_modified_today') == '1'), 'nullable', 'string'];

        } else {
            $allOperationalFields = array_keys($operationalSpecificRules);
            $conditionallyOperationalFields = [
                'well_1_operating_hours', 'well_2_operating_hours', 'well_3_operating_hours', 'well_4_operating_hours', 'well_5_operating_hours', 'well_6_operating_hours', 'well_7_operating_hours',
                'horizontal_pump_operating_hours', 'daily_no_disinfection_reason',
                'hours_electric_solar_blend', 'hours_generator_solar_blend', 'hours_on_solar',
                'hours_on_electricity', 'electricity_consumed_kwh', 'electric_meter_reading_start', 'electric_meter_reading_end',
                'electricity_recharged_amount_kwh',
                'hours_on_generator', 'diesel_consumed_liters_during_operation', 'generator_oil_changed_status','oil_added_to_generator_liters',
                'new_diesel_shipment_quantity_liters', 'diesel_shipment_supplier',
                'equipment_modification_location_type', 'equipment_modification_description_reason'
            ];
            $fieldsToAdjust = array_unique(array_merge($allOperationalFields, $conditionallyOperationalFields, ['daily_operator_entity', 'pumping_sector_id', 'daily_operator_entity_name']));

            foreach ($fieldsToAdjust as $field) {
                if (isset($rules[$field])) {
                    if (is_array($rules[$field])) {
                        $rules[$field] = array_filter($rules[$field], function ($rule_item) {
                            return !($rule_item === 'required' || $rule_item instanceof \Illuminate\Validation\Rules\RequiredIf || (is_object($rule_item) && method_exists($rule_item, '__toString') && str_starts_with($rule_item->__toString(), 'required_if')));
                        });
                        if (!in_array('nullable', $rules[$field])) {
                            array_unshift($rules[$field], 'nullable');
                        }
                        $rules[$field] = array_values(array_unique($rules[$field]));
                    } else {
                        $ruleString = $rules[$field];
                        $ruleString = preg_replace('/(^|\|)required(_if:[^|]*)?/', '', $ruleString);
                        $ruleString = trim($ruleString, '|');
                        $ruleString = 'nullable' . ($ruleString ? '|' . $ruleString : '');
                        $rules[$field] = implode('|', array_unique(explode('|', $ruleString)));
                    }
                } else {
                     $rules[$field] = 'nullable';
                }
                if (is_array($rules[$field]) && in_array('required', $rules[$field])) {
                    $rules[$field] = array_diff($rules[$field], ['required']);
                     if (!in_array('nullable', $rules[$field])) {
                        array_unshift($rules[$field], 'nullable');
                    }
                } elseif (is_string($rules[$field]) && str_contains($rules[$field], 'required') && !str_contains($rules[$field], 'required_if')) {
                     $rules[$field] = str_replace('required', 'nullable', $rules[$field]);
                     if (!str_starts_with($rules[$field], 'nullable')) {
                        $rules[$field] = 'nullable|' . $rules[$field];
                    }
                }
                 $rules[$field] = is_array($rules[$field]) ? array_values(array_unique($rules[$field])) : $rules[$field]; // Ensure unique and re-index
            }
        }
        return $rules;
    }

    public function store(Request $request): RedirectResponse
    {
        $loggedInUser = Auth::user();
        $rules = $this->getValidationRules($request);
        $validatedData = $request->validate($rules);

        $validatedData['operator_id'] = $loggedInUser->id;

        if ($loggedInUser->station_id) {
            $userStation = Station::with('town.unit')->find($loggedInUser->station_id);
            if ($userStation) {
                $validatedData['station_id'] = $userStation->id;
                $validatedData['station_code_snapshot'] = $userStation->station_code;
                if ($userStation->town) {
                    $validatedData['town_id'] = $userStation->town_id;
                    $validatedData['unit_id'] = optional($userStation->town->unit)->id;
                } else {
                    $validatedData['town_id'] = null;
                    $validatedData['unit_id'] = null;
                }

                if ($request->filled('pumping_sector_id')) {
                    $sectorExists = PumpingSector::where('id', $request->pumping_sector_id)
                                                ->where('station_id', $userStation->id)
                                                ->exists();
                    if (!$sectorExists) $validatedData['pumping_sector_id'] = null;
                } else {
                    $validatedData['pumping_sector_id'] = null;
                }
            } else {
                return back()->with('error', 'المستخدم غير مرتبط بمحطة صالحة.')->withInput();
            }
        } else {
            if ($request->filled('station_id') && Auth::user()->role_id === 'admin') {
                $station = Station::with('town.unit')->find($request->input('station_id'));
                if ($station) {
                    $validatedData['station_id'] = $station->id;
                    $validatedData['station_code_snapshot'] = $station->station_code;
                    $validatedData['town_id'] = optional($station->town)->id;
                    $validatedData['unit_id'] = optional($station->town->unit)->id;
                    if ($request->filled('pumping_sector_id')) {
                        $sectorExists = PumpingSector::where('id', $request->pumping_sector_id)
                                                    ->where('station_id', $station->id)
                                                    ->exists();
                        if (!$sectorExists) $validatedData['pumping_sector_id'] = null;
                    } else {
                        $validatedData['pumping_sector_id'] = null;
                    }
                } else {
                     return back()->with('error', 'المحطة المختارة غير صالحة.')->withInput();
                }
            } else {
                 return back()->with('error', 'لم يتم تحديد محطة للمستخدم أو لا تملك الصلاحية لاختيار محطة.')->withInput();
            }
        }

        $booleanFields = [
            'has_horizontal_pump', 'daily_has_disinfection',
            'new_diesel_shipment_received', 'station_equipment_modified_today',
            'electricity_meter_recharged_today'
        ];
        foreach ($booleanFields as $field) {
            $validatedData[$field] = filter_var($request->input($field, '0'), FILTER_VALIDATE_BOOLEAN);
        }

        $oilChangeStatus = $request->input('generator_oil_changed_status');
        $validatedData['generator_oil_changed'] = in_array($oilChangeStatus, ['زيادة الزيت', 'استبدال الزيت بالكامل']);
        if (!$validatedData['generator_oil_changed']) {
            $validatedData['oil_added_to_generator_liters'] = null;
        }

        if ($validatedData['daily_operational_status'] !== 'عاملة') {
            $fieldsToNullify = [
                'active_wells_during_pumping_count', 'total_station_pumping_hours',
                'horizontal_pump_operating_hours',
                'hours_electric_solar_blend', 'hours_generator_solar_blend',
                'hours_on_solar', 'hours_on_electricity', 'electricity_consumed_kwh',
                'electric_meter_reading_start', 'electric_meter_reading_end',
                'hours_on_generator', 'diesel_consumed_liters_during_operation',
                'oil_added_to_generator_liters', 'water_pumped_to_network_m3',
                'diesel_in_station_total_liters', 'new_diesel_shipment_quantity_liters',
                'diesel_shipment_supplier', 'equipment_modification_location_type',
                'equipment_modification_description_reason', 'equipment_transferred_to_entity',
                'electricity_recharged_amount_kwh', 'daily_energy_source',
                'generator_oil_changed_status',
                'pumping_sector_id', 'daily_operator_entity', 'daily_operator_entity_name'
            ];
            for ($i = 1; $i <= 7; $i++) {
                $fieldsToNullify[] = 'well_' . $i . '_operating_hours';
            }
            foreach ($fieldsToNullify as $fieldToNull) {
                if (array_key_exists($fieldToNull, $validatedData)) {
                    $validatedData[$fieldToNull] = null;
                }
            }
            if ($validatedData['daily_has_disinfection']) {
                $validatedData['daily_no_disinfection_reason'] = null;
            } else {
                // Keep the reason if disinfection is 'no' and a reason was provided
                $validatedData['daily_no_disinfection_reason'] = $request->input('daily_no_disinfection_reason', null);
            }

            $validatedData['generator_oil_changed'] = false;
            $validatedData['oil_added_to_generator_liters'] = null;

            if (!$validatedData['electricity_meter_recharged_today']) $validatedData['electricity_recharged_amount_kwh'] = null;
            if (!$validatedData['new_diesel_shipment_received']) {
                $validatedData['new_diesel_shipment_quantity_liters'] = null;
                $validatedData['diesel_shipment_supplier'] = null;
            }
            if (!$validatedData['station_equipment_modified_today']) {
                $validatedData['equipment_modification_location_type'] = null;
                $validatedData['equipment_modification_description_reason'] = null;
                $validatedData['equipment_transferred_to_entity'] = null;
            }
             if (!$validatedData['has_horizontal_pump']) {
                $validatedData['horizontal_pump_operating_hours'] = null;
            }
        } else {
            if ($request->input('daily_has_disinfection') == '1' || $request->input('daily_has_disinfection') === true) $validatedData['daily_no_disinfection_reason'] = null;
            if ($request->input('has_horizontal_pump') == '0' || $request->input('has_horizontal_pump') === false) $validatedData['horizontal_pump_operating_hours'] = null;
            if ($request->input('electricity_meter_recharged_today') == '0' || $request->input('electricity_meter_recharged_today') === false) $validatedData['electricity_recharged_amount_kwh'] = null;
            if ($request->input('new_diesel_shipment_received') == '0' || $request->input('new_diesel_shipment_received') === false) {
                $validatedData['new_diesel_shipment_quantity_liters'] = null;
                $validatedData['diesel_shipment_supplier'] = null;
            }
            if ($request->input('station_equipment_modified_today') == '0' || $request->input('station_equipment_modified_today') === false) {
                $validatedData['equipment_modification_location_type'] = null;
                $validatedData['equipment_modification_description_reason'] = null;
                $validatedData['equipment_transferred_to_entity'] = null;
            }
        }

        DailyStationReport::create($validatedData);

        return redirect()->route('daily-station-reports.index')
                         ->with('success', 'تم إنشاء التقرير اليومي بنجاح.');
    }

    public function show(DailyStationReport $dailyStationReport): View
    {
        $dailyStationReport->load(['station.town.unit', 'operator', 'pumpingSector']);
        return view('daily_station_reports.show', compact('dailyStationReport'));
    }

    public function edit(DailyStationReport $dailyStationReport): View
    {
        $loggedInUser = Auth::user();
        $operatorName = $dailyStationReport->operator->name ?? $loggedInUser->name;

        $stations = Station::orderBy('station_name')->pluck('station_name', 'id');
        $operators = User::orderBy('name')->pluck('name', 'id');
        $units = Unit::orderBy('unit_name')->pluck('unit_name', 'id');
        $towns = Town::orderBy('town_name')->pluck('town_name', 'id');
        $allPumpingSectors = PumpingSector::orderBy('sector_name')->pluck('sector_name', 'id');

        $pumpingSectorsForEdit = collect();
        $preselectedStationName = '';
        $preselectedStationCode = '';
        $preselectedUnitName = '';
        $preselectedTownName = '';

        $dailyStationReport->load(['station.town.unit', 'station.pumpingSectors', 'operator', 'unit', 'town', 'pumpingSector']);

        if ($dailyStationReport->station) {
            $preselectedStation = $dailyStationReport->station;
            $preselectedStationName = $preselectedStation->station_name;
            $preselectedStationCode = $dailyStationReport->station_code_snapshot;
            if ($preselectedStation->town) {
                $preselectedTownName = $preselectedStation->town->town_name;
                if ($preselectedStation->town->unit) {
                    $preselectedUnitName = $preselectedStation->town->unit->unit_name;
                }
            }
            $pumpingSectorsForEdit = $preselectedStation->pumpingSectors()->orderBy('sector_name')->get();
        }

        return view('daily_station_reports.edit', [
            'dailyStationReport' => $dailyStationReport,
            'operatorName' => $operatorName,
            'stations' => $stations,
            'operators' => $operators,
            'units' => $units,
            'towns' => $towns,
            'allPumpingSectors' => $allPumpingSectors,
            'pumpingSectors' => $pumpingSectorsForEdit,
            'pumpingSectorsForEdit' => $pumpingSectorsForEdit,
            'preselectedStationName' => $preselectedStationName,
            'preselectedStationCode' => $preselectedStationCode,
            'preselectedUnitName' => $preselectedUnitName,
            'preselectedTownName' => $preselectedTownName,
        ]);
    }

    public function update(Request $request, DailyStationReport $dailyStationReport): RedirectResponse
    {
        $rules = $this->getValidationRules($request, true, $dailyStationReport);
        $validatedData = $request->validate($rules);

        $booleanFields = [
            'has_horizontal_pump', 'daily_has_disinfection',
            'new_diesel_shipment_received', 'station_equipment_modified_today',
            'electricity_meter_recharged_today'
        ];
        foreach ($booleanFields as $field) {
            $validatedData[$field] = filter_var($request->input($field, $dailyStationReport->$field ? '1' : '0'), FILTER_VALIDATE_BOOLEAN);
        }

        $oilChangeStatus = $request->input('generator_oil_changed_status');
        $validatedData['generator_oil_changed'] = in_array($oilChangeStatus, ['زيادة الزيت', 'استبدال الزيت بالكامل']);
         if (!$validatedData['generator_oil_changed']) {
            $validatedData['oil_added_to_generator_liters'] = null;
        }

        if ($validatedData['daily_operational_status'] !== 'عاملة') {
            $fieldsToNullify = [
                'active_wells_during_pumping_count', 'total_station_pumping_hours',
                'horizontal_pump_operating_hours',
                'hours_electric_solar_blend', 'hours_generator_solar_blend',
                'hours_on_solar', 'hours_on_electricity', 'electricity_consumed_kwh',
                'electric_meter_reading_start', 'electric_meter_reading_end',
                'hours_on_generator', 'diesel_consumed_liters_during_operation',
                'oil_added_to_generator_liters', 'water_pumped_to_network_m3',
                'diesel_in_station_total_liters', 'new_diesel_shipment_quantity_liters',
                'diesel_shipment_supplier', 'equipment_modification_location_type',
                'equipment_modification_description_reason', 'equipment_transferred_to_entity',
                'electricity_recharged_amount_kwh', 'daily_energy_source',
                'generator_oil_changed_status',
                'pumping_sector_id', 'daily_operator_entity', 'daily_operator_entity_name'
            ];
            for ($i = 1; $i <= 7; $i++) {
                $fieldsToNullify[] = 'well_' . $i . '_operating_hours';
            }
            foreach ($fieldsToNullify as $fieldToNull) {
                 if (array_key_exists($fieldToNull, $validatedData)) {
                    $validatedData[$fieldToNull] = null;
                }
            }
            if ($validatedData['daily_has_disinfection']) {
                 $validatedData['daily_no_disinfection_reason'] = null;
            } else {
                $validatedData['daily_no_disinfection_reason'] = $request->input('daily_no_disinfection_reason', $dailyStationReport->daily_no_disinfection_reason);
            }

            if (!$validatedData['generator_oil_changed']) $validatedData['oil_added_to_generator_liters'] = null;
            if (!$validatedData['electricity_meter_recharged_today']) $validatedData['electricity_recharged_amount_kwh'] = null;
            if (!$validatedData['new_diesel_shipment_received']) {
                $validatedData['new_diesel_shipment_quantity_liters'] = null;
                $validatedData['diesel_shipment_supplier'] = null;
            }
            if (!$validatedData['station_equipment_modified_today']) {
                $validatedData['equipment_modification_location_type'] = null;
                $validatedData['equipment_modification_description_reason'] = null;
                $validatedData['equipment_transferred_to_entity'] = null;
            }
             if (!$validatedData['has_horizontal_pump']) {
                $validatedData['horizontal_pump_operating_hours'] = null;
            }
        } else {
            if ($request->input('daily_has_disinfection') == '1' || $request->input('daily_has_disinfection') === true) $validatedData['daily_no_disinfection_reason'] = null;
            if ($request->input('has_horizontal_pump') == '0' || $request->input('has_horizontal_pump') === false) $validatedData['horizontal_pump_operating_hours'] = null;
            if ($request->input('electricity_meter_recharged_today') == '0' || $request->input('electricity_meter_recharged_today') === false) $validatedData['electricity_recharged_amount_kwh'] = null;
            if ($request->input('new_diesel_shipment_received') == '0' || $request->input('new_diesel_shipment_received') === false) {
                $validatedData['new_diesel_shipment_quantity_liters'] = null;
                $validatedData['diesel_shipment_supplier'] = null;
            }
            if ($request->input('station_equipment_modified_today') == '0' || $request->input('station_equipment_modified_today') === false) {
                $validatedData['equipment_modification_location_type'] = null;
                $validatedData['equipment_modification_description_reason'] = null;
                $validatedData['equipment_transferred_to_entity'] = null;
            }
        }

        if ($request->has('station_id') && $dailyStationReport->station_id != $request->input('station_id')) {
            $newStation = Station::find($request->input('station_id'));
            if ($newStation) {
                $validatedData['station_id'] = $newStation->id;
                $validatedData['station_code_snapshot'] = $newStation->station_code;
                if($newStation->town){
                    $validatedData['town_id'] = $newStation->town_id;
                    $validatedData['unit_id'] = $newStation->town->unit_id ?? null;
                } else {
                    $validatedData['town_id'] = null;
                    $validatedData['unit_id'] = null;
                }
            }
        } else {
            $validatedData['station_id'] = $dailyStationReport->station_id;
            $validatedData['station_code_snapshot'] = $dailyStationReport->station_code_snapshot;
            $validatedData['town_id'] = $dailyStationReport->town_id;
            $validatedData['unit_id'] = $dailyStationReport->unit_id;
        }
        $validatedData['operator_id'] = $dailyStationReport->operator_id;

        $dailyStationReport->update($validatedData);

        return redirect()->route('daily-station-reports.index')
                         ->with('success', 'تم تحديث التقرير اليومي بنجاح.');
    }

    public function destroy(DailyStationReport $dailyStationReport): RedirectResponse
    {
        $dailyStationReport->delete();
        return redirect()->route('daily-station-reports.index')
                         ->with('success', 'تم حذف التقرير اليومي بنجاح.');
    }
}
