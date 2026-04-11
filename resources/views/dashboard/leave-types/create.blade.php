@extends('layouts.app')

@section('title', 'إضافة نوع إجازة')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>تعريف نوع إجازة جديد</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card card-success card-outline">
                    <form action="{{ route('dashboard.leave-types.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="type_name">اسم النوع <span class="text-danger">*</span></label>
                                <input type="text" name="type_name" class="form-control @error('type_name') is-invalid @enderror" placeholder="مثلاً: إجازة زواج، إجازة مرضية" value="{{ old('type_name') }}" required>
                                @error('type_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group">
                                <label>تأثير الرصيد السنوي <span class="text-danger">*</span></label>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="affect1" name="affects_balance" value="1" checked>
                                    <label for="affect1" class="custom-control-label font-weight-normal">تخصم من رصيد الموظف السنوي</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="affect2" name="affects_balance" value="0">
                                    <label for="affect2" class="custom-control-label font-weight-normal">إجازة إضافية (لا تخصم من الرصيد)</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success px-4">حفظ النوع</button>
                            <a href="{{ route('dashboard.leave-types.index') }}" class="btn btn-default">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
