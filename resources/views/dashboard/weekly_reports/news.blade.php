{{-- resources/views/weekly_reports/news.blade.php --}}
<link href="{{ asset('css/show.css') }}" rel="stylesheet">
<style>
    .post-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        max-width: 750px;
        margin: 30px auto;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .post-header .unit-name {
        font-weight: 600;
    }

    .post-header .report-date {
        font-size: 0.9rem;
    }

    .post-body p {
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .image-carousel {
        position: relative;
        overflow: hidden;
        margin-top: 15px;
    }

    .image-carousel img {
        width: 100%;
        height: auto;
        max-height: 350px;
        object-fit: cover;
        border: 1px solid #ddd;
        display: none;
    }

    .image-carousel img.active {
        display: block;
    }

    .carousel-nav {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        transform: translateY(-50%);
        display: flex;
        justify-content: space-between;
    }

    .carousel-nav button {
        background: rgba(0, 0, 0, 0.5);
        border: none;
        color: #fff;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .download-btn {
        position: absolute;
        top: 10px;
        left: 10px;
        background: transparent;
        color: #007bff;
        border: none;
        cursor: pointer;
        font-size: 1.2rem;
    }
</style>
@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center; padding: 20px;">

        <h1 class="mb-4">تصفح التقارير</h1>

        @foreach ($reports as $report)
            @php
                $images = [];
                if ($report->maintenance_image) {
                    $images[] = $report->maintenance_image;
                }
                if ($report->administrative_image) {
                    $images[] = $report->administrative_image;
                }
            @endphp

            <div class="post-card" id="post-{{ $report->id }}">
                {{-- أيقونة تنزيل --}}
                <button class="download-btn" data-post-id="{{ $report->id }}" title="تحميل كصورة">
                    <i class="fas fa-download"></i>
                </button>

                {{-- رأس المنشور --}}
                <div class="post-header d-flex justify-content-between align-items-center">
                    <span class="unit-name h5 mb-0">{{ $report->unit->unit_name }}</span>
                    <span class="report-date text-muted">
                        {{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}
                    </span>
                </div>

                {{-- محتوى المنشور --}}
                <div class="post-body text-right mt-3">
                    @if ($report->sender_name)
                        <p><strong>المرسل:</strong> {{ $report->sender_name }}</p>
                    @endif
                    <p><strong>الوضع التشغيلي:</strong> {{ $report->operational_status }}</p>
                    @if ($report->stop_reason)
                        <p><strong>سبب التوقف:</strong> {{ $report->stop_reason }}</p>
                    @endif
                    @if ($report->maintenance_works)
                        <p><strong>أعمال الصيانة:</strong> {{ $report->maintenance_works }}</p>
                    @endif
                    @if ($report->administrative_works)
                        <p><strong>الأعمال الإدارية:</strong> {{ $report->administrative_works }}</p>
                    @endif
                    @if ($report->additional_notes)
                        <p><strong>ملاحظات إضافية:</strong> {{ $report->additional_notes }}</p>
                    @endif
                </div>

                {{-- معرض الصور --}}
                @if (count($images) > 0)
                    <div class="image-carousel" data-post-id="{{ $report->id }}">
                        @foreach ($images as $i => $img)
                            <img src="{{ asset($img) }}" class="{{ $i === 0 ? 'active' : '' }}"
                                alt="صورة {{ $i + 1 }}">
                        @endforeach

                        {{-- أسهم فقط إذا أكثر من صورة --}}
                        @if (count($images) > 1)
                            <div class="carousel-nav">
                                <button class="prev">&#10094;</button>
                                <button class="next">&#10095;</button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach

        {{-- ترقيم الصفحات --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $reports->withQueryString()->links() }}
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        // تنزيل المنشور كصورة
        document.querySelectorAll('.download-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const postEl = document.getElementById('post-' + btn.dataset.postId);
                html2canvas(postEl).then(canvas => {
                    const link = document.createElement('a');
                    link.href = canvas.toDataURL('image/png');
                    link.download = `report_${btn.dataset.postId}.png`;
                    link.click();
                });
            });
        });

        // Carousel navigation
        document.querySelectorAll('.image-carousel').forEach(carousel => {
            const images = carousel.querySelectorAll('img');
            let idx = 0;
            const show = i => images.forEach((img, j) => img.classList.toggle('active', j === i));
            carousel.querySelector('.prev')?.addEventListener('click', () => {
                idx = (idx - 1 + images.length) % images.length;
                show(idx);
            });
            carousel.querySelector('.next')?.addEventListener('click', () => {
                idx = (idx + 1) % images.length;
                show(idx);
            });
        });
    </script>
@endsection
