<?php

namespace App\Http\Controllers\Monitoring;

use App\Models\ZScoreModel;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\StuntingUserModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Growth\StuntingUserRequest;

class StuntingGrowthController extends Controller
{
    public function create()
    {
        $setting = SettingModel::all();
        return view('monitoring.growth-detection.stunting.form', compact('setting'));
    }

    public function store(StuntingUserRequest $request)
    {
        try {
            $data = $request->validated();

            // Calculate age from birth date if provided
            if ($request->tanggal_lahir) {
                $birthDate = new \Carbon\Carbon($request->tanggal_lahir);
                $data['tanggal_lahir'] = $birthDate->format('Y-m-d');
                $data['usia'] = $birthDate->diffInMonths(now());
            }
            
            // Cek apakah anak ini sudah pernah didata sebelumnya
            $existingChild = StuntingUserModel::where('user_id', auth()->user()->id)
                ->where('nama', $request->nama)
                ->where('jenis_kelamin', $request->jenis_kelamin)
                ->first();
            
            // Gunakan medical_id dan photo dari data pertama jika sudah ada
            if ($existingChild) {
                $data['medical_id'] = $existingChild->medical_id;
                $data['photo'] = $existingChild->photo;
                \Log::info('Stunting Store - Using existing medical_id and photo', [
                    'medical_id' => $existingChild->medical_id,
                    'photo' => $existingChild->photo
                ]);
            } else {
                // Data pertama kali - generate medical_id baru jika belum ada
                if (!$request->medical_id) {
                    $totalChildren = StuntingUserModel::where('user_id', auth()->user()->id)
                        ->distinct('nama')
                        ->count('nama') + 1;
                    $data['medical_id'] = 'RM-' . date('Y') . '-' . str_pad($totalChildren, 3, '0', STR_PAD_LEFT);
                }
                
                \Log::info('Stunting Store - Generated new medical_id', ['medical_id' => $data['medical_id']]);
            }

            // WHO Z-score
            $haz = $this->lhfa($request->tinggi_badan, $data['usia'], $request->jenis_kelamin);
            $whz = $this->wfa($request->berat_badan, $data['usia'], $request->jenis_kelamin);

            // Cek apakah Z-score berhasil dihitung
            if ($haz === 0 && $whz === 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Data Z-Score tidak ditemukan untuk usia dan jenis kelamin yang dimasukkan. Pastikan usia antara 0-60 bulan.');
            }

            // Klasifikasi WHO dasar
            [$whoStatus, $whoSeverity, $whoFactors] = $this->classifyWho($haz, $whz);

            // Risk protection dari semua field tambahan
            [$riskLevel, $riskFactors, $tips] = $this->riskProtection($data);

            // Ambil level tertinggi antara WHO vs Risk Protection
            $finalLevel = $this->maxLevel($whoSeverity, $riskLevel);

            // Gabung faktor & rekomendasi
            $faktorUtama = $this->joinPhrases(array_filter([$whoFactors, $riskFactors]));
            $rekomendasi = $this->buildRecommendations($whoStatus, $tips, $data);

            // Get nutrition recommendations using helper
            $hazRecommendation = \App\Helpers\NutritionRecommendation::getRecommendation(
                'TB/U', 
                $haz, 
                $whoStatus
            );
            
            $whzRecommendation = \App\Helpers\NutritionRecommendation::getRecommendation(
                'BB/U', 
                $whz, 
                $whoStatus
            );

            // Simpan
            $child = StuntingUserModel::create(
                array_merge($data, [
                    'user_id' => auth()->user()->id,
                    'haz' => $haz,
                    'whz' => $whz,
                    'status_pertumbuhan' => $whoStatus,
                    'level_risiko' => $finalLevel,
                    'faktor_utama' => $faktorUtama,
                    'rekomendasi' => $rekomendasi,
                    'haz_recommendation' => json_encode($hazRecommendation),
                    'whz_recommendation' => json_encode($whzRecommendation),
                ]),
            );

            $child->save();

            return redirect()
                ->to(locale_route('growth-detection.stunting.result', encrypt($child->id)))
                ->with('success', 'Data anak berhasil disimpan & dianalisis.');
        } catch (\Exception $e) {
            \Log::error('Error storing stunting data: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
        // ->with('result', [
        //     'haz' => $haz,
        //     'whz' => $whz,
        //     'status' => $whoStatus,
        //     'level' => $finalLevel,
        //     'faktor' => $faktorUtama,
        //     'rekomendasi' => $rekomendasi,
        // ]);
    }

    public function lhfa($lh, $age, $gender)
    {
        $param = ZScoreModel::where('month', $age)->where('gender', $gender)->where('type', 'LH')->first();

        if (!$param) {
            \Log::error("ZScore data not found for LH", ['month' => $age, 'gender' => $gender]);
            return 0;
        }

        $zscore = 0;

        if ($param->L >= 1) {
            $pembilang = $lh - $param->M;
            $penyebut = $param->S * $param->M;
            $zscore = $pembilang / $penyebut;
        } else {
            $pembilang = pow($lh / $param->M, $param->L);
            $pembilang2 = $pembilang - 1;
            $penyebut = $param->L * $param->S;
            $zscore = $pembilang2 / $penyebut;
        }

        return $zscore;
    }

    public function wfa($w, $age, $gender)
    {
        $param = ZScoreModel::where('month', $age)->where('gender', $gender)->where('type', 'W')->first();
        
        if (!$param) {
            \Log::error("ZScore data not found for W", ['month' => $age, 'gender' => $gender]);
            return 0;
        }

        $zscore = 0;

        if ($param->L >= 1) {
            $pembilang = $w - $param->M;
            $penyebut = $param->S * $param->M;
            $zscore = $pembilang / $penyebut;
        } else {
            $pembilang = pow($w / $param->M, $param->L);
            $pembilang2 = $pembilang - 1;
            $penyebut = $param->L * $param->S;
            $zscore = $pembilang2 / $penyebut;
        }

        return $zscore;
    }

    private function classifyWho(?float $haz, ?float $whz): array
    {
        $status = 'Normal';
        $severity = 'Rendah';
        $factors = [];

        if ($haz !== null) {
            if ($haz < -3) {
                $status = 'Severely Stunted';
                $severity = 'Tinggi';
                $factors[] = 'Tinggi badan sangat rendah untuk usia (HAZ < -3)';
            } elseif ($haz < -2) {
                $status = 'Stunted';
                $severity = 'Sedang';
                $factors[] = 'Tinggi badan rendah untuk usia (HAZ < -2)';
            }
        }
        if ($whz !== null) {
            if ($whz < -3) {
                $status = 'Severe Wasting';
                $severity = 'Tinggi';
                $factors[] = 'Berat badan sangat rendah untuk tinggi badan (WHZ < -3)';
            } elseif ($whz < -2 && $status === 'Normal') {
                $status = 'Wasting';
                $severity = 'Sedang';
                $factors[] = 'Berat badan rendah untuk tinggi badan (WHZ < -2)';
            }
        }

        return [$status, $severity, $this->joinPhrases($factors)];
    }

    private function riskProtection(array $d): array
    {
        $score = 0;
        $factors = [];
        $tips = [];

        // MUAC (lingkar lengan)
        if (!empty($d['lingkar_lengan'])) {
            if ($d['lingkar_lengan'] < 11.5) {
                $score += 3;
                $factors[] = 'MUAC < 11.5 cm (malnutrisi akut berat)';
                $tips[] = 'Rujuk segera untuk tatalaksana gizi akut';
            } elseif ($d['lingkar_lengan'] < 12.5) {
                $score += 2;
                $factors[] = 'MUAC 11.5 - 12.5 cm (malnutrisi akut sedang)';
                $tips[] = 'Terapi makanan siap saji (RUTF) / tambahan energi-protein';
            }
        }

        // Frekuensi sakit
        if (!empty($d['frekuensi_sakit_6_bulan']) && $d['frekuensi_sakit_6_bulan'] >= 3) {
            $score += 2;
            $factors[] = 'Sering sakit (≥3x/6 bulan)';
            $tips[] = 'Perbaiki kebersihan, imunisasi lengkap, cek infeksi berulang';
        }

        // Riwayat penyakit
        $penyakitBerat = ['Diare Kronis', 'TB', 'HIV', 'Penyakit Jantung Bawaan'];
        if (!empty($d['riwayat_penyakit'])) {
            $intersect = array_intersect($penyakitBerat, $d['riwayat_penyakit']);
            if (count($intersect) > 0) {
                $score += 2;
                $factors[] = 'Riwayat penyakit kronis: ' . implode(', ', $intersect);
                $tips[] = 'Tatalaksana penyakit dasar untuk dukung catch-up growth';
            } else {
                $score += 1;
                $factors[] = 'Riwayat penyakit lain';
            }
        }

        // Obat
        if (!empty($d['menggunakan_obat'])) {
            $score += 1;
            $factors[] = 'Penggunaan obat (potensi pengaruh nafsu makan/metabolisme)';
        }

        // Nutrisi (1-5)
        if (!empty($d['protein']) && $d['protein'] <= 2) {
            $score += 2;
            $factors[] = 'Asupan protein rendah';
            $tips[] = 'Tambah protein hewani: telur, ikan, ayam, daging';
        }
        if (!empty($d['sayur_buah']) && $d['sayur_buah'] <= 2) {
            $score += 1;
            $factors[] = 'Asupan sayur & buah rendah';
            $tips[] = 'Tambah 2 porsi sayur + 1 porsi buah per hari';
        }
        if (!empty($d['gula']) && $d['gula'] >= 4) {
            $score += 1;
            $factors[] = 'Konsumsi gula tinggi';
            $tips[] = 'Kurangi minuman manis/jajanan manis';
        }
        if (!empty($d['frekuensi_jajan']) && $d['frekuensi_jajan'] >= 4) {
            $score += 1;
            $factors[] = 'Sering jajan di luar';
            $tips[] = 'Batasi jajanan ultra-proses, pilih camilan bergizi';
        }

        // Akses pangan
        if (!empty($d['akses_pangan'])) {
            if (in_array('Kesulitan akses sayur/buah segar', $d['akses_pangan'])) {
                $score += 1;
                $factors[] = 'Akses sayur/buah terbatas';
                $tips[] = 'Manfaatkan sayur beku/kaleng rendah garam sebagai alternatif';
            }
            if (in_array('Ketergantungan makanan instan', $d['akses_pangan'])) {
                $score += 1;
                $factors[] = 'Ketergantungan makanan instan';
                $tips[] = 'Masak batch sederhana: sup, tumis, telur, tempe-tahu';
            }
            if (in_array('Air bersih terbatas', $d['akses_pangan'])) {
                $score += 1;
                $factors[] = 'Air bersih terbatas';
                $tips[] = 'Pastikan air minum dimasak/diolah; kebersihan makanan';
            }
        }

        // Tren pertumbuhan (stagnan/penurunan)
        if (!empty($d['pola_pertumbuhan']) && is_array($d['pola_pertumbuhan']) && count($d['pola_pertumbuhan']) >= 2) {
            // urutkan by bulan string (YYYY-MM)
            $arr = $d['pola_pertumbuhan'];
            usort($arr, fn($a, $b) => strcmp($a['bulan'], $b['bulan']));
            $last = end($arr);
            $prev = prev($arr);

            if ($last && $prev) {
                $deltaTb = ($last['tb'] ?? 0) - ($prev['tb'] ?? 0); // cm
                $deltaBb = ($last['bb'] ?? 0) - ($prev['bb'] ?? 0); // kg
                if ($deltaTb < 0.5) {
                    $score += 2;
                    $factors[] = 'Pertumbuhan tinggi stagnan (∆TB < 0.5 cm/periode)';
                    $tips[] = 'Evaluasi kecukupan energi-protein & penyakit penyerta';
                }
                if ($deltaBb < -0.5 || (($prev['bb'] ?? 0) > 0 && $deltaBb / $prev['bb'] <= -0.05)) {
                    $score += 3;
                    $factors[] = 'Penurunan berat badan bermakna';
                    $tips[] = 'Tingkatkan densitas energi: tambah minyak/keju/susu bubuk pada makanan';
                }
            }
        }

        // Skor → level
        $level = 'Rendah';
        if ($score >= 6) {
            $level = 'Tinggi';
        } elseif ($score >= 3) {
            $level = 'Sedang';
        }

        return [$level, $this->joinPhrases($factors), array_values(array_unique($tips))];
    }

    private function maxLevel(string $a, string $b): string
    {
        $rank = ['Rendah' => 0, 'Sedang' => 1, 'Tinggi' => 2];
        return ($rank[$a] ?? 0) >= ($rank[$b] ?? 0) ? $a : $b;
    }

    private function joinPhrases(array $parts): string
    {
        return implode(' + ', array_filter($parts, fn($p) => trim($p) !== ''));
    }

    private function buildRecommendations(string $whoStatus, array $tips, array $d): string
    {
        $base = match ($whoStatus) {
            'Severely Stunted', 'Severe Wasting' => 'Intervensi gizi segera, rujuk ke layanan kesehatan. ',
            'Stunted', 'Wasting' => 'Tingkatkan asupan protein hewani, susu, vitamin; pemantauan bulanan. ',
            default => 'Pertahankan pola makan seimbang dan pemantauan berkala. ',
        };

        // Target spesifik
        $target = [];
        if (!empty($d['target_tinggi'])) {
            $target[] = 'stimulasi & protein untuk catch-up tinggi';
        }
        if (!empty($d['target_berat'])) {
            $target[] = 'tingkatkan densitas energi untuk naik berat';
        }
        if (!empty($d['target_gizi'])) {
            $target[] = 'perbaiki keragaman pangan (protein, sayur, buah)';
        }
        if ($target) {
            $base .= 'Fokus: ' . implode(', ', $target) . '. ';
        }

        // Monitoring
        if (!empty($d['izinkan_monitoring']) && !empty($d['frekuensi_update'])) {
            $base .= 'Monitoring ' . $d['frekuensi_update'] . ' via sistem. ';
        }

        if ($tips) {
            $base .= 'Tips: ' . implode('; ', $tips) . '.';
        }
        return trim($base);
    }

    public function result($locale, $id)
    {
        $setting = SettingModel::all();
        $data = StuntingUserModel::find(decrypt($id));

        $records = StuntingUserModel::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')
        ->take(5)
        ->get()
        ->sortBy('created_at');;

        if ($records->isEmpty()) {
            return view('monitoring.growth-detection.stunting.result', compact('setting'))->with('noData', true);
        }

        // Data untuk grafik
        $months = $records->pluck('created_at')->map(function ($m) {
            return \Carbon\Carbon::parse($m)->format('M Y');
        });

        $weights = $records->pluck('berat_badan'); // kg
        $heights = $records->pluck('tinggi_badan');

        // WHO Reference sesuai usia tiap record
        $whow = [];
        $hwho = [];

        foreach ($records as $r) {
            $wm = ZScoreModel::where('month', $r->usia)->where('gender', $r->jenis_kelamin)->where('type', 'W')->value('M');

            $hm = ZScoreModel::where('month', $r->usia)->where('gender', $r->jenis_kelamin)->where('type', 'LH')->value('M');

            $whow[] = $wm ?? null;
            $hwho[] = $hm ?? null;
        }

        $childName = $data ? $data->nama : 'Anak';

        return view('monitoring.growth-detection.stunting.result', compact('setting', 'data', 'weights', 'heights', 'months', 'whow', 'hwho', 'childName'));
    }

    /**
     * Download PDF - Data Hari Ini (Latest Only)
     */
    public function downloadPDFToday($locale, $id)
    {
        $child = StuntingUserModel::findOrFail(decrypt($id));
        
        // Check authorization
        if ($child->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $hazRec = json_decode($child->haz_recommendation, true);
        $whzRec = json_decode($child->whz_recommendation, true);
        $setting = SettingModel::all();
        
        $pdf = \PDF::loadView('monitoring.growth-detection.stunting.pdf-today', [
            'child' => $child,
            'hazRec' => $hazRec,
            'whzRec' => $whzRec,
            'setting' => $setting,
        ])->setPaper('a4', 'portrait');
        
        $filename = 'laporan-hari-ini-' . str_replace(' ', '-', $child->nama) . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Download PDF - Data Lengkap (All History)
     */
    public function downloadPDFComplete($locale, $id)
    {
        $child = StuntingUserModel::findOrFail(decrypt($id));
        
        // Check authorization
        if ($child->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        // Get all history for this child (by name or medical_id)
        $allHistory = StuntingUserModel::where('user_id', auth()->id())
            ->where(function($query) use ($child) {
                $query->where('nama', $child->nama);
                if ($child->medical_id) {
                    $query->orWhere('medical_id', $child->medical_id);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        $hazRec = json_decode($child->haz_recommendation, true);
        $whzRec = json_decode($child->whz_recommendation, true);
        $setting = SettingModel::all();
        
        $pdf = \PDF::loadView('monitoring.growth-detection.stunting.pdf-complete', [
            'child' => $child,
            'allHistory' => $allHistory,
            'hazRec' => $hazRec,
            'whzRec' => $whzRec,
            'setting' => $setting,
        ])->setPaper('a4', 'portrait');
        
        $filename = 'laporan-lengkap-' . str_replace(' ', '-', $child->nama) . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
