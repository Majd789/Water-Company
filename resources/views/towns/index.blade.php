@extends('layouts.app')

@section('content')


    <div class="recent-orders" style="text-align: center">
        <h2>قائمة البلدات</h2>
        <a style="font-size: x-large" href="{{ route('towns.export') }}" class="btn btn-success">📥Excel </a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (auth()->check() && (auth()->user()->role_id == 'admin' || auth()->user()->unit_id == null))
            <form method="GET" action="{{ route('towns.index') }}" class="text-center mb-3" id="unitFilterForm">
                <div class="stats-container" style="margin-bottom: 20px;">
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
        <a id="btnb" href="{{ route('towns.create') }}">إضافة بلدة جديدة</a>

        <table class="table" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th scope="col">رقم</th>
                    <th scope="col">اسم البلدة</th>
                    <th scope="col">كود البلدة</th>
                    <th scope="col">الوحدة</th>
                    <th scope="col">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($towns as $town)
                    <tr>

                        <td>{{ $town->id }}</td>
                        <td>{{ $town->town_name }}</td>
                        <td>{{ $town->town_code }}</td>
                        <td>{{ $town->unit->unit_name }}</td>
                        <td>
                            <a id="show" href="{{ route('towns.show', $town->id) }}" class="btn btn-info btn-sm"><i
                                    class="fas fa-eye"></i></a>
                            <a id="edit" href="{{ route('towns.edit', $town->id) }}" class="btn btn-warning btn-sm"><i
                                    class="fas fa-pencil-alt"></i></a>

                            @if (auth()->check() && auth()->user()->role_id == 'admin')
                                <form action="{{ route('towns.destroy', $town->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button id="remove" type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('هل أنت متأكد؟')"><i class="fas fa-trash"></i></button>
                            @endif
                            </form>
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
