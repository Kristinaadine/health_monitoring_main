<?php

namespace App\Http\Controllers\Home;

use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GrowthMonitoringModel;

class HomeController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();

        $height = [];
        $weight = [];
        $xAxis = [];

        $data = GrowthMonitoringModel::with(['history' => function($query) {
                    $query->select('id', 'id_growth', 'type', 'zscore');
                }])
                ->select('id', 'name', 'age', 'users_id')
                ->where('users_id', auth()->user()->id)
                ->get();

            if (count($data) > 0) {
                $name = $data[0]->name;
            } else {
                $name = '';
            }

            $data = GrowthMonitoringModel::with(['history' => function($query) {
                    $query->select('id', 'id_growth', 'type', 'zscore');
                }])
                ->select('id', 'name', 'age', 'users_id')
                ->where('users_id', auth()->user()->id)
                ->where('name', $name)
                ->orderBy('id', 'desc')
                ->get();

        for ($i = count($data) - 1; $i >= 0; $i--) {
            $history = $data[$i]->history;
        
            // Skip jika tidak ada history
            if ($history->count() == 0) {
                continue;
            }
            
            // Cari history berdasarkan type untuk konsistensi
            $heightHistory = $history->where('type', 'LH')->first();
            $weightHistory = $history->where('type', 'W')->first();
            
            // Pastikan data history ada dan ambil Z-Score
            $heightZ = $heightHistory && $heightHistory->zscore !== null 
                ? (float) $heightHistory->zscore 
                : 0;
            $weightZ = $weightHistory && $weightHistory->zscore !== null 
                ? (float) $weightHistory->zscore 
                : 0;
        
            $height[] = $heightZ;
            $weight[] = $weightZ;
            $xAxis[]  = $data[$i]->age . " month";
        }
        
        $graph = [
            'height' => $height,
            'weight' => $weight,
            'xAxis' => $xAxis,
        ];

        return view('index', compact('setting', 'graph'));
    }
}
