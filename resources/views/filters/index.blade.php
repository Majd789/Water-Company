@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <h1>قائمة المرشحات</h1>
        <a style="font-size: x-large" href="{{ route('filters.export') }}" class="btn btn-success">📥(Excel)</a>
        @if (auth()->check() && (auth()->user()->role_id == 'admin' || auth()->user()->unit_id == null))
            <form method="GET" action="{{ route('filters.index') }}" class="text-center mb-3" id="unitFilterForm">
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
        @endif
        <form method="GET" action="{{ route('filters.index') }}" class="d-flex justify-content-center mb-3">
            <div class="recent-orders d-flex align-items-center gap-2 p-3 rounded shadow bg-light">
                @if (auth()->check() && in_array(auth()->user()->role_id, ['admin', 'super']))
                    <a id="btnb" href="{{ route('filters.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> &nbsp; إضافة
                    </a>
                @endif

                <input type="text" name="search" id="search" class="form-control"
                    placeholder="أدخل اسم المحطة، كود المحطة، أو اسم المنهل" value="{{ request('search') }}">

                <button id="btnb" type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> &nbsp;
                </button>

                <a id="btnb" href="{{ route('filters.index') }}" class="btn btn-primary">
                    <i class="fas fa-times"></i> &nbsp;
                </a>
            </div>
        </form>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif



        <table class="table">
            <thead>
                <tr>
                    <th>المحطة</th>
                    <th>استطاعة المرشح</th>
                    <th>حالة الجاهزية</th>
                    <th>نوع المرشح</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($filters as $filter)
                    <tr>
                        <td>{{ $filter->station->station_name }}</td>
                        <td>{{ $filter->filter_capacity }}</td>
                        <td>{{ $filter->readiness_status }}</td>
                        <td>{{ $filter->filter_type }}</td>
                        <td>
                            <a id="show" href="{{ route('filters.show', $filter->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if (auth()->check() && in_array(auth()->user()->role_id, ['admin', 'super']))
                                <a id="edit" href="{{ route('filters.edit', $filter->id) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            @endif
                            @if (auth()->check() && auth()->user()->role_id == 'admin')
                                <form action="{{ route('filters.destroy', $filter->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button id="remove" type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('هل أنت متأكد؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        function selectUnit(unitId) {
            document.getElementById('selectedUnit').value = unitId;
            document.getElementById('unitFilterForm').submit();
        }
    </script>
@endsection
