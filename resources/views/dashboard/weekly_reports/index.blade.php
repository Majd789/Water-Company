<style>
    .custom-date {
        width: 100%;
        padding-right: 40px;
        /* مساحة لأيقونة التقويم */
        padding-left: 12px;
        height: 38px;
        font-size: 15px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background-color: #fff;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
</style>
@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <h1>قائمة التقارير الأسبوعية</h1>
        <a style="font-size: x-large" href="{{ route('dashboard.weekly_reports.export') }}"
            class="btn btn-success">📥(Excel)</a>

        <form method="GET" action="{{ route('dashboard.weekly_reports.index') }}" class="text-center mb-3"
            id="unitFilterForm">
            <div class="stats-container">
                <div class="stat-box unit-box" onclick="selectUnit('')" data-unit-id=""
                    style="{{ request('unit_id') == '' ? 'background:#b3d4f6; color:white;' : '' }}">
                    <h4>جميع الوحدات</h4>
                </div>

                @foreach ($units as $unit)
                    <div class="stat-box unit-box" onclick="selectUnit('{{ $unit->id }}')"
                        data-unit-id="{{ $unit->id }}"
                        style="{{ request('unit_id') == $unit->id ? 'background:#b3d4f6; color:white;' : '' }}">
                        <h4>{{ $unit->unit_name }}</h4>
                    </div>
                @endforeach
            </div>
            <input type="hidden" name="unit_id" id="selectedUnit" value="{{ request('unit_id') }}">
        </form>

        <form method="GET" action="{{ route('dashboard.weekly_reports.index') }}"
            class="d-flex justify-content-center mb-3">
            <div class="recent-orders d-flex align-items-center gap-2 p-3 rounded shadow bg-light flex-wrap">


                {{-- حقل التاريخ --}}
                <div class="date-wrapper">
                    <input type="date" name="report_date" id="report_date" class="form-control custom-date"
                        value="{{ request('report_date') }}">
                    <span class="datepicker-icon"></span>
                </div>


                <a id="btnb" href="{{ route('dashboard.weekly_reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> &nbsp; إضافة
                </a>


                {{-- حقل البحث --}}
                <input type="text" name="search" id="search" class="form-control"
                    placeholder="أدخل اسم المرسل، الوحدة، أو اسم صيانة" value="{{ request('search') }}">

                {{-- زر البحث --}}
                <button id="btnb" type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> &nbsp;
                </button>

                {{-- زر الإلغاء --}}
                <a id="btnb" href="{{ route('dashboard.weekly_reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> &nbsp;
                </a>

            </div>
        </form>



        <div class="login-card">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-striped" style="width: 100%;">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المُرسل</th>
                        <th>الوحدة</th>
                        <th>الوضع التشغيلي</th>
                        <th>جهة الصيانة</th>
                        <th>أعمال إدارية</th>
                        <th>التحكم</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') }}</td>
                            <td>{{ $report->sender_name }}</td>
                            <td>{{ $report->unit->unit_name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($report->operational_status, 30) }}</td>
                            <td>{{ $report->maintenance_entity }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($report->administrative_works, 30) }}</td>
                            <td>
                                <a id="show" href="{{ route('dashboard.weekly_reports.show', $report->id) }}"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                              
                                    <a id="edit" href="{{ route('dashboard.weekly_reports.edit', $report->id) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                              
                            
                                    <form action="{{ route('dashboard.weekly_reports.destroy', $report->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button id="remove" type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('هل أنت متأكد؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function selectUnit(unitId) {
            // حدّث قيمة الحقل المخفي
            document.getElementById('selectedUnit').value = unitId;

            // تحديث الألوان لتمييز الوحدة المختارة
            document.querySelectorAll('.unit-box').forEach(function(el) {
                el.style.background = '';
                el.style.color = '';
            });

            const selectedBox = document.querySelector(`[data-unit-id="${unitId}"]`);
            if (selectedBox) {
                selectedBox.style.background = '#b3d4f6';
                selectedBox.style.color = 'white';
            }

            // إرسال النموذج تلقائيًا
            document.getElementById('unitFilterForm').submit();
        }
    </script>
@endsection
