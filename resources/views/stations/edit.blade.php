<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل بيانات المحطة: {{ $station->station_name }}</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>عفوًا! هناك بعض الأخطاء في الإدخال:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('stations.update', $station->id) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">

                    <!-- ======================= الكرت الأول: المعلومات الأساسية ======================= -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-primary">المعلومات الأساسية</div>
                            <div class="card-body">
                                <label for="station_code">كود المحطة<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="station_code"
                                    value="{{ old('station_code', $station->station_code) }}" required>

                                <label for="station_name">اسم المحطة<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="station_name"
                                    value="{{ old('station_name', $station->station_name) }}" required>

                                <label for="operational_status">حالة التشغيل<span class="text-danger">*</span></label>
                                <select name="operational_status" class="form-control" required>
                                    <option value="عاملة"
                                        {{ old('operational_status', $station->operational_status) == 'عاملة' ? 'selected' : '' }}>
                                        عاملة</option>
                                    <option value="متوقفة"
                                        {{ old('operational_status', $station->operational_status) == 'متوقفة' ? 'selected' : '' }}>
                                        متوقفة</option>
                                    <option value="خارج الخدمة"
                                        {{ old('operational_status', $station->operational_status) == 'خارج الخدمة' ? 'selected' : '' }}>
                                        خارج الخدمة</option>
                                </select>

                                <label for="stop_reason">سبب التوقف</label>
                                <input type="text" class="form-control" name="stop_reason"
                                    value="{{ old('stop_reason', $station->stop_reason) }}"
                                    placeholder="أدخل سبب التوقف (إن وجد)">

                                <label for="town_id">البلدة<span class="text-danger">*</span></label>
                                <select name="town_id" class="form-control" required>
                                    <option value="">-- اختر البلدة --</option>
                                    @foreach ($towns as $town)
                                        <option value="{{ $town->id }}"
                                            {{ old('town_id', $station->town_id) == $town->id ? 'selected' : '' }}>
                                            {{ $town->town_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ======================= الكرت الثاني: الطاقة والتشغيل ======================= -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-success">الطاقة والتشغيل</div>
                            <div class="card-body">
                                <label for="energy_source">مصدر الطاقة</label>
                                <select name="energy_source" class="form-control">
                                    <option value="">-- اختر مصدر الطاقة --</option>
                                    <option value="لا يوجد"
                                        {{ old('energy_source', $station->energy_source) == 'لا يوجد' ? 'selected' : '' }}>
                                        لا يوجد</option>
                                    <option value="كهرباء"
                                        {{ old('energy_source', $station->energy_source) == 'كهرباء' ? 'selected' : '' }}>
                                        كهرباء</option>
                                    <option value="مولدة"
                                        {{ old('energy_source', $station->energy_source) == 'مولدة' ? 'selected' : '' }}>
                                        مولدة</option>
                                    <option value="طاقة شمسية"
                                        {{ old('energy_source', $station->energy_source) == 'طاقة شمسية' ? 'selected' : '' }}>
                                        طاقة شمسية</option>
                                    <option value="كهرباء و مولدة"
                                        {{ old('energy_source', $station->energy_source) == 'كهرباء و مولدة' ? 'selected' : '' }}>
                                        كهرباء و مولدة</option>
                                    <option value="كهرباء و طاقة شمسية"
                                        {{ old('energy_source', $station->energy_source) == 'كهرباء و طاقة شمسية' ? 'selected' : '' }}>
                                        كهرباء و طاقة شمسية</option>
                                    <option value="مولدة و طاقة شمسية"
                                        {{ old('energy_source', $station->energy_source) == 'مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                        مولدة و طاقة شمسية</option>
                                    <option value="كهرباء و مولدة و طاقة شمسية"
                                        {{ old('energy_source', $station->energy_source) == 'كهرباء و مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                        كهرباء و مولدة و طاقة شمسية</option>
                                </select>

                                <label for="operator_entity">جهة التشغيل:</label>
                                <select name="operator_entity" class="form-control" id="operator_entity">
                                    <option value="تشغيل تشاركي"
                                        {{ old('operator_entity', $station->operator_entity) == 'تشغيل تشاركي' ? 'selected' : '' }}>
                                        تشغيل تشاركي</option>
                                    <option value="المؤسسة العامة لمياه الشرب"
                                        {{ old('operator_entity', $station->operator_entity) == 'المؤسسة العامة لمياه الشرب' ? 'selected' : '' }}>
                                        المؤسسة العامة لمياه الشرب</option>
                                </select>

                                <label for="operator_name">اسم جهة التشغيل</label>
                                <input type="text" class="form-control" name="operator_name" id="operator_name"
                                    value="{{ old('operator_name', $station->operator_name) }}"
                                    placeholder="أدخل اسم جهة التشغيل (إن وجد)">

                                <label for="general_notes">ملاحظات عامة</label>
                                <textarea name="general_notes" class="form-control" placeholder="أدخل ملاحظات عامة (إن وجد)">{{ old('general_notes', $station->general_notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ======================= الكرت الثالث: بيانات الشبكة ======================= -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-success">بيانات الشبكة</div>
                            <div class="card-body">
                                <label for="water_delivery_method">طريقة توصيل المياه</label>
                                <select name="water_delivery_method" class="form-control">
                                    <option value="">-- اختر طريقة التوصيل --</option>
                                    <option value="شبكة"
                                        {{ old('water_delivery_method', $station->water_delivery_method) == 'شبكة' ? 'selected' : '' }}>
                                        شبكة</option>
                                    <option value="منهل"
                                        {{ old('water_delivery_method', $station->water_delivery_method) == 'منهل' ? 'selected' : '' }}>
                                        منهل</option>
                                    <option value="شبكة و منهل"
                                        {{ old('water_delivery_method', $station->water_delivery_method) == 'شبكة و منهل' ? 'selected' : '' }}>
                                        شبكة و منهل</option>
                                </select>

                                <label for="network_readiness_percentage">نسبة جاهزية الشبكة (%)</label>
                                <input type="number" step="0.01" class="form-control"
                                    name="network_readiness_percentage"
                                    value="{{ old('network_readiness_percentage', $station->network_readiness_percentage) }}">

                                <label for="network_type">نوع الشبكة:</label>
                                <select name="network_type" id="network_type" class="form-control">
                                    <option value="">-- اختر نوع الشبكة --</option>
                                    <optgroup label="المواد الأساسية">
                                        <option value="بولي إيثيلين"
                                            {{ old('network_type', $station->network_type) == 'بولي إيثيلين' ? 'selected' : '' }}>
                                            بولي إيثيلين</option>
                                        <option value="حديد"
                                            {{ old('network_type', $station->network_type) == 'حديد' ? 'selected' : '' }}>
                                            حديد</option>
                                        <option value="فونط (حديد صب)"
                                            {{ old('network_type', $station->network_type) == 'فونط (حديد صب)' ? 'selected' : '' }}>
                                            فونط (حديد صب)</option>
                                        <option value="أترنيت"
                                            {{ old('network_type', $station->network_type) == 'أترنيت' ? 'selected' : '' }}>
                                            أترنيت</option>
                                        <option value="PVC"
                                            {{ old('network_type', $station->network_type) == 'PVC' ? 'selected' : '' }}>
                                            PVC</option>
                                    </optgroup>
                                    <optgroup label="التوليفات الشائعة">
                                        <option value="بولي إيثيلين و حديد"
                                            {{ old('network_type', $station->network_type) == 'بولي إيثيلين و حديد' ? 'selected' : '' }}>
                                            بولي إيثيلين و حديد</option>
                                        <option value="بولي إيثيلين و فونط"
                                            {{ old('network_type', $station->network_type) == 'بولي إيثيلين و فونط' ? 'selected' : '' }}>
                                            بولي إيثيلين و فونط</option>
                                        <option value="بولي إيثيلين و أترنيت"
                                            {{ old('network_type', $station->network_type) == 'بولي إيثيلين و أترنيت' ? 'selected' : '' }}>
                                            بولي إيثيلين و أترنيت</option>
                                        <option value="حديد و أترنيت"
                                            {{ old('network_type', $station->network_type) == 'حديد و أترنيت' ? 'selected' : '' }}>
                                            حديد و أترنيت</option>
                                        <option value="PVC و أترنيت"
                                            {{ old('network_type', $station->network_type) == 'PVC و أترنيت' ? 'selected' : '' }}>
                                            PVC و أترنيت</option>
                                        <option value="بولي إيثيلين و حديد و أترنيت"
                                            {{ old('network_type', $station->network_type) == 'بولي إيثيلين و حديد و أترنيت' ? 'selected' : '' }}>
                                            بولي إيثيلين و حديد و أترنيت</option>
                                    </optgroup>
                                    <optgroup label="أنواع أخرى">
                                        <option value="خط ضخ"
                                            {{ old('network_type', $station->network_type) == 'خط ضخ' ? 'selected' : '' }}>
                                            خط ضخ</option>
                                        <option value="غير محدد / أخرى"
                                            {{ old('network_type', $station->network_type) == 'غير محدد / أخرى' ? 'selected' : '' }}>
                                            غير محدد / أخرى</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ======================= الكرت الرابع: أرقام وإحصائيات المحطة ======================= -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-info">أرقام وإحصائيات المحطة</div>
                            <div class="card-body">
                                <label for="beneficiary_families_count">عدد الأسر المستفيدة</label>
                                <input type="number" class="form-control" name="beneficiary_families_count"
                                    value="{{ old('beneficiary_families_count', $station->beneficiary_families_count) }}">

                                <label for="has_disinfection">هل يوجد تعقيم؟<span class="text-danger">*</span></label>
                                <select name="has_disinfection" class="form-control" required>
                                    <option value="1"
                                        {{ old('has_disinfection', $station->has_disinfection) == 1 ? 'selected' : '' }}>
                                        نعم</option>
                                    <option value="0"
                                        {{ old('has_disinfection', $station->has_disinfection) == 0 ? 'selected' : '' }}>لا
                                    </option>
                                </select>

                                <label for="disinfection_reason">سبب عدم وجود تعقيم</label>
                                <input type="text" class="form-control" name="disinfection_reason"
                                    value="{{ old('disinfection_reason', $station->disinfection_reason) }}"
                                    placeholder="أدخل السبب (إن وجد)">

                                <label for="served_locations">المواقع المخدومة</label>
                                <textarea name="served_locations" class="form-control">{{ old('served_locations', $station->served_locations) }}</textarea>

                                <label for="actual_flow_rate">معدل التدفق الفعلي</label>
                                <input type="number" step="0.01" name="actual_flow_rate" class="form-control"
                                    value="{{ old('actual_flow_rate', $station->actual_flow_rate) }}">

                                <label for="station_type">نوع المحطة:</label>
                                <select name="station_type" class="form-control">
                                    <option value="محطة ابار ارتوازيه"
                                        {{ old('station_type', $station->station_type) == 'محطة ابار ارتوازيه' ? 'selected' : '' }}>
                                        محطة آبار ارتوازية</option>
                                    <option value="محطة رفع"
                                        {{ old('station_type', $station->station_type) == 'محطة رفع' ? 'selected' : '' }}>
                                        محطة رفع</option>
                                    <option value="محطة آبار سطحية"
                                        {{ old('station_type', $station->station_type) == 'محطة آبار سطحية' ? 'selected' : '' }}>
                                        محطة آبار سطحية</option>
                                    <option value="محطة ضخ"
                                        {{ old('station_type', $station->station_type) == 'محطة ضخ' ? 'selected' : '' }}>
                                        محطة ضخ</option>
                                    <option value="محطة ابار ارتوازيه ورفع"
                                        {{ old('station_type', $station->station_type) == 'محطة ابار ارتوازيه ورفع' ? 'selected' : '' }}>
                                        محطة آبار ارتوازية ورفع</option>
                                    <option value="محطة نبع"
                                        {{ old('station_type', $station->station_type) == 'محطة نبع' ? 'selected' : '' }}>
                                        محطة نبع</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ======================= الكرت الخامس: الموقع والأرض ======================= -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-warning">الموقع والأرض</div>
                            <div class="card-body">
                                <label for="detailed_address">العنوان التفصيلي</label>
                                <textarea name="detailed_address" class="form-control">{{ old('detailed_address', $station->detailed_address) }}</textarea>

                                <label for="land_area">مساحة الأرض (متر مربع)</label>
                                <input type="number" step="0.01" name="land_area" class="form-control"
                                    value="{{ old('land_area', $station->land_area) }}">

                                <label for="soil_type">نوع التربة:</label>
                                <select name="soil_type" id="soil_type" class="form-control">
                                    <option value="">-- اختر نوع التربة --</option>
                                    <option value="أرض زراعية"
                                        {{ old('soil_type', $station->soil_type) == 'أرض زراعية' ? 'selected' : '' }}>أرض
                                        زراعية</option>
                                    <option value="أرض صخرية"
                                        {{ old('soil_type', $station->soil_type) == 'أرض صخرية' ? 'selected' : '' }}>أرض
                                        صخرية</option>
                                </select>

                                <label for="building_notes">ملاحظات حول المبنى</label>
                                <textarea name="building_notes" class="form-control">{{ old('building_notes', $station->building_notes) }}</textarea>

                                <label for="latitude">خط العرض</label>
                                <input type="number" step="0.000001" name="latitude" class="form-control"
                                    value="{{ old('latitude', $station->latitude) }}">

                                <label for="longitude">خط الطول</label>
                                <input type="number" step="0.000001" name="longitude" class="form-control"
                                    value="{{ old('longitude', $station->longitude) }}">

                                <label for="is_verified">هل تم التحقق؟<span class="text-danger">*</span></label>
                                <select name="is_verified" class="form-control" required>
                                    <option value="1"
                                        {{ old('is_verified', $station->is_verified) == 1 ? 'selected' : '' }}>نعم</option>
                                    <option value="0"
                                        {{ old('is_verified', $station->is_verified) == 0 ? 'selected' : '' }}>لا</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div> <!-- نهاية cards-container -->

                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">تحديث
                    البيانات</button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const operatorEntitySelect = document.getElementById('operator_entity');
            const operatorNameInput = document.getElementById('operator_name');

            function updateOperatorName() {
                if (operatorEntitySelect.value === 'المؤسسة العامة لمياه الشرب') {
                    operatorNameInput.value = 'المؤسسة العامة لمياه الشرب';
                    operatorNameInput.readOnly = true;
                } else {
                    // في صفحة التعديل، لا نفرغ الحقل إذا كان المستخدم قد ملأه سابقاً
                    // إلا إذا كان النص هو نص المؤسسة
                    if (operatorNameInput.value === 'المؤسسة العامة لمياه الشرب') {
                        operatorNameInput.value = '';
                    }
                    operatorNameInput.readOnly = false;
                    operatorNameInput.placeholder = 'أدخل اسم جهة التشغيل (إن وجد)';
                }
            }
            operatorEntitySelect.addEventListener('change', updateOperatorName);

            // قم بتشغيل الوظيفة عند تحميل الصفحة لتطبيق المنطق فورًا
            updateOperatorName();
        });
    </script>
@endpush
