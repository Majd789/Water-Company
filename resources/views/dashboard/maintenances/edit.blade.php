<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h1>تعديل بيانات الصيانة</h1>

            <!-- عرض الأخطاء في المدخلات -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('dashboard.maintenances.update', $maintenance) }}" method="POST" class="login-form">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <!-- الكرت 1: المعلومات الأساسية -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                المعلومات الأساسية
                            </div>
                            <div class="card-body">
                                <!-- اختيار المحطة -->
                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id', $maintenance->station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اختيار نوع الصيانة -->
                                <label for="maintenance_type_id">نوع الصيانة</label>
                                <select name="maintenance_type_id" id="maintenance_type_id"
                                    class="form-control @error('maintenance_type_id') is-invalid @enderror" required>
                                    <option value="">اختر نوع الصيانة</option>
                                    @foreach ($maintenanceTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('maintenance_type_id', $maintenance->maintenance_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('maintenance_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- كمية الصيانة الإجمالية -->
                                <label for="total_quantity">كمية الصيانة الإجمالية</label>
                                <input type="number" name="total_quantity" id="total_quantity"
                                    class="form-control @error('total_quantity') is-invalid @enderror"
                                    value="{{ old('total_quantity', $maintenance->total_quantity) }}"
                                    placeholder="أدخل الكمية" required>
                                @error('total_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- مواقع التنفيذ -->
                                <label for="execution_sites">مواقع التنفيذ</label>
                                <input type="text" name="execution_sites" id="execution_sites"
                                    class="form-control @error('execution_sites') is-invalid @enderror"
                                    value="{{ old('execution_sites', $maintenance->execution_sites) }}"
                                    placeholder="أدخل مواقع التنفيذ" required>
                                @error('execution_sites')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- التكلفة الإجمالية -->
                                <label for="total_cost">التكلفة الإجمالية بالدولار</label>
                                <input type="number" step="0.01" name="total_cost" id="total_cost"
                                    class="form-control @error('total_cost') is-invalid @enderror"
                                    value="{{ old('total_cost', $maintenance->total_cost) }}" placeholder="أدخل التكلفة"
                                    required>
                                @error('total_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- الكرت 2: التفاصيل الإضافية -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-success">
                                تفاصيل إضافية
                            </div>
                            <div class="card-body">
                                <!-- تاريخ الصيانة -->
                                <label for="maintenance_date">تاريخ الصيانة</label>
                                <input type="date" name="maintenance_date" id="maintenance_date"
                                    class="form-control @error('maintenance_date') is-invalid @enderror"
                                    value="{{ old('maintenance_date', $maintenance->maintenance_date) }}" required>
                                @error('maintenance_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- تفاصيل الصيانة -->
                                <label for="maintenance_details">تفاصيل الصيانة</label>
                                <textarea name="maintenance_details" id="maintenance_details"
                                    class="form-control @error('maintenance_details') is-invalid @enderror" placeholder="أدخل تفاصيل الصيانة">{{ old('maintenance_details', $maintenance->maintenance_details) }}</textarea>
                                @error('maintenance_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اسم المقاول -->
                                <label for="contractor_name">اسم المقاول</label>
                                <input type="text" name="contractor_name" id="contractor_name"
                                    class="form-control @error('contractor_name') is-invalid @enderror"
                                    value="{{ old('contractor_name', $maintenance->contractor_name) }}"
                                    placeholder="أدخل اسم المقاول">
                                @error('contractor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- اسم الفني -->
                                <label for="technician_name">اسم الفني</label>
                                <input type="text" name="technician_name" id="technician_name"
                                    class="form-control @error('technician_name') is-invalid @enderror"
                                    value="{{ old('technician_name', $maintenance->technician_name) }}"
                                    placeholder="أدخل اسم الفني">
                                @error('technician_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- حالة الصيانة -->
                                <label for="status">حالة الصيانة</label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="تمت"
                                        {{ old('status', $maintenance->status) == 'تمت' ? 'selected' : '' }}>تمت</option>
                                    <option value="قيد التنفيذ"
                                        {{ old('status', $maintenance->status) == 'قيد التنفيذ' ? 'selected' : '' }}>قيد
                                        التنفيذ</option>
                                    <option value="فشلت"
                                        {{ old('status', $maintenance->status) == 'فشلت' ? 'selected' : '' }}>فشلت</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- زر الحفظ -->
                <button type="submit" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>
@endsection
