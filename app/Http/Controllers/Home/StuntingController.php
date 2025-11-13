<?php

namespace App\Http\Controllers\Home;

use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StuntingController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();
        return view('home.stunting', compact('setting'));
    }
}
