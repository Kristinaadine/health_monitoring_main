<?php

namespace App\Http\Controllers\Home;

use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\CalorieHistoryModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CaloriCalcController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();
        $histories = CalorieHistoryModel::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('home.caloricalc.index', compact('setting', 'histories'));
    }

    public function create()
    {
        $setting = SettingModel::all();
        return view('home.caloricalc.form', compact('setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'age' => 'required|integer|min:1|max:120',
            'sex' => 'required|in:male,female',
            'height' => 'required|numeric|min:50|max:250',
            'weight' => 'required|numeric|min:20|max:300',
            'activity_level' => 'required|numeric',
            'gain_loss_amount' => 'required|integer',
            'daily_calories' => 'required|integer',
            'carbs' => 'required|integer',
            'protein' => 'required|integer',
            'fat' => 'required|integer',
        ]);

        CalorieHistoryModel::create([
            'user_id' => Auth::id(),
            'age' => $request->age,
            'sex' => $request->sex,
            'height' => $request->height,
            'weight' => $request->weight,
            'activity_level' => $request->activity_level,
            'gain_loss_amount' => $request->gain_loss_amount,
            'daily_calories' => $request->daily_calories,
            'carbs' => $request->carbs,
            'protein' => $request->protein,
            'fat' => $request->fat,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan',
            'redirect' => locale_route('caloric')
        ]);
    }

    public function destroy($locale, $id)
    {
        try {
            $history = CalorieHistoryModel::where('user_id', Auth::id())->findOrFail($id);
            $history->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
