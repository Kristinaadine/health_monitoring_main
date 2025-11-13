<?php

namespace App\Http\Controllers\Admin;

use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeAdminController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();
        return view('admin.home.index', compact('setting'));
    }
}
