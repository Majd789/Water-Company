@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <h1>قائمة الصيانات للمحطات</h1>
        <a style="font-size: x-large" href="{{ route('dashboard.maintenances.export') }}" class="btn btn-success">📥
            (Excel)</a>

       
            <form method="GET" action="{{ route('dashboard.maintenances.index') }}" class="text-center mb-3"
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
      

        <form method="GET" action="{{ route('dashboard.maintenances.index') }}"
            class="d-flex justify-content-center mb-3">
            <div class="recent-orders d-flex align-items-center gap-2 p-3 rounded shadow bg-light">
              
                    <a id="btnb" href="{{ route('dashboard.maintenances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> &nbsp; إضافة
                    </a>
              
                <input type="text" name="search" id="search" class="form-control"
                    placeholder="أدخل اسم المحطة، كود المحطة، أو نوع الصيانة" value="{{ request('search') }}">
                <button id="btnb" type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> &nbsp;
                </button>
                <a id="btnb" href="{{ route('dashboard.maintenances.index') }}" class="btn btn-primary">
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
                    <th>#</th>
                    <th>المحطة</th>
                    <th>نوع الصيانة</th>
                    <th>تاريخ الصيانة</th>

                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($maintenances as $index => $maintenance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $maintenance->station->station_name ?? 'غير معروف' }}</td>
                        <td>{{ $maintenance->maintenanceType->name }}</td>
                        <td>{{ $maintenance->maintenance_date }}</td>

                        <td>
                            <a id="show" href="{{ route('dashboard.maintenances.show', $maintenance->id) }}"
                                class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                           
                                <a id="edit" href="{{ route('dashboard.maintenances.edit', $maintenance->id) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            
                           
                                <form action="{{ route('dashboard.maintenances.destroy', $maintenance->id) }}"
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

        {{ $maintenances->links() }}
    </div>

    <script>
        function selectUnit(unitId) {
            document.getElementById('selectedUnit').value = unitId;
            document.getElementById('unitFilterForm').submit();
        }
    </script>
@endsection
