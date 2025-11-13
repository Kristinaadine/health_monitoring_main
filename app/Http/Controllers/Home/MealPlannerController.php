<?php

namespace App\Http\Controllers\Home;

use App\Models\FoodModel;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\NutrientRatioModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Profile\UpdateNutritionRequest;

class MealPlannerController extends Controller
{
    public function index()
    {
        $ratio = NutrientRatioModel::all();
        $setting = SettingModel::all();
        return view('home.meal-planner.index', compact('setting', 'ratio'));
    }

    public function updateNutrition(UpdateNutritionRequest $request)
    {
        try {
            $validated = $request->validated();
            
            $user = auth()->user();
            $data = $validated;
            $data['login_edit'] = auth()->user()->email;
            $user->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Nutrition updated successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating nutrition: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
            ], 500);
        }
    }

    public function getMeal()
    {
        // Validasi user sudah set nutrition target
        if (!auth()->user()->calorie_target || !auth()->user()->nutrient_ration) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please set your nutrition target first in Profile > Nutrition.',
            ], 400);
        }

        // Cek apakah ada data food
        $foodCount = FoodModel::count();
        if ($foodCount == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No food data available. Please contact administrator.',
            ], 404);
        }

        $data = [];

        $breakfast = FoodModel::where('calories', '>=', auth()->user()->calorie_target * 0.2)
            ->where('calories', '<=', auth()->user()->calorie_target * 0.25)
            ->get();

        if ($breakfast->count() == 0) {
            $breakfast = FoodModel::get();
        }
        $data['breakfast'] = $breakfast[array_rand($breakfast->toArray())];

        if ($data['breakfast']->image_path == null) {
            $data['breakfast']->image_path = asset('assets-admin/assets/img/noimage.png');
        } else {
            $data['breakfast']->image_path = Storage::url($data['breakfast']->image_path);
        }

        $lunch = FoodModel::where('calories', '>=', auth()->user()->calorie_target * 0.3)
            ->where('calories', '<=', auth()->user()->calorie_target * 0.4)
            ->get();

        if ($lunch->count() == 0) {
            $lunch = FoodModel::where('id', '!=', $data['breakfast']->id)->get();
        }
        $data['lunch'] = $lunch[array_rand($lunch->toArray())];

        if ($data['lunch']->image_path == null) {
            $data['lunch']->image_path = asset('assets-admin/assets/img/noimage.png');
        } else {
            $data['lunch']->image_path = Storage::url($data['lunch']->image_path);
        }

        $dinner = FoodModel::where('calories', '>=', auth()->user()->calorie_target * 0.2)->where('calories', '<=', auth()->user()->calorie_target * 0.35);
        if ($lunch->count() > 0) {
            $dinner = $dinner->where('id', '!=', $data['lunch']->id);
        }
        if ($breakfast->count() > 0) {
            $dinner = $dinner->where('id', '!=', $data['breakfast']->id);
        }
        $dinner = $dinner->get();

        if ($dinner->count() == 0) {
            $dinner = FoodModel::where('id', '!=', $data['breakfast']->id)->where('id', '!=', $data['lunch']->id)->get();
        }
        $data['dinner'] = $dinner[array_rand($dinner->toArray())];

        if ($data['dinner']->image_path == null) {
            $data['dinner']->image_path = asset('assets-admin/assets/img/noimage.png');
        } else {
            $data['dinner']->image_path = Storage::url($data['dinner']->image_path);
        }

        $data['protein'] = $data['breakfast']->protein + $data['lunch']->protein + $data['dinner']->protein;
        $data['carbs'] = $data['breakfast']->carbs + $data['lunch']->carbs + $data['dinner']->carbs;
        $data['fiber'] = $data['breakfast']->fiber + $data['lunch']->fiber + $data['dinner']->fiber;

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'Meal plan generated successfully.',
        ]);
    }
}
