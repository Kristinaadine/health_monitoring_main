<?php

namespace App\Helpers;

class NutritionRecommendation
{
    /**
     * Get nutrition recommendation based on Z-score status
     */
    public static function getRecommendation($indicator, $zscore, $status)
    {
        $recommendations = [];

        // Rekomendasi berdasarkan TB/U (Tinggi Badan menurut Umur)
        if ($indicator === 'TB/U' || $indicator === 'height_for_age') {
            if ($zscore < -3) {
                // Sangat Pendek
                $recommendations = [
                    'status' => 'Sangat Pendek (Severely Stunted)',
                    'color' => 'danger',
                    'icon' => '🚨',
                    'advice' => [
                        '🥚 Perbanyak protein hewani: telur, ikan, daging ayam, susu',
                        '🥦 Konsumsi sayuran hijau: bayam, brokoli, kangkung',
                        '🍊 Buah-buahan kaya vitamin: jeruk, pepaya, pisang',
                        '🥛 Minum susu 2-3 gelas per hari',
                        '⚠️ Segera konsultasi ke dokter atau ahli gizi',
                        '💊 Pertimbangkan suplemen zinc dan vitamin A',
                    ],
                    'warning' => 'Anak memerlukan perhatian medis segera!'
                ];
            } elseif ($zscore < -2) {
                // Pendek
                $recommendations = [
                    'status' => 'Pendek (Stunted)',
                    'color' => 'warning',
                    'icon' => '⚠️',
                    'advice' => [
                        '🥚 Tingkatkan asupan protein: telur 1-2 butir/hari',
                        '🐟 Ikan 3-4x seminggu (ikan kembung, tongkol, salmon)',
                        '🥛 Susu atau produk olahan susu setiap hari',
                        '🥜 Kacang-kacangan: kacang hijau, kacang merah, tempe, tahu',
                        '🍎 Buah segar minimal 2 porsi per hari',
                        '👨‍⚕️ Konsultasi ke posyandu atau puskesmas',
                    ],
                    'warning' => 'Perlu perhatian khusus untuk pertumbuhan'
                ];
            } elseif ($zscore <= 2) {
                // Normal
                $recommendations = [
                    'status' => 'Normal',
                    'color' => 'success',
                    'icon' => '✅',
                    'advice' => [
                        '👍 Pertahankan pola makan seimbang',
                        '🍽️ Makan 3x sehari dengan 2x camilan sehat',
                        '🥗 Variasi makanan: karbohidrat, protein, sayur, buah',
                        '💧 Cukupi kebutuhan cairan (air putih)',
                        '🏃 Aktivitas fisik teratur',
                        '😴 Tidur cukup 10-12 jam per hari',
                    ],
                    'warning' => null
                ];
            } else {
                // Tinggi
                $recommendations = [
                    'status' => 'Tinggi',
                    'color' => 'info',
                    'icon' => 'ℹ️',
                    'advice' => [
                        '👍 Pertumbuhan tinggi badan baik',
                        '⚖️ Pastikan berat badan proporsional',
                        '🍽️ Jaga pola makan seimbang',
                        '🏃 Aktivitas fisik teratur',
                        '👨‍⚕️ Monitoring rutin tetap diperlukan',
                    ],
                    'warning' => null
                ];
            }
        }

        // Rekomendasi berdasarkan BB/U (Berat Badan menurut Umur)
        if ($indicator === 'BB/U' || $indicator === 'weight_for_age') {
            if ($zscore < -3) {
                // Gizi Buruk
                $recommendations = [
                    'status' => 'Gizi Buruk (Severely Underweight)',
                    'color' => 'danger',
                    'icon' => '🚨',
                    'advice' => [
                        '🚨 SEGERA ke dokter atau rumah sakit!',
                        '🥛 Susu formula khusus gizi buruk (F75/F100)',
                        '🥚 Protein tinggi: telur, ikan, daging cincang',
                        '🍚 Porsi kecil tapi sering (6-8x sehari)',
                        '💊 Suplemen vitamin dan mineral sesuai anjuran dokter',
                        '👨‍⚕️ Monitoring ketat oleh tenaga kesehatan',
                    ],
                    'warning' => 'DARURAT! Butuh penanganan medis segera!'
                ];
            } elseif ($zscore < -2) {
                // Gizi Kurang
                $recommendations = [
                    'status' => 'Gizi Kurang (Underweight)',
                    'color' => 'warning',
                    'icon' => '⚠️',
                    'advice' => [
                        '🥚 Tingkatkan kalori: telur, daging, ikan',
                        '🥛 Susu full cream 3x sehari',
                        '🥜 Camilan bernutrisi: kacang, biskuit, buah',
                        '🍚 Nasi dengan lauk protein setiap makan',
                        '🥑 Lemak sehat: alpukat, minyak zaitun',
                        '👨‍⚕️ Konsultasi ke ahli gizi',
                    ],
                    'warning' => 'Perlu peningkatan asupan nutrisi'
                ];
            } elseif ($zscore <= 1) {
                // Normal
                $recommendations = [
                    'status' => 'Normal',
                    'color' => 'success',
                    'icon' => '✅',
                    'advice' => [
                        '👍 Berat badan ideal, pertahankan!',
                        '🍽️ Pola makan seimbang 3x sehari',
                        '🥗 Kombinasi karbohidrat, protein, sayur, buah',
                        '💧 Minum air putih cukup',
                        '🏃 Aktivitas fisik sesuai usia',
                        '📊 Monitoring rutin setiap bulan',
                    ],
                    'warning' => null
                ];
            } else {
                // Berat Badan Lebih
                $recommendations = [
                    'status' => 'Berat Badan Lebih (Overweight)',
                    'color' => 'warning',
                    'icon' => '⚠️',
                    'advice' => [
                        '⚖️ Kurangi makanan tinggi gula dan lemak',
                        '🚫 Hindari: gorengan, fast food, minuman manis',
                        '🥗 Perbanyak sayur dan buah',
                        '🍚 Porsi nasi secukupnya, tidak berlebihan',
                        '🏃 Tingkatkan aktivitas fisik',
                        '👨‍⚕️ Konsultasi untuk diet seimbang',
                    ],
                    'warning' => 'Risiko obesitas, perlu pengaturan pola makan'
                ];
            }
        }

        // Rekomendasi berdasarkan BB/TB (Berat Badan menurut Tinggi Badan)
        if ($indicator === 'BB/TB' || $indicator === 'weight_for_height') {
            if ($zscore < -3) {
                // Gizi Buruk
                $recommendations = [
                    'status' => 'Gizi Buruk (Severely Wasted)',
                    'color' => 'danger',
                    'icon' => '🚨',
                    'advice' => [
                        '🚨 SEGERA ke fasilitas kesehatan!',
                        '🥛 Makanan padat kalori dan protein',
                        '🥚 Telur, daging, ikan setiap hari',
                        '🍚 Makan porsi kecil tapi sering',
                        '💊 Suplemen sesuai resep dokter',
                        '👨‍⚕️ Rawat jalan atau rawat inap jika perlu',
                    ],
                    'warning' => 'DARURAT! Butuh penanganan segera!'
                ];
            } elseif ($zscore < -2) {
                // Kurus
                $recommendations = [
                    'status' => 'Kurus (Wasted)',
                    'color' => 'warning',
                    'icon' => '⚠️',
                    'advice' => [
                        '🥚 Protein tinggi: telur, ayam, ikan, tahu, tempe',
                        '🥛 Susu dan produk susu 3x sehari',
                        '🥜 Camilan berkalori: kacang, keju, yogurt',
                        '🍚 Karbohidrat kompleks: nasi, roti, kentang',
                        '🥑 Lemak sehat: alpukat, kacang',
                        '👨‍⚕️ Monitoring berat badan mingguan',
                    ],
                    'warning' => 'Perlu peningkatan berat badan'
                ];
            } elseif ($zscore <= 2) {
                // Normal
                $recommendations = [
                    'status' => 'Normal',
                    'color' => 'success',
                    'icon' => '✅',
                    'advice' => [
                        '👍 Proporsi berat dan tinggi ideal!',
                        '🍽️ Pertahankan pola makan seimbang',
                        '🥗 Variasi menu setiap hari',
                        '💧 Hidrasi cukup',
                        '🏃 Aktivitas fisik teratur',
                        '😴 Istirahat cukup',
                    ],
                    'warning' => null
                ];
            } else {
                // Gemuk/Obesitas
                $recommendations = [
                    'status' => 'Gemuk/Obesitas (Obese)',
                    'color' => 'danger',
                    'icon' => '⚠️',
                    'advice' => [
                        '⚖️ Kurangi makanan tinggi kalori',
                        '🚫 Hindari: gorengan, junk food, soda, permen',
                        '🥗 Perbanyak sayur dan buah segar',
                        '🍚 Kurangi porsi nasi dan karbohidrat',
                        '🏃 Olahraga minimal 30 menit/hari',
                        '👨‍⚕️ Konsultasi ahli gizi untuk program diet',
                    ],
                    'warning' => 'Risiko penyakit metabolik, perlu penanganan'
                ];
            }
        }

        return $recommendations;
    }

    /**
     * Get Z-score color indicator
     */
    public static function getZScoreColor($zscore)
    {
        if ($zscore < -3) {
            return 'danger'; // Merah
        } elseif ($zscore < -2) {
            return 'warning'; // Kuning
        } elseif ($zscore <= 2) {
            return 'success'; // Hijau
        } else {
            return 'info'; // Biru
        }
    }

    /**
     * Get Z-score badge HTML
     */
    public static function getZScoreBadge($zscore, $showValue = true)
    {
        $color = self::getZScoreColor($zscore);
        $value = $showValue ? number_format($zscore, 2) : '';
        
        $icons = [
            'danger' => '🚨',
            'warning' => '⚠️',
            'success' => '✅',
            'info' => 'ℹ️'
        ];
        
        $icon = $icons[$color] ?? '';
        
        return "<span class='badge bg-{$color}'>{$icon} {$value}</span>";
    }
}
