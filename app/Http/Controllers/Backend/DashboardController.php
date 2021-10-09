<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * Index
     *
     * @param
     *
     * @return mixed
     *
     * @author SD
     */
    public function index() {
        return view('admin.dashboard.index');
    }
}
