# 📊 DOKUMENTASI FITUR NUTRITION MONITORING
## Pemantauan dan Perkembangan Gizi & Kesehatan

---

## 🎯 TUJUAN FITUR

Fitur **Nutrition Monitoring** dirancang untuk:
1. **Memantau pertumbuhan fisik anak** (tinggi & berat badan)
2. **Melacak asupan nutrisi harian** (kalori, protein, karbohidrat, lemak)
3. **Memberikan alert otomatis** jika ada masalah gizi
4. **Visualisasi progress** dengan grafik interaktif

---

## 📋 KOMPONEN UTAMA

### 1. **Data Anak (Children)**
- Nama, usia, jenis kelamin
- Target nutrisi harian
- Riwayat pertumbuhan

### 2. **Log Pertumbuhan (Growth Logs)**
- Pencatatan tinggi & berat badan berkala
- Tracking perubahan dari waktu ke waktu

### 3. **Log Makanan (Food Logs)**
- Pencatatan makanan yang dikonsumsi
- Kalkulasi nutrisi otomatis

### 4. **Target Nutrisi (Nutrition Targets)**
- Target kalori, protein, karbo, lemak harian
- Disesuaikan dengan usia & kebutuhan anak

### 5. **Alert System**
- Notifikasi otomatis jika:
  - Asupan nutrisi kurang dari target
  - Pertumbuhan tidak sesuai standar
  - Ada pola makan yang tidak sehat

---

## 👶 CONTOH KASUS: ANAK "A"

### **Profil Anak A**
```
Nama          : A
Usia          : 36 bulan (3 tahun)
Jenis Kelamin : Perempuan
Berat Badan   : 13.5 kg
Tinggi Badan  : 92 cm
```

### **Target Nutrisi Harian Anak A**
Berdasarkan usia 3 tahun:
```
Kalori    : 1,300 kcal/hari
Protein   : 20 gram/hari
Karbohidrat: 180 gram/hari
Lemak     : 45 gram/hari
```

---

## 📊 ANALISIS HASIL - CONTOH DATA ANAK A

### **1. GRAFIK PERTUMBUHAN (7 Hari Terakhir)**

#### Data Berat Badan:
```
Tanggal       | Berat (kg)
--------------|------------
06 Nov 2025   | 13.2
07 Nov 2025   | 13.3
08 Nov 2025   | 13.3
09 Nov 2025   | 13.4
10 Nov 2025   | 13.4
11 Nov 2025   | 13.5
12 Nov 2025   | 13.5
```

**📈 Analisis:**
- ✅ **Tren Positif**: Berat badan naik 0.3 kg dalam 7 hari
- ✅ **Pertumbuhan Stabil**: Kenaikan bertahap dan konsisten
- ✅ **Status**: Normal untuk usia 3 tahun

#### Data Tinggi Badan:
```
Tanggal       | Tinggi (cm)
--------------|------------
06 Nov 2025   | 91.5
09 Nov 2025   | 91.8
12 Nov 2025   | 92.0
```

**📈 Analisis:**
- ✅ **Tren Positif**: Tinggi bertambah 0.5 cm dalam 7 hari
- ✅ **Pertumbuhan Normal**: Sesuai standar WHO
- ✅ **Status**: Tidak ada indikasi stunting

---

### **2. GRAFIK NUTRISI HARIAN (7 Hari Terakhir)**

#### Asupan Kalori:
```
Tanggal       | Kalori (kcal) | Target | Status
--------------|---------------|--------|--------
06 Nov 2025   | 1,250         | 1,300  | 96% ⚠️
07 Nov 2025   | 1,320         | 1,300  | 102% ✅
08 Nov 2025   | 1,180         | 1,300  | 91% ⚠️
09 Nov 2025   | 1,350         | 1,300  | 104% ✅
10 Nov 2025   | 1,290         | 1,300  | 99% ✅
11 Nov 2025   | 1,310         | 1,300  | 101% ✅
12 Nov 2025   | 1,280         | 1,300  | 98% ✅
```

**📊 Analisis:**
- ✅ **Rata-rata**: 1,283 kcal/hari (98.7% dari target)
- ⚠️ **Perhatian**: 2 hari di bawah 95% target
- 💡 **Rekomendasi**: Tambahkan snack sehat di sore hari

#### Asupan Protein:
```
Tanggal       | Protein (g) | Target | Status
--------------|-------------|--------|--------
06 Nov 2025   | 18          | 20     | 90% ⚠️
07 Nov 2025   | 22          | 20     | 110% ✅
08 Nov 2025   | 17          | 20     | 85% 🚨
09 Nov 2025   | 21          | 20     | 105% ✅
10 Nov 2025   | 19          | 20     | 95% ✅
11 Nov 2025   | 20          | 20     | 100% ✅
12 Nov 2025   | 18          | 20     | 90% ⚠️
```

**📊 Analisis:**
- ⚠️ **Rata-rata**: 19.3 gram/hari (96.5% dari target)
- 🚨 **Alert**: 1 hari di bawah 85% (08 Nov)
- 💡 **Rekomendasi**: 
  - Tambahkan telur rebus untuk sarapan
  - Berikan susu tinggi protein 2x sehari
  - Snack: kacang-kacangan atau yogurt

---

### **3. PROGRESS NUTRISI HARI INI (12 Nov 2025)**

#### Makanan yang Dikonsumsi:
```
Waktu    | Makanan                    | Kalori | Protein | Karbo | Lemak
---------|----------------------------|--------|---------|-------|-------
07:00    | Nasi + Telur + Sayur       | 350    | 8g      | 45g   | 12g
10:00    | Pisang + Susu              | 180    | 4g      | 28g   | 5g
12:30    | Nasi + Ayam + Sayur        | 420    | 15g     | 52g   | 14g
15:00    | Biskuit + Jus Jeruk        | 150    | 2g      | 25g   | 4g
18:30    | Nasi + Ikan + Sayur        | 380    | 12g     | 48g   | 10g
---------|----------------------------|--------|---------|-------|-------
TOTAL    |                            | 1,480  | 41g     | 198g  | 45g
TARGET   |                            | 1,300  | 20g     | 180g  | 45g
CAPAIAN  |                            | 114%✅ | 205%✅  | 110%✅| 100%✅
```

**📊 Analisis Hari Ini:**
- ✅ **Kalori**: MELEBIHI target (+180 kcal) - Sangat Baik!
- ✅ **Protein**: MELEBIHI target (+21g) - Excellent!
- ✅ **Karbohidrat**: MELEBIHI target (+18g) - Baik
- ✅ **Lemak**: TEPAT target - Perfect!

**🎯 Kesimpulan Hari Ini:**
- **Status**: SANGAT BAIK ⭐⭐⭐⭐⭐
- **Pola Makan**: Seimbang dan teratur (5x makan)
- **Variasi**: Baik (ada protein hewani, sayur, buah)

---

### **4. ALERT SYSTEM - CONTOH NOTIFIKASI**

#### Alert Terbaru untuk Anak A:

**⚠️ Alert 1 (08 Nov 2025, 20:00)**
```
Tipe    : Nutrisi Kurang
Pesan   : Asupan protein hari ini hanya 17g (85% dari target).
          Tambahkan sumber protein untuk besok.
Status  : Sudah ditindaklanjuti ✅
```

**✅ Alert 2 (09 Nov 2025, 20:00)**
```
Tipe    : Perbaikan
Pesan   : Asupan protein kembali normal (21g). Pertahankan!
Status  : Informasi
```

**📊 Alert 3 (12 Nov 2025, 20:00)**
```
Tipe    : Pencapaian
Pesan   : Semua target nutrisi tercapai hari ini! 🎉
Status  : Positif
```

---

## 📈 VISUALISASI GRAFIK

### **Grafik 1: Pertumbuhan Berat & Tinggi**
```
Grafik Line Chart menampilkan:
- Garis Biru: Berat badan (kg) - Tren naik
- Garis Hijau: Tinggi badan (cm) - Tren naik
- X-axis: Tanggal (7 hari terakhir)
- Y-axis: Nilai (kg/cm)
```

### **Grafik 2: Nutrisi Harian**
```
Grafik Bar Chart menampilkan:
- Bar Oranye: Kalori (kcal)
- Bar Biru: Protein (gram)
- Bar Hijau: Karbohidrat (gram)
- Bar Merah: Lemak (gram)
- X-axis: Tanggal (7 hari terakhir)
- Y-axis: Jumlah
```

### **Grafik 3: Progress Hari Ini**
```
Grafik Donut Chart menampilkan:
- Persentase capaian vs target
- Warna berbeda untuk setiap nutrisi
- Tooltip menampilkan: "Kalori: 1,480 / 1,300"
```

---

## 💡 REKOMENDASI UNTUK ANAK A

### **Berdasarkan Analisis 7 Hari:**

#### ✅ **Yang Sudah Baik:**
1. Pertumbuhan berat & tinggi badan stabil
2. Pola makan teratur 5x sehari
3. Variasi makanan cukup baik
4. Asupan lemak konsisten

#### ⚠️ **Yang Perlu Diperbaiki:**
1. **Protein**: Masih sering di bawah target
   - Solusi: Tambahkan telur/susu di pagi hari
   
2. **Kalori**: Beberapa hari kurang dari target
   - Solusi: Tambahkan snack bergizi di sore hari

#### 🎯 **Target Minggu Depan:**
1. Pastikan protein minimal 20g setiap hari
2. Tambahkan 1 snack sehat (buah/yogurt)
3. Pertahankan pola makan 5x sehari
4. Monitor berat badan setiap 3 hari

---

## 🔔 CARA KERJA ALERT SYSTEM

### **Trigger Alert Otomatis:**

1. **Alert Nutrisi Kurang** (Merah 🚨)
   - Trigger: Asupan < 85% target
   - Contoh: "Protein hari ini hanya 17g (85%)"

2. **Alert Perhatian** (Kuning ⚠️)
   - Trigger: Asupan 85-95% target
   - Contoh: "Kalori hari ini 1,250 kcal (96%)"

3. **Alert Positif** (Hijau ✅)
   - Trigger: Semua target tercapai
   - Contoh: "Semua nutrisi tercapai hari ini!"

4. **Alert Pertumbuhan** (Biru 📊)
   - Trigger: Berat/tinggi tidak naik 7 hari
   - Contoh: "Berat badan stagnan, periksa asupan"

---

## 📱 FITUR TAMBAHAN

### **1. Kelola Data Anak**
- Tambah/edit/hapus data anak
- Set target nutrisi custom
- Lihat riwayat lengkap

### **2. Log Makanan**
- Input makanan yang dikonsumsi
- Kalkulasi nutrisi otomatis
- Database makanan Indonesia

### **3. Log Pertumbuhan**
- Input tinggi & berat berkala
- Tracking perubahan
- Grafik tren pertumbuhan

### **4. Export Laporan**
- Download data dalam format PDF/Excel
- Laporan mingguan/bulanan
- Grafik dan analisis lengkap

---

## 🎓 KESIMPULAN

### **Untuk Anak A:**

**Status Keseluruhan: BAIK ✅**

- ✅ Pertumbuhan fisik normal
- ✅ Asupan nutrisi rata-rata baik
- ⚠️ Perlu sedikit peningkatan protein
- ✅ Pola makan teratur dan seimbang

**Rekomendasi Utama:**
1. Tambahkan 1 telur rebus setiap pagi
2. Berikan susu tinggi protein 2x sehari
3. Lanjutkan monitoring rutin
4. Konsultasi ke ahli gizi jika protein tetap kurang

---

## 📞 KAPAN HARUS KONSULTASI DOKTER?

Segera konsultasi jika:
- ❌ Berat badan tidak naik selama 2 minggu
- ❌ Tinggi badan tidak bertambah 1 bulan
- ❌ Asupan nutrisi < 80% target selama 5 hari berturut-turut
- ❌ Anak menolak makan atau muntah terus-menerus
- ❌ Alert merah muncul lebih dari 3x dalam seminggu

---

**© 2025 Healthy Monitoring System**
*Dokumentasi ini dibuat untuk membantu orang tua memahami fitur Nutrition Monitoring*
