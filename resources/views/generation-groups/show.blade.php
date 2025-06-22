<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')  
    <h1 style="text-align: center">{{ $generationGroup->station->station_name }}</h1>
    
    <div class="cards-container">
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary">
                    المعلومات الأساسية
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr><th>المحطة</th><td>{{ $generationGroup->station->station_name }}</td></tr>
                        <tr><th>اسم المولدة</th><td>{{ $generationGroup->generator_name }}</td></tr>
                        <tr><th>الوضع التشغيلي</th><td>{{ $generationGroup->operational_status == 'working' ? 'يعمل' : 'متوقف' }}</td></tr>
                        <tr><th>سبب التوقف</th><td>{{ $generationGroup->stop_reason ?? 'غير محدد' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    بيانات التوليد
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr><th>استطاعة التوليد (KVA)</th><td>{{ $generationGroup->generation_capacity }}</td></tr>
                        <tr><th>استطاعة العمل الفعلية</th><td>{{ $generationGroup->actual_operating_capacity }}</td></tr>
                        <tr><th>نسبة الجاهزية (%)</th><td>{{ $generationGroup->generation_group_readiness_percentage ?? 'غير محدد' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    استهلاك الوقود والزيت
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr><th>استهلاك الوقود (لتر/ساعة)</th><td>{{ $generationGroup->fuel_consumption }}</td></tr>
                        <tr><th>مدة استخدام الزيت (ساعة)</th><td>{{ $generationGroup->oil_usage_duration }}</td></tr>
                        <tr><th>كمية الزيت للتبديل (لتر)</th><td>{{ $generationGroup->oil_quantity_for_replacement }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-warning">
                    الملاحظات
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr><th>ملاحظات</th><td>{{ $generationGroup->notes ?? 'لا توجد ملاحظات' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div style="text-align: center" class="text-center mt-4">
        <a href="{{ route('generation-groups.index') }}" class="btn btn-primary">الرجوع إلى القائمة</a>
       
       
    </div>
@endsection
