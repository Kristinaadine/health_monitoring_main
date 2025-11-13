# Rumus Perhitungan dan Class

## 1. BMI (Body Mass Index)

### Rumus:
```
BMI = Berat Badan (kg) / (Tinggi Badan (m))²
```

### Class dan Implementasi:

**File:** `app/Services/BMICalculator.php`

```php
<?php

namespace App\Services;

class BMICalculator
{
    /**
     * Menghitung BMI
     * @param float $berat - Berat badan dalam kg
     * @param float $tinggi - Tinggi badan dalam cm
     * @return float - Nilai BMI (dibulatkan 1 desimal)
     */
    public function hitungBMI(float $berat, float $tinggi): float
    {
        // Konversi tinggi dari cm ke meter, lalu kuadratkan
        // Formula: BMI = berat / (tinggi/100)²
        return round($berat / pow($tinggi / 100, 2), 1);
    }

    /**
     * Menentukan status gizi berdasarkan BMI
     * @param float $bmi - Nilai BMI
     * @return string - Status gizi
     */
    public function statusGizi(float $bmi): string
    {
        if ($bmi < 18.5) return 'Kurus';
        if ($bmi < 25) return 'Normal';
        if ($bmi < 30) return 'Overweight';
        return 'Obesitas';
    }

    /**
     * Memberikan rekomendasi berdasarkan status gizi
     * @param string $status - Status gizi
     * @return string - Rekomendasi
     */
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
```

### Kategori BMI (WHO):
| BMI | Kategori | Keterangan |
|-----|----------|------------|
| < 18.5 | Kurus | Underweight |
| 18.5 - 24.9 | Normal | Healthy weight |
| 25.0 - 29.9 | Overweight | Kelebihan berat badan |
| ≥ 30.0 | Obesitas | Obese |

### Contoh Perhitungan:
```
Berat: 70 kg
Tinggi: 170 cm = 1.7 m

BMI = 70 / (1.7)²
    = 70 / 2.89
    = 24.2

Status: Normal
```

### Penggunaan di Controller:

**File:** `app/Http/Controllers/Monitoring/DietUserController.php`

```php
public function store(DietRequest $request, BMICalculator $bmiCalc)
{
    $data = $request->all();

    // Hitung BMI
    $bmi = $bmiCalc->hitungBMI($data['berat_badan'], $data['tinggi_badan']);
    
    // Tentukan status gizi
    $status = $bmiCalc->statusGizi($bmi);
    
    // Dapatkan rekomendasi
    $rekomendasi = $bmiCalc->rekomendasi($status);

    $data['bmi'] = $bmi;
    $data['user_id'] = auth()->user()->id;
    $data['status_gizi'] = $status;
    $data['rekomendasi'] = $rekomendasi;

    $dietUser = DietUserModel::create($data);

    return redirect()->to(locale_route('growth-detection.diet-user.show', $dietUser->id));
}
```

---

## 2. CALORIES (Kalori Harian)

### Rumus: Harris-Benedict Equation (Revised)

#### Untuk Laki-laki:
```
BMR = 88.362 + (13.397 × berat_kg) + (4.799 × tinggi_cm) - (5.677 × umur_tahun)
```

#### Untuk Perempuan:
```
BMR = 447.593 + (9.247 × berat_kg) + (3.098 × tinggi_cm) - (4.330 × umur_tahun)
```

### Total Daily Energy Expenditure (TDEE):
```
TDEE = BMR × Activity Factor
```

### Activity Factor:
| Level Aktivitas | Faktor | Keterangan |
|----------------|--------|------------|
| Sedentary | 1.2 | Tidak olahraga |
| Light | 1.375 | Olahraga 1-3 hari/minggu |
| Moderate | 1.55 | Olahraga 3-5 hari/minggu |
| Active | 1.725 | Olahraga 6-7 hari/minggu |
| Very Active | 1.9 | Olahraga 2x sehari |

### Class dan Implementasi:

**File:** `app/Http/Controllers/Home/CaloriCalcController.php`

```php
<?php

namespace App\Http\Controllers\Home;

use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CaloriCalcController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();
        return view('home.caloricalc', compact('setting'));
    }
    
    /**
     * Menghitung BMR (Basal Metabolic Rate)
     */
    private function calculateBMR($gender, $weight, $height, $age)
    {
        if ($gender == 'male') {
            // Harris-Benedict untuk laki-laki
            return 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
        } else {
            // Harris-Benedict untuk perempuan
            return 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
        }
    }
    
    /**
     * Menghitung TDEE (Total Daily Energy Expenditure)
     */
    private function calculateTDEE($bmr, $activityLevel)
    {
        $activityFactors = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            'very_active' => 1.9
        ];
        
        return $bmr * $activityFactors[$activityLevel];
    }
}
```

### Contoh Perhitungan:
```
Gender: Laki-laki
Berat: 70 kg
Tinggi: 170 cm
Umur: 25 tahun
Aktivitas: Moderate (1.55)

BMR = 88.362 + (13.397 × 70) + (4.799 × 170) - (5.677 × 25)
    = 88.362 + 937.79 + 815.83 - 141.925
    = 1700.057 kalori

TDEE = 1700.057 × 1.55
     = 2635.09 kalori/hari
```

---

## 3. NUTRIENT RATIO (Rasio Makronutrien)

### Rumus:

#### Kalori per Gram:
```
Protein: 1 gram = 4 kalori
Karbohidrat: 1 gram = 4 kalori
Lemak: 1 gram = 9 kalori
```

#### Perhitungan Gram dari Persentase:
```
Gram Protein = (Target Kalori × % Protein) / 4
Gram Karbohidrat = (Target Kalori × % Karbohidrat) / 4
Gram Lemak = (Target Kalori × % Lemak) / 9
```

### Class dan Implementasi:

**Model:** `app/Models/NutrientRatioModel.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NutrientRatioModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nutrient_ratio';
    protected $guarded = ['id'];
    
    /**
     * Menghitung gram nutrisi berdasarkan target kalori
     * @param int $targetCalories - Target kalori harian
     * @return array - Array berisi gram protein, carbs, fat
     */
    public function calculateGrams(int $targetCalories): array
    {
        return [
            'protein_grams' => round(($targetCalories * $this->protein / 100) / 4, 1),
            'carbs_grams' => round(($targetCalories * $this->carbs / 100) / 4, 1),
            'fat_grams' => round(($targetCalories * $this->fat / 100) / 9, 1),
        ];
    }
}
```

### Preset Nutrient Ratios:

**File:** `database/seeders/NutrientRatioSeeder.php`

```php
$data = [
    [
        'name' => 'Low Carb',
        'protein' => 40,  // 40%
        'carbs' => 20,    // 20%
        'fat' => 40,      // 40%
    ],
    [
        'name' => 'Balanced',
        'protein' => 30,  // 30%
        'carbs' => 40,    // 40%
        'fat' => 30,      // 30%
    ],
    [
        'name' => 'High Protein',
        'protein' => 40,  // 40%
        'carbs' => 40,    // 40%
        'fat' => 20,      // 20%
    ],
];
```

### Contoh Perhitungan:
```
Target Kalori: 2000 kalori/hari
Rasio: Balanced (30% Protein, 40% Carbs, 30% Fat)

Protein:
- Kalori dari protein = 2000 × 30% = 600 kalori
- Gram protein = 600 / 4 = 150 gram

Karbohidrat:
- Kalori dari carbs = 2000 × 40% = 800 kalori
- Gram carbs = 800 / 4 = 200 gram

Lemak:
- Kalori dari fat = 2000 × 30% = 600 kalori
- Gram fat = 600 / 9 = 66.7 gram

Total: 150g protein + 200g carbs + 66.7g fat = 2000 kalori
```

---

## 4. GROWTH MONITORING (Z-Score WHO)

### Rumus: LMS Method (Lambda-Mu-Sigma)

#### Formula Dasar:
```
Jika L ≥ 1:
    Z-Score = (X - M) / (S × M)

Jika L < 1:
    Z-Score = [(X/M)^L - 1] / (L × S)

Dimana:
- X = Nilai pengukuran (tinggi/berat anak)
- L = Lambda (Box-Cox transformation parameter)
- M = Mu (Median populasi referensi)
- S = Sigma (Coefficient of variation)
```

### Class dan Implementasi:

**File:** `app/Http/Controllers/Monitoring/GrowthMonitoringController.php`

```php
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
     * Menyimpan data growth monitoring
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['users_id'] = auth()->user()->id;
        $data['login_created'] = auth()->user()->email;

        $growth = GrowthMonitoringModel::create($data);
        $growth->save();

        // Hitung Z-Score untuk tinggi badan
        $lhfa = $this->lhfa($request->height, $request->age, $growth->id, $request->gender);
        
        // Hitung Z-Score untuk berat badan
        $wfa = $this->wfa($request->weight, $request->age, $growth->id, $request->gender);

        return response()->json([
            'status' => 'success',
            'message' => 'Hasil Z-Score berhasil disimpan',
            'redirect' => route('growth-monitoring.show', encrypt($growth->id)),
        ]);
    }

    /**
     * Menghitung Z-Score LHFA (Length/Height for Age)
     * @param float $lh - Tinggi badan dalam cm
     * @param int $age - Umur dalam bulan
     * @param int $id - ID growth monitoring
     * @param string $gender - Jenis kelamin (L/P)
     */
    public function lhfa($lh, $age, $id, $gender)
    {
        // 1. Ambil parameter WHO dari database
        $param = ZScoreModel::where('month', $age)
            ->where('gender', $gender)
            ->where('type', 'LH')
            ->first();

        $zscore = 0;
        $hasil_diagnosa = '';
        $deskripsi_diagnosa = '';
        $penanganan = '';

        // 2. Hitung Z-Score menggunakan formula LMS
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

        // 3. Kategorisasi berdasarkan Z-Score
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

        // 4. Simpan hasil ke database
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
    }

    /**
     * Menghitung Z-Score WFA (Weight for Age)
     * @param float $w - Berat badan dalam kg
     * @param int $age - Umur dalam bulan
     * @param int $id - ID growth monitoring
     * @param string $gender - Jenis kelamin (L/P)
     */
    public function wfa($w, $age, $id, $gender)
    {
        // Proses sama dengan LHFA, tapi untuk berat badan
        $param = ZScoreModel::where('month', $age)
            ->where('gender', $gender)
            ->where('type', 'W')
            ->first();
            
        $zscore = 0;
        $hasil_diagnosa = '';
        $deskripsi_diagnosa = '';
        $penanganan = '';

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

        // Kategorisasi untuk berat badan
        if ($zscore > 3) {
            $hasil_diagnosa = 'Obesitas';
            // ... dst
        } elseif ($zscore > 2 && $zscore <= 3) {
            $hasil_diagnosa = 'Gizi Lebih';
            // ... dst
        } elseif ($zscore > 1 && $zscore <= 2) {
            $hasil_diagnosa = 'Risiko Gizi Lebih';
            // ... dst
        } elseif ($zscore >= -2 && $zscore <= 1) {
            $hasil_diagnosa = 'Gizi Normal';
            // ... dst
        } elseif ($zscore < -2 && $zscore >= -3) {
            $hasil_diagnosa = 'Gizi Kurang';
            // ... dst
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
    }
}
```

### Model Z-Score:

**File:** `app/Models/ZScoreModel.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZScoreModel extends Model
{
    use HasFactory;

    protected $table = 'zscore';
    protected $guarded = ['id'];
    
    /**
     * Struktur tabel zscore:
     * - id: Primary key
     * - month: Umur dalam bulan (0-60)
     * - gender: Jenis kelamin (L/P)
     * - type: Tipe pengukuran (LH/W)
     * - L: Lambda (Box-Cox parameter)
     * - M: Mu (Median)
     * - S: Sigma (Coefficient of variation)
     */
}
```

### Threshold Z-Score WHO:

#### LHFA (Tinggi Badan):
| Z-Score | Kategori | Status |
|---------|----------|--------|
| > +3 | Sangat Tinggi | Perlu monitoring |
| +2 to +3 | Tinggi | Perlu perhatian |
| -2 to +2 | Normal | Sehat |
| -3 to -2 | Pendek | Stunting |
| < -3 | Sangat Pendek | Severe Stunting |

#### WFA (Berat Badan):
| Z-Score | Kategori | Status |
|---------|----------|--------|
| > +3 | Obesitas | Perlu intervensi |
| +2 to +3 | Gizi Lebih | Perlu perhatian |
| +1 to +2 | Risiko Gizi Lebih | Monitoring |
| -2 to +1 | Normal | Sehat |
| -3 to -2 | Gizi Kurang | Malnutrisi |

### Contoh Perhitungan Z-Score:

```
Data Anak:
- Umur: 24 bulan
- Tinggi: 85 cm
- Berat: 12 kg
- Gender: Laki-laki (L)

Parameter WHO dari database (contoh):
LHFA (Tinggi):
- L = 1.0
- M = 86.5 (median tinggi untuk 24 bulan)
- S = 0.04 (coefficient of variation)

Perhitungan:
Karena L >= 1, gunakan formula:
Z-Score = (X - M) / (S × M)
        = (85 - 86.5) / (0.04 × 86.5)
        = -1.5 / 3.46
        = -0.43

Hasil: Z-Score = -0.43
Kategori: Normal (karena -2 < Z < +2)
```

---

## RINGKASAN CLASS DAN FILE

### 1. BMI:
- **Class:** `BMICalculator`
- **File:** `app/Services/BMICalculator.php`
- **Method:** `hitungBMI()`, `statusGizi()`, `rekomendasi()`

### 2. Calories:
- **Class:** `CaloriCalcController`
- **File:** `app/Http/Controllers/Home/CaloriCalcController.php`
- **Method:** `calculateBMR()`, `calculateTDEE()`

### 3. Nutrient Ratio:
- **Class:** `NutrientRatioModel`
- **File:** `app/Models/NutrientRatioModel.php`
- **Method:** `calculateGrams()`

### 4. Growth Monitoring:
- **Class:** `GrowthMonitoringController`
- **File:** `app/Http/Controllers/Monitoring/GrowthMonitoringController.php`
- **Method:** `lhfa()`, `wfa()`
- **Model:** `ZScoreModel` (`app/Models/ZScoreModel.php`)

---

**Referensi:**
- WHO Child Growth Standards (2006)
- Harris-Benedict Equation (Revised 1984)
- Institute of Medicine: Dietary Reference Intakes (2005)
