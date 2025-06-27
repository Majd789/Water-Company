@extends('layouts.app')

@section('content')

    <div class="recent-orders" style="text-align: center">
        <h2>قائمة مجموعات التوليد</h2>
        <a style="font-size: x-large" href="{{ route('generation-groups.export') }}" class="btn btn-primary mb-3">
            📥Excel </a>
        @if (auth()->check() && (auth()->user()->role_id == 'admin' || auth()->user()->unit_id == null))
            <form method="GET" action="{{ route('generation-groups.index') }}" class="text-center mb-3" id="unitFilterForm">
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
        <!-- فورم البحث -->
        <form method="GET" action="{{ route('generation-groups.index') }}" class="d-flex justify-content-center mb-3">
            <div class="recent-orders d-flex align-items-center gap-2 p-3 rounded shadow bg-light">
                @if (auth()->check() && in_array(auth()->user()->role_id, ['admin', 'super']))
                    <a id="btnb" href="{{ route('generation-groups.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> &nbsp; إضافة
                    </a>
                @endif
                <input type="text" name="search" id="search" class="form-control"
                    placeholder="أدخل اسم المحطة، كود المحطة، أو اسم المنهل" value="{{ request('search') }}">

                <button id="btnb" type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> &nbsp;
                </button>

                <a id="btnb" href="{{ route('generation-groups.index') }}" class="btn btn-primary">
                    <i class="fas fa-times"></i> &nbsp;
                </a>
            </div>
        </form>

        <!-- رسالة النجاح -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- زر إضافة مجموعة توليد جديدة -->

        <!-- جدول عرض مجموعات التوليد -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>اسم المحطة</th>
                    <th>اسم المولدة</th>
                    <th>استطاعة التوليد (KVA)</th>
                    <th>نسبة الجاهزية</th>
                    <th>الوضع التشغيلي</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($generationGroups as $group)
                    <tr>
                        <td>{{ $group->id }}</td>
                        <td>{{ $group->station->station_name }}</td>
                        <td>{{ $group->generator_name }}</td>
                        <td>{{ $group->generation_capacity }}</td>
                        <td>{{ $group->generation_group_readiness_percentage ?? 'غير متوفرة' }}</td>
                        <td>
                            <span
                                class="badge {{ $group->operational_status === 'working' ? 'bg-success' : 'bg-danger' }}">
                                {{ $group->operational_status === 'working' ? 'يعمل' : 'متوقف' }}
                            </span>
                        </td>
                        <td>
                            <a id="show" href="{{ route('generation-groups.show', $group->id) }}"
                                class="btn btn-info btn-sm"> <i class="fas fa-eye"></i></a>
                            @if (auth()->check() && in_array(auth()->user()->role_id, ['admin', 'super']))
                                <a id="edit" href="{{ route('generation-groups.edit', $group->id) }}"
                                    class="btn btn-warning btn-sm"> <i class="fas fa-pencil-alt"></i></a>
                            @endif
                            @if (auth()->check() && auth()->user()->role_id == 'admin')
                                <form action="{{ route('generation-groups.destroy', $group->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button id="remove" type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('هل أنت متأكد من الحذف؟')"> <i
                                            class="fas fa-trash"></i></button>
                            @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">لا توجد بيانات لعرضها.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- روابط الترقيم -->
        <div class="d-flex justify-content-center mt-3">
            {{ $generationGroups->links() }}
        </div>
    </div>
    <script>
        function selectUnit(unitId) {
            document.getElementById('selectedUnit').value = unitId;
            document.getElementById('unitFilterForm').submit();
        }
    </script>
@endsection
