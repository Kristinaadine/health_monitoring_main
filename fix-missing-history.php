<?php
/**
 * Script untuk regenerate history yang hilang
 * Jalankan dengan: php fix-missing-history.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\GrowthMonitoringModel;
use App\Models\GrowthMonitoringHistoryModel;
use App\Models\ZScoreModel;

echo "=== Fix Missing History ===" . PHP_EOL;
echo PHP_EOL;

// Get data without history
$dataWithoutHistory = GrowthMonitoringModel::whereDoesntHave('history')->get();

echo "Found " . $dataWithoutHistory->count() . " records without history" . PHP_EOL;
echo PHP_EOL;

foreach ($dataWithoutHistory as $data) {
    echo "Processing ID: {$data->id}, Name: {$data->name}, Age: {$data->age} months" . PHP_EOL;
    
    // Generate LHFA (Length/Height for Age)
    $lhParam = ZScoreModel::where('month', $data->age)
        ->where('gender', $data->gender)
        ->where('type', 'LH')
        ->first();
    
    if ($lhParam) {
        $lh = $data->height;
        
        if ($lhParam->L >= 1) {
            $zscore = ($lh - $lhParam->M) / ($lhParam->S * $lhParam->M);
        } else {
            $pembilang = pow($lh / $lhParam->M, $lhParam->L);
            $pembilang2 = $pembilang - 1;
            $penyebut = $lhParam->L * $lhParam->S;
            $zscore = $pembilang2 / $penyebut;
        }
        
        // Determine diagnosis
        if ($zscore > 3) {
            $hasil = 'Tinggi Badan Sangat Tinggi';
            $deskripsi = 'Anak mengalami status tinggi badan sangat tinggi';
            $penanganan = 'Konsultasi dengan ahli gizi untuk program pertumbuhan yang sehat.';
        } elseif ($zscore > 2 && $zscore <= 3) {
            $hasil = 'Tinggi';
            $deskripsi = 'Anak mengalami status tinggi badan tinggi';
            $penanganan = 'Perbaiki pola makan dan tingkatkan aktivitas fisik.';
        } elseif ($zscore >= -2 && $zscore <= 2) {
            $hasil = 'Tinggi Badan Normal';
            $deskripsi = 'Anak memiliki tinggi badan yang normal';
            $penanganan = 'Pertahankan pola makan sehat dan aktif secara fisik.';
        } elseif ($zscore < -2 && $zscore >= -3) {
            $hasil = 'Pendek';
            $deskripsi = 'Anak mengalami status tinggi badan pendek';
            $penanganan = 'Tingkatkan asupan gizi dengan makanan bergizi.';
        } else {
            $hasil = 'Sangat Pendek';
            $deskripsi = 'Anak mengalami status tinggi badan sangat pendek';
            $penanganan = 'Konsultasi dengan ahli gizi dan dokter untuk program pertumbuhan yang intensif.';
        }
        
        GrowthMonitoringHistoryModel::create([
            'id_growth' => $data->id,
            'type' => 'LH',
            'value' => $lh,
            'zscore' => $zscore,
            'hasil_diagnosa' => $hasil,
            'deskripsi_diagnosa' => $deskripsi,
            'penanganan' => $penanganan,
        ]);
        
        echo "  ✓ Created LH history (Z-Score: " . number_format($zscore, 2) . ")" . PHP_EOL;
    }
    
    // Generate WFA (Weight for Age)
    $wParam = ZScoreModel::where('month', $data->age)
        ->where('gender', $data->gender)
        ->where('type', 'W')
        ->first();
    
    if ($wParam) {
        $w = $data->weight;
        
        if ($wParam->L >= 1) {
            $zscore = ($w - $wParam->M) / ($wParam->S * $wParam->M);
        } else {
            $pembilang = pow($w / $wParam->M, $wParam->L);
            $pembilang2 = $pembilang - 1;
            $penyebut = $wParam->L * $wParam->S;
            $zscore = $pembilang2 / $penyebut;
        }
        
        // Determine diagnosis
        if ($zscore >= 3) {
            $hasil = 'Obesitas';
            $deskripsi = 'Anak mengalami obesitas';
            $penanganan = 'Konsultasi dengan dokter dan ahli gizi untuk program diet.';
        } elseif ($zscore >= 2 && $zscore < 3) {
            $hasil = 'Gizi Lebih';
            $deskripsi = 'Anak mengalami gizi lebih';
            $penanganan = 'Konsultasi dengan ahli gizi untuk program diet.';
        } elseif ($zscore >= 1 && $zscore < 2) {
            $hasil = 'Risiko Gizi Lebih';
            $deskripsi = 'Anak berisiko mengalami gizi lebih';
            $penanganan = 'Perhatikan pola makan dan tingkatkan aktivitas fisik.';
        } elseif ($zscore >= -2 && $zscore < 1) {
            $hasil = 'Gizi Normal';
            $deskripsi = 'Anak memiliki gizi yang normal';
            $penanganan = 'Pertahankan pola makan sehat.';
        } else {
            $hasil = 'Gizi Kurang';
            $deskripsi = 'Anak mengalami gizi kurang';
            $penanganan = 'Tingkatkan asupan gizi dengan makanan bergizi.';
        }
        
        GrowthMonitoringHistoryModel::create([
            'id_growth' => $data->id,
            'type' => 'W',
            'value' => $w,
            'zscore' => $zscore,
            'hasil_diagnosa' => $hasil,
            'deskripsi_diagnosa' => $deskripsi,
            'penanganan' => $penanganan,
        ]);
        
        echo "  ✓ Created W history (Z-Score: " . number_format($zscore, 2) . ")" . PHP_EOL;
    }
    
    echo PHP_EOL;
}

echo "=== Done! ===" . PHP_EOL;
echo "Total fixed: " . $dataWithoutHistory->count() . " records" . PHP_EOL;
