<?php

namespace App\Enum;

enum OperatingEntityName: string
{
    // case WATER_COMPANY = 'water_company'; // المؤسسة العامة لمياه الشرب

    // --- English Organizations ---
    case WATER_COMPANY = 'water_company';
    case ACTED = 'acted';
    case ACTION_FOR_HUMANITY = 'action_for_humanity';
    case ACU = 'acu';
    case ADRA = 'adra';
    case AFAD = 'afad';
    case ARHE_NOVA = 'arhe_nova';
    case BAHAR = 'bahar';
    case ERT = 'emergency_response_team';
    case GOAL = 'goal';
    case HAND_IN_HAND = 'hand_in_hand';
    case HORIZON_HUMANITARIAN = 'horizon_humanitarian';
    case HUMAN_APPEAL = 'human_appeal';
    case HCR = 'humanitarian_care_relief';
    case IHH = 'ihh';
    case IRC = 'irc';
    case IYD = 'iyd';
    case MEDGLOBAL = 'medglobal';
    case MENTOR = 'mentor';
    case MERCY_CORPS = 'mercy_corps';
    case MERCY_USA = 'mercy_usa';
    case NEWDAY = 'newday';
    case NRC = 'nrc';
    case ORANGE = 'orange';
    case PEOPLE_IN_NEED = 'people_in_need';
    case POINT = 'point';
    case REACH = 'reach';
    case SAMS = 'sams';
    case SARD = 'sard';
    case SDI = 'sdi';
    case SEMA = 'sema';
    case SENED = 'sened';
    case SOLIDARITES_INTERNATIONAL = 'solidarites_international';
    case SRD = 'srd';
    case SSCH = 'ssch';
    case SYRIA_CHARITY = 'syria_charity';
    case SYRIA_RELIEF = 'syria_relief';
    case UOSSM = 'uossm';
    case WORLD_VISION = 'world_vision';
    
    // --- Arabic Organizations ---
    case ISLAMIC_RELIEF = 'islamic_relief'; // الإغاثة الإسلامية
    case INTERNATIONAL_RELIEF = 'international_relief'; // الإغاثة الدولية
    case INTERNATIONAL_MEDICAL = 'international_medical'; // الأطباء الدوليين
    case AL_AMAL = 'al_amal'; // الأمل الخيرية
    case WHITE_HANDS = 'white_hands'; // الأيادي البيضاء
    case AYADE = 'ayade'; // الأيادي للإغاثة والتنمية
    case AL_BIR_WA_IHSAN = 'al_bir_wa_ihsan'; // البر و الإحسان
    case AL_BASHIR = 'al_bashir'; // البشير
    case AL_BUNYAN_AL_MARSUS = 'al_bunyan_al_marsus'; // البنيان المرصوص
    case SYRIAN_RELIEF_ASSOCIATION = 'syrian_relief_association'; // الجمعية السورية للإغاثة والتنمية
    case WHITE_HELMETS = 'white_helmets'; // الدفاع المدني السوري
    case RAHMA_VOLUNTEER = 'rahma_volunteer'; // الرحمة التطوعي / مزن
    case AL_RISALA = 'al_risala'; // الرسالة / جمعية إيلاف
    case ALSERAJ = 'al_seraj'; // السراج
    case ALSALAM = 'al_salam'; // السلام
    case ALAEILA = 'alaeila'; // العائلة
    case FRENCH_RELIEF = 'french_relief'; // الفرنسية للإغاثة
    case BIG_HEART = 'big_heart'; // القلب الكبير
    case GLOBAL_COMMUNITIES = 'global_communities'; // المجتمعات العالمية
    case MEDICAL = 'medical'; // الميديكال
    case ISLAMIC_RENAISSANCE = 'islamic_renaissance'; // النهضة الإسلامية
    case AL_HUDA_BARAKA = 'al_huda_baraka'; // الهدى/ بركة
    case SARC = 'sarc'; // الهلال الأحمر العربي السوري
    case TURKISH_REDCRESCENT = 'turkish_red_crescent'; // الهلال التركي
    case QATARI_REDCRESCENT = 'qatari_red_crescent'; // الهلال القطري
    case SYRIAN_RESCUE_AGENCY = 'syrian_rescue_agency'; // الوكالة السورية للانقاذ
    case AMESA = 'amesa'; // اميسا
    case ANSOR = 'ansor'; // انصر
    case IHSAN = 'ihsan'; // إحسان
    case IHSAN_GERMAN = 'ihsan_german'; // إحسان الألماني
    case EMAR = 'emar'; // إعمار
    case IQRA = 'iqra'; // إقرأ / بيرامة
    case IMDAD = 'imdad'; // إمداد
    case ELAF = 'elaf'; // إيلاف
    case DOCTORS_WORLD = 'doctors_world'; // أطباء العالم
    case MSF_SPAIN = 'msf_spain'; // أطباء بلا حدود / اسبانيا
    case MSF_BELGIUM = 'msf_belgium'; // أطباء بلا حدود / بلجيكيا
    case MSF_FRANCE = 'msf_france'; // أطباء بلا حدود / فرنسا
    case DOCTORS_ACROSS_CONTINENTS = 'doctors_across_continents'; // أطباء عبر القارات
    case DOCTORS_SYRIA = 'doctors_syria'; // أطباء في سوريا
    case UMMA = 'umma'; // أمة الخيرية
    case AMAL_DEVELOPMENT = 'amal_development'; // أمل للإغاثة والتنمية
    case AHL_AL_HADITH = 'ahl_al_hadith'; // أهل الحديث
    case AFAQ = 'afaq'; // آفاق للتطوير والتغير
    case BASMA = 'basma'; // بسمة
    case BASMAT_AMAL = 'basmat_amal'; // بسمة أمل
    case BALAD = 'balad'; // بلد
    case BINA = 'bina'; // بناء للتنمية
    case BANAFSAJ = 'banafsaj'; // بنفسج
    case BUNYAN = 'bunyan'; // بنيان
    case BIRAMA = 'birama'; // بيرامة
    case TAKAFUL_ALSHAM = 'takafal_alsham'; // تكافل الشام
    case TAKAFUL_AUSTRIA = 'takafal_austria'; // تكافل النمسا
    case HAQ_ASSOCIATION = 'haq_association'; // جمعية حق
    case CHILDHOOD_PROTECTORS = 'childhood_protectors'; // حراس الطفولة
    case HIFZ_ALNIMA = 'hifz_alnima'; // حفظ النعمة
    case SYRIAN_ENV_PROTECTION = 'syrian_env_protection'; // حماية البيئة السورية
    case KHEIR_UMMA = 'kheir_umma'; // خير أمة
    case DARNA = 'darna'; // دارنا
    case HOURAN_ASSOCIATION = 'houran_association'; // رابطة أهل حوران
    case RAHMA_HUMANITY = 'rahma_humanity'; // رحمة الإنسانية
    case RAHMA_WORLD = 'rahma_world'; // رحمة العالمية
    case RAHMA_LIMITLESS = 'rahma_limitless'; // رحمة بلا حدود
    case RAHMA_AROUND_WORLD = 'rahma_around_world'; // رحمة حول العالم
    case RASHA_SHAAM = 'rasha_shaam'; // رسالة الشام الإنسانية
    case SAED = 'saed'; // ساعد
    case SAKHAA_WA_ATAA = 'sakhaw_ataa'; // سخاء وعطاء
    case SADAD = 'sadad'; // سداد
    case SANAD = 'sanad'; // سند الخيرية
    case SYRIA_AID = 'syria_aid'; // سوريا للمساعدات الإنسانية و التنمية
    case SHAM_INSANIYA = 'sham_insaniya'; // شام الإنسانية
    case SHAM_KHEIR = 'sham_kheir'; // شام الخير
    case SHAM_SHARIF = 'sham_sharif'; // شام شريف
    case SHAM_ORPHANS = 'sham_orphans'; // شام للأيتام
    case SHAFAQ = 'shafaq'; // شفق
    case SILK_ROAD = 'silk_road'; // طريق الحرير
    case ATAA_ATMA = 'ataa_atma'; // عطاء / أطمة
    case ATAA_SARMADA = 'ataa_sarmada'; // عطاء / سرمدا
    case GRASS_KHEIR = 'grass_kheir'; // غراس الخير
    case GRASS_NAHDA = 'grass_nahda'; // غراس النهضة
    case GHASAQ = 'ghasaq'; // غسق
    case GHOSN_ZAYTOON = 'ghosn_zaytoon'; // غصن الزيتون
    case FAEIL_KHEIR = 'faeil_kheir'; // فاعل خير
    case FATH_DAR = 'fath_dar'; // فتح دار
    case QATAR_CHARITY = 'qatar_charity'; // قطر الخيرية
    case KAHATAYN = 'kahatayn'; // كهاتين
    case MADAD = 'madad'; // مداد الإنسانية
    case MARAM = 'maram'; // مرام
    case MARJ_DABIQ = 'marj_dabiq'; // مرج دابق
    case MUZN = 'muzn'; // مزن
    case PEACE_SPACE = 'peace_space'; // مساحة السلام
    case MASAR = 'masar'; // مسار
    case MASRAT = 'masrat'; // مسرات
    case MASEK = 'masek'; // مسك
    case MUDMAR = 'mudmar'; // مضمار / صنوبر
    case MATAR = 'matar'; // مطر
    case CHILDCARE = 'childcare'; // مكتب رعاية الطفولة و الامومة
    case MOLHAM = 'molham'; // ملهم التطوعي
    case MINBAR_ALSHAAM = 'minbar_alshaam'; // منبر الشام
    case WOMEN_FOUNDATION = 'women_foundation'; // مؤسسة المرأة السورية
    case SKT = 'skt'; // مؤسسة سكن للرعاية والتنمية
    case NASAEM_ALKHEIR = 'nasaem_alkheir'; // نسائم الخير
    case HAYATI = 'hayati'; // هذه حياتي
    case IHR = 'ihr'; // هيئة الإغاثة الإنسانية
    case HRC = 'hrc'; // هيئة العمل الإنساني
    case PALESTINE = 'palestine'; // هيئة فلسطين الخيرية
    case WATAN = 'watan'; // وطن
    case TURKEY_WAQF = 'turkey_waqf'; // وقف الديانة التركي
    case ENVIRONMENT_AGENCY = 'environment_agency'; // وكالة حماية البيئة

    public static function getValues(): array
    {
        return array_map(static fn(self $case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return config('organizations.' . $this->value . '.label', $this->value);
    }

    public function getColor(): string
    {
        return config('organizations.' . $this->value . '.color', 'primary');
    }
}
