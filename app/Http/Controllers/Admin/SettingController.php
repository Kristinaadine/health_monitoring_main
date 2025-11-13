<?php

namespace App\Http\Controllers\Admin;

use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();
        return view('admin.setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        if ($request->website_name) {
            $setting = SettingModel::where('key', 'website_name')->first();
            $setting->value = $request->website_name;
            $setting->save();

            return response()->json(['status' => 'success', 'message' => 'Website Name updated successfully']);
        }

        if ($request->website_logo) {
            $logo = $request->file('website_logo');
                $name = $logo->getClientOriginalName();
            $path = 'assets/img/logo';
            $logo->move($path, $name);

            $setting = SettingModel::where('key', 'website_logo')->first();
            $setting->value = $name;
            $setting->save();

            return response()->json(['status' => 'success', 'message' => 'Website Logo updated successfully']);
        }

        if ($request->maintenance_mode) {
            $setting = SettingModel::where('key', 'maintenance_mode')->first();
            $setting->value = $request->maintenance_mode;
            $setting->save();

            return response()->json(['status' => 'success', 'message' => 'Website Mode updated successfully']);
        }

    }
}
