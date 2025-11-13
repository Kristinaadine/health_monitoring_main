<?php

namespace App\Http\Controllers;

use App\Models\SettingModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();
        
        // Get growth monitoring data for dashboard
        $growthData = [];
        if (auth()->check()) {
            $growthData = \App\Models\GrowthMonitoringModel::with('history')
                ->where('users_id', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->take(10) // Last 10 records
                ->get();
                
            // Prepare graph data
            $height = [];
            $weight = [];
            $xAxis = [];
            
            for ($i = count($growthData) - 1; $i >= 0; $i--) {
                $history = $growthData[$i]->history;
                
                $heightZ = isset($history[0]) && $history[0]->zscore !== null 
                    ? (float) $history[0]->zscore 
                    : 0;
                $weightZ = isset($history[1]) && $history[1]->zscore !== null 
                    ? (float) $history[1]->zscore 
                    : 0;
                
                $height[] = $heightZ;
                $weight[] = $weightZ;
                $xAxis[] = $growthData[$i]->age . " bulan";
            }
            
            $graph = [
                'height' => $height,
                'weight' => $weight,
                'xAxis' => $xAxis,
            ];
        } else {
            $graph = [
                'height' => [],
                'weight' => [],
                'xAxis' => [],
            ];
        }
        
        return view('welcome', compact('setting', 'growthData', 'graph'));
    }

}
