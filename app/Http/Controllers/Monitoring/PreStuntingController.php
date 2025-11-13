<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PreStunting;
use Illuminate\Support\Facades\Auth;
use App\Models\SettingModel;

class PreStuntingController extends Controller
{
    public function index()
    {
        // $data = PreStunting::where('users_id', auth()->id())->latest()->get();
        $data = PreStunting::when(request('nama'), function($q) {
            $q->where('nama', 'like', '%' . request('nama') . '%');
        })
        ->latest() 
        ->paginate(5);

        // Hitung jumlah berdasarkan level risiko
        $low = $data->where('level_risiko', 'Risiko rendah')->count();
        $mid = $data->where('level_risiko', 'Risiko sedang')->count();
        $high = $data->where('level_risiko', 'Risiko tinggi')->count();
        $setting = SettingModel::all();

        return view('monitoring.growth-detection.pre-stunting.index', compact('data', 'low', 'mid', 'high', 'setting'));
    }

    public function create()
    {
        $data = null;
        $setting = SettingModel::all();
        return view('monitoring.growth-detection.pre-stunting.form', compact('data', 'setting'));
    }

    public function calculateRiskScore(Request $request)
    {
        $v = $request->validate([
            'nama' => 'nullable|string',
            'age' => 'required|numeric',
            'height' => 'required|numeric',
            'pre_pregnancy_weight' => 'nullable|numeric',
            'weight_at_g12' => 'nullable|numeric',
            'weight_at_g36' => 'nullable|numeric',
            'pre_pregnancy_bmi' => 'required|numeric',
            'weight_gain_trimester' => 'nullable|numeric',
            'trimester' => 'required|in:1,2,3',
            'muac' => 'required|numeric',
            'birth_interval' => 'nullable|numeric',
            'anc_visits' => 'nullable|numeric',
            'hb' => 'nullable|numeric',
            'ttd_compliance' => 'nullable|in:0,1',
            'has_infection' => 'nullable|in:0,1',
            'efw_sga' => 'nullable|in:0,1',
        ]);

        $score = 0;

        // MUAC
        if (($v['muac'] ?? 999) < 23.5) $score++;

        // Birth interval
        if (($v['birth_interval'] ?? 999) < 24) $score++;

        // ANC visits
        if (($v['anc_visits'] ?? 999) < 4) $score++;

        // TTD compliance
        if (isset($v['ttd_compliance']) && $v['ttd_compliance'] == 0) $score++;

        // Has infection
        if (!empty($v['has_infection']) && $v['has_infection']) $score++;

        // EFW SGA
        if (!empty($v['efw_sga']) && $v['efw_sga']) $score += 2;

        // Usia ibu ≥35 tahun
        if (($v['age'] ?? 0) >= 35) $score++;

        // Kenaikan BB per aturan baru berdasarkan trimester dan BMI
        $bmi = $v['pre_pregnancy_bmi'] ?? 999;
        $weightGain = $v['weight_gain_trimester'] ?? null;
        $trimester = $v['trimester'] ?? null;

        if ($trimester == 1) {
            // Trimester 1 <0.5 kg
            if ($weightGain !== null && $weightGain < 0.5) {
                $score++;
            }
        } elseif (in_array($trimester, [2,3])) {
            // Trimester 2-3 < threshold per BMI
            // Thresholds (kg/minggu):
            // BMI <18.5: 0.5
            // BMI 18.5-24.9: 0.35
            // BMI ≥25: 0.3
            if ($weightGain !== null) {
                if ($bmi < 18.5 && $weightGain < 0.5) {
                    $score++;
                } elseif ($bmi >= 18.5 && $bmi < 25 && $weightGain < 0.35) {
                    $score++;
                } elseif ($bmi >= 25 && $weightGain < 0.3) {
                    $score++;
                }
            }
        }

        // Threshold Hb sesuai trimester
        $hb = $v['hb'] ?? null;
        if ($hb !== null && $trimester !== null) {
            if ($trimester == 1 && $hb < 11.0) {
                $score++;
            } elseif ($trimester == 2 && $hb < 10.5) {
                $score++;
            } elseif ($trimester == 3 && $hb < 11.0) {
                $score++;
            }
        }

        if ($score <= 1) {
            $category = 'Risiko rendah';
            $message = 'Edukasi gizi, pemantauan rutin';
        } elseif ($score <= 3) {
            $category = 'Risiko sedang';
            $message = 'Konseling gizi intensif, tambah frekuensi ANC, cek lab ulang';
        } else {
            $category = 'Risiko tinggi';
            $message = 'Rujukan gizi/obgin, intervensi (PMT KEK, tata laksana anemia/infeksi)';
        }

        try {
            // Simpan input dengan risk_score dan level_risiko baru
            $record = PreStunting::create([
                'users_id' => auth()->id(),
                'nama' => $v['nama'] ?? null,
                'usia' => $v['age'] ?? null,
                'tinggi_badan' => $v['height'] ?? null,
                'berat_badan_pra_hamil' => $v['pre_pregnancy_weight'] ?? null,
                'weight_at_g12' => $v['weight_at_g12'] ?? null,
                'weight_at_g36' => $v['weight_at_g36'] ?? null,
                'bmi_pra_hamil' => $v['pre_pregnancy_bmi'] ?? null,
                'kenaikan_bb_trimester' => $v['weight_gain_trimester'] ?? null,
                'weight_gain_trimester' => $v['weight_gain_trimester'] ?? null,
                'muac' => $v['muac'] ?? null,
                'jarak_kelahiran' => $v['birth_interval'] ?? null,
                'anc_visits' => $v['anc_visits'] ?? null,
                'hb' => $v['hb'] ?? null,
                'ttd_compliance' => isset($v['ttd_compliance']) ? (bool)$v['ttd_compliance'] : true,
                'has_infection' => !empty($v['has_infection']),
                'efw_sga' => !empty($v['efw_sga']),
                'status_pertumbuhan' => null,
                'level_risiko' => $category,
                'risk_score' => $score,
            ]);
        } catch (\Throwable $e) {
            return redirect()->to(locale_route('growth-detection.pre-stunting.index'))
                ->with('error', 'Gagal menghitung risiko, silakan coba lagi');
        }

        return redirect()->to(locale_route('growth-detection.pre-stunting.index'))
            ->with('success', 'Data berhasil dihitung dan disimpan');
        // return redirect()->route('growth-detection.pre-stunting.index', [
        //     'locale' => app()->getLocale()
        // ])->with('success', 'Data berhasil dihitung dan disimpan');
    }

    public function show($locale, $id)
    {
        $setting = SettingModel::all();
        try {
            $realId = decrypt(urldecode($id));
        } catch (\Throwable $e) {
            $realId = $id;
        }

        $item = PreStunting::where('users_id', auth()->id())->findOrFail($realId);

        $trimester = $item->trimester ?? null; 

        // hitung ulang skor & rekomendasi berdasarkan field yang tersimpan dan aturan baru
        $arr = [
            'muac' => $item->muac,
            'hb' => $item->hb,
            'ttd_compliance' => $item->ttd_compliance,
            'pre_pregnancy_bmi' => $item->bmi_pra_hamil,
            'weight_gain_trimester' => $item->weight_gain_trimester ?? $item->kenaikan_bb_trimester,
            'age' => $item->usia,
            'height' => $item->tinggi_badan,
            'birth_interval' => $item->jarak_kelahiran,
            'anc_visits' => $item->anc_visits,
            'has_infection' => $item->has_infection,
            'efw_sga' => $item->efw_sga,
            'trimester' => $trimester,
        ];

        $score = 0;

        if (($arr['muac'] ?? 999) < 23.5) $score++;
        if (($arr['birth_interval'] ?? 999) < 24) $score++;
        if (($arr['anc_visits'] ?? 999) < 4) $score++;
        if (isset($arr['ttd_compliance']) && $arr['ttd_compliance'] == 0) $score++;
        if (!empty($arr['has_infection']) && $arr['has_infection']) $score++;
        if (!empty($arr['efw_sga']) && $arr['efw_sga']) $score += 2;
        if (($arr['age'] ?? 0) >= 35) $score++;

        $bmi = $arr['pre_pregnancy_bmi'] ?? 999;
        $weightGain = $arr['weight_gain_trimester'] ?? null;
        $trimester = $arr['trimester'] ?? null;

        if ($trimester == 1) {
            if ($weightGain !== null && $weightGain < 0.5) {
                $score++;
            }
        } elseif (in_array($trimester, [2,3])) {
            if ($weightGain !== null) {
                if ($bmi < 18.5 && $weightGain < 0.5) {
                    $score++;
                } elseif ($bmi >= 18.5 && $bmi < 25 && $weightGain < 0.35) {
                    $score++;
                } elseif ($bmi >= 25 && $weightGain < 0.3) {
                    $score++;
                }
            }
        }

        $hb = $arr['hb'] ?? null;
        if ($hb !== null && $trimester !== null) {
            if ($trimester == 1 && $hb < 11.0) {
                $score++;
            } elseif ($trimester == 2 && $hb < 10.5) {
                $score++;
            } elseif ($trimester == 3 && $hb < 11.0) {
                $score++;
            }
        }

        if ($score <= 1) {
            $category = 'Risiko rendah';
            $message = 'Edukasi gizi, pemantauan rutin';
        } elseif ($score <= 3) {
            $category = 'Risiko sedang';
            $message = 'Konseling gizi intensif, tambah frekuensi ANC, cek lab ulang';
        } else {
            $category = 'Risiko tinggi';
            $message = 'Rujukan gizi/obgin, intervensi (PMT KEK, tata laksana anemia/infeksi)';
        }

        $item->risk_score = $score;
        $item->rekomendasi = $message;
        $item->level_risiko = $category;

        return view('monitoring.growth-detection.pre-stunting.result', ['item' => $item, 'data' => $item, 'setting' => $setting]);
    }

    public function edit($locale, $id)
    {
        try {
            $realId = decrypt(urldecode($id));
        } catch (\Throwable $e) {
            $realId = $id;
        }
        $data = PreStunting::where('users_id', auth()->id())->findOrFail($realId);
        $setting = SettingModel::all();
        return view('monitoring.growth-detection.pre-stunting.form', compact('data', 'setting'));
    }

    public function update(Request $request, $locale, $id)
    {
        try {
            $realId = decrypt(urldecode($id));
        } catch (\Throwable $e) {
            $realId = $id;
        }
        $item = PreStunting::where('users_id', auth()->id())->findOrFail($realId);

        $validated = $request->validate([
            'nama' => 'nullable|string',
            'usia' => 'nullable|numeric',
            'tinggi_badan' => 'nullable|numeric',
            'berat_badan_pra_hamil' => 'nullable|numeric',
            'weight_at_g12' => 'nullable|numeric',
            'weight_at_g36' => 'nullable|numeric',
            'bmi_pra_hamil' => 'nullable|numeric',
            'weight_at_g12' => 'nullable|numeric',
            'weight_gain_trimester' => 'nullable|numeric',
            'kenaikan_bb_trimester' => 'nullable|numeric',
            'muac' => 'nullable|numeric',
            'jarak_kelahiran' => 'nullable|numeric',
            'anc_visits' => 'nullable|numeric',
            'hb' => 'nullable|numeric',
            'ttd_compliance' => 'nullable|boolean',
            'has_infection' => 'nullable|boolean',
            'efw_sga' => 'nullable|boolean',
        ]);

        $item->update($validated);

        // Recalculate risk_score and level_risiko after update
        $arr = [
            'muac' => $item->muac,
            'hb' => $item->hb,
            'ttd_compliance' => $item->ttd_compliance,
            'pre_pregnancy_bmi' => $item->bmi_pra_hamil,
            'weight_gain_trimester' => $item->weight_gain_trimester ?? $item->kenaikan_bb_trimester,
            'age' => $item->usia,
            'height' => $item->tinggi_badan,
            'birth_interval' => $item->jarak_kelahiran,
            'anc_visits' => $item->anc_visits,
            'has_infection' => $item->has_infection,
            'efw_sga' => $item->efw_sga,
            'trimester' => $item->trimester ?? null,
        ];

        $score = 0;

        if (($arr['muac'] ?? 999) < 23.5) $score++;
        if (($arr['birth_interval'] ?? 999) < 24) $score++;
        if (($arr['anc_visits'] ?? 999) < 4) $score++;
        if (isset($arr['ttd_compliance']) && $arr['ttd_compliance'] == 0) $score++;
        if (!empty($arr['has_infection']) && $arr['has_infection']) $score++;
        if (!empty($arr['efw_sga']) && $arr['efw_sga']) $score += 2;
        if (($arr['age'] ?? 0) >= 35) $score++;

        $bmi = $arr['pre_pregnancy_bmi'] ?? 999;
        $weightGain = $arr['weight_gain_trimester'] ?? null;
        $trimester = $arr['trimester'] ?? null;

        if ($trimester == 1) {
            if ($weightGain !== null && $weightGain < 0.5) {
                $score++;
            }
        } elseif (in_array($trimester, [2,3])) {
            if ($weightGain !== null) {
                if ($bmi < 18.5 && $weightGain < 0.5) {
                    $score++;
                } elseif ($bmi >= 18.5 && $bmi < 25 && $weightGain < 0.35) {
                    $score++;
                } elseif ($bmi >= 25 && $weightGain < 0.3) {
                    $score++;
                }
            }
        }

        $hb = $arr['hb'] ?? null;
        if ($hb !== null && $trimester !== null) {
            if ($trimester == 1 && $hb < 11.0) {
                $score++;
            } elseif ($trimester == 2 && $hb < 10.5) {
                $score++;
            } elseif ($trimester == 3 && $hb < 11.0) {
                $score++;
            }
        }

        if ($score <= 1) {
            $category = 'Risiko rendah';
        } elseif ($score <= 3) {
            $category = 'Risiko sedang';
        } else {
            $category = 'Risiko tinggi';
        }

        $item->update([
            'risk_score' => $score,
            'level_risiko' => $category,
        ]);

        return redirect()->to(locale_route('growth-detection.pre-stunting.index'))
                         ->with('success', 'Data berhasil diperbarui');
        // return redirect()->route('growth-detection.pre-stunting.index', [
        //     'locale' => app()->getLocale()
        // ])->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($locale, $id)
    {
        try {
            $realId = decrypt(urldecode($id));
        } catch (\Throwable $e) {
            $realId = $id;
        }
        $item = PreStunting::where('users_id', auth()->id())->findOrFail($realId);
        $item->delete();

        return redirect()->to(locale_route('growth-detection.pre-stunting.index'))
                         ->with('success', 'Data berhasil dihapus');
        // return redirect()->route('growth-detection.pre-stunting.index', [
        //     'locale' => app()->getLocale()
        // ])->with('success', 'Data berhasil dihapus');
    }
}
