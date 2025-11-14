# Cara Kerja ID User Permanen

## Konsep Utama

**ID User (child_id) adalah PERMANEN per user, bukan per anak!**

```
User "Enida" → ID: 01-D-3905 (PERMANEN SELAMANYA)
```

## Ilustrasi Alur

### 📅 Input Data Pertama (14 Nov 2025)
```
┌─────────────────────────────────────┐
│ Form Tambah Data Anak               │
├─────────────────────────────────────┤
│ 🏥 ID Pengenal: 01-D-3905 [Copy]   │  ← GENERATE BARU
│    (ID ini permanen untuk anak ini) │
│                                     │
│ 📸 Foto Anak (Opsional)            │
│    [Choose File] No file chosen     │  ← UPLOAD FOTO PERTAMA
│    Format: JPG, PNG (Max 2MB)      │
│                                     │
│ Nama: Enida                         │
│ Usia: 12 bulan                      │
│ Tinggi: 68 cm                       │
│ Berat: 12 kg                        │
└─────────────────────────────────────┘

✅ Data tersimpan dengan:
   - ID: 01-D-3905 (PERMANEN)
   - Foto: enida_photo.jpg
```

### 📅 Input Data Kedua (15 Des 2025) - Progress 1 Bulan
```
┌─────────────────────────────────────┐
│ Form Tambah Data Anak               │
├─────────────────────────────────────┤
│ 🏥 ID Pengenal: 01-D-3905 [Copy]   │  ← ID SAMA! (PERMANEN)
│    (ID ini permanen untuk anak ini) │
│                                     │
│ 📸 Foto Anak                        │
│    [Foto saat ini]                  │  ← TAMPIL FOTO LAMA
│    [Ubah Foto]                      │  ← BISA UBAH JIKA MAU
│    Klik "Ubah Foto" jika ingin     │
│    mengganti foto                   │
│                                     │
│ Nama: Enida                         │
│ Usia: 13 bulan                      │  ← UPDATE
│ Tinggi: 70 cm                       │  ← UPDATE
│ Berat: 12.5 kg                      │  ← UPDATE
└─────────────────────────────────────┘

✅ Data tersimpan dengan:
   - ID: 01-D-3905 (TETAP SAMA!)
   - Foto: enida_photo.jpg (atau foto baru jika diubah)
```

### 📅 Input Data Ketiga (15 Jan 2026) - Progress 2 Bulan
```
┌─────────────────────────────────────┐
│ Form Tambah Data Anak               │
├─────────────────────────────────────┤
│ 🏥 ID Pengenal: 01-D-3905 [Copy]   │  ← ID TETAP SAMA!
│    (ID ini permanen untuk anak ini) │
│                                     │
│ 📸 Foto Anak                        │
│    [Foto saat ini]                  │  ← FOTO DARI DATA PERTAMA
│    [Ubah Foto]                      │  ← BISA UBAH KAPAN SAJA
│                                     │
│ Nama: Enida                         │
│ Usia: 14 bulan                      │  ← UPDATE
│ Tinggi: 72 cm                       │  ← UPDATE
│ Berat: 13 kg                        │  ← UPDATE
└─────────────────────────────────────┘

✅ Data tersimpan dengan:
   - ID: 01-D-3905 (TETAP SAMA!)
   - Foto: enida_photo.jpg (atau foto baru jika diubah)
```

## Grafik Pertumbuhan

Semua data dengan ID yang sama akan ditampilkan dalam satu grafik:

```
Pemantauan Grafik Pertumbuhan

enida
🏥 ID: 01-D-3905  [📋]

Grafik:
  ┌─────────────────────────────────┐
  │                            ●    │  ← Data ke-3 (14 bln)
  │                       ●         │  ← Data ke-2 (13 bln)
  │                  ●              │  ← Data ke-1 (12 bln)
  │                                 │
  └─────────────────────────────────┘
    12 bln    13 bln    14 bln
```

## Keuntungan Sistem Ini

### ✅ ID Permanen
- User tidak bingung dengan ID yang berubah-ubah
- Mudah tracking riwayat pertumbuhan
- Konsisten untuk laporan dan dokumentasi

### ✅ Foto Dapat Diubah
- Foto anak bisa diupdate seiring pertumbuhan
- Tidak terpaku dengan foto lama
- Fleksibel sesuai kebutuhan

### ✅ User Experience Lebih Baik
- Form lebih sederhana untuk input kedua dan seterusnya
- Fokus pada data kesehatan yang penting
- Tidak perlu input ulang ID dan foto setiap kali

## Perbedaan dengan Sistem Lama

| Aspek | Sistem Lama | Sistem Baru |
|-------|-------------|-------------|
| ID User | Berubah setiap input | **PERMANEN per user** |
| Foto | Harus input ulang | Tampil foto lama + bisa edit |
| Form | Selalu lengkap | Lebih sederhana untuk input ke-2 dst |
| Tracking | Sulit (ID berbeda) | Mudah (ID sama) |

## FAQ

**Q: Apakah ID akan berubah jika saya input data anak yang berbeda?**
A: Tidak! ID permanen per user, bukan per anak. Satu user = satu ID selamanya.

**Q: Bagaimana jika saya punya 2 anak?**
A: Sistem ini menggunakan satu ID per user. Untuk tracking multiple anak, gunakan nama yang berbeda, tapi ID tetap sama.

**Q: Bisakah saya mengubah foto?**
A: Ya! Klik tombol "Ubah Foto" kapan saja untuk mengganti foto.

**Q: Apakah foto wajib diisi?**
A: Tidak, foto bersifat opsional.

**Q: Bagaimana sistem tahu data mana yang untuk anak yang sama?**
A: Sistem menggunakan ID user yang permanen. Semua data dengan ID yang sama akan ditampilkan dalam satu grafik pertumbuhan.

## Tanggal Update
14 November 2025 - Revisi 2
