<?php

namespace App\Http\Controllers\Monitoring;

use App\Models\ZScoreModel;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GrowthMonitoringModel;
use App\Models\GrowthMonitoringHistoryModel;

class GrowthMonitoringController extends Controller
{
    /**
     * Get user data (child_id dan photo) untuk user yang sudah pernah input
     */
    public function getUserData(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'has_data' => false,
                'child_id' => null,
                'error' => 'User not authenticated'
            ], 401);
        }
        
        // Cek apakah user sudah punya child_id di database
        if (!empty($user->child_id)) {
            // Gunakan child_id yang sudah ada (PERMANEN)
            $childId = $user->child_id;
        } else {
            // Generate child_id baru dengan format: [Nomor Urut]-[Huruf Urut]-[4 Angka Random]
            $totalUsersWithChildId = \App\Models\User::whereNotNull('child_id')
                ->where('child_id', '!=', '')
                ->count();
            
            $nomorUrut = str_pad($totalUsersWithChildId + 1, 2, '0', STR_PAD_LEFT);
            $hurufUrut = chr(65 + $totalUsersWithChildId); // A, B, C, ...
            $angkaRandom = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            
            $childId = "{$nomorUrut}-{$hurufUrut}-{$angkaRandom}";
            
            // Simpan ke database (PERMANEN)
            $user->child_id = $childId;
            $user->save();
        }
        
        // Ambil foto dari data pertama (jika ada)
        $firstData = GrowthMonitoringModel::where('users_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->first();
        
        return response()->json([
            'has_data' => $firstData ? true : false,
            'child_id' => $childId,
            'photo' => $firstData ? $firstData->photo : null,
            'photo_url' => ($firstData && $firstData->photo) ? asset('uploads/growth-monitoring/' . $firstData->photo) : null
        ]);
    }
    
    public function index(Request $request)
    {
        $setting = SettingModel::all();
        $name = $request->name;
        
        // Optimized query dengan eager loading
        if ($name) {
            $data = GrowthMonitoringModel::with(['history' => function($query) {
                    $query->select('id', 'id_growth', 'type', 'zscore', 'hasil_diagnosa', 'deskripsi_diagnosa', 'penanganan');
                }])
                ->where('users_id', auth()->user()->id)
                ->where('name', 'like', '%' . $name . '%')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $data = GrowthMonitoringModel::with(['history' => function($query) {
                    $query->select('id', 'id_growth', 'type', 'zscore', 'hasil_diagnosa', 'deskripsi_diagnosa', 'penanganan');
                }])
                ->where('users_id', auth()->user()->id)
                ->get();

            if (count($data) > 0) {
                $name = $data[0]->name;
            } else {
                $name = '';
            }

            $data = GrowthMonitoringModel::with(['history' => function($query) {
                    $query->select('id', 'id_growth', 'type', 'zscore', 'hasil_diagnosa', 'deskripsi_diagnosa', 'penanganan');
                }])
                ->where('users_id', auth()->user()->id)
                ->where('name', $name)
                ->orderBy('id', 'desc')
                ->get();
        }

        $choosename = GrowthMonitoringModel::select(['name', 'gender'])
            ->groupBy(['name', 'gender'])
            ->distinct('name')
            ->get();

        $height = [];
        $weight = [];
        $xAxis = [];

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

        return view('monitoring.growth-monitoring.index', compact('setting', 'data', 'choosename', 'graph'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Growth Monitoring Store - Start', $request->all());
            
            // Validasi input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'age' => 'required|integer|min:0|max:60',
                'gender' => 'required|in:L,P',
                'height' => 'required|numeric|min:40|max:130',
                'weight' => 'required|numeric|min:2|max:50',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'age.required' => 'Usia wajib diisi.',
                'age.min' => 'Usia minimal 0 bulan.',
                'age.max' => 'Usia maksimal 60 bulan (5 tahun). Untuk anak lebih besar, gunakan fitur lain.',
                'gender.required' => 'Jenis kelamin wajib dipilih.',
                'gender.in' => 'Jenis kelamin harus L atau P.',
                'height.required' => 'Tinggi badan wajib diisi.',
                'height.min' => 'Tinggi badan minimal 40 cm.',
                'height.max' => 'Tinggi badan maksimal 130 cm (untuk anak 0-60 bulan).',
                'weight.required' => 'Berat badan wajib diisi.',
                'weight.min' => 'Berat badan minimal 2 kg.',
                'weight.max' => 'Berat badan maksimal 50 kg (untuk anak 0-60 bulan).',
                'photo.image' => 'File harus berupa gambar.',
                'photo.mimes' => 'Format foto harus JPG, PNG, atau JPEG.',
                'photo.max' => 'Ukuran foto maksimal 2MB.',
            ]);

            \Log::info('Growth Monitoring Store - Validation passed');

            $data = $validated;
            $data['users_id'] = auth()->user()->id;
            $data['login_created'] = auth()->user()->email;
            
            $user = auth()->user();
            
            // Cek apakah user sudah punya child_id di database
            if (!empty($user->child_id)) {
                // Gunakan child_id yang sudah ada (PERMANEN)
                $childId = $user->child_id;
            } else {
                // Generate child_id baru dengan format: [Nomor Urut]-[Huruf Urut]-[4 Angka Random]
                // Hitung total user yang sudah punya child_id
                $totalUsersWithChildId = \App\Models\User::whereNotNull('child_id')
                    ->where('child_id', '!=', '')
                    ->count();
                
                $nomorUrut = str_pad($totalUsersWithChildId + 1, 2, '0', STR_PAD_LEFT);
                $hurufUrut = chr(65 + $totalUsersWithChildId); // A, B, C, ...
                $angkaRandom = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                
                $childId = "{$nomorUrut}-{$hurufUrut}-{$angkaRandom}";
                
                // Simpan ke database (PERMANEN)
                $user->child_id = $childId;
                $user->save();
                
                \Log::info('Generated NEW child_id', [
                    'user_id' => $user->id,
                    'child_id' => $childId
                ]);
            }
            
            $data['child_id'] = $childId;
            
            \Log::info('Using child_id', [
                'user_id' => $user->id,
                'child_id' => $childId
            ]);
            
            \Log::info('Growth Monitoring Store - Final child_id to be saved', [
                'user_id' => $user->id,
                'child_id' => $data['child_id']
            ]);
            
            // Cek data sebelumnya untuk foto
            $firstData = GrowthMonitoringModel::where('users_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->first();
            
            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/growth-monitoring'), $photoName);
                $data['photo'] = $photoName;
                
                // Hapus foto lama jika ada
                if ($firstData && $firstData->photo && file_exists(public_path('uploads/growth-monitoring/' . $firstData->photo))) {
                    @unlink(public_path('uploads/growth-monitoring/' . $firstData->photo));
                }
                
                \Log::info('Growth Monitoring Store - Photo uploaded', ['filename' => $photoName]);
            } else {
                // Gunakan foto dari data pertama (jika ada)
                $data['photo'] = $firstData ? $firstData->photo : null;
            }

            $growth = GrowthMonitoringModel::create($data);
            \Log::info('Growth Monitoring Store - Growth created', ['id' => $growth->id]);

            // Hitung Z-Score untuk data baru
            $heightZScore = $this->lhfa($request->height, $request->age, $growth->id, $request->gender);
            \Log::info('Growth Monitoring Store - LHFA completed');
            
            $weightZScore = $this->wfa($request->weight, $request->age, $growth->id, $request->gender);
            \Log::info('Growth Monitoring Store - WFA completed');
            
            // Validasi data anomali: cek perubahan ekstrem
            $anomalyWarnings = [];
            $previousData = GrowthMonitoringModel::where('users_id', $user->id)
                ->where('name', $request->name)
                ->where('id', '!=', $growth->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($previousData) {
                $ageDiff = abs($request->age - $previousData->age);
                
                // Ambil Z-Score sebelumnya
                $prevHeightHistory = $previousData->history->where('type', 'LH')->first();
                $prevWeightHistory = $previousData->history->where('type', 'W')->first();
                
                if ($prevHeightHistory && $heightZScore !== null) {
                    $heightZDiff = abs($heightZScore - $prevHeightHistory->zscore);
                    
                    // Peringatan jika perubahan > 3 SD dalam < 1 bulan
                    if ($ageDiff < 1 && $heightZDiff > 3) {
                        $anomalyWarnings[] = "⚠️ Perubahan tinggi badan tampak tidak wajar (Z-Score berubah {$heightZDiff} dalam {$ageDiff} bulan). Mohon pastikan kembali input tinggi badan.";
                    }
                }
                
                if ($prevWeightHistory && $weightZScore !== null) {
                    $weightZDiff = abs($weightZScore - $prevWeightHistory->zscore);
                    
                    // Peringatan jika perubahan > 3 SD dalam < 1 bulan
                    if ($ageDiff < 1 && $weightZDiff > 3) {
                        $anomalyWarnings[] = "⚠️ Perubahan berat badan tampak tidak wajar (Z-Score berubah {$weightZDiff} dalam {$ageDiff} bulan). Mohon pastikan kembali input berat badan.";
                    }
                }
            }

            try {
                $redirectUrl = locale_route('growth-monitoring.show', encrypt($growth->id));
                \Log::info('Growth Monitoring Store - Success', ['redirect' => $redirectUrl]);
            } catch (\Exception $e) {
                \Log::error('Error generating redirect URL: ' . $e->getMessage());
                // Fallback to index if redirect URL fails
                $redirectUrl = locale_route('growth-monitoring.index');
            }

            $responseData = [
                'status' => 'success',
                'message' => 'Hasil Z-Score berhasil disimpan',
                'redirect' => $redirectUrl,
                'child_id' => $growth->child_id, // Kirim child_id untuk ditampilkan
            ];
            
            // Tambahkan peringatan anomali jika ada
            if (!empty($anomalyWarnings)) {
                $responseData['warnings'] = $anomalyWarnings;
            }

            return response()->json($responseData, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Growth Monitoring Store - Validation Error', ['errors' => $e->errors()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Growth Monitoring Store - Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null,
            ], 500);
        }
    }

    /**
     * Calculate Z-Score using WHO LMS Method
     * 
     * @param float $nilaiAnak - Actual measurement (height or weight)
     * @param object $param - WHO parameters (L, M, S)
     * @return float - Calculated Z-Score
     */
    private function hitungZScore($nilaiAnak, $param)
    {
        // WHO LMS Method Formula:
        // If L = 0 (special case): Z = ln(X/M) / S
        // If L ≠ 0 (general case): Z = ((X/M)^L - 1) / (L·S)
        
        if ($param->L == 0) {
            // Kasus khusus: L = 0
            // Z = ln(X/M) / S
            return log($nilaiAnak / $param->M) / $param->S;
        } else {
            // Kasus umum: L ≠ 0
            // Z = ((X/M)^L - 1) / (L·S)
            $ratio = $nilaiAnak / $param->M;
            $powered = pow($ratio, $param->L);
            return ($powered - 1) / ($param->L * $param->S);
        }
    }

    public function lhfa($lh, $age, $id, $gender)
    {
        $param = ZScoreModel::where('month', $age)->where('gender', $gender)->where('type', 'LH')->first();

        if (!$param) {
            \Log::error("ZScore data not found for LH", ['month' => $age, 'gender' => $gender]);
            // Create default history record
            GrowthMonitoringHistoryModel::create([
                'id_growth' => $id,
                'type' => 'LH',
                'value' => $lh,
                'zscore' => 0,
                'hasil_diagnosa' => 'Data tidak tersedia',
                'deskripsi_diagnosa' => 'Data Z-Score untuk usia dan jenis kelamin ini tidak tersedia dalam database.',
                'penanganan' => 'Silakan konsultasi dengan tenaga kesehatan.',
            ]);
            return 0;
        }

        // Hitung Z-Score menggunakan WHO LMS Method
        $zscore = $this->hitungZScore($lh, $param);
        
        $hasil_diagnosa = '';
        $deskripsi_diagnosa = '';
        $penanganan = '';

        if ($zscore > 3) {
            $hasil_diagnosa = 'Tinggi Badan Sangat Tinggi';
            $deskripsi_diagnosa = 'Anak mengalami status tinggi badan sangat tinggi berdasarkan Z-score tinggi badan menurut umur (TB/U), Nilai Z-score sebesar ' . $zscore . ' menandakan tinggi badan anak berada di atas standar WHO untuk usianya, perlu penanganan khusus untuk menjaga kesehatan tulang dan sendi.';
            $penanganan = 'Konsultasi dengan ahli gizi untuk program pertumbuhan yang sehat.';
        } elseif ($zscore > 2 && $zscore <= 3) {
            $hasil_diagnosa = 'Tinggi';
            $deskripsi_diagnosa = 'Anak mengalami status tinggi badan tinggi berdasarkan Z-score tinggi badan menurut umur (TB/U), Nilai Z-score sebesar ' . $zscore . ' menandakan tinggi badan anak berada di atas standar WHO untuk usianya, perlu perhatian khusus untuk menjaga kesehatan tulang dan sendi.';
            $penanganan = 'Perbaiki pola makan dan tingkatkan aktivitas fisik.';
        } elseif ($zscore >= -2 && $zscore <= 2) {
            $hasil_diagnosa = 'Tinggi Badan Normal';
            $deskripsi_diagnosa = 'Anak memiliki tinggi badan yang normal berdasarkan Z-score tinggi badan menurut umur (TB/U), Nilai Z-score sebesar ' . $zscore . ' menandakan tinggi badan anak sesuai dengan standar WHO untuk usianya.';
            $penanganan = 'Pertahankan pola makan sehat dan aktif secara fisik.';
        } elseif ($zscore < -2 && $zscore >= -3) {
            $hasil_diagnosa = 'Pendek';
            $deskripsi_diagnosa = 'Anak mengalami status tinggi badan pendek berdasarkan Z-score tinggi badan menurut umur (TB/U), Nilai Z-score sebesar ' . $zscore . ' menandakan tinggi badan anak sedikit di bawah standar WHO untuk usianya, perlu perhatian untuk meningkatkan asupan gizi.';
            $penanganan = 'Tingkatkan asupan gizi dengan makanan bergizi.';
        } elseif ($zscore < -3) {
            $hasil_diagnosa = 'Sangat Pendek';
            $deskripsi_diagnosa = 'Anak mengalami status tinggi badan sangat pendek berdasarkan Z-score tinggi badan menurut umur (TB/U), Nilai Z-score sebesar ' . $zscore . ' menandakan tinggi badan anak jauh di bawah standar WHO untuk usianya, perlu penanganan khusus untuk meningkatkan pertumbuhan.';
            $penanganan = 'Konsultasi dengan ahli gizi dan dokter untuk program pertumbuhan yang intensif.';
        }

        $data = [
            'id_growth' => $id,
            'type' => 'LH',
            'value' => $lh,
            'zscore' => $zscore,
            'hasil_diagnosa' => $hasil_diagnosa,
            'deskripsi_diagnosa' => $deskripsi_diagnosa,
            'penanganan' => $penanganan,
        ];

        $result = GrowthMonitoringHistoryModel::create($data);
        
        return $zscore;
    }

    public function wfa($w, $age, $id, $gender)
    {
        $param = ZScoreModel::where('month', $age)->where('gender', $gender)->where('type', 'W')->first();
        
        if (!$param) {
            \Log::error("ZScore data not found for W", ['month' => $age, 'gender' => $gender]);
            // Create default history record
            GrowthMonitoringHistoryModel::create([
                'id_growth' => $id,
                'type' => 'W',
                'value' => $w,
                'zscore' => 0,
                'hasil_diagnosa' => 'Data tidak tersedia',
                'deskripsi_diagnosa' => 'Data Z-Score untuk usia dan jenis kelamin ini tidak tersedia dalam database.',
                'penanganan' => 'Silakan konsultasi dengan tenaga kesehatan.',
            ]);
            return 0;
        }
        
        // Hitung Z-Score menggunakan WHO LMS Method
        $zscore = $this->hitungZScore($w, $param);
        
        $hasil_diagnosa = '';
        $deskripsi_diagnosa = '';
        $penanganan = '';

        if ($zscore > 3) {
            $hasil_diagnosa = 'Obesitas';
            $deskripsi_diagnosa = 'Anak mengalami status obesitas berdasarkan Z-score berat badan menurut umur (BB/U),  Nilai Z-score sebesar ' . $zscore . ' menandakan berat badan anak berada di atas standar WHO untuk usianya, perlu penanganan khusus untuk mengurangi berat badan.';
            $penanganan = 'Konsultasi dengan ahli gizi untuk program penurunan berat badan.';
        } elseif ($zscore > 2 && $zscore <= 3) {
            $hasil_diagnosa = 'Gizi Lebih';
            $deskripsi_diagnosa = 'Anak mengalami status gizi lebih berdasarkan Z-score berat badan menurut umur (BB/U), Nilai Z-score sebesar ' . $zscore . ' menandakan berat badan anak berada di atas standar WHO untuk usianya, perlu perhatian khusus untuk menjaga pola makan yang sehat.';
            $penanganan = 'Perbaiki pola makan dan tingkatkan aktivitas fisik.';
        } elseif ($zscore > 1 && $zscore <= 2) {
            $hasil_diagnosa = 'Risiko Gizi Lebih';
            $deskripsi_diagnosa = 'Anak berada pada risiko gizi lebih berdasarkan Z-score berat badan menurut umur (BB/U), Nilai Z-score sebesar ' . $zscore . ' menandakan berat badan anak sedikit di atas standar WHO untuk usianya, perlu perhatian untuk menjaga pola makan yang sehat.';
            $penanganan = 'Perhatikan pola makan dan tingkatkan aktivitas fisik.';
        } elseif ($zscore >= -2 && $zscore <= 1) {
            $hasil_diagnosa = 'Gizi Normal';
            $deskripsi_diagnosa = 'Anak memiliki berat badan yang normal berdasarkan Z-score berat badan menurut umur (BB/U), Nilai Z-score sebesar ' . $zscore . ' menandakan berat badan anak sesuai dengan standar WHO untuk usianya.';
            $penanganan = 'Pertahankan pola makan sehat dan aktif secara fisik.';
        } elseif ($zscore < -2 && $zscore >= -3) {
            $hasil_diagnosa = 'Gizi Kurang';
            $deskripsi_diagnosa = 'Anak mengalami status gizi kurang berdasarkan Z-score berat badan menurut umur (BB/U), Nilai Z-score sebesar ' . $zscore . ' menandakan berat badan anak sedikit di bawah standar WHO untuk usianya, perlu perhatian untuk meningkatkan asupan gizi.';
            $penanganan = 'Tingkatkan asupan gizi dengan makanan bergizi.';
        }

        $data = [
            'id_growth' => $id,
            'type' => 'W',
            'value' => $w,
            'zscore' => $zscore,
            'hasil_diagnosa' => $hasil_diagnosa,
            'deskripsi_diagnosa' => $deskripsi_diagnosa,
            'penanganan' => $penanganan,
        ];

        $result = GrowthMonitoringHistoryModel::create($data);
        
        return $zscore;
    }

    public function show($locale, $id)
    {
        $setting = SettingModel::all();
        $data = GrowthMonitoringModel::with('history')->findOrFail(decrypt($id));
        if (!$data) {
            return redirect()->to(locale_route('growth-monitoring.index'))->with('error', 'Data not found');
        }
        return view('monitoring.growth-monitoring.show', compact('setting', 'data'));
    }
    
    /**
     * Generate PDF Report - Single Record
     */
    public function downloadReport($locale, $id)
    {
        try {
            $data = GrowthMonitoringModel::with('history')->findOrFail(decrypt($id));
            
            if (!$data) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }
            
            // Get history data
            $heightHistory = $data->history->where('type', 'LH')->first();
            $weightHistory = $data->history->where('type', 'W')->first();
            
            // Prepare report data
            $reportData = [
                'child' => $data,
                'heightHistory' => $heightHistory,
                'weightHistory' => $weightHistory,
                'generatedAt' => now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm'),
            ];
            
            // Load view
            $pdf = \PDF::loadView('monitoring.growth-monitoring.report-pdf', $reportData);
            
            // Set paper size and orientation
            $pdf->setPaper('a4', 'portrait');
            
            // Generate filename
            $filename = 'Laporan_Pertumbuhan_' . str_replace(' ', '_', $data->name) . '_' . date('Ymd') . '.pdf';
            
            // Download PDF
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            
            return redirect()->back()->with('error', 'Gagal generate laporan: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate PDF Report - All Records for Current Child
     */
    public function downloadCompleteReport(Request $request)
    {
        try {
            $name = $request->name;
            
            if (!$name) {
                return redirect()->back()->with('error', 'Nama anak tidak ditemukan');
            }
            
            // Get all data for this child
            $data = GrowthMonitoringModel::with('history')
                ->where('users_id', auth()->user()->id)
                ->where('name', $name)
                ->orderBy('created_at', 'desc')
                ->get();
            
            if ($data->isEmpty()) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }
            
            // Prepare report data
            $reportData = [
                'data' => $data,
                'childName' => $name,
            ];
            
            // Load view
            $pdf = \PDF::loadView('monitoring.growth-monitoring.report-pdf', $reportData);
            
            // Set paper size and orientation
            $pdf->setPaper('a4', 'portrait');
            
            // Generate filename
            $filename = 'Laporan_Lengkap_' . str_replace(' ', '_', $name) . '_' . date('Ymd') . '.pdf';
            
            // Download PDF
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF Complete Report Generation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Gagal generate laporan: ' . $e->getMessage());
        }
    }

    public function destroy($locale, $id)
    {
        try {
            $growth = GrowthMonitoringModel::find(decrypt($id));
            
            if (!$growth) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            
            // Log before delete for audit trail
            \Log::info('Growth Monitoring Delete', [
                'id' => $growth->id,
                'name' => $growth->name,
                'age' => $growth->age,
                'deleted_by' => auth()->user()->email,
                'deleted_at' => now()
            ]);
            
            // Update login_deleted before soft delete
            $data['login_deleted'] = auth()->user()->email;
            $growth->update($data);
            
            // Soft delete (data masih bisa di-restore)
            $growth->delete();
            
            return response()->json([
                'status' => 'success', 
                'message' => 'Data berhasil dihapus. Data masih dapat dipulihkan jika diperlukan.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Growth Monitoring Delete Error', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data'
            ], 500);
        }
    }
    
    /**
     * Restore soft deleted data
     */
    public function restore($locale, $id)
    {
        try {
            $growth = GrowthMonitoringModel::withTrashed()->find(decrypt($id));
            
            if (!$growth) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            
            if (!$growth->trashed()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak dalam status terhapus'
                ], 400);
            }
            
            // Log restore action
            \Log::info('Growth Monitoring Restore', [
                'id' => $growth->id,
                'name' => $growth->name,
                'restored_by' => auth()->user()->email,
                'restored_at' => now()
            ]);
            
            $growth->restore();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dipulihkan'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Growth Monitoring Restore Error', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memulihkan data'
            ], 500);
        }
    }
}
