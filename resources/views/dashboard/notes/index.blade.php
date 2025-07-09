@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center ">
        <h2 class="mb-4">قسم الملاحظات</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>الاسم</th>
                    <th>الموضوع</th>
                    <th>الحالة</th>
                    <th>العمليات</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($notes as $note)
                    <tr>
                        <td>{{ $note->id }}</td>
                        <td>{{ $note->user->name ?? 'مجهول' }}</td>
                        <td>{{ $note->subject }}</td>

                        <td>{{ $note->status }}</td>

                        <td>
                            <a id="btnb" href="{{ route('dashboard.notes.show', $note->id) }}"
                                class="btn btn-info btn-sm">عرض</a>
                          
                                <form action="{{ route('dashboard.notes.destroy', $note->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button id="btnd" type="submit" class="btn btn-sm btn-danger">حذف</button>

                                </form>
                                <form action="{{ route('dashboard.notes.updateStatus', $note->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="معلقة" {{ $note->status == 'معلقة' ? 'selected' : '' }}>معلقة
                                        </option>
                                        <option value="قيد المعالجة"
                                            {{ $note->status == 'قيد المعالجة' ? 'selected' : '' }}>قيد المعالجة</option>
                                        <option value="مغلقة" {{ $note->status == 'مغلقة' ? 'selected' : '' }}>مغلقة
                                        </option>
                                    </select>
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
