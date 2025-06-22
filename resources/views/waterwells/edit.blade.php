<!-- resources/views/waterwells/edit.blade.php -->
<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <div class="login-card">
            <h2>تعديل المنهل</h2>

            <form class="login-form" action="{{ route('waterwells.update', $waterWell->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="cards-container">
                    <div class="card-box" style="width: 400px">
                        <div class="card">
                            <div class="card-header bg-primary">
                                تعديل المنهل
                            </div>
                            <div class="card-body">

                                <label for="station_code" class="form-label">كود المحطة</label>
                                <input type="text" name="station_code" id="station_code" class="form-control"
                                    value="{{ old('station_code', $waterWell->station_code) }}" required>
                                @error('station_code')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="well_name" class="form-label">اسم المنهل</label>
                                <input type="text" name="well_name" id="well_name" class="form-control"
                                    value="{{ old('well_name', $waterWell->well_name) }}" required>
                                @error('well_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="has_flow_meter" class="form-label">هل يوجد عداد غزارة؟</label>
                                <select name="has_flow_meter" id="has_flow_meter" class="form-control" required>
                                    <option value="نعم"
                                        {{ old('has_flow_meter', $waterWell->has_flow_meter) == 'نعم' ? 'selected' : '' }}>
                                        نعم</option>
                                    <option value="لا"
                                        {{ old('has_flow_meter', $waterWell->has_flow_meter) == 'لا' ? 'selected' : '' }}>لا
                                    </option>
                                </select>
                                @error('has_flow_meter')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="flow_meter_start" class="form-label">مؤشر بداية العداد</label>
                                <input type="number" name="flow_meter_start" id="flow_meter_start" class="form-control"
                                    value="{{ old('flow_meter_start', $waterWell->flow_meter_start) }}" required>
                                @error('flow_meter_start')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="flow_meter_end" class="form-label">مؤشر نهاية العداد</label>
                                <input type="number" name="flow_meter_end" id="flow_meter_end" class="form-control"
                                    value="{{ old('flow_meter_end', $waterWell->flow_meter_end) }}" required>
                                @error('flow_meter_end')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="water_sold_quantity" class="form-label">كمية المياه المباعة</label>
                                <input type="number" name="water_sold_quantity" id="water_sold_quantity"
                                    class="form-control"
                                    value="{{ old('water_sold_quantity', $waterWell->water_sold_quantity) }}" required>
                                @error('water_sold_quantity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <label for="water_price" class="form-label">سعر المتر</label>
                                <input type="number" name="water_price" id="water_price" class="form-control"
                                    value="{{ old('water_price', $waterWell->water_price) }}" required>
                                @error('water_price')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="total_amount" class="form-label">المبلغ الإجمالي</label>
                                <input type="number" name="total_amount" id="total_amount" class="form-control"
                                    value="{{ old('total_amount', $waterWell->total_amount) }}" required>
                                @error('total_amount')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="has_vehicle_filling" class="form-label">هل يوجد تعبئة للسيارات؟</label>
                                <select name="has_vehicle_filling" id="has_vehicle_filling" class="form-control" required>
                                    <option value="نعم"
                                        {{ old('has_vehicle_filling', $waterWell->has_vehicle_filling) == 'نعم' ? 'selected' : '' }}>
                                        نعم</option>
                                    <option value="لا"
                                        {{ old('has_vehicle_filling', $waterWell->has_vehicle_filling) == 'لا' ? 'selected' : '' }}>
                                        لا</option>
                                </select>
                                @error('has_vehicle_filling')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="vehicle_filling_quantity" class="form-label">كمية تعبئة السيارة</label>
                                <input type="number" name="vehicle_filling_quantity" id="vehicle_filling_quantity"
                                    class="form-control"
                                    value="{{ old('vehicle_filling_quantity', $waterWell->vehicle_filling_quantity) }}">
                                @error('vehicle_filling_quantity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <label for="has_free_filling" class="form-label">هل يوجد تعبئة مجانية؟</label>
                                <select name="has_free_filling" id="has_free_filling" class="form-control" required>
                                    <option value="نعم"
                                        {{ old('has_free_filling', $waterWell->has_free_filling) == 'نعم' ? 'selected' : '' }}>
                                        نعم</option>
                                    <option value="لا"
                                        {{ old('has_free_filling', $waterWell->has_free_filling) == 'لا' ? 'selected' : '' }}>
                                        لا</option>
                                </select>
                                @error('has_free_filling')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="free_filling_quantity" class="form-label">كمية التعبئة المجانية</label>
                                <input type="number" name="free_filling_quantity" id="free_filling_quantity"
                                    class="form-control"
                                    value="{{ old('free_filling_quantity', $waterWell->free_filling_quantity) }}">
                                @error('free_filling_quantity')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="entity_for_free_filling" class="form-label">الكيان المستفيد من التعبئة
                                    المجانية</label>
                                <input type="text" name="entity_for_free_filling" id="entity_for_free_filling"
                                    class="form-control"
                                    value="{{ old('entity_for_free_filling', $waterWell->entity_for_free_filling) }}">
                                @error('entity_for_free_filling')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="document_number" class="form-label">رقم المستند</label>
                                <input type="text" name="document_number" id="document_number" class="form-control"
                                    value="{{ old('document_number', $waterWell->document_number) }}">
                                @error('document_number')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror



                                <label for="notes" class="form-label">ملاحظات</label>
                                <textarea name="notes" id="notes" class="form-control">{{ old('notes', $waterWell->notes) }}</textarea>
                                @error('notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                                <button type="submit" class="btn btn-primary">تعديل</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


        </div>
    @endsection
