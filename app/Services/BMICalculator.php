<?php

namespace App\Services;

class BMICalculator
{
    public function hitungBMI(float $berat, float $tinggi): float
    {
        return round($berat / pow($tinggi / 100, 2), 1);
    }

    public function statusGizi(float $bmi): string
    {
        if ($bmi < 18.5) return 'Kurus';
        if ($bmi < 25) return 'Normal';
        if ($bmi < 30) return 'Overweight';
        return 'Obesitas';
    }

    public function rekomendasi(string $status): string
    {
        return match ($status) {
            'Kurus' => __('general.perbanyak_konsumsi_protein_dan_kalori_sehat'),
            'Normal' => __('general.pertahankan_pola_makan_seimbang'),
            'Overweight' => __('general.kurangi_gula_dan_lemak_perbanyak_sayur'),
            'Obesitas' => __('general.segera_konsultasi_ke_tenaga_kesehatan'),
            default => __('general.jaga_pola_makan_seimbang'),
        };
    }
}
