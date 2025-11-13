<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function login_index()
    {
        $setting = SettingModel::all();
        if (Auth::check()) {
            return redirect()->to(locale_route('home'));
        } else {
            return view('auth.login', compact('setting'));
        }

    }

    public function login_store(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            if (auth()->user()->roles == 'Admin') {
                return redirect()->to(locale_route('administration.home'))->with('success', 'Login Successfully!');
            } else {
                return redirect()->to(locale_route('home'))->with('success', 'Login Successfully!');
            }
        } else {
            return redirect()->back()->with('error', 'Email and password dont match');
        }
        return redirect()->to(locale_route('/login'));
    }

    public function signup_index()
    {
        $setting = SettingModel::all();
        if (Auth::check()) {
            return redirect()->to(locale_route('home'));
        } else {
            return view('auth.register', compact('setting'));
        }
    }

    public function signup_store(RegisterRequest $request)
    {
        if ($request->validated()) {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            $data['roles'] = 'User';
            $data['calorie_target'] = '1200';
            $data['nutrient_ration'] = '1';

            $create = User::create($data);
            if($create) {
                Auth::login($create);
                $request->session()->regenerate();
                return redirect()->to(locale_route('home'))->with('success', 'Register Successfully');
            }else {
                return redirect()->back()->with('error', 'Register Failed');
            }
        }
        return redirect()
            ->back()
            ->withErrors(['errors' => $request->errors()]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()
            ->to(locale_route('login'))
            ->with('success', 'Logout Successfully!');
    }
}
