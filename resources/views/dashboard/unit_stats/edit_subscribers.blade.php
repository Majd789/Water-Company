@extends('layouts.app')

@section('title', 'تعديل بيانات المشتركين')

@section('content')
<div class="container-fluid">
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">تعبئة/تعديل بيانات المشتركين للوحدة: <strong>{{ $unitMonthlyStat->unit->unit_name }}</strong> - شهر: <strong>{{ $unitMonthlyStat->month }}/{{ $unitMonthlyStat->year }}</strong></h3>
        </div>
        <form action="{{ route('dashboard.unit-stats.update_subscribers', $unitMonthlyStat->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6><i class="icon fas fa-ban"></i> خطأ!</h6>
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                {{-- Subscriber Counts Section --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list-ol mr-2"></i>أعداد المشتركين</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3"><label>عدد المشتركين الكلي</label><input type="number" name="total_subscribers" class="form-control" value="{{ old('total_subscribers', $unitMonthlyStat->total_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>عدد الفعالين</label><input type="number" name="active_subscribers" class="form-control" value="{{ old('active_subscribers', $unitMonthlyStat->active_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>بجباية على العداد</label><input type="number" name="metered_subscribers" class="form-control" value="{{ old('metered_subscribers', $unitMonthlyStat->metered_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>بجباية مقطوعة</label><input type="number" name="flat_rate_subscribers" class="form-control" value="{{ old('flat_rate_subscribers', $unitMonthlyStat->flat_rate_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>عدد المغادرين</label><input type="number" name="departed_subscribers" class="form-control" value="{{ old('departed_subscribers', $unitMonthlyStat->departed_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>الملغى خطوطهم</label><input type="number" name="canceled_subscribers" class="form-control" value="{{ old('canceled_subscribers', $unitMonthlyStat->canceled_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>المقطوع خطوطهم</label><input type="number" name="disconnected_subscribers" class="form-control" value="{{ old('disconnected_subscribers', $unitMonthlyStat->disconnected_subscribers) }}"></div>
                        </div>
                    </div>
                </div>

                {{-- Subscriber Segments Section --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-layer-group mr-2"></i>شرائح المشتركين والمتخلفين</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3"><label>مشتركي بناء إسكان</label><input type="number" name="housing_project_subscribers" class="form-control" value="{{ old('housing_project_subscribers', $unitMonthlyStat->housing_project_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>متخلفي بناء إسكان</label><input type="number" name="housing_project_defaulters" class="form-control" value="{{ old('housing_project_defaulters', $unitMonthlyStat->housing_project_defaulters) }}"></div>
                            <div class="form-group col-md-3"><label>مشتركي أبنية حكومية</label><input type="number" name="gov_building_subscribers" class="form-control" value="{{ old('gov_building_subscribers', $unitMonthlyStat->gov_building_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>متخلفي أبنية حكومية</label><input type="number" name="gov_building_defaulters" class="form-control" value="{{ old('gov_building_defaulters', $unitMonthlyStat->gov_building_defaulters) }}"></div>
                            <div class="form-group col-md-3"><label>مشتركي بناء ملكية</label><input type="number" name="owned_property_subscribers" class="form-control" value="{{ old('owned_property_subscribers', $unitMonthlyStat->owned_property_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>متخلفي بناء ملكية</label><input type="number" name="owned_property_defaulters" class="form-control" value="{{ old('owned_property_defaulters', $unitMonthlyStat->owned_property_defaulters) }}"></div>
                            <div class="form-group col-md-3"><label>مشتركي بناء مستأجر</label><input type="number" name="rented_property_subscribers" class="form-control" value="{{ old('rented_property_subscribers', $unitMonthlyStat->rented_property_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>متخلفي بناء مستأجر</label><input type="number" name="rented_property_defaulters" class="form-control" value="{{ old('rented_property_defaulters', $unitMonthlyStat->rented_property_defaulters) }}"></div>
                            <div class="form-group col-md-3"><label>مشتركي اشتراك منزلي</label><input type="number" name="domestic_subscription_subscribers" class="form-control" value="{{ old('domestic_subscription_subscribers', $unitMonthlyStat->domestic_subscription_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>متخلفي اشتراك منزلي</label><input type="number" name="domestic_subscription_defaulters" class="form-control" value="{{ old('domestic_subscription_defaulters', $unitMonthlyStat->domestic_subscription_defaulters) }}"></div>
                            <div class="form-group col-md-3"><label>مشتركي اشتراك تجاري</label><input type="number" name="commercial_subscription_subscribers" class="form-control" value="{{ old('commercial_subscription_subscribers', $unitMonthlyStat->commercial_subscription_subscribers) }}"></div>
                            <div class="form-group col-md-3"><label>متخلفي اشتراك تجاري</label><input type="number" name="commercial_subscription_defaulters" class="form-control" value="{{ old('commercial_subscription_defaulters', $unitMonthlyStat->commercial_subscription_defaulters) }}"></div>
                        </div>
                    </div>
                </div>

                {{-- Financials Section --}}
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-dollar-sign mr-2"></i>البيانات المالية</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3"><label>عدد المسددين الإجمالي</label><input type="number" name="total_paid_count" class="form-control" value="{{ old('total_paid_count', $unitMonthlyStat->total_paid_count) }}"></div>
                            <div class="form-group col-md-3"><label>قيمة التسديد</label><input type="number" step="0.01" name="total_paid_amount" class="form-control" value="{{ old('total_paid_amount', $unitMonthlyStat->total_paid_amount) }}"></div>
                            <div class="form-group col-md-3"><label>عدد المتخلفين الإجمالي</label><input type="number" name="total_defaulters_count" class="form-control" value="{{ old('total_defaulters_count', $unitMonthlyStat->total_defaulters_count) }}"></div>
                            <div class="form-group col-md-3"><label>قيمة التخلف</label><input type="number" step="0.01" name="total_defaulters_amount" class="form-control" value="{{ old('total_defaulters_amount', $unitMonthlyStat->total_defaulters_amount) }}"></div>
                            <div class="form-group col-md-3"><label>عدد المعفيين</label><input type="number" name="exempted_count" class="form-control" value="{{ old('exempted_count', $unitMonthlyStat->exempted_count) }}"></div>
                            <div class="form-group col-md-3"><label>القيمة المعفاة</label><input type="number" step="0.01" name="exempted_amount" class="form-control" value="{{ old('exempted_amount', $unitMonthlyStat->exempted_amount) }}"></div>
                        </div>
                    </div>
                </div>

                {{-- Notes Section --}}
                <div class="form-group mt-4">
                    <label for="notes">ملاحظات (سيتم تحديث الملاحظات العامة للسجل)</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $unitMonthlyStat->notes) }}</textarea>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">حفظ بيانات المشتركين</button>
                <a href="{{ route('dashboard.unit-stats.index') }}" class="btn btn-secondary">العودة للقائمة</a>
            </div>
        </form>
    </div>
</div>
@endsection
