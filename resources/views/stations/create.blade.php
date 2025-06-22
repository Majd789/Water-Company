<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')


    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة بئر جديدة</h2>
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
                                    class="form-control @error('energy_source') is-invalid @enderror" required>
                                    <option value="">-- اختر مصدر الطاقة --</option>
                                    <option value="كهرباء ومولدة وطاقة شمسية"
                                        {{ old('energy_source') == 'كهرباء ومولدة وطاقة شمسية' ? 'selected' : '' }}>كهرباء
                                        ومولدة وطاقة شمسية</option>
                                    <option value="كهرباء ومولدة"
                                        {{ old('energy_source') == 'كهرباء ومولدة' ? 'selected' : '' }}>كهرباء ومولدة
                                    </option>
                                    <option value="متوقفة" {{ old('energy_source') == 'متوقفة' ? 'selected' : '' }}>متوقفة
                                    </option>
                                    <option value="خارج الخدمة"
                                        {{ old('energy_source') == 'خارج الخدمة' ? 'selected' : '' }}>خارج الخدمة</option>
                                    <option value="مولدة" {{ old('energy_source') == 'مولدة' ? 'selected' : '' }}>مولدة
                                    </option>
                                    <option value="طاقة شمسية ومولدة"
                                        {{ old('energy_source') == 'طاقة شمسية ومولدة' ? 'selected' : '' }}>طاقة شمسية
                                        ومولدة</option>
                                    <option value="كهرباء وطاقة شمسية"
                                        {{ old('energy_source') == 'كهرباء وطاقة شمسية' ? 'selected' : '' }}>كهرباء وطاقة
                                        شمسية</option>
                                    <option value="كهرباء" {{ old('energy_source') == 'كهرباء' ? 'selected' : '' }}>كهرباء
                                    </option>
                                    <option value="طاقة شمسية"
                                        {{ old('energy_source') == 'طاقة شمسية' ? 'selected' : '' }}>طاقة شمسية</option>
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
                                    <option value="شبكة">شبكة</option>
                                    <option value="شبكة ومنهل">شبكة ومنهل</option>
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

                                <!-- نوع الشبكة -->
                                <label for="network_type">نوع الشبكة:</label>
                                <select name="network_type" id="network_type"
                                    class="form-control @error('network_type') is-invalid @enderror">
                                    <option value="">-- اختر نوع الشبكة --</option>
                                    <option value="بولي ايتلين">بولي ايتلين</option>
                                    <option value="بولي ايتلين فونط">بولي ايتلين فونط</option>
                                    <option value="بولي ايتلين+ اترنيت+حديد">بولي ايتلين+ اترنيت+حديد</option>
                                    <option value="بولي ايتلين اترنيت">بولي ايتلين اترنيت</option>
                                    <option value="بولي اتلين+حديد">بولي اتلين+حديد</option>
                                    <option value="اتلين + حديد+ اترنيت">اتلين + حديد+ اترنيت</option>
                                    <option value="بولي ايتلين+حديد">بولي ايتلين+حديد</option>
                                    <option value="فونط">فونط</option>
                                    <option value="اترنيت">اترنيت</option>
                                    <option value="حديد">حديد</option>
                                    <option value="pvc">pvc</option>
                                    <option value="اترنيت حديد">اترنيت حديد</option>
                                    <option value="خط ضخ">خط ضخ</option>
                                    <option value="فونت">فونت</option>
                                    <option value="pvc اترنيت">pvc اترنيت</option>
                                    <option value="other">أخرى</option>
                                </select>
                                @error('network_type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <!-- حقل لإدخال نوع شبكة جديد -->
                                <input id="custom_network_type" type="text" name="custom_network_type"
                                    class="form-control @error('custom_network_type') is-invalid @enderror"
                                    placeholder="أدخل نوع الشبكة" style="display: none; margin-top: 10px;">
                                @error('custom_network_type')
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
