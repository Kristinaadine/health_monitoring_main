# Instruksi Test ID Permanen - Step by Step

## ⚠️ PENTING: Ikuti Langkah Ini Dengan Teliti!

### Langkah 1: Clear Semua Cache

```bash
# Clear cache Laravel
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Clear cache browser
# Tekan Ctrl + Shift + Delete
# Pilih "All time"
# Centang semua (Cache, Cookies, dll)
# Klik "Clear data"
```

### Langkah 2: Verifikasi Database

```bash
php artisan tinker
```

Lalu jalankan:
```php
// Cek semua user
\App\Models\User::all()->each(function($u) {
    echo "ID: {$u->id}, Name: {$u->name}, Email: {$u->email}, child_id: {$u->child_id}\n";
});

// Cek user yang sedang login (ganti 5 dengan ID user Anda)
$user = \App\Models\User::find(5);
echo "User ID: {$user->id}\n";
echo "Name: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "child_id: {$user->child_id}\n";

// Exit
exit
```

**Hasil yang Diharapkan:**
```
User ID: 5
Name: Pak Sigit
Email: sigit@gmail.com
child_id: 01-K-4749  ← Harus ada nilai!
```

**Jika child_id NULL atau kosong:**
```bash
php artisan tinker
```

```php
$user = \App\Models\User::find(5);
$user->child_id = '01-K-4749';
$user->save();
echo "Fixed! child_id: {$user->child_id}\n";
exit
```

### Langkah 3: Restart Server

```bash
# Jika pakai php artisan serve
Ctrl + C
php artisan serve

# Jika pakai XAMPP
# Restart Apache dari XAMPP Control Panel
```

### Langkah 4: Test dengan Browser Baru

1. **Buka browser dalam mode Incognito/Private**
   - Chrome: Ctrl + Shift + N
   - Firefox: Ctrl + Shift + P
   - Edge: Ctrl + Shift + N

2. **Login ke aplikasi**
   - Email: sigit@gmail.com (atau email Anda)
   - Password: (password Anda)

3. **Buka Developer Console**
   - Tekan F12
   - Pilih tab "Console"
   - Pilih tab "Network"

### Langkah 5: Test Input Data Pertama

1. **Klik "Tambah Data Anak"**

2. **Periksa Network Request**
   - Di tab Network, cari request `get-user-data`
   - Klik request tersebut
   - Lihat Response:
     ```json
     {
       "has_data": false,
       "child_id": "01-K-4749",  ← Harus ada!
       "photo": null,
       "photo_url": null
     }
     ```

3. **Periksa ID di Modal**
   - Harus menampilkan: `01-K-4749`
   - Jika menampilkan "Akan dibuat otomatis", ada masalah di JavaScript

4. **Isi Form**
   - Nama: Enida
   - Usia: 24
   - Jenis Kelamin: Perempuan
   - Tinggi: 70
   - Berat: 12
   - Foto: (opsional)

5. **Klik "Simpan"**

6. **Periksa Network Request**
   - Di tab Network, cari request `growth-monitoring` (POST)
   - Klik request tersebut
   - Lihat Request Payload:
     ```
     name: Enida
     age: 24
     gender: P
     height: 70
     weight: 12
     photo: (binary)
     ```
   - **PENTING: TIDAK BOLEH ADA `user_id` di request!**

7. **Periksa Response**
   ```json
   {
     "status": "success",
     "message": "Hasil Z-Score berhasil disimpan",
     "redirect": "...",
     "child_id": "01-K-4749"  ← Harus SAMA!
   }
   ```

8. **Periksa Notifikasi**
   - Harus muncul: "Hasil Z-Score berhasil disimpan | ID Anda: 01-K-4749"

### Langkah 6: Test Input Data Kedua

1. **Klik "Tambah Data Anak" lagi**

2. **Periksa Network Request `get-user-data`**
   - Response harus:
     ```json
     {
       "has_data": true,
       "child_id": "01-K-4749",  ← Harus SAMA dengan sebelumnya!
       "photo": "...",
       "photo_url": "..."
     }
     ```

3. **Periksa ID di Modal**
   - Harus menampilkan: `01-K-4749` (SAMA!)

4. **Isi Form dengan data baru**
   - Nama: Enida
   - Usia: 25 (update)
   - Tinggi: 72 (update)
   - Berat: 13 (update)

5. **Klik "Simpan"**

6. **Periksa Response**
   ```json
   {
     "status": "success",
     "message": "Hasil Z-Score berhasil disimpan",
     "redirect": "...",
     "child_id": "01-K-4749"  ← Harus TETAP SAMA!
   }
   ```

### Langkah 7: Verifikasi Database

```bash
php artisan tinker
```

```php
// Cek semua data user
$data = \App\Models\GrowthMonitoringModel::where('users_id', 5)
    ->orderBy('created_at', 'desc')
    ->get();

foreach ($data as $d) {
    echo "ID: {$d->id}, child_id: {$d->child_id}, Name: {$d->name}, Age: {$d->age}, Created: {$d->created_at}\n";
}

exit
```

**Hasil yang Diharapkan:**
```
ID: 30, child_id: 01-K-4749, Name: Enida, Age: 25, Created: 2025-11-14 12:00
ID: 29, child_id: 01-K-4749, Name: Enida, Age: 24, Created: 2025-11-14 11:00
```

**Semua harus punya child_id yang SAMA: 01-K-4749**

### Langkah 8: Cek Log Laravel

```bash
tail -f storage/logs/laravel.log
```

Lalu input data baru. Log harus menunjukkan:

```
[timestamp] Get User Data - START
{
    "user_id": 5,
    "user_name": "Pak Sigit",
    "user_email": "sigit@gmail.com",
    "child_id_from_db": "01-K-4749"  ← Harus ada!
}

[timestamp] Get User Data - FINAL
{
    "user_id": 5,
    "child_id": "01-K-4749",  ← SAMA!
    "has_previous_data": "YES"
}

[timestamp] Growth Monitoring Store - START
{
    "user_id": 5,
    "user_name": "Pak Sigit",
    "user_email": "sigit@gmail.com",
    "child_id_from_db": "01-K-4749",  ← SAMA!
    "child_id_is_empty": "NO"
}

[timestamp] Growth Monitoring Store - Using EXISTING child_id from users table
{
    "user_id": 5,
    "child_id": "01-K-4749",  ← SAMA!
    "child_id_length": 10
}

[timestamp] Growth Monitoring Store - FINAL child_id to be saved
{
    "user_id": 5,
    "child_id": "01-K-4749"  ← SAMA!
}
```

## 🚨 Troubleshooting

### Problem 1: child_id NULL di Database

**Solusi:**
```bash
php artisan tinker
```

```php
$user = \App\Models\User::find(5);
$user->child_id = '01-K-4749';
$user->save();
echo "Fixed!\n";
exit
```

### Problem 2: ID Masih Berubah

**Cek Log:**
```bash
tail -f storage/logs/laravel.log
```

**Jika log menunjukkan "child_id_is_empty": "YES":**
- child_id di database NULL atau kosong
- Jalankan fix di Problem 1

**Jika log menunjukkan "Generated NEW child_id":**
- User tidak punya child_id di database
- Jalankan fix di Problem 1

### Problem 3: Request Mengirim `user_id`

**Cek Network Request:**
- Buka F12 → Network
- Cari request `growth-monitoring` (POST)
- Lihat Request Payload

**Jika ada `user_id` di request:**
- Clear cache browser (Ctrl + Shift + Delete)
- Hard reload (Ctrl + Shift + R)
- Test lagi

### Problem 4: Modal Tidak Menampilkan ID

**Cek Console:**
- Buka F12 → Console
- Lihat error JavaScript

**Cek Network:**
- Buka F12 → Network
- Cari request `get-user-data`
- Lihat Response

**Jika Response tidak ada `child_id`:**
- Cek log Laravel
- Pastikan user punya child_id di database

## 📞 Jika Masih Bermasalah

Kirimkan screenshot:
1. Database query result (users table)
2. Database query result (growth_monitoring table)
3. Browser Network tab (get-user-data response)
4. Browser Network tab (growth-monitoring request)
5. Laravel log (tail -f storage/logs/laravel.log)

---
**Tanggal:** 14 November 2025
**Versi:** 6.0 (Final - With Detailed Logging)
