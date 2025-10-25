@extends('layouts.app')

@section('title', 'إضافة سجل إحصائي جديد')

@section('content')
<div class="container-fluid">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">نموذج إنشاء سجل إحصائي شهري</h3>
        </div>
        <form action="{{ route('dashboard.unit-stats.store') }}" method="POST">
            @csrf

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6><i class="icon fas fa-ban"></i> خطأ! الرجاء مراجعة الحقول التالية:</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Date and Unit Selection - Common for all --}}
                <div class="card card-outline card-info">
                    <div class="card-header"><h3 class="card-title">المعلومات الأساسية للسجل</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="unit_id">الوحدة الإدارية <span class="text-danger">*</span></label>
                                <select name="unit_id" id="unit_id" class="form-control select2bs4" required>
                                    <option value="">-- اختر الوحدة --</option>
                                    @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="year">السنة <span class="text-danger">*</span></label>
                                <input type="number" name="year" id="year" class="form-control" value="{{ old('year', date('Y')) }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="month">الشهر <span class="text-danger">*</span></label>
                                <select name="month" id="month" class="form-control" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ old('month', date('m')) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Technical Department Section - Visible only to users with this permission --}}
                @can('unit_stats.edit_technical')
                <div class="card card-outline card-primary mt-4">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-water mr-2"></i>إحصائيات المياه (القسم التقني)</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4"><label>إجمالي المياه المنتجة (م³)</label><input type="number" step="0.01" name="produced_water_m3" class="form-control" value="{{ old('produced_water_m3', 0) }}"></div>
                            <div class="form-group col-md-4"><label>إجمالي الهدر (م³)</label><input type="number" step="0.01" name="lost_water_m3" class="form-control" value="{{ old('lost_water_m3', 0) }}"></div>
                            <div class="form-group col-md-4"><label>المياه الموزعة للمستفيدين (م³)</label><input type="number" step="0.01" name="distributed_water_m3" class="form-control" value="{{ old('distributed_water_m3', 0) }}"></div>
                        </div>
                    </div>
                </div>
                @endcan

                {{-- Subscribers Department Section - Visible only to users with this permission --}}
                @can('unit_stats.edit_subscribers')
                <div class="card card-outline card-success mt-4">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-users mr-2"></i>بيانات المشتركين والمالية</h3></div>
                    <div class="card-body">
                        <h6 class="text-muted">أعداد المشتركين</h6>
                        <div class="row">
                            <div class="form-group col-md-3"><label>الكلي</label><input type="number" name="total_subscribers" class="form-control" value="{{ old('total_subscribers', 0) }}"></div>
                            <div class="form-group col-md-3"><label>الفعالين</label><input type="number" name="active_subscribers" class="form-control" value="{{ old('active_subscribers', 0) }}"></div>
                            <div class="form-group col-md-3"><label>جباية عداد</label><input type="number" name="metered_subscribers" class="form-control" value="{{ old('metered_subscribers', 0) }}"></div>
                            <div class="form-group col-md-3"><label>جباية مقطوعة</label><input type="number" name="flat_rate_subscribers" class="form-control" value="{{ old('flat_rate_subscribers', 0) }}"></div>
                            <div class="form-group col-md-3"><label>المغادرين</label><input type="number" name="departed_subscribers" class="form-control" value="{{ old('departed_subscribers', 0) }}"></div>
                            <div class="form-group col-md-3"><label>الملغى خطوطهم</label><input type="number" name="canceled_subscribers" class="form-control" value="{{ old('canceled_subscribers', 0) }}"></div>
                            <div class="form-group col-md-3"><label>المقطوع خطوطهم</label><input type="number" name="disconnected_subscribers" class="form-control" value="{{ old('disconnected_subscribers', 0) }}"></div>
                        </div>
                        <hr>
                        <h6 class="text-muted">شرائح المشتركين والمتخلفين</h6>
                        <div class="row">
                            <div class="form-group col-md-3"><label>مشتركي إسكان</label><input type="number" name="housing_project_subscribers" class="form-control" value="{{ old('housing_project_subscribers', 0) }}"></div>
                            <div class="form-group col-md-3"><label>متخلفي إسكان</label><input type="number" name="housing_project_defaulters" class="form-control" value="{{ old('housing_project_defaulters', 0) }}"></div>
                            {{-- Add other segment fields here in the same pattern --}}
                        </div>
                        <hr>
                        <h6 class="text-muted">البيانات المالية</h6>
                        <div class="row">
                             <div class="form-group col-md-3"><label>عدد المسددين</label><input type="number" name="total_paid_count" class="form-control" value="{{ old('total_paid_count', 0) }}"></div>
                             <div class="form-group col-md-3"><label>قيمة التسديد</label><input type="number" step="0.01" name="total_paid_amount" class="form-control" value="{{ old('total_paid_amount', 0) }}"></div>
                             <div class="form-group col-md-3"><label>عدد المتخلفين</label><input type="number" name="total_defaulters_count" class="form-control" value="{{ old('total_defaulters_count', 0) }}"></div>
                             <div class="form-group col-md-3"><label>قيمة التخلف</label><input type="number" step="0.01" name="total_defaulters_amount" class="form-control" value="{{ old('total_defaulters_amount', 0) }}"></div>
                             <div class="form-group col-md-3"><label>عدد المعفيين</label><input type="number" name="exempted_count" class="form-control" value="{{ old('exempted_count', 0) }}"></div>
                             <div class="form-group col-md-3"><label>القيمة المعفاة</label><input type="number" step="0.01" name="exempted_amount" class="form-control" value="{{ old('exempted_amount', 0) }}"></div>
                        </div>
                    </div>
                </div>
                @endcan

                {{-- Common Notes Section --}}
                <div class="form-group mt-4">
                    <label for="notes">ملاحظات عامة</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">إنشاء السجل</button>
                <a href="{{ route('dashboard.unit-stats.index') }}" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
