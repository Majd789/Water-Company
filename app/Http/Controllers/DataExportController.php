<?php

namespace App\Http\Controllers;

use App\Exports\AllDataExport;
use Maatwebsite\Excel\Facades\Excel;

class DataExportController extends Controller
{
    // وظيفة لتصدير جميع البيانات
    public function exportAll()
    {
        // تحميل الملف و تسميته
        return Excel::download(new AllDataExport, 'all_data.xlsx');
    }
}
