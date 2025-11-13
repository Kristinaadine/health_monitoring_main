<?php

namespace App\Http\Controllers\Monitoring;

use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\StuntingUserModel;
use App\Http\Controllers\Controller;

class GrowthDetectionController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();
        return view('monitoring.growth-detection.index', compact('setting'));
    }

    public function dietUser()
    {
        $setting = SettingModel::all();
        return view('monitoring.growth-detection.dietuser.index', compact('setting'));
    }

    public function stunting()
    {
        $setting = SettingModel::all();
        $cek = StuntingUserModel::where('user_id', auth()->user()->id)->get()->count();
        if ($cek == 0) {
            return redirect()->to(locale_route("growth-detection.stunting.create"));
        }
        $data = StuntingUserModel::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get();
        return view('monitoring.growth-detection.stunting.index', compact('setting', 'data'));
    }
}
