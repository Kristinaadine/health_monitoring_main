# 📄 Implementasi PDF Download untuk Stunting Monitoring

## 🎯 Fitur PDF yang Diminta:

1. **Download Data Hari Ini** - Data terbaru saja
2. **Download Data Lengkap** - Semua riwayat data

## 📝 Update Controller

### File: `app/Http/Controllers/Monitoring/StuntingGrowthController.php`

Tambahkan method berikut:

```php
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * Handle photo upload
 */
private function handlePhotoUpload($request)
{
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        
        // Validate
        $request->validate([
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
        
        // Move to public/uploads/stunting
        $photo->move(public_path('uploads/stunting'), $filename);
        
        return $filename;
    }
    
    return null;
}

/**
 * Update store method to handle photo
 */
public function store(StuntingUserRequest $request)
{
    try {
        $data = $request->validated();
        
        // Handle photo upload
        if ($photoFilename = $this->handlePhotoUpload($request)) {
            $data['photo'] = $photoFilename;
        }
        
        // Calculate age from birth date if provided
        if ($request->tanggal_lahir) {
            $birthDate = new \Carbon\Carbon($request->tanggal_lahir);
            $data['tanggal_lahir'] = $birthDate->format('Y-m-d');
            $data['usia'] = $birthDate->diffInMonths(now());
        }
        
        // WHO Z-score calculations
        $haz = $this->lhfa($request->tinggi_badan, $data['usia'], $request->jenis_kelamin);
        $whz = $this->wfa($request->berat_badan, $data['usia'], $request->jenis_kelamin);

        // ... rest of existing code ...
        
        // Get nutrition recommendations
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
        
        // Save with recommendations
        $child = StuntingUserModel::create(array_merge($data, [
            'user_id' => auth()->user()->id,
            'haz' => $haz,
            'whz' => $whz,
            'status_pertumbuhan' => $whoStatus,
            'level_risiko' => $finalLevel,
            'faktor_utama' => $faktorUtama,
            'rekomendasi' => $rekomendasi,
            'haz_recommendation' => json_encode($hazRecommendation),
            'whz_recommendation' => json_encode($whzRecommendation),
        ]));

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
    
    $pdf = PDF::loadView('monitoring.growth-detection.stunting.pdf-today', [
        'child' => $child,
        'hazRec' => $hazRec,
        'whzRec' => $whzRec,
        'type' => 'today'
    ])->setPaper('a4', 'portrait');
    
    $filename = 'laporan-hari-ini-' . $child->nama . '-' . date('Y-m-d') . '.pdf';
    
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
    
    // Get all history for this child
    $allHistory = StuntingUserModel::where('user_id', auth()->id())
        ->where('nama', $child->nama)
        ->orderBy('created_at', 'desc')
        ->get();
    
    $hazRec = json_decode($child->haz_recommendation, true);
    $whzRec = json_decode($child->whz_recommendation, true);
    
    $pdf = PDF::loadView('monitoring.growth-detection.stunting.pdf-complete', [
        'child' => $child,
        'allHistory' => $allHistory,
        'hazRec' => $hazRec,
        'whzRec' => $whzRec,
        'type' => 'complete'
    ])->setPaper('a4', 'portrait');
    
    $filename = 'laporan-lengkap-' . $child->nama . '-' . date('Y-m-d') . '.pdf';
    
    return $pdf->download($filename);
}
```

## 🛣️ Update Routes

### File: `routes/web.php`

Tambahkan routes berikut:

```php
// Stunting PDF Downloads
Route::get('/growth-detection/stunting/result/{id}/pdf-today', [StuntingGrowthController::class, 'downloadPDFToday'])
    ->name('growth-detection.stunting.result.pdf-today');
    
Route::get('/growth-detection/stunting/result/{id}/pdf-complete', [StuntingGrowthController::class, 'downloadPDFComplete'])
    ->name('growth-detection.stunting.result.pdf-complete');
```

## 📄 Template PDF - Data Hari Ini

### File: `resources/views/monitoring/growth-detection/stunting/pdf-today.blade.php`

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pertumbuhan Anak - {{ $child->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 11px;
            line-height: 1.6;
            color: #333;
        }
        .container { padding: 20px; }
        .header { 
            text-align: center; 
            margin-bottom: 30px;
            border-bottom: 3px solid #28a745;
            padding-bottom: 15px;
        }
        .header h1 { 
            color: #28a745; 
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header p { 
            color: #666; 
            font-size: 10px;
        }
        
        .photo-section {
            text-align: center;
            margin: 20px 0;
        }
        .photo-section img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 10px;
            border: 3px solid #ddd;
        }
        
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-box h3 {
            color: #28a745;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 2px solid #28a745;
            padding-bottom: 5px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 15px 0;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left;
        }
        th { 
            background-color: #28a745; 
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
        }
        .badge-danger { background: #dc3545; }
        .badge-warning { background: #ffc107; color: #000; }
        .badge-success { background: #28a745; }
        .badge-info { background: #17a2b8; }
        
        .status-box {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .status-box.danger {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        .status-box.success {
            background: #d4edda;
            border-left-color: #28a745;
        }
        
        .recommendation {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .recommendation h4 {
            color: #0056b3;
            margin-bottom: 10px;
        }
        .recommendation ul {
            margin-left: 20px;
        }
        .recommendation li {
            margin-bottom: 8px;
            line-height: 1.8;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .watermark {
            position: fixed;
            bottom: 20px;
            right: 20px;
            opacity: 0.1;
            font-size: 60px;
            color: #28a745;
            transform: rotate(-45deg);
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>📊 LAPORAN PERTUMBUHAN ANAK</h1>
            <p>Sistem Monitoring Kesehatan & Nutrisi</p>
            <p><strong>Data Pemeriksaan Hari Ini</strong></p>
        </div>

        {{-- Photo Section --}}
        @if($child->photo)
        <div class="photo-section">
            <img src="{{ public_path('uploads/stunting/' . $child->photo) }}" alt="Foto {{ $child->nama }}">
        </div>
        @endif

        {{-- Identitas Anak --}}
        <div class="info-box">
            <h3>👤 Identitas Anak</h3>
            <table>
                <tr>
                    <th width="35%">Nama Lengkap</th>
                    <td><strong>{{ $child->nama }}</strong></td>
                </tr>
                @if($child->medical_id)
                <tr>
                    <th>ID Rekam Medis</th>
                    <td><strong>{{ $child->medical_id }}</strong></td>
                </tr>
                @endif
                <tr>
                    <th>Tanggal Lahir</th>
                    <td>{{ $child->tanggal_lahir ? \Carbon\Carbon::parse($child->tanggal_lahir)->format('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <th>Usia</th>
                    <td><strong>{{ $child->usia }} bulan</strong></td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ $child->jenis_kelamin == 'L' ? '👦 Laki-laki' : '👧 Perempuan' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pemeriksaan</th>
                    <td>{{ \Carbon\Carbon::parse($child->created_at)->format('d F Y, H:i') }} WIB</td>
                </tr>
            </table>
        </div>

        {{-- Data Antropometri --}}
        <div class="info-box">
            <h3>📏 Data Antropometri</h3>
            <table>
                <tr>
                    <th width="35%">Berat Badan</th>
                    <td><strong>{{ $child->berat_badan }} kg</strong></td>
                </tr>
                <tr>
                    <th>Tinggi Badan</th>
                    <td><strong>{{ $child->tinggi_badan }} cm</strong></td>
                </tr>
                <tr>
                    <th>Lingkar Lengan Atas (MUAC)</th>
                    <td><strong>{{ $child->lingkar_lengan }} cm</strong></td>
                </tr>
            </table>
        </div>

        {{-- Hasil Analisis Z-Score --}}
        <div class="info-box">
            <h3>📊 Hasil Analisis Z-Score (WHO)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Indikator</th>
                        <th>Z-Score</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>TB/U</strong> (Tinggi menurut Umur)</td>
                        <td><strong>{{ number_format($child->haz, 2) }}</strong></td>
                        <td>
                            <span class="badge badge-{{ $hazRec['color'] ?? 'secondary' }}">
                                {{ $hazRec['icon'] ?? '📊' }} {{ $hazRec['status'] ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>BB/U</strong> (Berat menurut Umur)</td>
                        <td><strong>{{ number_format($child->whz, 2) }}</strong></td>
                        <td>
                            <span class="badge badge-{{ $whzRec['color'] ?? 'secondary' }}">
                                {{ $whzRec['icon'] ?? '📊' }} {{ $whzRec['status'] ?? '-' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Status Pertumbuhan --}}
        @php
            $statusClass = 'success';
            if ($child->haz < -3 || $child->whz < -3) {
                $statusClass = 'danger';
            } elseif ($child->haz < -2 || $child->whz < -2) {
                $statusClass = 'warning';
            }
        @endphp
        
        <div class="status-box {{ $statusClass }}">
            <h3 style="margin-bottom: 10px;">
                {{ $hazRec['icon'] ?? '📊' }} Status Pertumbuhan: 
                <strong>{{ $hazRec['status'] ?? $child->status_pertumbuhan }}</strong>
            </h3>
            @if(isset($hazRec['warning']) && $hazRec['warning'])
                <p style="font-size: 12px; margin-top: 10px;">
                    <strong>⚠️ {{ $hazRec['warning'] }}</strong>
                </p>
            @endif
        </div>

        {{-- Rekomendasi Nutrisi --}}
        @if($hazRec && isset($hazRec['advice']))
        <div class="recommendation">
            <h4>🍎 Rekomendasi Nutrisi & Perawatan</h4>
            <ul>
                @foreach($hazRec['advice'] as $advice)
                    <li>{{ $advice }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            <p><strong>Catatan Penting:</strong></p>
            <p>Laporan ini adalah hasil analisis sistem berdasarkan standar WHO.</p>
            <p>Konsultasikan hasil ini dengan tenaga kesehatan profesional (dokter/ahli gizi) untuk penanganan lebih lanjut.</p>
            <p style="margin-top: 10px;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
            <p>© {{ date('Y') }} Sistem Monitoring Kesehatan</p>
        </div>
    </div>
</body>
</html>
```

## 📄 Template PDF - Data Lengkap

### File: `resources/views/monitoring/growth-detection/stunting/pdf-complete.blade.php`

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Lengkap - {{ $child->nama }}</title>
    <style>
        /* Same styles as pdf-today.blade.php */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 10px;
            line-height: 1.5;
            color: #333;
        }
        .container { padding: 20px; }
        .header { 
            text-align: center; 
            margin-bottom: 20px;
            border-bottom: 3px solid #28a745;
            padding-bottom: 10px;
        }
        .header h1 { 
            color: #28a745; 
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .history-item {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #28a745;
            page-break-inside: avoid;
        }
        .history-item h4 {
            color: #28a745;
            margin-bottom: 10px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 10px 0;
            font-size: 9px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 6px; 
            text-align: left;
        }
        th { 
            background-color: #28a745; 
            color: white;
        }
        
        .chart-placeholder {
            background: #e9ecef;
            padding: 40px;
            text-align: center;
            margin: 20px 0;
            border: 2px dashed #adb5bd;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>📊 LAPORAN LENGKAP PERTUMBUHAN ANAK</h1>
            <p>Sistem Monitoring Kesehatan & Nutrisi</p>
            <p><strong>Riwayat Pemeriksaan Lengkap</strong></p>
        </div>

        {{-- Summary --}}
        <div style="background: #e7f3ff; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
            <h3 style="color: #0056b3; margin-bottom: 10px;">📋 Ringkasan</h3>
            <table>
                <tr>
                    <th width="30%">Nama Anak</th>
                    <td><strong>{{ $child->nama }}</strong></td>
                </tr>
                @if($child->medical_id)
                <tr>
                    <th>ID Rekam Medis</th>
                    <td><strong>{{ $child->medical_id }}</strong></td>
                </tr>
                @endif
                <tr>
                    <th>Total Pemeriksaan</th>
                    <td><strong>{{ $allHistory->count() }} kali</strong></td>
                </tr>
                <tr>
                    <th>Periode</th>
                    <td>
                        {{ $allHistory->last()->created_at->format('d M Y') }} - 
                        {{ $allHistory->first()->created_at->format('d M Y') }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- Grafik Pertumbuhan (Placeholder) --}}
        <div class="chart-placeholder">
            <h3 style="color: #666;">📈 Grafik Pertumbuhan</h3>
            <p style="margin-top: 10px; color: #999;">
                [Grafik kurva pertumbuhan WHO akan ditampilkan di sini]<br>
                Menunjukkan perkembangan tinggi dan berat badan dari waktu ke waktu
            </p>
        </div>

        {{-- Riwayat Pemeriksaan --}}
        <h3 style="color: #28a745; margin: 20px 0 10px 0; border-bottom: 2px solid #28a745; padding-bottom: 5px;">
            📅 Riwayat Pemeriksaan Detail
        </h3>

        @foreach($allHistory as $index => $history)
        <div class="history-item">
            <h4>
                Pemeriksaan #{{ $allHistory->count() - $index }} - 
                {{ \Carbon\Carbon::parse($history->created_at)->format('d F Y, H:i') }} WIB
            </h4>
            
            <table>
                <tr>
                    <th width="25%">Usia</th>
                    <td width="25%">{{ $history->usia }} bulan</td>
                    <th width="25%">Berat Badan</th>
                    <td width="25%">{{ $history->berat_badan }} kg</td>
                </tr>
                <tr>
                    <th>Tinggi Badan</th>
                    <td>{{ $history->tinggi_badan }} cm</td>
                    <th>MUAC</th>
                    <td>{{ $history->lingkar_lengan }} cm</td>
                </tr>
                <tr>
                    <th>Z-Score TB/U</th>
                    <td><strong>{{ number_format($history->haz, 2) }}</strong></td>
                    <th>Z-Score BB/U</th>
                    <td><strong>{{ number_format($history->whz, 2) }}</strong></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td colspan="3">
                        @php
                            $historyRec = json_decode($history->haz_recommendation, true);
                        @endphp
                        <strong>{{ $historyRec['status'] ?? $history->status_pertumbuhan }}</strong>
                    </td>
                </tr>
            </table>
        </div>
        @endforeach

        {{-- Footer --}}
        <div class="footer">
            <p><strong>Catatan Penting:</strong></p>
            <p>Laporan ini berisi riwayat lengkap pemeriksaan pertumbuhan anak berdasarkan standar WHO.</p>
            <p>Konsultasikan dengan tenaga kesehatan profesional untuk interpretasi dan tindak lanjut.</p>
            <p style="margin-top: 10px;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
            <p>© {{ date('Y') }} Sistem Monitoring Kesehatan</p>
        </div>
    </div>
</body>
</html>
```

## 🎨 Update Result View dengan Tombol PDF

### File: `resources/views/monitoring/growth-detection/stunting/result.blade.php`

Tambahkan section tombol download:

```php
{{-- Tombol Download PDF --}}
<div class="card mb-3">
    <div class="card-header bg-primary text-white">
        <strong>📄 Download Laporan PDF</strong>
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-6">
                <a href="{{ locale_route('growth-detection.stunting.result.pdf-today', encrypt($child->id)) }}" 
                   class="btn btn-success w-100">
                    <i class="icofont-file-pdf"></i> Data Hari Ini
                </a>
                <small class="text-muted d-block mt-1 text-center">Pemeriksaan terbaru</small>
            </div>
            <div class="col-6">
                <a href="{{ locale_route('growth-detection.stunting.result.pdf-complete', encrypt($child->id)) }}" 
                   class="btn btn-primary w-100">
                    <i class="icofont-file-pdf"></i> Data Lengkap
                </a>
                <small class="text-muted d-block mt-1 text-center">Semua riwayat</small>
            </div>
        </div>
    </div>
</div>
```

## ✅ Checklist Implementasi

- [x] Migration untuk photo, medical_id, tanggal_lahir
- [x] Form input dengan foto & medical ID
- [x] JavaScript untuk preview foto
- [x] Auto-calculate usia dari tanggal lahir
- [ ] Update controller store() untuk handle foto
- [ ] Method downloadPDFToday()
- [ ] Method downloadPDFComplete()
- [ ] Template PDF untuk data hari ini
- [ ] Template PDF untuk data lengkap
- [ ] Update routes
- [ ] Update result view dengan tombol download

## 🚀 Cara Testing

1. Jalankan migration
2. Buka form stunting
3. Upload foto anak
4. Input medical ID
5. Pilih tanggal lahir (usia auto-calculate)
6. Submit form
7. Di halaman result, klik "Data Hari Ini" atau "Data Lengkap"
8. PDF akan terdownload

---

**Version:** 2.2  
**Last Updated:** November 13, 2025
