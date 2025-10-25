@extends('layouts.app')

@section('title', 'تعديل البيانات التقنية')

@section('content')
<div class="container-fluid">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">تعبئة/تعديل البيانات التقنية للوحدة: <strong>{{ $unitMonthlyStat->unit->unit_name }}</strong> - شهر: <strong>{{ $unitMonthlyStat->month }}/{{ $unitMonthlyStat->year }}</strong></h3>
        </div>
        <form action="{{ route('dashboard.unit-stats.update_technical', $unitMonthlyStat->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <h5 class="mb-3"><i class="fas fa-water mr-2"></i>إحصائيات المياه</h5>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>إجمالي المياه المنتجة (م³)</label>
                        <input type="number" step="0.01" name="produced_water_m3" class="form-control" value="{{ old('produced_water_m3', $unitMonthlyStat->produced_water_m3) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>إجمالي الهدر (م³)</label>
                        <input type="number" step="0.01" name="lost_water_m3" class="form-control" value="{{ old('lost_water_m3', $unitMonthlyStat->lost_water_m3) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>المياه الموزعة للمستفيدين (م³)</label>
                        <input type="number" step="0.01" name="distributed_water_m3" class="form-control" value="{{ old('distributed_water_m3', $unitMonthlyStat->distributed_water_m3) }}">
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="notes">ملاحظات (سيتم تحديث الملاحظات العامة للسجل)</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $unitMonthlyStat->notes) }}</textarea>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">حفظ البيانات التقنية</button>
                <a href="{{ route('dashboard.unit-stats.index') }}" class="btn btn-secondary">العودة للقائمة</a>
            </div>
        </form>
    </div>
</div>
@endsection
