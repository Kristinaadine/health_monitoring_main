# 📊 Changelog - Fitur Kalori Kalkulator

**Tanggal Update:** 13 November 2025  
**Versi:** 2.0  
**Fitur:** Menambahkan History & Database Storage

---

## 🔄 Ringkasan Perubahan

Fitur Kalori Kalkulator telah diupgrade dari sistem **tanpa penyimpanan data** menjadi sistem dengan **history lengkap** yang tersimpan di database.

---

## 📋 Perbandingan Versi

### ❌ VERSI LAMA (Sebelum Revisi)

#### Karakteristik:
- ✗ Tidak ada penyimpanan data ke database
- ✗ Perhitungan hanya di frontend (JavaScript)
- ✗ User harus input ulang setiap kali akses
- ✗ Tidak ada riwayat perhitungan
- ✗ Data hilang setelah refresh/logout

#### Struktur File:
```
app/Http/Controllers/Home/
└── CaloriCalcController.php (hanya 1 method: index)

resources/views/home/
└── caloricalc.blade.php (form + hasil dalam 1 file)

routes/web.php
└── Route::get('/caloric', ...) (hanya 1 route)
```

#### Database:
```
Tidak ada tabel untuk kalori kalkulator
```

---

### ✅ VERSI BARU (Sesudah Revisi)

#### Karakteristik:
- ✓ Data tersimpan di database
- ✓ History perhitungan lengkap
- ✓ User bisa lihat riwayat
- ✓ Bisa hapus history lama
- ✓ Empty state untuk user baru
- ✓ AJAX untuk operasi smooth

#### Struktur File:
```
app/Http/Controllers/Home/
└── CaloriCalcController.php (4 methods: index, create, store, destroy)

app/Models/
└── CalorieHistoryModel.php (NEW)

database/migrations/
└── 2025_11_13_045344_create_calorie_history_models_table.php (NEW)

resources/views/home/caloricalc/
├── index.blade.php (halaman history) (NEW)
└── form.blade.php (halaman form input) (NEW)

routes/web.php
├── Route::get('/caloric', ...) (index - history)
├── Route::get('/caloric/create', ...) (NEW)
├── Route::post('/caloric', ...) (NEW)
└── Route::delete('/caloric/{id}', ...) (NEW)
```

#### Database:
```sql
CREATE TABLE `calorie_history_models` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `age` int(11) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `activity_level` decimal(4,3) NOT NULL,
  `gain_loss_amount` int(11) NOT NULL,
  `daily_calories` int(11) NOT NULL,
  `carbs` int(11) NOT NULL,
  `protein` int(11) NOT NULL,
  `fat` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

---

## 🗂️ Detail Perubahan File

### 1. **CaloriCalcController.php**

#### Sebelum:
```php
class CaloriCalcController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();
        return view('home.caloricalc', compact('setting'));
    }
}
```

#### Sesudah:
```php
class CaloriCalcController extends Controller
{
    public function index()
    {
        $setting = SettingModel::all();
        $histories = CalorieHistoryModel::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('home.caloricalc.index', compact('setting', 'histories'));
    }

    public function create() { ... }      // NEW
    public function store() { ... }       // NEW
    public function destroy() { ... }     // NEW
}
```

**Perubahan:**
- ✓ Menambah 3 method baru
- ✓ Index sekarang load history dari database
- ✓ View path berubah dari `home.caloricalc` ke `home.caloricalc.index`

---

### 2. **routes/web.php**

#### Sebelum:
```php
// CALORI CALCULATOR
Route::get('/caloric', [CaloriCalcController::class, 'index'])->name('caloric');
```

#### Sesudah:
```php
// CALORI CALCULATOR
Route::get('/caloric', [CaloriCalcController::class, 'index'])->name('caloric');
Route::get('/caloric/create', [CaloriCalcController::class, 'create'])->name('caloric.create');
Route::post('/caloric', [CaloriCalcController::class, 'store'])->name('caloric.store');
Route::delete('/caloric/{id}', [CaloriCalcController::class, 'destroy'])->name('caloric.destroy');
```

**Perubahan:**
- ✓ Menambah 3 route baru
- ✓ Support CRUD operations

---

### 3. **View Files**

#### Sebelum:
```
resources/views/home/
└── caloricalc.blade.php (1 file untuk semua)
```

#### Sesudah:
```
resources/views/home/caloricalc/
├── index.blade.php (halaman history)
└── form.blade.php (halaman form + hasil)
```

**Perubahan:**
- ✓ Dipisah menjadi 2 file untuk separation of concerns
- ✓ Index untuk menampilkan history
- ✓ Form untuk input & kalkulasi

---

## 🗄️ Struktur Database Baru

### Tabel: `calorie_history_models`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint UNSIGNED | Primary key |
| `user_id` | bigint UNSIGNED | Foreign key ke users |
| `age` | int | Usia (tahun) |
| `sex` | enum | male/female |
| `height` | decimal(5,2) | Tinggi (cm) |
| `weight` | decimal(5,2) | Berat (kg) |
| `activity_level` | decimal(4,3) | Level aktivitas (1.2-1.9) |
| `gain_loss_amount` | int | Target kalori (+/-) |
| `daily_calories` | int | Hasil kalori harian |
| `carbs` | int | Karbohidrat (gram) |
| `protein` | int | Protein (gram) |
| `fat` | int | Lemak (gram) |
| `created_at` | timestamp | Waktu dibuat |
| `updated_at` | timestamp | Waktu diupdate |

---

## 🎯 Fitur Baru

### 1. **Halaman History (Index)**
- Menampilkan semua riwayat perhitungan user
- Empty state jika belum ada data
- Tombol "Tambah Data" untuk perhitungan baru
- Tombol delete untuk hapus history
- Card design dengan info lengkap

### 2. **Halaman Form (Create)**
- Form input data user
- Kalkulasi real-time
- Tombol "Simpan Hasil Perhitungan"
- Redirect ke history setelah simpan

### 3. **Delete History**
- Konfirmasi SweetAlert sebelum hapus
- AJAX delete tanpa reload
- Fade out animation

### 4. **Data Persistence**
- Semua perhitungan tersimpan
- Bisa diakses kapan saja
- Relasi dengan user account

---

## 🚀 Cara Migrasi

### Untuk Update ke Versi Baru:

```bash
# 1. Pull/update code terbaru
git pull origin main

# 2. Jalankan migration
php artisan migrate

# 3. Clear cache
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 4. Test fitur
# Akses: {locale}/caloric
```

### Untuk Rollback ke Versi Lama:

```bash
# 1. Rollback migration
php artisan migrate:rollback --step=1

# 2. Restore file lama dari git
git checkout HEAD~1 -- app/Http/Controllers/Home/CaloriCalcController.php
git checkout HEAD~1 -- routes/web.php
git checkout HEAD~1 -- resources/views/home/caloricalc.blade.php

# 3. Hapus file baru
rm -rf resources/views/home/caloricalc/
rm app/Models/CalorieHistoryModel.php

# 4. Clear cache
php artisan route:clear
php artisan view:clear
```

---

## 📊 Contoh Data

### Insert Sample Data:
```sql
INSERT INTO `calorie_history_models` 
  (`user_id`, `age`, `sex`, `height`, `weight`, `activity_level`, 
   `gain_loss_amount`, `daily_calories`, `carbs`, `protein`, `fat`, 
   `created_at`, `updated_at`) 
VALUES 
  (1, 25, 'male', 175.00, 70.00, 1.550, 0, 2450, 245, 184, 82, NOW(), NOW()),
  (1, 25, 'male', 175.00, 72.00, 1.550, 500, 2950, 295, 221, 98, NOW(), NOW());
```

### Query History User:
```sql
SELECT 
  age, sex, height, weight, daily_calories, carbs, protein, fat,
  DATE_FORMAT(created_at, '%d %M %Y %H:%i') AS tanggal
FROM calorie_history_models
WHERE user_id = 1
ORDER BY created_at DESC;
```

---

## ⚠️ Breaking Changes

### 1. **View Path Changed**
- **Lama:** `home.caloricalc`
- **Baru:** `home.caloricalc.index` dan `home.caloricalc.form`

### 2. **Route Behavior Changed**
- **Lama:** `/caloric` langsung ke form
- **Baru:** `/caloric` ke history, `/caloric/create` ke form

### 3. **Controller Method Changed**
- **Lama:** `index()` return form
- **Baru:** `index()` return history

---

## 🔒 Security & Validation

### Validasi Input:
```php
'age' => 'required|integer|min:1|max:120',
'sex' => 'required|in:male,female',
'height' => 'required|numeric|min:50|max:250',
'weight' => 'required|numeric|min:20|max:300',
'activity_level' => 'required|numeric',
'gain_loss_amount' => 'required|integer',
```

### Security Features:
- ✓ CSRF Protection
- ✓ User authentication required
- ✓ Foreign key constraint
- ✓ Cascade delete on user deletion
- ✓ Input validation server-side

---

## 📝 Notes

1. **Backup Database** sebelum migrate:
   ```bash
   mysqldump -u username -p database_name > backup_before_calorie_update.sql
   ```

2. **Test Thoroughly** setelah update:
   - Test create new calculation
   - Test view history
   - Test delete history
   - Test empty state

3. **User Experience:**
   - User lama akan melihat empty state pertama kali
   - Mereka perlu input data baru untuk mulai history

4. **Data Migration:**
   - Tidak ada data lama yang perlu dimigrate
   - Karena versi lama tidak menyimpan data

---

## 📞 Support

Jika ada masalah setelah update:
1. Check migration status: `php artisan migrate:status`
2. Check error logs: `storage/logs/laravel.log`
3. Clear all cache: `php artisan optimize:clear`
4. Rollback jika perlu (lihat section Rollback)

---

**End of Changelog**
