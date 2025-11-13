# 🎯 Implementasi Peningkatan Fitur Growth Monitoring for Stunting

## 📋 Daftar Peningkatan

### ✅ Yang Sudah Diimplementasikan:

1. **Helper Rekomendasi Nutrisi** ✅
   - File: `app/Helpers/NutritionRecommendation.php`
   - Fungsi: Memberikan rekomendasi berdasarkan Z-score
   - Fitur:
     - Rekomendasi spesifik untuk TB/U, BB/U, BB/TB
     - Warna badge (merah/kuning/hijau/biru)
     - Icon emoji untuk visual
     - Advice list yang actionable
     - Warning untuk kasus darurat

2. **Migration untuk Foto & Medical ID** ✅
   - File: `database/migrations/2025_11_13_105433_add_photo_and_medical_id_to_children_table.php`
   - Kolom baru:
     - `medical_id` (string, nullable, unique)
     - `photo` (string, nullable)

### 🔄 Yang Perlu Diupdate:

#### 1. Form Input (form.blade.php)
**Lokasi:** `resources/views/monitoring/growth-detection/stunting/form.blade.php`

**Perubahan:**
```php
// Tambahkan di form:
<div class="form-group mb-3">
    <label for="medical_id">ID Rekam Medis (Opsional)</label>
    <input type="text" class="form-control" id="medical_id" name="medical_id" 
           placeholder="Contoh: RM-2025-001">
    <small class="text-muted">ID untuk sistem internal (opsional)</small>
</div>

<div class="form-group mb-3">
    <label for="tanggal_lahir">Tanggal Lahir</label>
    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
           required max="{{ date('Y-m-d') }}">
    <small class="text-muted">Usia akan dihitung otomatis</small>
</div>

<div class="form-group mb-3">
    <label for="usia">Usia (bulan)</label>
    <input type="number" class="form-control" id="usia" name="usia" 
           readonly placeholder="Akan terisi otomatis">
</div>

<div class="form-group mb-3">
    <label for="photo">Foto Anak (Opsional)</label>
    <input type="file" class="form-control" id="photo" name="photo" 
           accept="image/*">
    <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
    <div id="photo-preview" class="mt-2"></div>
</div>

<script>
// Auto calculate age from birth date
$('#tanggal_lahir').on('change', function() {
    const birthDate = new Date($(this).val());
    const today = new Date();
    
    let months = (today.getFullYear() - birthDate.getFullYear()) * 12;
    months -= birthDate.getMonth();
    months += today.getMonth();
    
    if (today.getDate() < birthDate.getDate()) {
        months--;
    }
    
    if (months >= 0 && months <= 60) {
        $('#usia').val(months);
    } else {
        alert('Usia harus antara 0-60 bulan');
        $(this).val('');
        $('#usia').val('');
    }
});

// Photo preview
$('#photo').on('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            $(this).val('');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#photo-preview').html(`
                <img src="${e.target.result}" class="img-thumbnail" 
                     style="max-width: 200px; max-height: 200px;">
            `);
        };
        reader.readAsDataURL(file);
    }
});
</script>
```

#### 2. Controller Update (StuntingGrowthController.php)

**Tambahkan di method store():**
```php
public function store(StuntingUserRequest $request)
{
    try {
        $data = $request->validated();
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('uploads/children'), $photoName);
            $data['photo'] = $photoName;
        }
        
        // Calculate age from birth date if provided
        if ($request->tanggal_lahir) {
            $birthDate = new \Carbon\Carbon($request->tanggal_lahir);
            $data['usia'] = $birthDate->diffInMonths(now());
        }
        
        // ... rest of the code
        
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
        
        // ... rest of the code
    }
}
```

#### 3. Result View (result.blade.php)

**Lokasi:** `resources/views/monitoring/growth-detection/stunting/result.blade.php`

**Struktur Baru:**
```php
@extends('layouts.app')

@section('content')
<div class="p-3">
    {{-- Header dengan Foto --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center">
                @if($child->photo)
                    <img src="{{ asset('uploads/children/' . $child->photo) }}" 
                         class="rounded-circle me-3" 
                         style="width: 80px; height: 80px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-secondary me-3 d-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="icofont-baby icofont-3x text-white"></i>
                    </div>
                @endif
                
                <div class="flex-grow-1">
                    <h5 class="mb-1">{{ $child->nama }}</h5>
                    @if($child->medical_id)
                        <small class="text-muted">ID: {{ $child->medical_id }}</small><br>
                    @endif
                    <small class="text-muted">
                        {{ $child->usia }} bulan | 
                        {{ $child->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Utama (Ringkas) --}}
    <div class="card mb-3">
        <div class="card-body text-center">
            <h6 class="text-muted mb-2">Status Pertumbuhan</h6>
            <h3 class="mb-3">
                @php
                    $hazRec = json_decode($child->haz_recommendation, true);
                    $color = $hazRec['color'] ?? 'secondary';
                    $icon = $hazRec['icon'] ?? '📊';
                    $status = $hazRec['status'] ?? $child->status_pertumbuhan;
                @endphp
                <span class="badge bg-{{ $color }} p-3" style="font-size: 1.2rem;">
                    {{ $icon }} {{ $status }}
                </span>
            </h3>
            
            {{-- Z-Score dengan Warna --}}
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <div class="p-2 bg-light rounded">
                        <small class="text-muted d-block">TB/U (Tinggi)</small>
                        <strong>
                            {!! \App\Helpers\NutritionRecommendation::getZScoreBadge($child->haz) !!}
                        </strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-light rounded">
                        <small class="text-muted d-block">BB/U (Berat)</small>
                        <strong>
                            {!! \App\Helpers\NutritionRecommendation::getZScoreBadge($child->whz) !!}
                        </strong>
                    </div>
                </div>
            </div>
            
            {{-- Analisis Singkat --}}
            @if($hazRec && isset($hazRec['warning']) && $hazRec['warning'])
                <div class="alert alert-{{ $color }} mb-0">
                    <strong>{{ $hazRec['warning'] }}</strong>
                </div>
            @endif
        </div>
    </div>

    {{-- Rekomendasi Nutrisi --}}
    @if($hazRec && isset($hazRec['advice']))
        <div class="card mb-3">
            <div class="card-header bg-{{ $color }} text-white">
                <strong>{{ $icon }} Rekomendasi Nutrisi</strong>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach($hazRec['advice'] as $advice)
                        <li class="mb-2">
                            <i class="icofont-check-circled text-success"></i> 
                            {{ $advice }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Tombol Actions --}}
    <div class="row g-2">
        <div class="col-6">
            <a href="{{ locale_route('growth-detection.stunting.result.pdf', encrypt($child->id)) }}" 
               class="btn btn-primary w-100">
                <i class="icofont-file-pdf"></i> Download PDF
            </a>
        </div>
        <div class="col-6">
            <a href="{{ locale_route('growth-detection.stunting') }}" 
               class="btn btn-secondary w-100">
                <i class="icofont-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
```

#### 4. PDF Report dengan Grafik WHO

**Buat Controller Method Baru:**
```php
public function downloadPDF($locale, $id)
{
    $child = StuntingUserModel::findOrFail(decrypt($id));
    $hazRec = json_decode($child->haz_recommendation, true);
    $whzRec = json_decode($child->whz_recommendation, true);
    
    $pdf = PDF::loadView('monitoring.growth-detection.stunting.pdf', [
        'child' => $child,
        'hazRec' => $hazRec,
        'whzRec' => $whzRec,
    ]);
    
    return $pdf->download('laporan-pertumbuhan-' . $child->nama . '.pdf');
}
```

**Template PDF:**
```php
// resources/views/monitoring/growth-detection/stunting/pdf.blade.php
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pertumbuhan Anak</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .badge-danger { background: #dc3545; color: white; padding: 5px 10px; }
        .badge-warning { background: #ffc107; color: black; padding: 5px 10px; }
        .badge-success { background: #28a745; color: white; padding: 5px 10px; }
        .badge-info { background: #17a2b8; color: white; padding: 5px 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .chart { margin: 20px 0; text-align: center; }
        .recommendation { background: #f8f9fa; padding: 15px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PERTUMBUHAN ANAK</h2>
        <p>Sistem Monitoring Kesehatan</p>
    </div>

    {{-- Data Anak --}}
    <table>
        <tr>
            <th width="30%">Nama</th>
            <td>{{ $child->nama }}</td>
        </tr>
        @if($child->medical_id)
        <tr>
            <th>ID Rekam Medis</th>
            <td>{{ $child->medical_id }}</td>
        </tr>
        @endif
        <tr>
            <th>Usia</th>
            <td>{{ $child->usia }} bulan</td>
        </tr>
        <tr>
            <th>Jenis Kelamin</th>
            <td>{{ $child->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <th>Tinggi Badan</th>
            <td>{{ $child->tinggi_badan }} cm</td>
        </tr>
        <tr>
            <th>Berat Badan</th>
            <td>{{ $child->berat_badan }} kg</td>
        </tr>
    </table>

    {{-- Hasil Analisis --}}
    <h3>Hasil Analisis</h3>
    <table>
        <tr>
            <th>Indikator</th>
            <th>Z-Score</th>
            <th>Status</th>
        </tr>
        <tr>
            <td>TB/U (Tinggi menurut Umur)</td>
            <td>{{ number_format($child->haz, 2) }}</td>
            <td>
                <span class="badge-{{ $hazRec['color'] ?? 'secondary' }}">
                    {{ $hazRec['status'] ?? '-' }}
                </span>
            </td>
        </tr>
        <tr>
            <td>BB/U (Berat menurut Umur)</td>
            <td>{{ number_format($child->whz, 2) }}</td>
            <td>
                <span class="badge-{{ $whzRec['color'] ?? 'secondary' }}">
                    {{ $whzRec['status'] ?? '-' }}
                </span>
            </td>
        </tr>
    </table>

    {{-- Grafik WHO (Placeholder - bisa diganti dengan chart library) --}}
    <div class="chart">
        <h3>Posisi Anak pada Kurva Pertumbuhan WHO</h3>
        <p><em>[Grafik akan ditampilkan di sini]</em></p>
        <p>Titik merah menunjukkan posisi anak Anda pada kurva standar WHO</p>
    </div>

    {{-- Rekomendasi --}}
    @if($hazRec && isset($hazRec['advice']))
    <div class="recommendation">
        <h3>{{ $hazRec['icon'] ?? '📋' }} Rekomendasi Nutrisi</h3>
        @if($hazRec['warning'])
            <p><strong>⚠️ {{ $hazRec['warning'] }}</strong></p>
        @endif
        <ul>
            @foreach($hazRec['advice'] as $advice)
                <li>{{ $advice }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Footer --}}
    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        <p>Dicetak pada: {{ date('d F Y, H:i') }}</p>
        <p>Konsultasikan hasil ini dengan tenaga kesehatan profesional</p>
    </div>
</body>
</html>
```

## 📊 Struktur Database Update

### Migration yang Perlu Dijalankan:

```bash
php artisan migrate
```

### Tabel `children` - Kolom Baru:
- `medical_id` VARCHAR(255) NULL UNIQUE
- `photo` VARCHAR(255) NULL

### Tabel `stunting_users` - Kolom Baru (jika perlu):
```php
// Tambahkan di migration baru
$table->text('haz_recommendation')->nullable();
$table->text('whz_recommendation')->nullable();
```

## 🎨 UI/UX Improvements

### Warna Badge Z-Score:
- **Merah (danger):** Z-score < -3 (Darurat)
- **Kuning (warning):** Z-score -3 sampai -2 (Perlu Perhatian)
- **Hijau (success):** Z-score -2 sampai +2 (Normal)
- **Biru (info):** Z-score > +2 (Tinggi/Lebih)

### Icon Emoji:
- 🚨 Darurat
- ⚠️ Peringatan
- ✅ Normal
- ℹ️ Informasi

## 📝 Validation Rules Update

```php
// app/Http/Requests/Growth/StuntingUserRequest.php
public function rules()
{
    return [
        'nama' => 'required|string|max:255',
        'medical_id' => 'nullable|string|max:50|unique:children,medical_id',
        'tanggal_lahir' => 'required|date|before:today',
        'usia' => 'required|integer|min:0|max:60',
        'jenis_kelamin' => 'required|in:L,P',
        'tinggi_badan' => 'required|numeric|min:40|max:130',
        'berat_badan' => 'required|numeric|min:2|max:50',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ];
}
```

## 🚀 Cara Implementasi

### Step 1: Jalankan Migration
```bash
php artisan migrate
```

### Step 2: Update Files
1. Copy `app/Helpers/NutritionRecommendation.php`
2. Update `StuntingGrowthController.php`
3. Update `form.blade.php`
4. Update `result.blade.php`
5. Buat `pdf.blade.php`

### Step 3: Update Routes
```php
// routes/web.php
Route::get('/growth-detection/stunting/result/{id}/pdf', [StuntingGrowthController::class, 'downloadPDF'])
    ->name('growth-detection.stunting.result.pdf');
```

### Step 4: Test
1. Input data anak baru dengan foto
2. Cek hasil dengan warna badge
3. Lihat rekomendasi nutrisi
4. Download PDF

## ✅ Checklist Implementasi

- [x] Helper NutritionRecommendation
- [x] Migration foto & medical_id
- [ ] Update form input
- [ ] Update controller store
- [ ] Update result view
- [ ] Buat PDF template
- [ ] Update routes
- [ ] Testing

## 📞 Support

Jika ada pertanyaan atau masalah, silakan buka issue atau hubungi tim development.

---

**Version:** 2.1  
**Last Updated:** November 13, 2025
