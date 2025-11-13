<?php

namespace App\Http\Controllers\Monitoring;

use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\DietUserModel;
use App\Services\BMICalculator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Growth\DietRequest;

class DietUserController extends Controller
{
    public function store(DietRequest $request, BMICalculator $bmiCalc)
    {
        try {
            $data = $request->validated();

            $bmi = $bmiCalc->hitungBMI($data['berat_badan'], $data['tinggi_badan']);
            $status = $bmiCalc->statusGizi($bmi);
            $rekomendasi = $bmiCalc->rekomendasi($status);

            $data['bmi'] = $bmi;
            $data['user_id'] = auth()->user()->id;
            $data['status_gizi'] = $status;
            $data['rekomendasi'] = $rekomendasi;

            $dietUser = DietUserModel::create($data);

            return redirect()
                ->to(locale_route('growth-detection.diet-user.show', $dietUser->id))
                ->with('success', 'Data diet berhasil disimpan dan dianalisis.');
        } catch (\Exception $e) {
            \Log::error('Error storing diet user: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
    
    public function show($locale, $id)
    {
        $setting = SettingModel::all();
        $dietUser = DietUserModel::findOrFail($id);

        return view('monitoring.growth-detection.dietuser.show', compact('dietUser', 'setting'));
    }

    public function list(Request $request)
    {
        $setting = SettingModel::all();
        $cek = DietUserModel::where('user_id', auth()->user()->id)->get()->count();
        if ($cek == 0) {
            return redirect()->to(locale_route("growth-detection.diet-user"));
        }
        $data = DietUserModel::where('user_id', auth()->user()->id)->get();
        return view('monitoring.growth-detection.dietuser.list', compact('setting', 'data'));
    }
}
