<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class StationMapController extends Controller
{
    public function index()
    {
        // لا حاجة لتمرير بيانات حالياً
        return view('stations-map.map');
    }
}
