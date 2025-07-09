@extends('layouts.app')
@section('title', 'تفاصيل المحطة: ' . $station->station_name)

@push('styles')
    <style>
        .widget-user .widget-user-header {
            height: 200px;
            background-size: cover;
            background-position: center center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff !important;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
        }

        .widget-user .widget-user-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .widget-user .widget-user-username,
        .widget-user .widget-user-desc {
            position: relative;
            z-index: 1;
        }

        .widget-user .widget-user-image {
            position: absolute;
            top: 150px;
            left: 50%;
            margin-left: -50px;
        }

        .widget-user .widget-user-image>img,
        .widget-user .widget-user-image>.icon-circle {
            width: 100px;
            height: 100px;
            border: 3px solid #fff;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .widget-user .widget-user-image>.icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 45px;
            color: #fff;
        }

        .card-footer {
            padding-top: 60px;
        }

        .description-block {
            margin-bottom: 1.5rem;
            text-align: center;
            padding: 0 10px;
        }

        .description-text {
            display: block;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .description-header {
            font-size: 1.1rem;
            font-weight: 600;
            color: #343a40;
            display: block;
        }

        .section-divider {
            border-top: 1px solid #dee2e6;
            margin: 2rem 0;
        }

        .stats-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1.5rem;
            margin-top: 1.5rem;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">تفاصيل: <span class="text-primary">{{ $station->station_name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.stations.index') }}">المحطات</a></li>
                    <li class="breadcrumb-item active">{{ $station->station_name }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12" id="station-card">
                <div class="card card-widget widget-user shadow-lg rounded">
                    <div class="widget-user-header"
                        style="background-image: url('{{ $station->cover_image_path ? asset('storage/' . $station->cover_image_path) : asset('dist/img/photo1.png') }}');">

                        {{-- حاوية جديدة لضمان الترتيب العمودي والتوسيط --}}
                        <div class="d-flex flex-column align-items-center">
                            <h3 class="widget-user-username display-4 mb-0" style="font-weight: bold;">
                                {{ $station->station_name }}</h3>
                            <h5 class="widget-user-desc mt-2">{{ $station->town->town_name ?? 'غير محدد' }}</h5>
                        </div>

                    </div>
                    <div class="widget-user-image">
                        @if ($station->station_image_path)
                            <img class="img-circle elevation-2" src="{{ asset('storage/' . $station->station_image_path) }}"
                                alt="Station Image">
                        @else
                            <div class="icon-circle img-circle elevation-2 bg-primary"><i
                                    class="fas fa-broadcast-tower"></i></div>
                        @endif
                    </div>
                    <div class="card-footer">
                        {{-- الملخص الرئيسي --}}
                        <div class="row">
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block"><span class="description-text">الحالة التشغيلية</span>
                                    <h5 class="description-header">{{ $station->operational_status }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 border-right">
                                <div class="description-block"><span class="description-text">أسرة مستفيدة</span>
                                    <h5 class="description-header">
                                        {{ number_format($station->beneficiary_families_count ?? 0) }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="description-block"><span class="description-text">التدفق الفعلي</span>
                                    <h5 class="description-header">{{ $station->actual_flow_rate ?? '0' }}
                                        <small>م³/س</small>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <hr class="section-divider">
                        {{-- تفاصيل الطاقة والتشغيل --}}
                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-bolt text-info fa-2x mb-2"></i><span
                                        class="description-text">مصدر الطاقة</span>
                                    <h5 class="description-header">{{ $station->energy_source ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i
                                        class="fas fa-industry text-secondary fa-2x mb-2"></i><span
                                        class="description-text">نوع المحطة</span>
                                    <h5 class="description-header">{{ $station->station_type ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-users-cog text-muted fa-2x mb-2"></i><span
                                        class="description-text">جهة التشغيل</span>
                                    <h5 class="description-header">{{ $station->operator_entity ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="description-block"><i class="fas fa-user-tie text-muted fa-2x mb-2"></i><span
                                        class="description-text">اسم المشغل</span>
                                    <h5 class="description-header">{{ $station->operator_name ?? 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>
                        <hr class="section-divider">
                        {{-- تفاصيل الشبكة والتوصيل --}}
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="description-block"><i class="fas fa-faucet text-success fa-2x mb-2"></i><span
                                        class="description-text">طريقة التوصيل</span>
                                    <h5 class="description-header">{{ $station->water_delivery_method ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4 border-right">
                                <div class="description-block"><i
                                        class="fas fa-percentage text-primary fa-2x mb-2"></i><span
                                        class="description-text">جاهزية الشبكة</span>
                                    <h5 class="description-header">{{ $station->network_readiness_percentage ?? '0' }}%
                                    </h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="description-block"><i
                                        class="fas fa-network-wired text-success fa-2x mb-2"></i><span
                                        class="description-text">نوع الشبكة</span>
                                    <h5 class="description-header">{{ $station->network_type ?? 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>
                        <hr class="section-divider">
                        {{-- تفاصيل الموقع والأرض --}}
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="description-block"><i
                                        class="fas fa-ruler-combined text-warning fa-2x mb-2"></i><span
                                        class="description-text">مساحة الأرض</span>
                                    <h5 class="description-header">{{ $station->land_area ?? '0' }} م²</h5>
                                </div>
                            </div>
                            <div class="col-md-4 border-right">
                                <div class="description-block"><i
                                        class="fas fa-layer-group text-warning fa-2x mb-2"></i><span
                                        class="description-text">نوع التربة</span>
                                    <h5 class="description-header">{{ $station->soil_type ?? 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="description-block"><i
                                        class="fas fa-map-marked-alt text-danger fa-2x mb-2"></i><span
                                        class="description-text">الإحداثيات</span>
                                    <h5 class="description-header">{{ $station->latitude }}, {{ $station->longitude }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        {{-- قسم إحصائيات المكونات --}}
                        <hr class="section-divider">
                        <h3 class="stats-title">إحصائيات مكونات المحطة</h3>
                        <div class="row">
                            @foreach ($statistics as $name => $data)
                                <div class="col-md-3 col-6">
                                    <a href="{{ $data['route'] }}" class="description-block">
                                        <i class="{{ $data['icon'] }} text-{{ $data['color'] }} fa-2x mb-2"></i>
                                        <span class="description-text">{{ $name }}</span>
                                        <h5 class="description-header">{{ $data['count'] }}</h5>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div> {{-- نهاية card-footer --}}
                </div> {{-- نهاية card --}}
            </div> {{-- نهاية col-md-12 --}}
        </div> {{-- نهاية row --}}

        {{-- قسم الأزرار (الآن خارج البطاقة) --}}
        <div class="row mt-3 mb-4 buttons-section">
            <div class="col-12 text-center">
                <button id="pdf-btn" class="btn btn-lg btn-primary"><i class="fas fa-file-pdf ml-1"></i> تحميل تقرير
                    PDF</button>
                <a href="{{ route('dashboard.stations.edit', $station->id) }}" class="btn btn-lg btn-warning"><i
                        class="fas fa-edit ml-1"></i> تعديل</a>
                <a href="{{ route('dashboard.stations.index') }}" class="btn btn-lg btn-secondary"><i
                        class="fas fa-arrow-left ml-1"></i> العودة للقائمة</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- (لا تغيير في الـ JavaScript، سيعمل كما هو) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pdfBtn = document.getElementById('pdf-btn');
            pdfBtn.addEventListener('click', function() {
                const {
                    jsPDF
                } = window.jspdf;
                const card = document.getElementById('station-card');
                pdfBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-1"></i> جارٍ التحضير...';
                pdfBtn.disabled = true;

                html2canvas(card, {
                    scale: 3,
                    useCORS: true,
                    backgroundColor: '#ffffff'
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/jpeg', 0.9);
                    const contentWidth = canvas.width;
                    const contentHeight = canvas.height;
                    const A4_WIDTH = 210;
                    const contentRatio = contentWidth / contentHeight;
                    const pdfWidth = A4_WIDTH - 20;
                    const pdfHeight = pdfWidth / contentRatio;
                    const pdf = new jsPDF({
                        orientation: 'portrait',
                        unit: 'mm',
                        format: [pdfWidth + 20, pdfHeight + 20]
                    });
                    pdf.addImage(imgData, 'JPEG', 10, 10, pdfWidth, pdfHeight);
                    pdf.save("station-report-{{ $station->station_code }}.pdf");
                    pdfBtn.innerHTML = '<i class="fas fa-file-pdf ml-1"></i> تحميل تقرير PDF';
                    pdfBtn.disabled = false;
                });
            });
        });
    </script>
@endpush
