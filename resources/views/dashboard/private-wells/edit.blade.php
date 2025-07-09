@extends('layouts.app')
@section('title', 'تعديل بيانات بئر خاص')

{{-- استيراد مكتبة Select2 و CSS مخصص --}}
@push('styles')
    <style>
        .form-control:not(:placeholder-shown):invalid {
            border-color: #dc3545 !important;
        }

        .form-control:not(:placeholder-shown):valid {
            border-color: #28a745 !important;
        }

        .select2-container--bootstrap4 .select2-selection {
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تعديل بيانات بئر خاص</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.private-wells.index') }}">الآبار الخاصة</a></li>
                    <li class="breadcrumb-item active">تعديل بيانات</li>
                </ol>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div class="container-fluid pt-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban ml-1"></i> خطأ!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit ml-1"></i>
                            بيانات البئر الخاص
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.private-wells.update', $privateWell->id) }}" method="POST"
                        novalidate>
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="station_id">المحطة القريبة<span class="text-danger">*</span></label>
                                        <select name="station_id" id="station_id" class="form-control select2" required>
                                            <option value="">-- اختر المحطة الأقرب --</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->id }}"
                                                    {{ old('station_id', $privateWell->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->station_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_name">اسم البئر / المالك</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-user"></i></span></div>
                                            <input type="text" name="well_name" id="well_name" class="form-control"
                                                value="{{ old('well_name', $privateWell->well_name) }}"
                                                placeholder="أدخل اسم البئر أو المالك">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_type">نوع البئر</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-water"></i></span></div>
                                            <select name="well_type" id="well_type" class="form-control">
                                                <option value="">-- اختر نوع البئر --</option>
                                                <option value="جوفي"
                                                    {{ old('well_type', $privateWell->well_type) == 'جوفي' ? 'selected' : '' }}>
                                                    جوفي</option>
                                                <option value="سطحي"
                                                    {{ old('well_type', $privateWell->well_type) == 'سطحي' ? 'selected' : '' }}>
                                                    سطحي</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="well_count">عدد الآبار</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-hashtag"></i></span></div>
                                            <input type="number" name="well_count" id="well_count" class="form-control"
                                                value="{{ old('well_count', $privateWell->well_count) }}"
                                                placeholder="أدخل عدد الآبار">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="distance_from_nearest_well">المسافة من أقرب بئر مؤسساتي (متر)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fas fa-route"></i></span></div>
                                            <input type="number" name="distance_from_nearest_well"
                                                id="distance_from_nearest_well" class="form-control"
                                                value="{{ old('distance_from_nearest_well', $privateWell->distance_from_nearest_well) }}"
                                                placeholder="أدخل المسافة بالأمتار">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">ملاحظات</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="أدخل أي ملاحظات إضافية">{{ old('notes', $privateWell->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save ml-1"></i>
                                حفظ التعديلات
                            </button>
                            <a href="{{ route('dashboard.private-wells.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times ml-1"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // تفعيل Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: "rtl",
                placeholder: "-- اختر --"
            });
        });
    </script>
@endpush
