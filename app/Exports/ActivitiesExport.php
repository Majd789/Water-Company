<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ActivitiesExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:activities_logs.view')->only(['index', 'show']);
        $this->middleware('permission:activities_logs.create')->only(['create', 'store']);
        $this->middleware('permission:activities_logs.edit')->only(['edit', 'update']);
        // --- START: تعديل هنا ---
        // أضف 'deleteAll' إلى هذه القائمة لحماية الدالة الجديدة
        $this->middleware('permission:activities_logs.delete')->only(['destroy', 'deleteAll']);
        // --- END: تعديل هنا ---
    }

    public function index(Request $request)
    {
        $query = Activity::query()->latest();

        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        if ($request->filled('model')) {
            $query->where('subject_type', 'like', '%'.$request->model);
        }

        $activities = $query->paginate(1000);

        $users = User::select('id', 'name')->get();
        $models = Activity::select(DB::raw('DISTINCT(subject_type)'))->pluck('subject_type');

        return view('dashboard.activity-log.index', compact('activities', 'users', 'models'));
    }

    public function export(Request $request)
    {
        $userId = $request->input('user_id');
        $modelName = $request->input('model');

        $filename = 'سجل_التغييرات';
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $filename .= '_للمستخدم_' . $user->name;
            }
        }
        if ($modelName) {
            $filename .= '_للموديل_' . $modelName;
        }
        $filename .= '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ActivitiesExport($userId, $modelName), $filename);
    }

    // --- START: الدالة الجديدة للحذف الشامل ---
    /**
     * Remove all activity logs from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll()
    {
        // استخدام truncate أسرع للحذف الكامل، أو delete للحذف مع الالتزام بالـ events
        Activity::query()->truncate();

        return redirect()->route('dashboard.activity-log.index')
                         ->with('success', 'تم حذف جميع سجلات النشاط بنجاح.');
    }
    // --- END: الدالة الجديدة ---
}
