<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Analytical\FinancialReportExport;
use App\Exports\Analytical\GeographicalReportExport;
use App\Exports\Analytical\ContractorPerformanceExport;
// استدعاء ملفات التصدير السابقة أيضاً لدمجها هنا إذا أردت
use App\Exports\ProjectComprehensiveExport;

class ReportsController extends Controller
{
    public function index()
    {
        return view('dashboard.reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $type = $request->report_type;
        $filters = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        switch ($type) {
            case 'financial':
                return Excel::download(new FinancialReportExport($filters), 'Financial_Report_' . date('Y-m-d') . '.xlsx');

            case 'geographical':
                return Excel::download(new GeographicalReportExport($filters), 'Geographical_Report_' . date('Y-m-d') . '.xlsx');

            case 'contractors':
                return Excel::download(new ContractorPerformanceExport($filters), 'Contractors_Performance_' . date('Y-m-d') . '.xlsx');

            case 'projects_comprehensive':
                // نستخدم الملف الشامل الذي قمت بإنشائه سابقاً
                return (new ProjectComprehensiveExport($filters))->download('All_Projects_' . date('Y-m-d') . '.xlsx');

            default:
                return redirect()->back()->with('error', 'نوع التقرير غير صحيح.');
        }
    }
}
