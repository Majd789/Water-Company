<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('notes.index', compact('notes'));
    }

     public function create()
        {
            return view('notes.create');
        }

        public function show(Note $note)
        {
            return view('notes.show', compact('note'));
        }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'details' => 'required|string',
            'suggested_solution' => 'nullable|string',
        ]);

        Note::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'details' => $request->details,
            'suggested_solution' => $request->suggested_solution,
            'status' => 'معلقة',
        ]);

        return redirect()->route('notes.index')->with('success', 'تمت إضافة الملاحظة بنجاح.');
    }

    public function updateStatus(Request $request, Note $note)
    {
        $request->validate(['status' => 'required|in:معلقة,قيد المعالجة,مغلقة']);

        $note->update(['status' => $request->status]);

        return back()->with('success', 'تم تحديث حالة الملاحظة بنجاح.');
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return back()->with('success', 'تم حذف الملاحظة بنجاح.');
    }
}
