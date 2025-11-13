-- ============================================================================
-- DATABASE CHANGES - CALORIE CALCULATOR FEATURE
-- ============================================================================
-- Tanggal: 13 November 2025
-- Fitur: Menambahkan History untuk Kalori Kalkulator
-- ============================================================================

-- ============================================================================
-- SEBELUM REVISI (VERSI LAMA)
-- ============================================================================
-- Fitur Kalori Kalkulator TIDAK memiliki tabel database
-- Semua perhitungan hanya dilakukan di frontend (JavaScript)
-- Data TIDAK disimpan ke database
-- User harus input ulang setiap kali mengakses fitur

-- Tidak ada tabel untuk menyimpan history kalori kalkulator


-- ============================================================================
-- SESUDAH REVISI (VERSI BARU)
-- ============================================================================
-- Menambahkan tabel baru: calorie_history_models
-- Menyimpan semua perhitungan kalori user
-- User bisa melihat riwayat perhitungan

-- ----------------------------------------------------------------------------
-- TABEL BARU: calorie_history_models
-- ----------------------------------------------------------------------------
CREATE TABLE `calorie_history_models` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `age` int(11) NOT NULL COMMENT 'Usia user dalam tahun',
  `sex` enum('male','female') NOT NULL COMMENT 'Jenis kelamin',
  `height` decimal(5,2) NOT NULL COMMENT 'Tinggi badan dalam cm',
  `weight` decimal(5,2) NOT NULL COMMENT 'Berat badan dalam kg',
  `activity_level` decimal(4,3) NOT NULL COMMENT 'Level aktivitas (1.2 - 1.9)',
  `gain_loss_amount` int(11) NOT NULL COMMENT 'Target kalori (+/- untuk gain/loss)',
  `daily_calories` int(11) NOT NULL COMMENT 'Hasil perhitungan kalori harian',
  `carbs` int(11) NOT NULL COMMENT 'Kebutuhan karbohidrat dalam gram',
  `protein` int(11) NOT NULL COMMENT 'Kebutuhan protein dalam gram',
  `fat` int(11) NOT NULL COMMENT 'Kebutuhan lemak dalam gram',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calorie_history_models_user_id_foreign` (`user_id`),
  CONSTRAINT `calorie_history_models_user_id_foreign` 
    FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- PENJELASAN KOLOM TABEL
-- ============================================================================

-- id                : Primary key, auto increment
-- user_id           : Foreign key ke tabel users, untuk identifikasi user
-- age               : Usia user saat perhitungan (dalam tahun)
-- sex               : Jenis kelamin (male/female) untuk rumus BMR
-- height            : Tinggi badan dalam cm (max 999.99)
-- weight            : Berat badan dalam kg (max 999.99)
-- activity_level    : Faktor aktivitas fisik:
--                     1.2   = Little to no exercise
--                     1.375 = Light exercise (1-3 days/week)
--                     1.55  = Moderate exercise (3-5 days/week)
--                     1.725 = Heavy exercise (6-7 days/week)
--                     1.9   = Very heavy exercise (twice per day)
-- gain_loss_amount  : Target kalori untuk gain/loss berat badan:
--                     -1000 = Lose 2 pounds per week
--                     -750  = Lose 1.5 pounds per week
--                     -500  = Lose 1 pound per week
--                     -250  = Lose 0.5 pound per week
--                     0     = Maintain weight
--                     +250  = Gain 0.5 pound per week
--                     +500  = Gain 1 pound per week
--                     +750  = Gain 1.5 pounds per week
--                     +1000 = Gain 2 pounds per week
-- daily_calories    : Hasil perhitungan kebutuhan kalori harian (kcal)
-- carbs             : Kebutuhan karbohidrat harian (gram) - 40% dari kalori
-- protein           : Kebutuhan protein harian (gram) - 30% dari kalori
-- fat               : Kebutuhan lemak harian (gram) - 30% dari kalori
-- created_at        : Timestamp kapan data dibuat
-- updated_at        : Timestamp kapan data terakhir diupdate


-- ============================================================================
-- CONTOH DATA
-- ============================================================================

-- Contoh 1: Laki-laki, 25 tahun, ingin maintain weight
INSERT INTO `calorie_history_models` 
  (`user_id`, `age`, `sex`, `height`, `weight`, `activity_level`, 
   `gain_loss_amount`, `daily_calories`, `carbs`, `protein`, `fat`, 
   `created_at`, `updated_at`) 
VALUES 
  (1, 25, 'male', 175.00, 70.00, 1.550, 0, 2450, 245, 184, 82, 
   NOW(), NOW());

-- Contoh 2: Perempuan, 30 tahun, ingin lose weight
INSERT INTO `calorie_history_models` 
  (`user_id`, `age`, `sex`, `height`, `weight`, `activity_level`, 
   `gain_loss_amount`, `daily_calories`, `carbs`, `protein`, `fat`, 
   `created_at`, `updated_at`) 
VALUES 
  (2, 30, 'female', 160.00, 65.00, 1.375, -500, 1650, 165, 124, 55, 
   NOW(), NOW());

-- Contoh 3: Laki-laki, 22 tahun, ingin gain weight
INSERT INTO `calorie_history_models` 
  (`user_id`, `age`, `sex`, `height`, `weight`, `activity_level`, 
   `gain_loss_amount`, `daily_calories`, `carbs`, `protein`, `fat`, 
   `created_at`, `updated_at`) 
VALUES 
  (3, 22, 'male', 180.00, 65.00, 1.725, 500, 3100, 310, 233, 103, 
   NOW(), NOW());


-- ============================================================================
-- QUERY UNTUK MELIHAT HISTORY USER
-- ============================================================================

-- Melihat semua history user tertentu (user_id = 1)
SELECT 
  id,
  age AS 'Usia',
  sex AS 'Jenis Kelamin',
  height AS 'Tinggi (cm)',
  weight AS 'Berat (kg)',
  daily_calories AS 'Kalori Harian',
  carbs AS 'Karbohidrat (g)',
  protein AS 'Protein (g)',
  fat AS 'Lemak (g)',
  DATE_FORMAT(created_at, '%d %M %Y %H:%i') AS 'Tanggal Perhitungan'
FROM calorie_history_models
WHERE user_id = 1
ORDER BY created_at DESC;

-- Melihat history terbaru semua user
SELECT 
  u.name AS 'Nama User',
  ch.age AS 'Usia',
  ch.sex AS 'Gender',
  ch.daily_calories AS 'Kalori',
  DATE_FORMAT(ch.created_at, '%d %M %Y') AS 'Tanggal'
FROM calorie_history_models ch
JOIN users u ON ch.user_id = u.id
ORDER BY ch.created_at DESC
LIMIT 10;

-- Statistik rata-rata kalori per user
SELECT 
  u.name AS 'Nama User',
  COUNT(ch.id) AS 'Jumlah Perhitungan',
  ROUND(AVG(ch.daily_calories), 0) AS 'Rata-rata Kalori',
  MIN(ch.daily_calories) AS 'Kalori Minimum',
  MAX(ch.daily_calories) AS 'Kalori Maximum'
FROM calorie_history_models ch
JOIN users u ON ch.user_id = u.id
GROUP BY u.id, u.name
ORDER BY COUNT(ch.id) DESC;


-- ============================================================================
-- MIGRATION FILE (Laravel)
-- ============================================================================
-- File: database/migrations/2025_11_13_045344_create_calorie_history_models_table.php
-- 
-- Untuk rollback migration:
-- php artisan migrate:rollback --step=1
--
-- Untuk migrate ulang:
-- php artisan migrate


-- ============================================================================
-- PERUBAHAN ROUTES
-- ============================================================================

-- SEBELUM:
-- Route::get('/caloric', [CaloriCalcController::class, 'index'])->name('caloric');

-- SESUDAH:
-- Route::get('/caloric', [CaloriCalcController::class, 'index'])->name('caloric');
-- Route::get('/caloric/create', [CaloriCalcController::class, 'create'])->name('caloric.create');
-- Route::post('/caloric', [CaloriCalcController::class, 'store'])->name('caloric.store');
-- Route::delete('/caloric/{id}', [CaloriCalcController::class, 'destroy'])->name('caloric.destroy');


-- ============================================================================
-- PERUBAHAN FILE
-- ============================================================================

-- FILE BARU:
-- 1. app/Models/CalorieHistoryModel.php
-- 2. database/migrations/2025_11_13_045344_create_calorie_history_models_table.php
-- 3. resources/views/home/caloricalc/index.blade.php (halaman history)
-- 4. resources/views/home/caloricalc/form.blade.php (halaman form)

-- FILE DIMODIFIKASI:
-- 1. app/Http/Controllers/Home/CaloriCalcController.php
--    - Menambah method: create(), store(), destroy()
--    - Modifikasi method: index() untuk menampilkan history
-- 2. routes/web.php
--    - Menambah 3 route baru untuk create, store, destroy

-- FILE DIHAPUS:
-- 1. resources/views/home/caloricalc.blade.php (dipindah ke folder caloricalc/)


-- ============================================================================
-- CARA ROLLBACK KE VERSI LAMA
-- ============================================================================

-- 1. Rollback migration
-- php artisan migrate:rollback --step=1

-- 2. Hapus tabel manual (jika perlu)
DROP TABLE IF EXISTS `calorie_history_models`;

-- 3. Restore file lama:
--    - Kembalikan CaloriCalcController.php ke versi lama
--    - Kembalikan routes/web.php ke versi lama
--    - Hapus folder resources/views/home/caloricalc/
--    - Restore file resources/views/home/caloricalc.blade.php

-- 4. Clear cache
-- php artisan route:clear
-- php artisan view:clear
-- php artisan cache:clear


-- ============================================================================
-- CATATAN PENTING
-- ============================================================================

-- 1. Tabel ini menggunakan foreign key ke tabel users
--    Pastikan tabel users sudah ada sebelum migrate

-- 2. Data akan otomatis terhapus jika user dihapus (ON DELETE CASCADE)

-- 3. Tidak ada validasi di database level untuk range nilai
--    Validasi dilakukan di controller (Laravel validation)

-- 4. Decimal precision:
--    - height & weight: 5,2 (max 999.99)
--    - activity_level: 4,3 (max 9.999)

-- 5. Backup database sebelum migrate:
--    mysqldump -u username -p database_name > backup.sql

-- ============================================================================
-- END OF FILE
-- ============================================================================
