@extends('layouts.app')

@section('title', 'تعديل نوع الإجازة')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>تعديل النوع: {{ $leaveType->type_name }}</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card card-warning card-outline">
                    <form action="{{ route('dashboard.leave-types.update', $leaveType->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="type_name">اسم النوع</label>
                                <input type="text" name="type_name" class="form-control @error('type_name') is-invalid @enderror" value="{{ old('type_name', $leaveType->type_name) }}" required>
                                @error('type_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group">
                                <label>تأثير الرصيد السنوي</label>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="edit_affect1" name="affects_balance" value="1" {{ $leaveType->affects_balance ? 'checked' : '' }}>
                                    <label for="edit_affect1" class="custom-control-label font-weight-normal">تخصم من الرصيد السنوي</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="edit_affect2" name="affects_balance" value="0" {{ !$leaveType->affects_balance ? 'checked' : '' }}>
                                    <label for="edit_affect2" class="custom-control-label font-weight-normal">لا تخصم من الرصيد</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-warning text-white px-4">تحديث البيانات</button>
                            <a href="{{ route('dashboard.leave-types.index') }}" class="btn btn-default">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
