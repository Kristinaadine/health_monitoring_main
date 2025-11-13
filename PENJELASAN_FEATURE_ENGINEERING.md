# Pre-Stunting Risk Assessment: 10 Parameter Feature Engineering

## Penjelasan Lengkap Sistem Penilaian Risiko Stunting pada Ibu Hamil

---

## KONSEP DASAR

**Feature Engineering** adalah proses mengekstrak, mentransformasi, dan membuat fitur-fitur baru dari data mentah untuk meningkatkan performa prediksi sistem. Dalam konteks Pre-Stunting Risk Assessment, sistem mengambil 10 parameter kesehatan ibu hamil dan mengubahnya menjadi **Risk Score** yang dapat memprediksi risiko stunting pada bayi.

**Lokasi Implementasi:**
- **File:** `app/Http/Controllers/Monitoring/PreStuntingController.php`
- **Method:** `calculateRiskScore()` (Line 42-185)

---

## 10 PARAMETER FEATURE ENGINEERING

### **Parameter 1: MUAC (Mid-Upper Arm Circumference)**
**Lingkar Lengan Atas**

#### Data Mentah:
- Input: Ukuran lingkar lengan atas ibu dalam cm (contoh: 22.5 cm)

#### Feature Engineering:
```php
if (($v['muac'] ?? 999) < 23.5) $score++;
```

#### Transformasi:
| MUAC (cm) | Kondisi | Score | Interpretasi |
|-----------|---------|-------|--------------|
| < 23.5 | KEK (Kurang Energi Kronis) | +1 | Risiko tinggi |
| ≥ 23.5 | Normal | 0 | Tidak ada risiko |

#### Penjelasan Medis:
- MUAC < 23.5 cm menunjukkan ibu mengalami KEK (Kurang Energi Kronis)
- KEK pada ibu hamil meningkatkan risiko bayi lahir dengan berat badan rendah
- Berat badan lahir rendah adalah faktor risiko utama stunting

#### Contoh:
```
Input: MUAC = 22.0 cm
Proses: 22.0 < 23.5 → TRUE
Output: Score +1 (Risiko KEK)
```

---

### **Parameter 2: Birth Interval (Jarak Kelahiran)**
**Jarak antara Kelahiran Sebelumnya**

#### Data Mentah:
- Input: Jarak kelahiran dalam bulan (contoh: 18 bulan)

#### Feature Engineering:
```php
if (($v['birth_interval'] ?? 999) < 24) $score++;
```

#### Transformasi:
| Jarak Kelahiran | Kondisi | Score | Interpretasi |
|-----------------|---------|-------|--------------|
| < 24 bulan | Terlalu dekat | +1 | Risiko tinggi |
| ≥ 24 bulan | Ideal | 0 | Tidak ada risiko |

#### Penjelasan Medis:
- Jarak kelahiran < 24 bulan tidak memberikan waktu cukup bagi tubuh ibu untuk pulih
- Cadangan nutrisi ibu belum optimal untuk kehamilan berikutnya
- Meningkatkan risiko bayi lahir dengan status gizi kurang

#### Contoh:
```
Input: Birth Interval = 18 bulan
Proses: 18 < 24 → TRUE
Output: Score +1 (Jarak terlalu dekat)
```

---

### **Parameter 3: ANC Visits (Kunjungan Antenatal Care)**
**Frekuensi Pemeriksaan Kehamilan**

#### Data Mentah:
- Input: Jumlah kunjungan ANC (contoh: 3 kali)

#### Feature Engineering:
```php
if (($v['anc_visits'] ?? 999) < 4) $score++;
```

#### Transformasi:
| Jumlah ANC | Kondisi | Score | Interpretasi |
|------------|---------|-------|--------------|
| < 4 kali | Kurang | +1 | Risiko tinggi |
| ≥ 4 kali | Sesuai standar | 0 | Tidak ada risiko |

#### Penjelasan Medis:
- WHO merekomendasikan minimal 4 kunjungan ANC (sekarang 8 kunjungan)
- ANC yang kurang menyebabkan masalah kesehatan tidak terdeteksi dini
- Monitoring pertumbuhan janin dan kesehatan ibu tidak optimal

#### Contoh:
```
Input: ANC Visits = 2 kali
Proses: 2 < 4 → TRUE
Output: Score +1 (ANC tidak memadai)
```

---

### **Parameter 4: TTD Compliance (Tablet Tambah Darah)**
**Kepatuhan Konsumsi Suplemen Zat Besi**

#### Data Mentah:
- Input: Boolean (0 = tidak patuh, 1 = patuh)

#### Feature Engineering:
```php
if (isset($v['ttd_compliance']) && $v['ttd_compliance'] == 0) $score++;
```

#### Transformasi:
| TTD Compliance | Kondisi | Score | Interpretasi |
|----------------|---------|-------|--------------|
| 0 (Tidak) | Tidak patuh | +1 | Risiko anemia |
| 1 (Ya) | Patuh | 0 | Tidak ada risiko |

#### Penjelasan Medis:
- TTD mencegah anemia pada ibu hamil
- Anemia mengurangi suplai oksigen ke janin
- Menghambat pertumbuhan dan perkembangan janin

#### Contoh:
```
Input: TTD Compliance = 0 (tidak patuh)
Proses: 0 == 0 → TRUE
Output: Score +1 (Risiko anemia)
```

---

### **Parameter 5: Has Infection (Status Infeksi)**
**Riwayat Infeksi Selama Kehamilan**

#### Data Mentah:
- Input: Boolean (0 = tidak ada, 1 = ada infeksi)

#### Feature Engineering:
```php
if (!empty($v['has_infection']) && $v['has_infection']) $score++;
```

#### Transformasi:
| Has Infection | Kondisi | Score | Interpretasi |
|---------------|---------|-------|--------------|
| 1 (Ya) | Ada infeksi | +1 | Risiko tinggi |
| 0 (Tidak) | Tidak ada | 0 | Tidak ada risiko |

#### Penjelasan Medis:
- Infeksi (TORCH, ISK, malaria, dll) mengganggu pertumbuhan janin
- Meningkatkan risiko kelahiran prematur
- Dapat menyebabkan IUGR (Intrauterine Growth Restriction)

#### Contoh:
```
Input: Has Infection = 1 (ada ISK)
Proses: 1 == TRUE → TRUE
Output: Score +1 (Risiko infeksi)
```

---

### **Parameter 6: EFW SGA (Estimated Fetal Weight - Small for Gestational Age)**
**Perkiraan Berat Janin Kecil untuk Usia Kehamilan**

#### Data Mentah:
- Input: Boolean (0 = normal, 1 = SGA)

#### Feature Engineering:
```php
if (!empty($v['efw_sga']) && $v['efw_sga']) $score += 2;
```

#### Transformasi:
| EFW SGA | Kondisi | Score | Interpretasi |
|---------|---------|-------|--------------|
| 1 (Ya) | Janin kecil | +2 | **Risiko sangat tinggi** |
| 0 (Tidak) | Normal | 0 | Tidak ada risiko |

#### Penjelasan Medis:
- SGA adalah indikator kuat IUGR (pertumbuhan janin terhambat)
- Bayi SGA memiliki risiko sangat tinggi stunting
- **Diberi bobot 2x** karena merupakan prediktor terkuat

#### Contoh:
```
Input: EFW SGA = 1 (janin kecil)
Proses: 1 == TRUE → TRUE
Output: Score +2 (Risiko sangat tinggi - bobot ganda)
```

---

### **Parameter 7: Maternal Age (Usia Ibu)**
**Usia Ibu Saat Hamil**

#### Data Mentah:
- Input: Usia dalam tahun (contoh: 37 tahun)

#### Feature Engineering:
```php
if (($v['age'] ?? 0) >= 35) $score++;
```

#### Transformasi:
| Usia Ibu | Kondisi | Score | Interpretasi |
|----------|---------|-------|--------------|
| ≥ 35 tahun | Usia lanjut | +1 | Risiko tinggi |
| < 35 tahun | Usia ideal | 0 | Tidak ada risiko |

#### Penjelasan Medis:
- Kehamilan ≥35 tahun meningkatkan risiko komplikasi
- Kualitas sel telur menurun seiring usia
- Risiko hipertensi dan diabetes gestasional meningkat

#### Contoh:
```
Input: Age = 37 tahun
Proses: 37 >= 35 → TRUE
Output: Score +1 (Usia berisiko)
```

---

### **Parameter 8: Weight Gain - Trimester 1**
**Kenaikan Berat Badan Trimester Pertama**

#### Data Mentah:
- Input: Kenaikan BB dalam kg (contoh: 0.3 kg)

#### Feature Engineering:
```php
if ($trimester == 1) {
    if ($weightGain !== null && $weightGain < 0.5) {
        $score++;
    }
}
```

#### Transformasi:
| Kenaikan BB (Tri 1) | Kondisi | Score | Interpretasi |
|---------------------|---------|-------|--------------|
| < 0.5 kg | Kurang | +1 | Risiko malnutrisi |
| ≥ 0.5 kg | Normal | 0 | Tidak ada risiko |

#### Penjelasan Medis:
- Trimester 1 adalah periode kritis pembentukan organ janin
- Kenaikan BB < 0.5 kg menunjukkan asupan nutrisi tidak adekuat
- Dapat menghambat perkembangan janin

#### Contoh:
```
Input: Trimester = 1, Weight Gain = 0.3 kg
Proses: Trimester == 1 AND 0.3 < 0.5 → TRUE
Output: Score +1 (Kenaikan BB kurang)
```

---

### **Parameter 9: Weight Gain - Trimester 2-3 (BMI-based)**
**Kenaikan Berat Badan Trimester 2-3 Berdasarkan BMI**

#### Data Mentah:
- Input: 
  - Kenaikan BB per minggu dalam kg (contoh: 0.25 kg/minggu)
  - BMI pra-kehamilan (contoh: 17.5)

#### Feature Engineering:
```php
if (in_array($trimester, [2,3])) {
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
```

#### Transformasi (Multi-level):

**Untuk BMI < 18.5 (Underweight):**
| Kenaikan BB/minggu | Kondisi | Score | Interpretasi |
|--------------------|---------|-------|--------------|
| < 0.5 kg | Kurang | +1 | Risiko tinggi |
| ≥ 0.5 kg | Cukup | 0 | Tidak ada risiko |

**Untuk BMI 18.5-24.9 (Normal):**
| Kenaikan BB/minggu | Kondisi | Score | Interpretasi |
|--------------------|---------|-------|--------------|
| < 0.35 kg | Kurang | +1 | Risiko sedang |
| ≥ 0.35 kg | Cukup | 0 | Tidak ada risiko |

**Untuk BMI ≥ 25 (Overweight/Obese):**
| Kenaikan BB/minggu | Kondisi | Score | Interpretasi |
|--------------------|---------|-------|--------------|
| < 0.3 kg | Kurang | +1 | Risiko sedang |
| ≥ 0.3 kg | Cukup | 0 | Tidak ada risiko |

#### Penjelasan Medis:
- Rekomendasi kenaikan BB berbeda berdasarkan BMI awal
- Ibu underweight butuh kenaikan BB lebih banyak
- Ibu overweight butuh kenaikan BB lebih sedikit
- Kenaikan BB tidak sesuai meningkatkan risiko komplikasi

#### Contoh:
```
Input: 
- Trimester = 2
- BMI = 17.5 (underweight)
- Weight Gain = 0.4 kg/minggu

Proses: 
- Trimester in [2,3] → TRUE
- BMI < 18.5 → TRUE
- 0.4 < 0.5 → TRUE

Output: Score +1 (Kenaikan BB tidak cukup untuk underweight)
```

---

### **Parameter 10: Hemoglobin (Hb) - Trimester-based**
**Kadar Hemoglobin Berdasarkan Trimester**

#### Data Mentah:
- Input:
  - Kadar Hb dalam g/dL (contoh: 10.2 g/dL)
  - Trimester (1, 2, atau 3)

#### Feature Engineering:
```php
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

#### Transformasi (Trimester-specific):

**Trimester 1:**
| Hb (g/dL) | Kondisi | Score | Interpretasi |
|-----------|---------|-------|--------------|
| < 11.0 | Anemia | +1 | Risiko tinggi |
| ≥ 11.0 | Normal | 0 | Tidak ada risiko |

**Trimester 2:**
| Hb (g/dL) | Kondisi | Score | Interpretasi |
|-----------|---------|-------|--------------|
| < 10.5 | Anemia | +1 | Risiko tinggi |
| ≥ 10.5 | Normal | 0 | Tidak ada risiko |

**Trimester 3:**
| Hb (g/dL) | Kondisi | Score | Interpretasi |
|-----------|---------|-------|--------------|
| < 11.0 | Anemia | +1 | Risiko tinggi |
| ≥ 11.0 | Normal | 0 | Tidak ada risiko |

#### Penjelasan Medis:
- Threshold Hb berbeda per trimester karena hemodilusi fisiologis
- Trimester 2: volume darah meningkat, Hb turun (normal)
- Anemia mengurangi suplai oksigen ke janin
- Menghambat pertumbuhan dan perkembangan otak janin

#### Contoh:
```
Input: 
- Trimester = 2
- Hb = 10.2 g/dL

Proses: 
- Trimester == 2 → TRUE
- 10.2 < 10.5 → TRUE

Output: Score +1 (Anemia trimester 2)
```

---

## AGREGASI RISK SCORE

Setelah semua 10 parameter dievaluasi, sistem menjumlahkan score:

```php
// Total score bisa berkisar 0-11
// (9 parameter × 1 point) + (1 parameter × 2 points)
$totalScore = 0;

// Parameter 1-5, 7-10: masing-masing +1
// Parameter 6 (EFW SGA): +2

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
```

### Kategorisasi Risiko:

| Total Score | Kategori | Rekomendasi | Tindakan |
|-------------|----------|-------------|----------|
| 0-1 | **Risiko Rendah** | Edukasi gizi, pemantauan rutin | Monitoring standar |
| 2-3 | **Risiko Sedang** | Konseling gizi intensif, tambah frekuensi ANC | Intervensi preventif |
| ≥4 | **Risiko Tinggi** | Rujukan gizi/obgin, intervensi intensif | Intervensi kuratif |

---

## CONTOH KASUS LENGKAP

### **Kasus 1: Ibu dengan Risiko Tinggi**

**Data Input:**
```
Nama: Ibu A
Usia: 37 tahun
MUAC: 21.5 cm
BMI pra-hamil: 17.2
Trimester: 2
Kenaikan BB: 0.3 kg/minggu
Jarak kelahiran: 15 bulan
ANC visits: 2 kali
Hb: 9.8 g/dL
TTD compliance: Tidak (0)
Has infection: Ya (1)
EFW SGA: Ya (1)
```

**Proses Feature Engineering:**

| Parameter | Nilai | Kondisi | Score |
|-----------|-------|---------|-------|
| 1. MUAC | 21.5 cm | < 23.5 | +1 |
| 2. Birth Interval | 15 bulan | < 24 | +1 |
| 3. ANC Visits | 2 kali | < 4 | +1 |
| 4. TTD Compliance | 0 | == 0 | +1 |
| 5. Has Infection | 1 | == 1 | +1 |
| 6. EFW SGA | 1 | == 1 | **+2** |
| 7. Age | 37 tahun | ≥ 35 | +1 |
| 8. Weight Gain Tri 1 | - | N/A | 0 |
| 9. Weight Gain Tri 2 | 0.3 kg/minggu | BMI<18.5 & <0.5 | +1 |
| 10. Hb Trimester 2 | 9.8 g/dL | < 10.5 | +1 |

**Total Score: 10**

**Hasil:**
- **Kategori:** Risiko Tinggi (score ≥4)
- **Rekomendasi:** Rujukan ke ahli gizi dan obgin, intervensi intensif (PMT KEK, tata laksana anemia dan infeksi)

---

### **Kasus 2: Ibu dengan Risiko Rendah**

**Data Input:**
```
Nama: Ibu B
Usia: 28 tahun
MUAC: 26.0 cm
BMI pra-hamil: 22.5
Trimester: 2
Kenaikan BB: 0.4 kg/minggu
Jarak kelahiran: 36 bulan
ANC visits: 6 kali
Hb: 11.5 g/dL
TTD compliance: Ya (1)
Has infection: Tidak (0)
EFW SGA: Tidak (0)
```

**Proses Feature Engineering:**

| Parameter | Nilai | Kondisi | Score |
|-----------|-------|---------|-------|
| 1. MUAC | 26.0 cm | ≥ 23.5 | 0 |
| 2. Birth Interval | 36 bulan | ≥ 24 | 0 |
| 3. ANC Visits | 6 kali | ≥ 4 | 0 |
| 4. TTD Compliance | 1 | == 1 | 0 |
| 5. Has Infection | 0 | == 0 | 0 |
| 6. EFW SGA | 0 | == 0 | 0 |
| 7. Age | 28 tahun | < 35 | 0 |
| 8. Weight Gain Tri 1 | - | N/A | 0 |
| 9. Weight Gain Tri 2 | 0.4 kg/minggu | BMI normal & ≥0.35 | 0 |
| 10. Hb Trimester 2 | 11.5 g/dL | ≥ 10.5 | 0 |

**Total Score: 0**

**Hasil:**
- **Kategori:** Risiko Rendah (score ≤1)
- **Rekomendasi:** Edukasi gizi, pemantauan rutin

---

## KEUNGGULAN FEATURE ENGINEERING INI

### 1. **Multi-dimensional Assessment**
- Tidak hanya menilai 1 aspek, tapi 10 aspek kesehatan ibu
- Memberikan gambaran komprehensif risiko stunting

### 2. **Evidence-based Thresholds**
- Setiap threshold berdasarkan standar medis (WHO, Kemenkes)
- Parameter 6 (EFW SGA) diberi bobot 2x karena prediktor terkuat

### 3. **Context-aware Features**
- Parameter 9: Kenaikan BB disesuaikan dengan BMI awal
- Parameter 10: Threshold Hb disesuaikan dengan trimester

### 4. **Actionable Output**
- Tidak hanya memberikan score, tapi juga rekomendasi tindakan
- Membantu tenaga kesehatan membuat keputusan klinis

### 5. **Scalable & Maintainable**
- Mudah menambah parameter baru
- Mudah mengubah threshold berdasarkan evidence terbaru

---

## VALIDASI KLINIS

### Sensitivitas & Spesifisitas (Teoritis)

Berdasarkan literatur medis, sistem dengan 10 parameter ini diharapkan memiliki:

- **Sensitivitas:** 75-85% (kemampuan mendeteksi ibu berisiko tinggi)
- **Spesifisitas:** 70-80% (kemampuan mengidentifikasi ibu tidak berisiko)
- **Positive Predictive Value:** 60-70%
- **Negative Predictive Value:** 85-90%

### Limitasi

1. **Tidak menggantikan penilaian klinis:** Sistem ini adalah alat bantu, bukan pengganti dokter
2. **Memerlukan data akurat:** Kualitas output bergantung pada kualitas input
3. **Belum tervalidasi eksternal:** Perlu validasi dengan data real-world

---

## KESIMPULAN

**Pre-Stunting Risk Assessment dengan 10 Parameter Feature Engineering** adalah sistem cerdas yang:

1. ✅ Mengubah data mentah menjadi informasi prediktif
2. ✅ Menggunakan threshold berbasis evidence medis
3. ✅ Memberikan scoring yang objektif dan terukur
4. ✅ Menghasilkan rekomendasi tindakan yang actionable
5. ✅ Membantu deteksi dini risiko stunting pada ibu hamil

Sistem ini merupakan implementasi **Expert System** yang mengkodekan pengetahuan medis ke dalam algoritma komputer untuk membantu tenaga kesehatan dalam pengambilan keputusan klinis.

---

**Referensi Medis:**
- WHO Recommendations on Antenatal Care for a Positive Pregnancy Experience (2016)
- Kemenkes RI: Pedoman Pencegahan dan Penanggulangan Anemia pada Ibu Hamil (2018)
- WHO: Maternal Anthropometry and Pregnancy Outcomes (1995)
- Institute of Medicine: Weight Gain During Pregnancy (2009)
