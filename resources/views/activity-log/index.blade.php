@extends('layouts.app')

@section('content')
    <div class="recent-orders p-3" style="text-align: center">
        <h2 class="text-center mb-4">سجل التغييرات</h2>

        <form method="GET" class="mb-4 d-flex flex-column flex-md-row justify-content-center align-items-center gap-4">
            <div class="flex-grow-1 w-100 w-md-auto">
                <label for="user_id" class="form-label visually-hidden">المستخدم</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">كل المستخدمين</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-grow-1 w-100 w-md-auto">
                <label for="model" class="form-label visually-hidden">الموديل</label>
                <select name="model" id="model" class="form-select">
                    <option value="">كل الموديلات</option>
                    @foreach ($models as $model)
                        {{-- Make sure $model is the base name here from the controller --}}
                        <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                            {{ $model }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-100 w-md-auto">
                <button type="submit" class="btn btn-primary w-100">تصفية</button>
            </div>

            {{-- --- New Export Button --- --}}
            <div class="w-100 w-md-auto">
                <a href="{{ route('activity-log.export', request()->query()) }}" class="btn btn-success w-100">
                    <i class="bi bi-file-earmark-excel me-2"></i>تصدير إلى Excel
                </a>
            </div>
            {{-- --- End New Export Button --- --}}
        </form>

        <hr class="my-4"> {{-- Added a separator for clarity --}}

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th>المستخدم</th>
                        <th>الحدث</th>
                        <th>الموديل</th>
                        <th>رقم العنصر</th>
                        <th>التاريخ</th>
                        <th>تفاصيل التغيير</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr>
                            <td>{{ $activity->causer ? $activity->causer->name : 'غير معروف' }}</td>
                            <td>{{ ucfirst($activity->description) }}</td>
                            <td>{{ class_basename($activity->subject_type) }}</td>
                            <td>{{ $activity->subject_id }}</td>
                            <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @if ($activity->properties && isset($activity->properties['attributes']))
                                    <details>
                                        <summary class="text-primary cursor-pointer">عرض التفاصيل</summary>
                                        <ul class="activity-details-list list-unstyled text-start mt-2 border p-2 rounded bg-light"
                                            dir="rtl">
                                            @foreach ($activity->properties['attributes'] as $key => $new)
                                                <li class="d-flex flex-wrap align-items-baseline mb-1">
                                                    <strong class="me-1">{{ $key }}:</strong>
                                                    @if (isset($activity->properties['old'][$key]))
                                                        <span class="text-danger text-decoration-line-through me-1"
                                                            dir="ltr">
                                                            {{ $activity->properties['old'][$key] }}
                                                        </span>
                                                        <i class="bi bi-arrow-left text-muted mx-1"></i>
                                                    @else
                                                        <span class="text-muted me-1">(جديد)</span>
                                                    @endif
                                                    <span class="text-success" dir="ltr">{{ $new }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </details>
                                @else
                                    <span>—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">لا توجد تغييرات مسجلة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $activities->links() }}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        details>summary::-webkit-details-marker {
            display: none;
        }

        details>summary {
            list-style: none;
        }

        .activity-details-list {
            list-style-type: none !important;
            padding-right: 0 !important;
            margin-right: 0 !important;
        }

        .activity-details-list li {
            justify-content: flex-start;
        }

        .user-change-counts .card {
            min-width: 180px;
            max-width: 250px;
            flex: 1;
        }
    </style>
@endpush
