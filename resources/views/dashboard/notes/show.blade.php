@extends('layouts.app')

@section('content')
    <div class="recent-orders" style="text-align: center">
        <h2 class="mb-4">تفاصيل الملاحظة</h2>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $note->subject }}</h4>
                <p><strong>التفاصيل:</strong> {{ $note->details }}</p>
                <p><strong>الحل المقترح:</strong> {{ $note->suggested_solution ?? 'لم يتم اقتراح حل' }}</p>
                <p><strong>الحالة:</strong> <span class="badge bg-info">{{ $note->status }}</span></p>
                <p><strong>أضيفت بواسطة:</strong> {{ $note->user->name ?? 'مجهول' }}</p>
                <p><strong>تاريخ الإضافة:</strong> {{ $note->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <a href="{{ route('dashboard.notes.index') }}" class="btn btn-secondary mt-3">العودة للقائمة</a>
    </div>
@endsection
