# Dokumentasi Z-Score dan Fitur Utama

## 1. FITUR YANG MENGGUNAKAN ALGORITMA Z-SCORE

### A. Growth Monitoring (Pemantauan Pertumbuhan Anak)
**Lokasi:** `app/Http/Controllers/Monitoring/GrowthMonitoringController.php`

**Fitur Z-Score:**
- **LHFA (Length/Height for Age)** - Line 96-149
  - Menghitung Z-Score tinggi badan menurut umur
  - Menggunakan parameter L, M, S dari WHO
  
- **WFA (Weight for Age)** - Line 151-213
  - Menghitung Z-Score berat badan menurut umur
  - Menggunakan parameter L, M, S dari WHO

**Data yang Digunakan:**
- Model: `ZScoreModel` 
- Tabel: `zscore` (berisi data referensi WHO)
- Parameter: L (Lambda), M (Mu), S (Sigma) per umur dan gender

---

### B. Home Dashboard
**Lokasi:** `app/Http/Controllers/Home/HomeController.php`

**Fitur:**
- Menampilkan grafik Z-Score pertumbuhan anak di dashboard user
- Mengambil data Z-Score dari history growth monitoring
- Line 45-46: Mengambil nilai Z-Score tinggi dan berat badan

---

## 2. MENGAPA HARUS MENGGUNAKAN Z-SCORE?

### Alasan Teknis:

#### A. Standar Internasional WHO
Z-Score adalah **standar resmi WHO** untuk penilaian pertumbuhan anak:
- Digunakan di seluruh dunia
- Berbasis penelitian jutaan anak dari berbagai negara
- Terus diperbarui berdasarkan evidence terbaru

#### B. Normalisasi Data
Z-Score menormalkan data dengan karakteristik berbeda:

```
Formula: Z = (X - M) / SD
Dimana:
- X = Nilai pengukuran (tinggi/berat anak)
- M = Median populasi referensi
- SD = Standar deviasi
```

**Keuntungan:**
- Membandingkan anak dengan populasi standar
- Memperhitungkan variasi umur dan gender
- Hasil dalam satuan yang sama (standar deviasi)

#### C. Deteksi Dini yang Akurat
Z-Score memungkinkan deteksi dini masalah pertumbuhan:
- **Z < -2:** Indikasi masalah (pendek/gizi kurang)
- **Z < -3:** Masalah serius (sangat pendek/gizi buruk)
- **Z > +2:** Risiko obesitas/tinggi berlebih

#### D. Tidak Bisa Diganti Algoritma Lain

**Mengapa tidak Persentil?**
- Persentil kurang sensitif di ujung distribusi
- Z-Score lebih presisi untuk kasus ekstrem
- WHO merekomendasikan Z-Score, bukan persentil

**Mengapa tidak BMI saja?**
- BMI tidak memperhitungkan umur anak
- Tidak cocok untuk anak di bawah 5 tahun
- Z-Score lebih komprehensif (tinggi DAN berat)

**Mengapa tidak Machine Learning?**
- Stunting adalah masalah medis, bukan prediksi
- Butuh standar yang konsisten dan tervalidasi
- ML tidak bisa menggantikan standar klinis WHO

---

## 3. URUTAN CARA KERJA CODE (FITUR UTAMA)

### FITUR 1: GROWTH MONITORING (Pemantauan Pertumbuhan)

#### Alur Kerja Lengkap:

**Step 1: User Input Data**
```
Lokasi: resources/views/monitoring/growth-monitoring/index.blade.php
```

User mengisi form:
- Nama anak
- Umur (dalam bulan)
- Tinggi badan (cm)
- Berat badan (kg)
- Jenis kelamin (L/P)

**Step 2: Submit ke Controller**
```php
// File: app/Http/Controllers/Monitoring/GrowthMonitoringController.php
// Method: store() - Line 76-93

public function store(Request $request)
{
    // 1. Ambil semua data dari form
    $data = $request->all();
    $data['users_id'] = auth()->user()->id;
    $data['login_created'] = auth()->user()->email;

    // 2. Simpan data dasar ke database
    $growth = GrowthMonitoringModel::create($data);
    $growth->save();

    // 3. Hitung Z-Score untuk tinggi badan
    $lhfa = $this->lhfa($request->height, $request->age, $growth->id, $request->gender);
    
    // 4. Hitung Z-Score untuk berat badan
    $wfa = $this->wfa($request->weight, $request->age, $growth->id, $request->gender);

    // 5. Return response
    return response()->json([
        'status' => 'success',
        'message' => 'Hasil Z-Score berhasil disimpan',
        'redirect' => route('growth-monitoring.show', encrypt($growth->id)),
    ]);
}
```

**Step 3: Perhitungan Z-Score LHFA (Tinggi Badan)**
```php
// Method: lhfa() - Line 96-149

public function lhfa($lh, $age, $id, $gender)
{
    // 1. Ambil parameter WHO dari database
    $param = ZScoreModel::where('month', $age)
        ->where('gender', $gender)
        ->where('type', 'LH')
        ->first();
```


    // 2. Hitung Z-Score menggunakan formula WHO LMS
    $zscore = 0;
    
    if ($param->L >= 1) {
        // Formula untuk L >= 1
        $pembilang = $lh - $param->M;
        $penyebut = $param->S * $param->M;
        $zscore = $pembilang / $penyebut;
    } else {
        // Formula untuk L < 1 (Box-Cox transformation)
        $pembilang = pow($lh / $param->M, $param->L);
        $pembilang2 = $pembilang - 1;
        $penyebut = $param->L * $param->S;
        $zscore = $pembilang2 / $penyebut;
    }

    // 3. Tentukan kategori diagnosis berdasarkan Z-Score
    if ($zscore > 3) {
        $hasil_diagnosa = 'Tinggi Badan Sangat Tinggi';
        $deskripsi_diagnosa = 'Anak mengalami status tinggi badan sangat tinggi...';
        $penanganan = 'Konsultasi dengan ahli gizi...';
    } elseif ($zscore > 2 && $zscore <= 3) {
        $hasil_diagnosa = 'Tinggi';
        // ... dst
    } elseif ($zscore >= -2 && $zscore <= 2) {
        $hasil_diagnosa = 'Tinggi Badan Normal';
        // ... dst
    } elseif ($zscore < -2 && $zscore >= -3) {
        $hasil_diagnosa = 'Pendek';
        // ... dst
    } elseif ($zscore < -3) {
        $hasil_diagnosa = 'Sangat Pendek'; // STUNTING
        // ... dst
    }

    // 4. Simpan hasil ke database history
    $data = [
        'id_growth' => $id,
        'type' => 'LH',
        'value' => $lh,
        'zscore' => $zscore,
        'hasil_diagnosa' => $hasil_diagnosa,
        'deskripsi_diagnosa' => $deskripsi_diagnosa,
        'penanganan' => $penanganan,
    ];
    
    GrowthMonitoringHistoryModel::create($data);
}
```


**Step 4: Perhitungan Z-Score WFA (Berat Badan)**
```php
// Method: wfa() - Line 151-213
// Proses sama dengan LHFA, tapi untuk berat badan
// Menggunakan parameter type='W' dari ZScoreModel
```

**Step 5: Tampilkan Hasil**
```
Lokasi: resources/views/monitoring/growth-monitoring/show.blade.php
```
Menampilkan:
- Data anak (nama, umur, tinggi, berat)
- Z-Score tinggi badan
- Z-Score berat badan
- Diagnosis untuk masing-masing
- Rekomendasi penanganan

---

### FITUR 2: PRE-STUNTING RISK ASSESSMENT

#### Alur Kerja Lengkap:

**Step 1: User Input Data Ibu Hamil**
```
Lokasi: resources/views/monitoring/growth-detection/pre-stunting/form.blade.php
```
User mengisi 10 parameter:
1. MUAC (Lingkar lengan atas)
2. Birth Interval (Jarak kelahiran)
3. ANC Visits (Kunjungan ANC)
4. TTD Compliance (Kepatuhan tablet besi)
5. Has Infection (Status infeksi)
6. EFW SGA (Berat janin kecil)
7. Maternal Age (Usia ibu)
8. Weight Gain Trimester 1
9. Weight Gain Trimester 2-3 (berdasarkan BMI)
10. Hemoglobin (berdasarkan trimester)

**Step 2: Feature Engineering & Scoring**
```php
// File: app/Http/Controllers/Monitoring/PreStuntingController.php
// Method: calculateRiskScore() - Line 42-185

public function calculateRiskScore(Request $request)
{
    // 1. Validasi input
    $v = $request->validate([...]);
    
    // 2. Inisialisasi score
    $score = 0;
```


    // 3. Feature Engineering - Ekstrak fitur dari data mentah
    
    // Parameter 1: MUAC
    if (($v['muac'] ?? 999) < 23.5) $score++;
    
    // Parameter 2: Birth Interval
    if (($v['birth_interval'] ?? 999) < 24) $score++;
    
    // Parameter 3: ANC Visits
    if (($v['anc_visits'] ?? 999) < 4) $score++;
    
    // Parameter 4: TTD Compliance
    if (isset($v['ttd_compliance']) && $v['ttd_compliance'] == 0) $score++;
    
    // Parameter 5: Has Infection
    if (!empty($v['has_infection']) && $v['has_infection']) $score++;
    
    // Parameter 6: EFW SGA (bobot 2x karena prediktor terkuat)
    if (!empty($v['efw_sga']) && $v['efw_sga']) $score += 2;
    
    // Parameter 7: Maternal Age
    if (($v['age'] ?? 0) >= 35) $score++;
    
    // Parameter 8: Weight Gain Trimester 1
    if ($trimester == 1) {
        if ($weightGain !== null && $weightGain < 0.5) {
            $score++;
        }
    }
    
    // Parameter 9: Weight Gain Trimester 2-3 (Context-aware)
    elseif (in_array($trimester, [2,3])) {
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
    
    // Parameter 10: Hemoglobin (Context-aware per trimester)
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
```


    // 4. Kategorisasi Risiko berdasarkan Total Score
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

    // 5. Simpan hasil ke database
    $record = PreStunting::create([
        'users_id' => auth()->id(),
        'nama' => $v['nama'] ?? null,
        'usia' => $v['age'] ?? null,
        // ... semua parameter lainnya
        'level_risiko' => $category,
        'risk_score' => $score,
    ]);

    // 6. Redirect ke halaman index dengan pesan sukses
    return redirect()->route('growth-detection.pre-stunting.index', 
        ['locale' => app()->getLocale()])
        ->with('success', 'Data berhasil dihitung dan disimpan');
}
```

**Step 3: Tampilkan Hasil**
```
Lokasi: resources/views/monitoring/growth-detection/pre-stunting/result.blade.php
```
Menampilkan:
- Risk Score (0-11)
- Level Risiko (Rendah/Sedang/Tinggi)
- Rekomendasi tindakan
- Detail semua parameter yang dinilai

---

## PERBANDINGAN: Z-SCORE vs FEATURE ENGINEERING

### Z-Score (Growth Monitoring)
**Karakteristik:**
- Algoritma statistik standar WHO
- Membandingkan dengan populasi referensi
- Output: Nilai Z-Score (-3 sampai +3)
- Diagnosis: Berdasarkan threshold WHO

**Kelebihan:**
- Standar internasional
- Tervalidasi secara klinis
- Konsisten di seluruh dunia


### Feature Engineering (Pre-Stunting)
**Karakteristik:**
- Sistem scoring berbasis evidence medis
- Menggabungkan multiple risk factors
- Output: Risk Score (0-11)
- Diagnosis: Berdasarkan threshold penelitian

**Kelebihan:**
- Deteksi dini sebelum bayi lahir
- Memperhitungkan banyak faktor
- Actionable recommendations

**Mengapa Berbeda?**
- Z-Score: Untuk anak yang sudah lahir (data antropometri)
- Feature Engineering: Untuk ibu hamil (data prenatal)

---

## KESIMPULAN

### 1. Fitur dengan Z-Score:
- ✅ Growth Monitoring (LHFA & WFA)
- ✅ Home Dashboard (Grafik pertumbuhan)

### 2. Mengapa Z-Score?
- ✅ Standar WHO yang wajib digunakan
- ✅ Tidak bisa diganti algoritma lain
- ✅ Tervalidasi secara internasional
- ✅ Akurat untuk deteksi stunting

### 3. Urutan Kerja:
**Growth Monitoring:**
1. Input data anak → 2. Simpan ke DB → 3. Hitung Z-Score LHFA → 
4. Hitung Z-Score WFA → 5. Kategorisasi diagnosis → 6. Simpan history → 
7. Tampilkan hasil

**Pre-Stunting:**
1. Input 10 parameter ibu → 2. Feature engineering (ekstrak fitur) → 
3. Scoring (0-11) → 4. Kategorisasi risiko → 5. Simpan ke DB → 
6. Tampilkan rekomendasi

---

**Catatan Penting:**
- Z-Score adalah REQUIREMENT, bukan pilihan
- WHO mewajibkan penggunaan Z-Score untuk diagnosis stunting
- Feature Engineering digunakan untuk prediksi risiko (berbeda dengan diagnosis)
- Kedua metode saling melengkapi dalam sistem pencegahan stunting

---

**Referensi:**
- WHO Child Growth Standards (2006)
- WHO Anthro Software Documentation
- Kemenkes RI: Pedoman Pencegahan Stunting (2018)
