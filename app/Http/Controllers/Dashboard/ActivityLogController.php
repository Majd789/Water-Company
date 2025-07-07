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

    // للحصول على المستخدمين والموديلات لاستخدامهم في الفلتر
    $users = User::select('id', 'name')->get();
    $models = Activity::select(DB::raw('DISTINCT(subject_type)'))->pluck('subject_type');

    return view('activity-log.index', compact('activities', 'users', 'models'));
}
 // --- New Export Method ---
    public function export(Request $request)
    {
        $userId = $request->input('user_id');
        $modelName = $request->input('model');

        // Generate a dynamic filename based on filters
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
}
