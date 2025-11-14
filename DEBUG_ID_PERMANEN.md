# Debug Guide - ID User Permanen

## Masalah: ID Masih Berubah

Jika ID masih berubah setelah implementasi, ikuti langkah debugging berikut:

### 1. Cek Log Laravel

Buka file `storage/logs/laravel.log` dan cari log berikut:

```
[timestamp] Growth Monitoring Store - Start
[timestamp] Growth Monitoring Store - Using existing child_id (PERMANENT)
atau
[timestamp] Growth Monitoring Store - Generated new PERMANENT child_id
```

**Yang Harus Terjadi:**
- **Input Pertama**: Log "Generated new PERMANENT child_id" dengan ID baru
- **Input Kedua dst**: Log "Using existing child_id (PERMANENT)" dengan ID yang SAMA

**Jika Log Salah:**
- Jika selalu "Generated new", berarti query `$firstData` tidak menemukan data lama
- Periksa apakah `users_id` tersimpan dengan benar

### 2. Cek Database

Jalankan query di database:

```sql
-- Cek semua data user
SELECT id, users_id, child_id, name, age, created_at 
FROM growth_monitoring 
WHERE users_id = [ID_USER_ANDA]
ORDER BY created_at ASC;
```

**Yang Harus Terlihat:**
```
id | users_id | child_id    | name  | age | created_at
---+----------+-------------+-------+-----+-------------------
1  | 123      | 01-D-3905   | Enida | 12  | 2025-11-14 10:00
2  | 123      | 01-D-3905   | Enida | 13  | 2025-12-15 10:00  <- SAMA!
3  | 123      | 01-D-3905   | Enida | 14  | 2026-01-15 10:00  <- SAMA!
```

**Jika child_id Berbeda:**
- Ada bug di logika controller
- Periksa apakah `$firstData` benar-benar mengambil data pertama

### 3. Cek AJAX Response

Buka Browser Console (F12) → Network → Cari request `growth-monitoring` (POST)

**Response yang Benar:**
```json
{
  "status": "success",
  "message": "Hasil Z-Score berhasil disimpan",
  "redirect": "...",
  "child_id": "01-D-3905"
}
```

**Jika child_id Berbeda Setiap Kali:**
- Backend tidak menggunakan ID yang sama
- Periksa logika di `GrowthMonitoringController@store`

### 4. Cek Modal Form

Buka modal "Tambah Data Anak" dan periksa:

**Input Pertama (User Baru):**
```
ID Pengenal: Akan dibuat otomatis
Foto: [Choose File] (upload baru)
```

**Input Kedua dst (User Lama):**
```
ID Pengenal: 01-D-3905 [Copy]  <- HARUS SAMA!
Foto: [Foto saat ini] [Ubah Foto]
```

**Jika ID Berubah di Modal:**
- AJAX `get-user-data` tidak mengembalikan data yang benar
- Periksa response AJAX di console

### 5. Test Step by Step

#### Test 1: Input Data Pertama
1. Login sebagai user baru
2. Klik "Tambah Data Anak"
3. Isi form lengkap
4. Klik "Simpan"
5. **Catat ID yang muncul** (misal: 01-D-3905)
6. Cek database: `SELECT child_id FROM growth_monitoring WHERE users_id = [ID] ORDER BY created_at DESC LIMIT 1;`

#### Test 2: Input Data Kedua
1. Klik "Tambah Data Anak" lagi
2. **Periksa ID di modal** - harus sama dengan Test 1
3. Isi data kesehatan baru
4. Klik "Simpan"
5. **Periksa notifikasi** - harus menampilkan ID yang sama
6. Cek database: `SELECT child_id FROM growth_monitoring WHERE users_id = [ID] ORDER BY created_at DESC LIMIT 2;`
7. **Kedua record harus punya child_id yang SAMA**

### 6. Common Issues

#### Issue 1: ID Selalu Generate Baru
**Penyebab:** Query `$firstData` tidak menemukan data lama

**Solusi:**
```php
// Di GrowthMonitoringController@store
$firstData = GrowthMonitoringModel::where('users_id', auth()->user()->id)
    ->orderBy('created_at', 'asc')
    ->first();

// Debug: Tambahkan log
\Log::info('First Data Check', [
    'user_id' => auth()->user()->id,
    'found' => $firstData ? 'YES' : 'NO',
    'child_id' => $firstData ? $firstData->child_id : 'N/A'
]);
```

#### Issue 2: AJAX get-user-data Tidak Bekerja
**Penyebab:** Route tidak terdaftar atau URL salah

**Solusi:**
```bash
# Cek route
php artisan route:list | grep growth-monitoring

# Harus ada:
GET|HEAD  {locale}/growth-monitoring/get-user-data
```

#### Issue 3: Modal Tidak Menampilkan ID yang Benar
**Penyebab:** JavaScript tidak update field dengan benar

**Solusi:**
```javascript
// Tambahkan console.log untuk debug
$('#modalForm').on('shown.bs.modal', function() {
    $.ajax({
        url: "{{ locale_route('growth-monitoring.get-user-data') }}",
        type: "GET",
        success: function(response) {
            console.log('User Data Response:', response); // DEBUG
            if (response.has_data) {
                console.log('Setting ID to:', response.child_id); // DEBUG
                $('#user_id').val(response.child_id);
                $('#user_id_display').val(response.child_id);
            }
        }
    });
});
```

### 7. Verification Checklist

Setelah fix, verifikasi dengan checklist ini:

- [ ] Input data pertama → ID ter-generate (misal: 01-D-3905)
- [ ] Cek database → child_id tersimpan
- [ ] Input data kedua → Modal menampilkan ID yang SAMA
- [ ] Simpan data kedua → Notifikasi menampilkan ID yang SAMA
- [ ] Cek database → Kedua record punya child_id yang SAMA
- [ ] Input data ketiga → ID tetap SAMA
- [ ] Cek grafik → Semua data dengan ID yang sama muncul dalam satu grafik

### 8. Emergency Fix

Jika masih tidak bekerja, gunakan fix manual di database:

```sql
-- Ambil ID pertama user
SET @first_id = (
    SELECT child_id 
    FROM growth_monitoring 
    WHERE users_id = [ID_USER] 
    ORDER BY created_at ASC 
    LIMIT 1
);

-- Update semua record user dengan ID yang sama
UPDATE growth_monitoring 
SET child_id = @first_id 
WHERE users_id = [ID_USER];

-- Verifikasi
SELECT id, child_id, name, created_at 
FROM growth_monitoring 
WHERE users_id = [ID_USER] 
ORDER BY created_at ASC;
```

## Contact Support

Jika masih ada masalah setelah mengikuti semua langkah di atas, kirimkan:
1. Screenshot modal form
2. Screenshot database query result
3. Log dari `storage/logs/laravel.log`
4. Screenshot browser console (Network tab)

---
Last Updated: 14 November 2025
