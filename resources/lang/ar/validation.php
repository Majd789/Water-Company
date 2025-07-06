<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول الحقل :attribute',
    'accepted_if' => 'الحقل :attribute مقبول في حال ما إذا كان :other يساوي :value.',
    'active_url' => 'الحقل :attribute لا يُمثّل رابطًا صحيحًا',
    'after' => 'يجب على الحقل :attribute أن يكون تاريخًا لاحقًا للتاريخ :date.',
    'after_or_equal' => 'الحقل :attribute يجب أن يكون تاريخاً لاحقاً أو مطابقاً للتاريخ :date.',
    'alpha' => 'يجب أن لا يحتوي الحقل :attribute سوى على حروف',
    'alpha_dash' => 'يجب أن لا يحتوي الحقل :attribute على حروف، أرقام ومطّات.',
    'alpha_num' => 'يجب أن يحتوي :attribute على حروفٍ وأرقامٍ فقط',
    'array' => 'يجب أن يكون الحقل :attribute ًمصفوفة',
    'before' => 'يجب على الحقل :attribute أن يكون تاريخًا سابقًا للتاريخ :date.',
    'before_or_equal' => 'الحقل :attribute يجب أن يكون تاريخا سابقا أو مطابقا للتاريخ :date',
    'between' => [
        'array' => 'يجب أن يحتوي :attribute على عدد من العناصر بين :min و :max',
        'file' => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'string' => 'يجب أن يكون عدد حروف النّص :attribute بين :min و :max',
    ],
    'boolean' => 'يجب أن تكون قيمة الحقل :attribute إما true أو false ',
    'confirmed' => 'حقل التأكيد غير مُطابق للحقل :attribute',
    'current_password' => 'كلمة المرور غير صحيحة',
    'date' => 'الحقل :attribute ليس تاريخًا صحيحًا',
    'date_equals' => 'لا يساوي الحقل :attribute مع :date.',
    'date_format' => 'لا يتوافق الحقل :attribute مع الشكل :format.',
    'declined' => 'يجب رفض الحقل :attribute',
    'declined_if' => 'الحقل :attribute مرفوض في حال ما إذا كان :other يساوي :value.',
    'different' => 'يجب أن يكون الحقلان :attribute و :other مُختلفان',
    'digits' => 'يجب أن يحتوي الحقل :attribute على :digits رقمًا/أرقام',
    'digits_between' => 'يجب أن يحتوي الحقل :attribute بين :min و :max رقمًا/أرقام',
    'dimensions' => 'الـ :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct' => 'للحقل :attribute قيمة مُكرّرة.',
    'doesnt_end_with' => 'الحقل :attribute يجب ألا ينتهي بواحدة من القيم التالية: :values.',
    'doesnt_start_with' => 'الحقل :attribute يجب ألا يبدأ بواحدة من القيم التالية: :values.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح البُنية',
    'ends_with' => 'الـ :attribute يجب ان ينتهي بأحد القيم التالية :value.',
    'enum' => 'الحقل :attribute غير صحيح',
    'exists' => 'الحقل :attribute لاغٍ',
    'file' => 'الـ :attribute يجب أن يكون من ملفا.',
    'filled' => 'الحقل :attribute إجباري',
    'gt' => [
        'array' => 'الـ :attribute يجب ان يحتوي علي اكثر من :value عناصر/عنصر.',
        'file' => 'الـ :attribute يجب ان يكون اكبر من :value كيلو بايت.',
        'numeric' => 'الـ :attribute يجب ان يكون اكبر من :value.',
        'string' => 'الـ :attribute يجب ان يكون اكبر من :value حروفٍ/حرفًا.',
    ],
    'gte' => [
        'array' => 'الـ :attribute يجب ان يحتوي علي :value عناصر/عنصر او اكثر.',
        'file' => 'الـ :attribute يجب ان يكون اكبر من او يساوي :value كيلو بايت.',
        'numeric' => 'الـ :attribute يجب ان يكون اكبر من او يساوي :value.',
        'string' => 'الـ :attribute يجب ان يكون اكبر من او يساوي :value حروفٍ/حرفًا.',
    ],
    'image' => 'يجب أن يكون الحقل :attribute صورةً',
    'in' => 'الحقل :attribute لاغٍ',
    'in_array' => 'الحقل :attribute غير موجود في :other.',
    'integer' => 'يجب أن يكون الحقل :attribute عددًا صحيحًا',
    'ip' => 'يجب أن يكون الحقل :attribute عنوان IP ذا بُنية صحيحة',
    'ipv4' => 'يجب أن يكون الحقل :attribute عنوان IPv4 ذا بنية صحيحة.',
    'ipv6' => 'يجب أن يكون الحقل :attribute عنوان IPv6 ذا بنية صحيحة.',
    'json' => 'يجب أن يكون الحقل :attribute نصا من نوع JSON.',
    'lowercase' => 'الحقل :attribute يجب ان يتكون من حروف صغيرة',
    'lt' => [
        'array' => 'الـ :attribute يجب ان يحتوي علي اقل من :value عناصر/عنصر.',
        'file' => 'الـ :attribute يجب ان يكون اقل من :value كيلو بايت.',
        'numeric' => 'الـ :attribute يجب ان يكون اقل من :value.',
        'string' => 'الـ :attribute يجب ان يكون اقل من :value حروفٍ/حرفًا.',
    ],
    'lte' => [
        'array' => 'الـ :attribute يجب ان يحتوي علي اكثر من :value عناصر/عنصر.',
        'file' => 'الـ :attribute يجب ان يكون اقل من او يساوي :value كيلو بايت.',
        'numeric' => 'الـ :attribute يجب ان يكون اقل من او يساوي :value.',
        'string' => 'الـ :attribute يجب ان يكون اقل من او يساوي :value حروفٍ/حرفًا.',
    ],
    'mac_address' => 'يجب أن يكون الحقل :attribute عنوان MAC ذا بنية صحيحة.',
    'max' => [
        'array' => 'يجب أن لا يحتوي الحقل :attribute على أكثر من :max عناصر/عنصر.',
        'file' => 'يجب أن لا يتجاوز حجم الملف :attribute :max كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute مساوية أو أصغر لـ :max.',
        'string' => 'يجب أن لا يتجاوز طول نص :attribute :max حروفٍ/حرفًا',
    ],
    'max_digits' => 'الحقل :attribute يجب ألا يحتوي أكثر من :max أرقام.',
    'mimes' => 'يجب أن يكون الحقل ملفًا من نوع : :values.',
    'mimetypes' => 'يجب أن يكون الحقل ملفًا من نوع : :values.',
    'min' => [
        'array' => 'يجب أن يحتوي الحقل :attribute على الأقل على :min عُنصرًا/عناصر',
        'file' => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute مساوية أو أكبر لـ :min.',
        'string' => 'يجب أن يكون طول نص :attribute على الأقل :min حروفٍ/حرفًا',
    ],
    'min_digits' => 'الحقل :attribute يجب أن يحتوي :min أرقام على الأقل.',
    'multiple_of' => 'الحقل :attribute يجب أن يكون من مضاعفات :value.',
    'not_in' => 'الحقل :attribute لاغٍ',
    'not_regex' => 'الحقل :attribute نوعه لاغٍ',
    'numeric' => 'يجب على الحقل :attribute أن يكون رقمًا',
    'password' => [
        'letters' => 'يجب ان يشمل حقل :attribute على حرف واحد على الاقل.',
        'mixed' => 'يجب ان يشمل حقل :attribute على حرف واحد بصيغة كبيرة على الاقل وحرف اخر بصيغة صغيرة.',
        'numbers' => 'يجب ان يشمل حقل :attribute على رقم واحد على الاقل.',
        'symbols' => 'يجب ان يشمل حقل :attribute على رمز واحد على الاقل.',
        'uncompromised' => 'حقل :attribute تبدو غير آمنة. الرجاء اختيار قيمة اخرى.',
    ],
    'present' => 'يجب تقديم الحقل :attribute',
    'prohibited' => 'حقل :attribute محظور',
    'prohibited_if' => 'حقل :attribute محظور في حال ما إذا كان :other يساوي :value.',
    'prohibited_unless' => 'حقل :attribute محظور في حال ما لم يكون :other يساوي :value.',
    'prohibits' => 'حقل :attribute يحظر :other من اي يكون موجود',
    'regex' => 'صيغة الحقل :attribute .غير صحيحة',
    'required' => 'حقل :attribute مطلوب.',
    'required_array_keys' => 'الحقل :attribute يجب ان يحتوي علي مدخلات للقيم التالية :values.',
    'required_if' => 'الحقل :attribute مطلوب في حال ما إذا كان :other يساوي :value.',
    'required_if_accepted' => 'The :attribute field is required when :other is accepted.',
    'required_unless' => 'الحقل :attribute مطلوب في حال ما لم يكن :other يساوي :values.',
    'required_with' => 'الحقل :attribute إذا توفّر :values.',
    'required_with_all' => 'الحقل :attribute إذا توفّر :values.',
    'required_without' => 'الحقل :attribute إذا لم يتوفّر :values.',
    'required_without_all' => 'الحقل :attribute إذا لم يتوفّر :values.',
    'same' => 'يجب أن يتطابق الحقل :attribute مع :other',
    'size' => [
        'array' => 'يجب أن يحتوي الحقل :attribute على :size عنصرٍ/عناصر بالظبط',
        'file' => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت',
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute مساوية لـ :size',
        'string' => 'يجب أن يحتوي النص :attribute على :size حروفٍ/حرفًا بالظبط',
    ],
    'starts_with' => 'الحقل :attribute يجب ان يبدأ بأحد القيم التالية: :values.',
    'string' => 'يجب أن يكون الحقل :attribute نصآ.',
    'timezone' => 'يجب أن يكون :attribute نطاقًا زمنيًا صحيحًا',
    'unique' => 'قيمة الحقل :attribute مُستخدمة من قبل',
    'uploaded' => 'فشل في تحميل الـ :attribute',
    'uppercase' => 'The :attribute must be uppercase.',
    'url' => 'صيغة الرابط :attribute غير صحيحة',
    'uuid' => 'الحقل :attribute يجب ان ايكون رقم UUID صحيح.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name'                  => 'الاسم',
        'username'              => 'اسم المُستخدم',
        'email'                 => 'البريد الالكتروني',
        'first_name'            => 'الاسم',
        'last_name'             => 'اسم العائلة',
        'password'              => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'city'                  => 'المدينة',
        'country'               => 'الدولة',
        'address'               => 'العنوان',
        'phone'                 => 'الهاتف',
        'mobile'                => 'الجوال',
        'age'                   => 'العمر',
        'sex'                   => 'الجنس',
        'gender'                => 'النوع',
        'day'                   => 'اليوم',
        'month'                 => 'الشهر',
        'year'                  => 'السنة',
        'hour'                  => 'ساعة',
        'minute'                => 'دقيقة',
        'second'                => 'ثانية',
        'content'               => 'المُحتوى',
        'description'           => 'الوصف',
        'excerpt'               => 'المُلخص',
        'date'                  => 'التاريخ',
        'time'                  => 'الوقت',
        'available'             => 'مُتاح',
        'size'                  => 'الحجم',
        'price'                 => 'السعر',
        'desc'                  => 'نبذه',
        'title'                 => 'العنوان',
        'q'                     => 'البحث',
        'link'                  => ' ',
        'slug'                  => ' ',
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // أضف ترجمة حقولك هنا
        'station_code' => 'كود المحطة',
        'station_type' => 'نوع المحطة',
        'station_capacity' => 'سعة المحطة',
        'station_location' => 'موقع المحطة',
        'station_address' => 'عنوان المحطة',
        'station_description' => 'وصف المحطة',
        'station_image' => 'صورة المحطة',
        'station_latitude' => 'خط العرض للمحطة',
        'station_longitude' => 'خط الطول للمحطة',
        'station_phone' => 'هاتف المحطة',
        'station_email' => 'البريد الإلكتروني للمحطة',
        'station_manager' => 'مدير المحطة',
        'station_manager_phone' => 'هاتف مدير المحطة',
        'station_manager_email' => 'البريد الإلكتروني لمدير المحطة',
        'station_capacity_unit' => 'وحدة سعة المحطة',
        'station_status' => 'حالة المحطة',
        'station_start_date' => 'تاريخ بدء تشغيل المحطة',
        'station_name' => 'اسم المحطة',
        'town_id' => 'البلدة',

        'operational_status' => 'حالة التشغيل',

        'energy_source' => 'مصدر الطاقة',
        'energy_consumption' => 'استهلاك الطاقة',
        'energy_consumption_unit' => 'وحدة استهلاك الطاقة',
        'operator_id' => 'معرّف جهة التشغيل',
        'operator_type' => 'نوع جهة التشغيل',
        'operator_address' => 'عنوان جهة التشغيل',
        'operator_phone' => 'هاتف جهة التشغيل',
        'operator_email' => 'البريد الإلكتروني لجهة التشغيل',
        'operator_contact_person' => 'شخص الاتصال لجهة التشغيل',
        'operator_contact_phone' => 'هاتف شخص الاتصال لجهة التشغيل',
        'operator_entity' => 'جهة التشغيل',
        'operator_entity_type' => 'نوع جهة التشغيل',
        'operator_entity_address' => 'عنوان جهة التشغيل',
        'operator_name' => 'اسم جهة التشغيل',
        'network_readiness_percentage' => 'نسبة جاهزية الشبكة',
        'network_type' => 'نوع الشبكة',
        
        'water_source' => 'مصدر المياه',
        'water_quality' => 'جودة المياه',
  // حقول عامة ومتكررة
    'id' => 'المعرف',
    'name' => 'الاسم',
    'description' => 'الوصف',
    'notes' => 'ملاحظات',
    'general_notes' => 'ملاحظات عامة',
    'type' => 'النوع',
    'date' => 'التاريخ',
    'start' => 'بداية',
    'end' => 'نهاية',
    'properties' => 'الخصائص',
    'operational_status' => 'الحالة التشغيلية',
    'stop_reason' => 'سبب التوقف',
    'capacity' => 'السعة',
    'readiness_percentage' => 'نسبة الجاهزية',
    'latitude' => 'خط العرض',
    'longitude' => 'خط الطول',
    'created_at' => 'تاريخ الإنشاء',
    'updated_at' => 'تاريخ التحديث',
    
    //
    // جداول المستخدمين والصلاحيات
    //
    'users' => 'المستخدمون',
    'user' => 'المستخدم',
    'email' => 'البريد الإلكتروني',
    'password' => 'كلمة المرور',
    'role_id' => 'الصلاحية',
    'activity_log' => 'سجل النشاط',
    'log_name' => 'اسم السجل',
    'subject_type' => 'نوع السجل',
    'event' => 'الحدث',
    'subject_id' => 'معرف السجل',
    'causer_type' => 'نوع المتسبب',
    'causer_id' => 'معرف المتسبب',

    //
    // جداول الوحدات والمواقع
    //
    'units' => 'الوحدات',
    'unit' => 'الوحدة',
    'unit_name' => 'اسم الوحدة',
    'unit_id' => 'معرف الوحدة',
    'governorate_id' => 'المحافظة',
    'towns' => 'البلدات',
    'town' => 'البلدة',
    'town_name' => 'اسم البلدة',
    'town_code' => 'كود البلدة',
    'town_id' => 'معرف البلدة',
    'town_supply' => 'البلدات التي يغذيها',

    //
    // جداول المحطات وملحقاتها
    //
    'station_id' => 'المحطة',
    'station_code' => 'كود المحطة',
    'distance_from_station' => 'المسافة عن المحطة (متر)',
    'pumping_sectors' => 'قطاعات الضخ',
    'sector_name' => 'اسم القطاع',

    // الخزانات
    'diesel_tanks' => 'خزانات الديزل',
    'elevated_tanks' => 'الخزانات العالية',
    'tank_name' => 'اسم الخزان',
    'tank_capacity' => 'سعة الخزان',
    'building_entity' => 'الجهة المنفذة',
    'construction_date' => 'تاريخ الإنشاء',
    'height' => 'الارتفاع (متر)',
    'tank_shape' => 'شكل الخزان',
    'feeding_station' => 'المحطة المغذية',
    'in_pipe_diameter' => 'قطر أنبوب الدخول',
    'out_pipe_diameter' => 'قطر أنبوب الخروج',
    'altitude' => 'الارتفاع عن سطح البحر',
    'precision' => 'الدقة',

    // الكهرباء
    'electricity_hours' => 'ساعات الكهرباء',
    'electricity_hour_number' => 'رقم ساعة الكهرباء',
    'meter_type' => 'نوع الساعة',
    'operating_entity' => 'جهة التشغيل',
    'electricity_transformers' => 'محولات الكهرباء',
    'transformer_capacity' => 'استطاعة المحولة (KVA)',
    'is_station_transformer' => 'هل المحولة خاصة بالمحطة؟',
    'talk_about_station_transformer' => 'حديث عن محولة المحطة',
    'is_capacity_sufficient' => 'هل الاستطاعة كافية؟',
    'how_mush_capacity_need' => 'كم الاستطاعة المطلوبة',

    // الفلاتر
    'filters' => 'الفلاتر',
    'filter_capacity' => 'قدرة الفلتر',
    'readiness_status' => 'حالة الجاهزية',
    'filter_type' => 'نوع الفلتر',

    // مجموعات التوليد
    'generation_groups' => 'مجموعات التوليد',
    'generator_name' => 'اسم المولدة',
    'generation_capacity' => 'استطاعة التوليد (KVA)',
    'actual_operating_capacity' => 'الاستطاعة الفعلية للتشغيل',
    'generation_group_readiness_percentage' => 'نسبة جاهزية مجموعة التوليد',
    'fuel_consumption' => 'صرف الوقود (لتر/ساعة)',
    'oil_usage_duration' => 'مدة تغيير الزيت (ساعة)',
    'oil_quantity_for_replacement' => 'كمية الزيت اللازمة للتبديل',

    // الآبار
    'wells' => 'الآبار',
    'water_wells' => 'آبار المياه (مناطق البيع)',
    'well_name' => 'اسم البئر',
    'well_status' => 'حالة البئر',
    'well_type' => 'نوع البئر',
    'well_flow' => 'غزارة البئر',
    'static_depth' => 'العمق الستاتيكي',
    'dynamic_depth' => 'العمق الديناميكي',
    'drilling_depth' => 'عمق الحفر',
    'well_diameter' => 'قطر البئر',
    'pump_installation_depth' => 'عمق تنزيل المضخة',
    'pump_capacity' => 'استطاعة المضخة',
    'actual_pump_flow' => 'الغزارة الفعلية للمضخة',
    'pump_lifting' => 'رفع المضخة',
    'pump_brand_model' => 'ماركة وموديل المضخة',
    'energy_source' => 'مصدر الطاقة',
    'well_address' => 'عنوان البئر',
    'well_location' => 'موقع البئر',
    'has_flow_meter' => 'هل يوجد عداد تدفق؟',
    'flow_meter_start' => 'قراءة العداد الأولية',
    'flow_meter_end' => 'قراءة العداد النهائية',
    'water_sold_quantity' => 'كمية المياه المباعة',
    'water_price' => 'سعر المياه',
    'total_amount' => 'المبلغ الإجمالي',
    'has_vehicle_filling' => 'هل يوجد تعبئة صهاريج؟',
    'vehicle_filling_quantity' => 'كمية تعبئة الصهاريج',
    'has_free_filling' => 'هل يوجد تعبئة مجانية؟',
    'free_filling_quantity' => 'كمية التعبئة المجانية',
    'entity_for_free_filling' => 'الجهة المستفيدة من التعبئة المجانية',
    'document_number' => 'رقم الوثيقة',

    //
    // جداول التقارير
    //
    'station_reports' => 'تقارير المحطات',
    'weekly_reports' => 'التقارير الأسبوعية',
    'report_date' => 'تاريخ التقرير',
    'sender_name' => 'اسم المرسل',
    'maintenance_works' => 'أعمال الصيانة',
    'maintenance_entity' => 'الجهة المنفذة للصيانة',
    'maintenance_image' => 'صورة عن الصيانة',
    'administrative_works' => 'أعمال إدارية',
    'administrative_image' => 'صورة عن الأعمال الإدارية',
    'additional_notes' => 'ملاحظات إضافية',

    // حقول خاصة بالتقارير اليومية
    'operator_company' => 'الشركة المشغلة',
    'operating_wells_count' => 'عدد الآبار العاملة',
    'well_1_hours' => 'ساعات البئر 1',
    'well_2_hours' => 'ساعات البئر 2',
    'well_3_hours' => 'ساعات البئر 3',
    'well_4_hours' => 'ساعات البئر 4',
    'well_5_hours' => 'ساعات البئر 5',
    'well_6_hours' => 'ساعات البئر 6',
    'well_7_hours' => 'ساعات البئر 7',
    'total_well_hours' => 'إجمالي ساعات تشغيل الآبار',
    'has_horizontal_pump' => 'هل توجد مضخة أفقية؟',
    'horizontal_pump_hours' => 'ساعات عمل المضخة الأفقية',
    'station_operation_method' => 'طريقة تشغيل المحطة',
    'target_sector' => 'القطاع المستهدف',
    'has_disinfection' => 'هل يوجد تعقيم؟',
    'no_disinfection_reason' => 'سبب عدم وجود تعقيم',
    'solar_electricity_hours' => 'ساعات (طاقة شمسية وكهرباء)',
    'solar_generator_hours' => 'ساعات (طاقة شمسية ومولدة)',
    'solar_only_hours' => 'ساعات (طاقة شمسية فقط)',
    'electricity_consumption_kwh' => 'استهلاك الكهرباء (كيلوواط)',
    'electric_meter_before' => 'قراءة عداد الكهرباء (قبل)',
    'electric_meter_after' => 'قراءة عداد الكهرباء (بعد)',
    'generator_hours' => 'ساعات عمل المولدة',
    'diesel_consumption' => 'استهلاك الديزل (لتر)',
    'oil_replacement' => 'هل تم تبديل زيت؟',
    'oil_quantity' => 'كمية الزيت',
    'water_pumped_m3' => 'كمية المياه المضخوخة (م³)',
    'total_diesel_stock' => 'إجمالي مخزون الديزل',
    'diesel_received' => 'هل تم استلام ديزل؟',
    'new_diesel_quantity' => 'كمية الديزل الجديدة',
    'diesel_provider' => 'مصدر الديزل',
    'station_modification' => 'هل يوجد تعديل بالمحطة؟',
    'modification_location' => 'مكان التعديل',
    'modification_details' => 'تفاصيل التعديل',
    'transfer_destination' => 'الجهة المحول لها',
    'electric_meter_charged' => 'هل تم شحن عداد الكهرباء؟',
    'charged_electricity_kwh' => 'كمية الكهرباء المشحونة (كيلوواط)',
    'operator_notes' => 'ملاحظات المشغل',
    
    ],

];
