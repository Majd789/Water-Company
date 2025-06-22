<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>إضافة محولة كهربائية جديدة</h2>
            @if (auth()->check() && auth()->user()->role_id == 'admin')
                <form action="{{ route('electricity_transformers.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="file">اختر ملف محولات الكهرباء (Excel أو CSV)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">استيراد</button>
                </form>
            @endif
            <!-- عرض الأخطاء -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- نموذج إضافة محولة كهربائية -->
            <form action="{{ route('electricity-transformers.store') }}" method="POST" class="login-form">
                @csrf

                <!-- حاوية الكروت -->
                <div class="cards-container">

                    <!-- الكرت 1: المعلومات الأساسية -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                المعلومات الأساسية
                            </div>
                            <div class="card-body">
                                <label for="station_id">اختر المحطة</label>
                                <select name="station_id" id="station_id"
                                    class="form-control @error('station_id') is-invalid @enderror" required>
                                    <option value="">اختر المحطة</option>
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                            {{ $station->station_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('station_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="operational_status">الوضع التشغيلي</label>
                                <select name="operational_status" id="operational_status"
                                    class="form-control @error('operational_status') is-invalid @enderror" required>
                                    <option value="تعمل" {{ old('operational_status') == 'تعمل' ? 'selected' : '' }}>تعمل
                                    </option>
                                    <option value="متوقفة" {{ old('operational_status') == 'متوقفة' ? 'selected' : '' }}>
                                        متوقفة</option>
                                </select>
                                @error('operational_status')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="transformer_capacity">استطاعة المحولة (KVA)</label>
                                <input type="number" step="0.01" name="transformer_capacity" id="transformer_capacity"
                                    class="form-control @error('transformer_capacity') is-invalid @enderror"
                                    value="{{ old('transformer_capacity') }}" placeholder="استطاعة المحولة (KVA)" required>
                                @error('transformer_capacity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <label for="distance_from_station">البعد عن المحطة (م)</label>
                                <input type="number" step="0.01" name="distance_from_station" id="distance_from_station"
                                    class="form-control @error('distance_from_station') is-invalid @enderror"
                                    value="{{ old('distance_from_station') }}" placeholder="البعد عن المحطة (م)" required>
                                @error('distance_from_station')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                            </div>
                        </div>
                    </div>
                    <!-- الكرت 2: بيانات الحفر -->
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-success">
                                بيانات المحولة
                            </div>
                            <div class="card-body">


                                <label for="is_station_transformer">هل المحولة خاصة بالمحطة</label>
                                <select name="is_station_transformer" id="is_station_transformer"
                                    class="form-control @error('is_station_transformer') is-invalid @enderror" required>
                                    <option value="1" {{ old('is_station_transformer') == '1' ? 'selected' : '' }}>نعم
                                    </option>
                                    <option value="0" {{ old('is_station_transformer') == '0' ? 'selected' : '' }}>لا
                                    </option>
                                </select>
                                @error('is_station_transformer')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="talk_about_station_transformer">اذكر سردا الجهة التي تشترك بالمحولة مع
                                    المحطة</label>
                                <input name="talk_about_station_transformer" id="talk_about_station_transformer"
                                    class="form-control @error('talk_about_station_transformer') is-invalid @enderror"
                                    placeholder="اذكر سردا الجهة التي تشترك بالمحولة مع المحطة"
                                    value="{{ old('talk_about_station_transformer') }}">
                                @error('talk_about_station_transformer')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="is_capacity_sufficient">هل استطاعة المحولة كافية</label>
                                <select name="is_capacity_sufficient" id="is_capacity_sufficient"
                                    class="form-control @error('is_capacity_sufficient') is-invalid @enderror" required>
                                    <option value="1" {{ old('is_capacity_sufficient') == '1' ? 'selected' : '' }}>نعم
                                    </option>
                                    <option value="0" {{ old('is_capacity_sufficient') == '0' ? 'selected' : '' }}>لا
                                    </option>
                                </select>
                                @error('is_capacity_sufficient')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="how_mush_capacity_need">استطاعة المحولة المطلوبة (KVA)</label>
                                <input type="number" step="0.01" name="how_mush_capacity_need"
                                    id="how_mush_capacity_need"
                                    class="form-control @error('how_mush_capacity_need') is-invalid @enderror"
                                    value="{{ old('how_mush_capacity_need') }}" placeholder="استطاعة المحولة (KVA)"
                                    required>
                                @error('how_mush_capacity_need')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="notes">ملاحظات</label>
                                <input name="notes" id="notes"
                                    class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="أدخل أي ملاحظات إضافية" value="{{ old('notes') }}">
                                @error('notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                    </div>
                </div>
                <!-- زر الإرسال -->
                <button type="submit" class="btn btn-primary">إضافة المحولة</button>

            </form>
        </div>
    </div>

@endsection
