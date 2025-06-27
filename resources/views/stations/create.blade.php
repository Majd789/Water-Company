<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')


    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة محطة جديدة</h2>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <div class="card-body">
                    <form action="{{ route('stations.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" required>
                        <button type="submit" class="btn btn-primary">استيراد المحطات</button>
                    </form>
                </div>
            @endif

            <form action="{{ route('stations.store') }}" method="POST" class="login-form">
                @csrf
                <div class="cards-container">
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-primary">
                                المعلومات الأساسية
                            </div>
                            <!-- كود المحطة -->
                            <div class="card-body">
                                <label for="station_code">كود المحطة<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('station_code') is-invalid @enderror"
                                    id="station_code" name="station_code" placeholder="أدخل كود المحطة" required>
                                @error('station_code')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- اسم المحطة -->
                                <label for="station_name">اسم المحطة<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('station_name') is-invalid @enderror"
                                    id="station_name" name="station_name" placeholder="أدخل اسم المحطة" required>
                                @error('station_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- حالة التشغيل -->
                                <label for="operational_status">حالة التشغيل<span class="text-danger">*</span></label>
                                <select name="operational_status"
                                    class="form-control @error('operational_status') is-invalid @enderror" required>
                                    <option value="عاملة">عاملة</option>
                                    <option value="متوقفة">متوقفة</option>
                                    <option value="خارج الخدمة">خارج الخدمة</option>
                                </select>
                                @error('operational_status')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <input type="text" class="form-control" name="stop_reason"
                                    placeholder="أدخل سبب التوقف (إن وجد)">
                                <!-- البلدة -->
                                <label for="town_id">البلدة<span class="text-danger">*</span></label>
                                <select name="town_id" class="form-control @error('town_id') is-invalid @enderror" required>
                                    <option value="">-- اختر البلدة --</option>
                                    @if (auth()->user()->unit_id)
                                        @foreach (auth()->user()->unit->towns as $town)
                                            <option value="{{ $town->id }}">{{ $town->town_name }}</option>
                                        @endforeach
                                    @else
                                        @foreach ($towns as $town)
                                            <option value="{{ $town->id }}">{{ $town->town_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('town_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 2: بيانات الحفر -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-success">
                                بيانات الطاقة والشبكة
                            </div>
                            <div class="card-body">
                                <!-- حقل مصدر الطاقة -->
                                <label for="energy_source">مصدر الطاقة</label>
                                <select name="energy_source"
                                    class="form-control @error('energy_source') is-invalid @enderror">
                                    <option value="">-- اختر مصدر الطاقة --</option>
                                    <option value="لا يوجد" {{ old('energy_source') == 'لا يوجد' ? 'selected' : '' }}>لا
                                        يوجد</option>
                                    <option value="كهرباء" {{ old('energy_source') == 'كهرباء' ? 'selected' : '' }}>كهرباء
                                    </option>
                                    <option value="مولدة" {{ old('energy_source') == 'مولدة' ? 'selected' : '' }}>مولدة
                                    </option>
                                    <option value="طاقة شمسية"
                                        {{ old('energy_source') == 'طاقة شمسية' ? 'selected' : '' }}>طاقة شمسية</option>
                                    <option value="كهرباء و مولدة"
                                        {{ old('energy_source') == 'كهرباء و مولدة' ? 'selected' : '' }}>كهرباء و مولدة
                                    </option>
                                    <option value="كهرباء و طاقة شمسية"
                                        {{ old('energy_source') == 'كهرباء و طاقة شمسية' ? 'selected' : '' }}>كهرباء و طاقة
                                        شمسية</option>
                                    <option value="مولدة و طاقة شمسية"
                                        {{ old('energy_source') == 'مولدة و طاقة شمسية' ? 'selected' : '' }}>مولدة و طاقة
                                        شمسية</option>
                                    <option value="كهرباء و مولدة و طاقة شمسية"
                                        {{ old('energy_source') == 'كهرباء و مولدة و طاقة شمسية' ? 'selected' : '' }}>
                                        كهرباء و مولدة و طاقة شمسية</option>
                                </select>
                                @error('energy_source')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror

                                <label for="operator_entity">جهة التشغيل:</label>
                                <select name="operator_entity"
                                    class="form-control @error('operator_entity') is-invalid @enderror"
                                    id="operator_entity">
                                    <option value="تشغيل تشاركي">تشغيل تشاركي</option>
                                    <option value="المؤسسة العامة لمياه الشرب">المؤسسة العامة لمياه الشرب</option>
                                </select>
                                @error('operator_entity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- حقل اسم جهة التشغيل -->
                                <input type="text" class="form-control @error('operator_name') is-invalid @enderror"
                                    name="operator_name" id="operator_name" placeholder="أدخل اسم جهة التشغيل (إن وجد)">
                                @error('operator_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- ملاحظات عامة -->
                                <textarea name="general_notes" class="form-control @error('general_notes') is-invalid @enderror"
                                    placeholder="أدخل ملاحظات عامة (إن وجد)"></textarea>
                                @error('general_notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                            </div>
                        </div>
                    </div>

                    <!-- الكرت 2: بيانات الحفر -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-success">
                                بيانات الشبكة
                            </div>
                            <div class="card-body">
                                <!-- طريقة توصيل المياه -->
                                <label for="water_delivery_method">طريقة توصيل المياه:</label>
                                <select name="water_delivery_method"
                                    class="form-control @error('water_delivery_method') is-invalid @enderror">
                                    <option value="">-- اختر طريقة التوصيل --</option>
                                    <option value="شبكة" {{ old('water_delivery_method') == 'شبكة' ? 'selected' : '' }}>
                                        شبكة</option>
                                    <option value="منهل" {{ old('water_delivery_method') == 'منهل' ? 'selected' : '' }}>
                                        منهل</option>
                                    <option value="شبكة و منهل"
                                        {{ old('water_delivery_method') == 'شبكة و منهل' ? 'selected' : '' }}>شبكة و منهل
                                    </option>
                                </select>
                                @error('water_delivery_method')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <!-- نسبة جاهزية الشبكة -->
                                <label for="network_readiness_percentage">نسبة جاهزية الشبكة</label>
                                <input type="number" step="0.01" name="network_readiness_percentage"
                                    class="form-control @error('network_readiness_percentage') is-invalid @enderror"
                                    placeholder="أدخل نسبة جاهزية الشبكة">
                                @error('network_readiness_percentage')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <label for="network_type">نوع الشبكة:</label>
                                <select name="network_type" id="network_type"
                                    class="form-control @error('network_type') is-invalid @enderror">
                                    <option value="">-- اختر نوع الشبكة --</option>
                                    <optgroup label="المواد الأساسية">
                                        <option value="بولي إيثيلين"
                                            {{ old('network_type') == 'بولي إيثيلين' ? 'selected' : '' }}>بولي إيثيلين
                                        </option>
                                        <option value="حديد" {{ old('network_type') == 'حديد' ? 'selected' : '' }}>حديد
                                        </option>
                                        <option value="فونط (حديد صب)"
                                            {{ old('network_type') == 'فونط (حديد صب)' ? 'selected' : '' }}>فونط (حديد صب)
                                        </option>
                                        <option value="أترنيت" {{ old('network_type') == 'أترنيت' ? 'selected' : '' }}>
                                            أترنيت</option>
                                        <option value="PVC" {{ old('network_type') == 'PVC' ? 'selected' : '' }}>PVC
                                        </option>
                                    </optgroup>
                                    <optgroup label="التوليفات الشائعة">
                                        <option value="بولي إيثيلين و حديد"
                                            {{ old('network_type') == 'بولي إيثيلين و حديد' ? 'selected' : '' }}>بولي
                                            إيثيلين و حديد</option>
                                        <option value="بولي إيثيلين و فونط"
                                            {{ old('network_type') == 'بولي إيثيلين و فونط' ? 'selected' : '' }}>بولي
                                            إيثيلين و فونط</option>
                                        <option value="بولي إيثيلين و أترنيت"
                                            {{ old('network_type') == 'بولي إيثيلين و أترنيت' ? 'selected' : '' }}>بولي
                                            إيثيلين و أترنيت</option>
                                        <option value="حديد و أترنيت"
                                            {{ old('network_type') == 'حديد و أترنيت' ? 'selected' : '' }}>حديد و أترنيت
                                        </option>
                                        <option value="PVC و أترنيت"
                                            {{ old('network_type') == 'PVC و أترنيت' ? 'selected' : '' }}>PVC و أترنيت
                                        </option>
                                        <option value="بولي إيثيلين و حديد و أترنيت"
                                            {{ old('network_type') == 'بولي إيثيلين و حديد و أترنيت' ? 'selected' : '' }}>
                                            بولي إيثيلين و حديد و أترنيت</option>
                                    </optgroup>
                                    <optgroup label="أنواع أخرى">
                                        <option value="خط ضخ" {{ old('network_type') == 'خط ضخ' ? 'selected' : '' }}>خط
                                            ضخ</option>
                                        <option value="غير محدد / أخرى"
                                            {{ old('network_type') == 'غير محدد / أخرى' ? 'selected' : '' }}>غير محدد /
                                            أخرى</option>
                                    </optgroup>
                                </select>
                                @error('network_type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- الكرت 3: بيانات المضخة -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-info">
                                ارقام المحطة
                            </div>
                            <div class="card-body">
                                <!-- عدد الأسر المستفيدة -->
                                <label for="beneficiary_families_count">عدد الأسر المستفيدة</label>
                                <input type="number" name="beneficiary_families_count"
                                    class="form-control @error('beneficiary_families_count') is-invalid @enderror"
                                    placeholder="أدخل عدد الأسر المستفيدة">
                                @error('beneficiary_families_count')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- هل يوجد تعقيم؟ -->
                                <label for="has_disinfection">هل يوجد تعقيم؟</label>
                                <select name="has_disinfection"
                                    class="form-control @error('has_disinfection') is-invalid @enderror">
                                    <option value="0">لا</option>
                                    <option value="1">نعم</option>
                                </select>
                                @error('has_disinfection')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- سبب عدم وجود تعقيم -->
                                <label for="disinfection_reason">سبب عدم وجود تعقيم (إن وجد)</label>
                                <input type="text" name="disinfection_reason"
                                    class="form-control @error('disinfection_reason') is-invalid @enderror"
                                    placeholder="أدخل سبب عدم وجود تعقيم (إن وجد)">
                                @error('disinfection_reason')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- المواقع المخدومة -->
                                <label for="served_locations">المواقع المخدومة</label>
                                <textarea name="served_locations" class="form-control" placeholder="أدخل المواقع المخدومة"></textarea>

                                <!-- معدل التدفق الفعلي -->
                                <label for="actual_flow_rate">معدل التدفق الفعلي</label>
                                <input type="number" step="0.01" name="actual_flow_rate" class="form-control"
                                    placeholder="أدخل معدل التدفق الفعلي">

                                <!-- نوع المحطة -->
                                <label for="station_type">نوع المحطة:</label>
                                <select name="station_type" class="form-control">
                                    <option value="محطة ابار ارتوازيه">محطة آبار ارتوازية</option>
                                    <option value="محطة رفع">محطة رفع</option>
                                    <option value="محطة آبار سطحية">محطة آبار سطحية</option>
                                    <option value="محطة ضخ">محطة ضخ</option>
                                    <option value="محطة ابار ارتوازيه ورفع">محطة آبار ارتوازية ورفع</option>
                                    <option value="محطة نبع">محطة نبع</option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <!-- الكرت 4: تدفق البئر -->
                    <div class="card-box">
                        <div class="card">
                            <div class="card-header bg-warning">
                                الموقع الارض
                            </div>
                            <div class="card-body">
                                <!-- العنوان التفصيلي -->
                                <label for="detailed_address">العنوان التفصيلي</label>
                                <textarea name="detailed_address" class="form-control" placeholder="أدخل العنوان التفصيلي"></textarea>

                                <!-- مساحة الأرض -->
                                <label for="land_area">مساحة الأرض</label>
                                <input type="number" step="0.01" name="land_area" class="form-control"
                                    placeholder="أدخل مساحة الأرض">

                                <label for="soil_type">نوع التربة:</label>
                                <select name="soil_type" id="soil_type" class="form-control">
                                    <!-- الخيارات -->
                                    <option value="">-- اختر نوع التربة --</option>
                                    <option value="أرض زراعية">أرض زراعية</option>
                                    <option value="أرض صخرية">أرض صخرية</option>
                                </select>

                                <!-- ملاحظات حول المبنى -->
                                <label for="building_notes">ملاحظات حول المبنى</label>
                                <textarea name="building_notes" class="form-control" placeholder="أدخل ملاحظات حول المبنى"></textarea>

                                <!-- خط العرض -->
                                <label for="latitude">خط العرض</label>
                                <input type="number" step="0.000001" name="latitude" class="form-control"
                                    placeholder="أدخل خط العرض">

                                <!-- خط الطول -->
                                <label for="longitude">خط الطول</label>
                                <input type="number" step="0.000001" name="longitude" class="form-control"
                                    placeholder="أدخل خط الطول">

                                <!-- هل تم التحقق؟ -->
                                <label for="is_verified">هل تم التحقق؟</label>
                                <select name="is_verified" class="form-control">
                                    <option value="0">لا</option>
                                    <option value="1">نعم</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- الأزرار -->
                <button type="submit" class="btn btn-primary">حفظ</button>

            </form>
        </div>
    </div>

@endsection
{{--  إضافة السكريبت هنا --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // نحدد العناصر التي سنتعامل معها
        const operatorEntitySelect = document.getElementById('operator_entity');
        const operatorNameInput = document.getElementById('operator_name');

        // وظيفة لتحديث حقل اسم المشغل
        function updateOperatorName() {
            // نتحقق من القيمة المختارة
            if (operatorEntitySelect.value === 'المؤسسة العامة لمياه الشرب') {
                // نملأ الحقل بنفس القيمة
                operatorNameInput.value = 'المؤسسة العامة لمياه الشرب';
                // نجعل الحقل للقراءة فقط لمنع التعديل
                operatorNameInput.readOnly = true;
            } else {
                // نفرغ الحقل ونجعله قابلاً للكتابة
                operatorNameInput.value = '';
                operatorNameInput.readOnly = false;
                // نضع التركيز على الحقل ليسهل على المستخدم الكتابة
                operatorNameInput.placeholder = 'أدخل اسم جهة التشغيل (إن وجد)';
            }
        }

        // نضيف مستمع حدث 'change' للقائمة المنسدلة
        operatorEntitySelect.addEventListener('change', updateOperatorName);

        // نقوم بتشغيل الوظيفة مرة واحدة عند تحميل الصفحة لضبط الحالة الأولية
        updateOperatorName();
    });
</script>
