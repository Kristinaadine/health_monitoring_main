<?php

namespace App\Http\Controllers\Profile;

use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\NutrientRatioModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateNutritionRequest;

class ProfileController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();
        return view('profile.index', compact('setting'));
    }

    public function edit()
    {
        $setting = SettingModel::all();
        return view('profile.edit-profile', compact('setting'));
    }
    public function update(UpdateProfileRequest $request)
    {
        if ($request->validated()) {
            $user = auth()->user();
            $data = $request->all();
            $data['login_edit'] = auth()->user()->email;
            $user->update($data);

            return redirect()->to(locale_route('profile')->with('success', 'Profile updated successfully.'));
        }

        return redirect()
            ->back()
            ->withErrors(['errors' => $request->errors()]);
    }
    public function changePassword()
    {
        $setting = SettingModel::all();
        return view('profile.edit-pass', compact('setting'));
    }
    public function updatePassword(UpdatePasswordRequest $request)
    {
        if ($request->validated()) {
            $user = auth()->user();

            // Check if the current password is correct
            if (!password_verify($request->input('current_password'), $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            // Update the password
            $user->password = bcrypt($request->input('new_password'));
            $user->login_edit = auth()->user()->email;
            $user->save();

            return redirect()->to(locale_route('profile')->with('success', 'Password updated successfully.'));
        }
        return redirect()
            ->back()
            ->withErrors(['errors' => $request->errors()]);
    }

    public function nutrition()
    {
        $ratio = NutrientRatioModel::all();
        $setting = SettingModel::all();
        return view('profile.edit-nutrition', compact('ratio', 'setting'));
    }
    public function nutritionUpdate(UpdateNutritionRequest $request)
    {
        if ($request->validated()) {
            $user = auth()->user();
            $data = $request->all();
            $data['login_edit'] = auth()->user()->email;
            $user->update($data);

            return redirect()->to(locale_route('profile')->with('success', 'Profile updated successfully.'));
        }

        return redirect()
            ->back()
            ->withErrors(['errors' => $request->errors()]);
    }

    public function help()
    {
        $setting = SettingModel::all();
        return view('profile.help', compact('setting'));
    }
}
