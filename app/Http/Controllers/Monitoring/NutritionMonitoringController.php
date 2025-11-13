<?php

namespace App\Http\Controllers\Monitoring;

use App\Models\AlertModel;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\ChildrenModel;
use App\Models\FoodLogsModel;
use App\Models\GrowthLogsModel;
use App\Http\Controllers\Controller;
use App\Models\NutritionTargetModel;

class NutritionMonitoringController extends Controller
{
    public function index()
{
    $setting = SettingModel::all();
    $children = ChildrenModel::where('user_id', auth()->user()->id)
                ->orderBy('id', 'desc')
                ->get();
    $alerts = AlertModel::latest()->take(5)->get();

    $growthDates = $weights = $heights = [];
    $foodDates = $kalori = $protein = $karbo = $lemak = [];
    $progress = $target = [];

    // Variabel untuk info periode
    $growthPeriod = '';
    $foodPeriod = '';
    
    if ($children->count() > 0) {
        $child = $children->first();

        // --- Data pertumbuhan (7 hari terakhir) ---
        $growth = GrowthLogsModel::where('child_id', $child->id)
            ->where('tanggal', '>=', now()->subDays(7))
            ->orderBy('tanggal','asc')
            ->get();

        $growthDates = $growth->pluck('tanggal')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))
            ->toArray();
        $weights = $growth->pluck('berat')->toArray();
        $heights = $growth->pluck('tinggi')->toArray();
        
        // Hitung periode aktual pertumbuhan
        if ($growth->count() > 0) {
            $firstDate = \Carbon\Carbon::parse($growth->first()->tanggal);
            $lastDate = \Carbon\Carbon::parse($growth->last()->tanggal);
            $daysDiff = $firstDate->diffInDays($lastDate);
            
            if ($daysDiff == 0) {
                $growthPeriod = $firstDate->locale('id')->isoFormat('D MMMM YYYY');
            } else {
                $growthPeriod = $firstDate->locale('id')->isoFormat('D MMM') . ' - ' . 
                               $lastDate->locale('id')->isoFormat('D MMM YYYY') . 
                               ' (' . ($daysDiff + 1) . ' hari)';
            }
        }

        // --- Data nutrisi (7 hari terakhir) ---
        $food = FoodLogsModel::where('child_id', $child->id)
            ->where('tanggal', '>=', now()->subDays(7))
            ->orderBy('tanggal','asc')
            ->get();

        $foodDates = $food->pluck('tanggal')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))
            ->toArray();
        $kalori = $food->pluck('kalori')->toArray();
        $protein = $food->pluck('protein')->toArray();
        $karbo = $food->pluck('karbo')->toArray();
        $lemak = $food->pluck('lemak')->toArray();
        
        // Hitung periode aktual nutrisi
        if ($food->count() > 0) {
            $firstDate = \Carbon\Carbon::parse($food->first()->tanggal);
            $lastDate = \Carbon\Carbon::parse($food->last()->tanggal);
            $daysDiff = $firstDate->diffInDays($lastDate);
            
            if ($daysDiff == 0) {
                $foodPeriod = $firstDate->locale('id')->isoFormat('D MMMM YYYY');
            } else {
                $foodPeriod = $firstDate->locale('id')->isoFormat('D MMM') . ' - ' . 
                             $lastDate->locale('id')->isoFormat('D MMM YYYY') . 
                             ' (' . ($daysDiff + 1) . ' hari)';
            }
        }

        // --- Progress vs Target (hari ini) ---
        $today = now()->toDateString();
        $foodToday = FoodLogsModel::where('child_id', $child->id)
                        ->whereDate('tanggal', $today)
                        ->get();

        $progress = [
            'kalori' => $foodToday->sum('kalori'),
            'protein' => $foodToday->sum('protein'),
            'karbo' => $foodToday->sum('karbo'),
            'lemak' => $foodToday->sum('lemak'),
        ];

        $targetData = NutritionTargetModel::where('child_id', $child->id)->first();
        $target = [
            'kalori' => $targetData->kalori ?? 1200,
            'protein' => $targetData->protein ?? 50,
            'karbo' => $targetData->karbo ?? 150,
            'lemak' => $targetData->lemak ?? 40,
        ];
    }

    return view('monitoring.nutrition-monitoring.index', compact(
        'setting','children','alerts',
        'growthDates','weights','heights','growthPeriod',
        'foodDates','kalori','protein','karbo','lemak','foodPeriod',
        'progress','target'
    ));
}

}
