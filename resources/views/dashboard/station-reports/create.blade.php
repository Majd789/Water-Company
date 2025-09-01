@extends('layouts.app')

@section('title', 'إضافة تقرير محطة')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>إضافة تقرير محطة</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.station-reports.index') }}">تقارير المحطات</a></li>
                        <li class="breadcrumb-item active">إضافة تقرير</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">بيانات التقرير</h3>
                        </div>

                        <form action="{{ route('dashboard.station-reports.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                {{-- Basic Information --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="report_date">تاريخ التقرير <span class="text-danger">*</span></label>
                                            <input type="date" name="report_date" id="report_date"
                                                class="form-control @error('report_date') is-invalid @enderror"
                                                value="{{ old('report_date', date('Y-m-d')) }}" required>
                                            @error('report_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="station_id">المحطة</label>
                                            <select name="station_id" id="station_id" class="form-control select2 @error('station_id') is-invalid @enderror">
                                                <option value="">اختر المحطة</option>
                                                @foreach($stations as $station)
                                                    <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                                        {{ $station->station_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('station_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">الحالة التشغيلية</label>
                                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                                <option value="">اختر الحالة</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                                        {{ $status->getLabel() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="operating_entity">الجهة المشغلة</label>
                                            <select name="operating_entity" id="operating_entity" class="form-control @error('operating_entity') is-invalid @enderror">
                                                <option value="">اختر الجهة المشغلة</option>
                                                @foreach($operatingEntities as $entity)
                                                    <option value="{{ $entity->value }}" {{ old('operating_entity') == $entity->value ? 'selected' : '' }}>
                                                        {{ $entity->getLabel() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('operating_entity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Wells Information --}}
                                <div class="card card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title">معلومات الآبار</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="number_well">عدد الآبار</label>
                                                    <input type="number" name="number_well" id="number_well"
                                                        class="form-control @error('number_well') is-invalid @enderror"
                                                        value="{{ old('number_well') }}" min="0">
                                                    @error('number_well')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="operating_hours">إجمالي ساعات التشغيل</label>
                                                    <input type="number" name="operating_hours" id="operating_hours"
                                                        class="form-control @error('operating_hours') is-invalid @enderror"
                                                        value="{{ old('operating_hours') }}" step="0.01" min="0">
                                                    @error('operating_hours')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="water_pumped_m3">كمية المياه المضخوخة (م³)</label>
                                                    <input type="number" name="water_pumped_m3" id="water_pumped_m3"
                                                        class="form-control @error('water_pumped_m3') is-invalid @enderror"
                                                        value="{{ old('water_pumped_m3') }}" step="0.01" min="0">
                                                    @error('water_pumped_m3')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Energy Information --}}
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">معلومات الطاقة</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="power_source">مصدر الطاقة الرئيسي</label>
                                                    <select name="power_source" id="power_source" class="form-control @error('power_source') is-invalid @enderror">
                                                        <option value="">اختر مصدر الطاقة</option>
                                                        @foreach($energyResources as $resource)
                                                            <option value="{{ $resource->value }}" {{ old('power_source') == $resource->value ? 'selected' : '' }}>
                                                                {{ $resource->getLabel() }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('power_source')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="energy_resource">مصدر الطاقة التشغيلية</label>
                                                    <input type="text" name="energy_resource" id="energy_resource"
                                                        class="form-control @error('energy_resource') is-invalid @enderror"
                                                        value="{{ old('energy_resource') }}">
                                                    @error('energy_resource')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="electricity_hours">ساعات الكهرباء</label>
                                                    <input type="number" name="electricity_hours" id="electricity_hours"
                                                        class="form-control @error('electricity_hours') is-invalid @enderror"
                                                        value="{{ old('electricity_hours') }}" step="0.01" min="0">
                                                    @error('electricity_hours')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="solar_hours">ساعات الطاقة الشمسية</label>
                                                    <input type="number" name="solar_hours" id="solar_hours"
                                                        class="form-control @error('solar_hours') is-invalid @enderror"
                                                        value="{{ old('solar_hours') }}" step="0.01" min="0">
                                                    @error('solar_hours')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="generator_hours">ساعات المولدة</label>
                                                    <input type="number" name="generator_hours" id="generator_hours"
                                                        class="form-control @error('generator_hours') is-invalid @enderror"
                                                        value="{{ old('generator_hours') }}" step="0.01" min="0">
                                                    @error('generator_hours')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Notes --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="notes">ملاحظات</label>
                                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                                rows="3">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ التقرير
                                </button>
                                <a href="{{ route('dashboard.station-reports.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: 'rtl'
            });
        });
    </script>
@endpush
