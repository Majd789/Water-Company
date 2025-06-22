<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')  
    <h1 style="text-align: center">{{ $station->station_name }}</h1>
    
    <!-- حاوية الكروت -->
    <div class="cards-container">
        <!-- الكرت 1: المعلومات الأساسية -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-primary">
                    المعلومات الأساسية
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>اسم المحطة</th>
                            <td>{{ $station->station_name }}</td>
                        </tr>
                        <tr>
                            <th>كود المحطة</th>
                            <td>{{ $station->station_code }}</td>
                        </tr>
                        <tr>
                            <th>الوضع التشغيلي</th>
                            <td>{{ $station->operational_status }}</td>
                        </tr>
                        <tr>
                            <th>سبب التوقف</th>
                            <td>{{ $station->stop_reason }}</td>
                        </tr>
                        <tr>
                            <th>مصدر الطاقة</th>
                            <td>{{ $station->energy_source }}</td>
                        </tr>
                        <tr>
                            <th>الجهة المشغلة</th>
                            <td>{{ $station->operator_entity }}</td>
                        </tr>
                        <tr>
                            <th>اسم المشغل</th>
                            <td>{{ $station->operator_name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- الكرت 2: الموقع -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-success">
                    الموقع
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>البلدة</th>
                            <td>{{ $station->town->town_name }}</td>
                        </tr>
                        <tr>
                            <th>العنوان التفصيلي</th>
                            <td>{{ $station->detailed_address }}</td>
                        </tr>
                        <tr>
                            <th>خط العرض</th>
                            <td>{{ $station->latitude }}</td>
                        </tr>
                        <tr>
                            <th>خط الطول</th>
                            <td>{{ $station->longitude }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- الكرت 3: الشبكة وتوصيل المياه -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-info">
                    الشبكة وتوصيل المياه
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>طريقة توصيل المياه</th>
                            <td>{{ $station->water_delivery_method }}</td>
                        </tr>
                        <tr>
                            <th>نسبة جاهزية الشبكة</th>
                            <td>{{ $station->network_readiness_percentage }}%</td>
                        </tr>
                        <tr>
                            <th>نوع الشبكة</th>
                            <td>{{ $station->network_type }}</td>
                        </tr>
                        <tr>
                            <th>عدد الأسر المستفيدة</th>
                            <td>{{ $station->beneficiary_families_count }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- الكرت 4: بيانات البئر -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-warning">
                    بيانات البئر
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>معدل التدفق الفعلي</th>
                            <td>{{ $station->actual_flow_rate }} متر مكعب/ساعة</td>
                        </tr>
                        <tr>
                            <th>نوع المحطة</th>
                            <td>{{ $station->station_type }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- الكرت 5: الأرض والمبنى -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-secondary">
                    الأرض والمبنى
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>مساحة الأرض</th>
                            <td>{{ $station->land_area }} متر مربع</td>
                        </tr>
                        <tr>
                            <th>نوع التربة</th>
                            <td>{{ $station->soil_type }}</td>
                        </tr>
                        <tr>
                            <th>ملاحظات المبنى</th>
                            <td>{{ $station->building_notes }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- الكرت 6: التعقيم والتحقق -->
        <div class="card-box">
            <div class="card">
                <div class="card-header bg-dark">
                    التعقيم والتحقق
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>هل توجد تعقيم؟</th>
                            <td>{{ $station->has_disinfection ? 'نعم' : 'لا' }}</td>
                        </tr>
                        <tr>
                            <th>سبب عدم التعقيم</th>
                            <td>{{ $station->disinfection_reason }}</td>
                        </tr>
                        <tr>
                            <th>تم التحقق؟</th>
                            <td>{{ $station->is_verified ? 'نعم' : 'لا' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
  
    <div style="text-align: center" class="text-center">
        <a href="{{ route('stations.index') }}" class="btn btn-primary">الرجوع إلى قائمة المحطات</a>
    </div>

@endsection
