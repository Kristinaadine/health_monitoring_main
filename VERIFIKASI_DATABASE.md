# Verifikasi Database - ID User Permanen

## Langkah Verifikasi

### 1. Cek Semua Data User

Jalankan query ini di database untuk melihat semua data user:

```sql
SELECT 
    id,
    users_id,
    child_id,
    name,
    age,
    photo,
    created_at
FROM growth_monitoring
WHERE users_id = [ID_USER_ANDA]
ORDER BY created_at ASC;
```

**Yang Harus Terlihat:**
```
id | users_id | child_id  | name  | age | photo      | created_at
---+----------+-----------+-------+-----+------------+-------------------
1  | 123      | 01-D-3905 | Enida | 12  | photo1.jpg | 2025-11-14 10:00
2  | 123      | 01-D-3905 | Enida | 13  | photo1.jpg | 2025-12-15 10:00  ✅ SAMA!
3  | 123      | 01-D-3905 | Enida | 14  | photo2.jpg | 2026-01-15 10:00  ✅ SAMA!
```

**Jika child_id Berbeda:**
- Ada masalah di logika controller
- Jalankan fix manual (lihat bagian Fix Manual)

### 2. Cek Data Tanpa child_id

```sql
SELECT COUNT(*) as total_without_child_id
FROM growth_monitoring
WHERE child_id IS NULL;
```

**Hasil yang Diharapkan:**
```
total_without_child_id
----------------------
0
```

**Jika Ada Data Tanpa child_id:**
- Jalankan migration lagi: `php artisan migrate:refresh --path=database/migrations/2025_11_14_010536_add_child_id_to_existing_records.php`
- Atau jalankan fix manual

### 3. Cek Jumlah child_id Unik per User

```sql
SELECT 
    users_id,
    COUNT(DISTINCT child_id) as unique_child_ids,
    COUNT(*) as total_records
FROM growth_monitoring
GROUP BY users_id;
```

**Hasil yang Diharapkan:**
```
users_id | unique_child_ids | total_records
---------+------------------+--------------
123      | 1                | 5             ✅ Hanya 1 ID untuk semua record
456      | 1                | 3             ✅ Hanya 1 ID untuk semua record
```

**Jika unique_child_ids > 1:**
- User punya lebih dari 1 ID (SALAH!)
- Jalankan fix manual untuk unifikasi ID

## Fix Manual

### Fix 1: Update Data Lama Tanpa child_id

```sql
-- Untuk setiap user, generate child_id dan update semua record
-- Ganti [ID_USER] dengan ID user yang bermasalah

-- 1. Generate ID baru (atau gunakan ID yang sudah ada)
SET @new_child_id = '01-D-3905'; -- Gunakan ID yang sudah ada atau generate baru

-- 2. Update semua record user dengan ID yang sama
UPDATE growth_monitoring
SET child_id = @new_child_id
WHERE users_id = [ID_USER]
  AND (child_id IS NULL OR child_id = '');

-- 3. Verifikasi
SELECT id, child_id, name, created_at
FROM growth_monitoring
WHERE users_id = [ID_USER]
ORDER BY created_at ASC;
```

### Fix 2: Unifikasi ID untuk User dengan Multiple IDs

```sql
-- Jika user punya lebih dari 1 child_id, unifikasi ke ID pertama

-- 1. Ambil ID pertama (paling lama)
SET @first_child_id = (
    SELECT child_id
    FROM growth_monitoring
    WHERE users_id = [ID_USER]
    ORDER BY created_at ASC
    LIMIT 1
);

-- 2. Update semua record dengan ID pertama
UPDATE growth_monitoring
SET child_id = @first_child_id
WHERE users_id = [ID_USER];

-- 3. Verifikasi
SELECT id, child_id, name, created_at
FROM growth_monitoring
WHERE users_id = [ID_USER]
ORDER BY created_at ASC;
```

### Fix 3: Batch Fix untuk Semua User

```sql
-- Fix untuk semua user sekaligus
-- HATI-HATI: Backup database dulu!

-- Untuk setiap user, ambil child_id pertama dan update semua record
UPDATE growth_monitoring gm1
JOIN (
    SELECT 
        users_id,
        MIN(child_id) as first_child_id
    FROM growth_monitoring
    WHERE child_id IS NOT NULL
    GROUP BY users_id
) gm2 ON gm1.users_id = gm2.users_id
SET gm1.child_id = gm2.first_child_id;

-- Verifikasi
SELECT 
    users_id,
    COUNT(DISTINCT child_id) as unique_ids,
    COUNT(*) as total_records
FROM growth_monitoring
GROUP BY users_id;
```

## Cek Log Laravel

Setelah input data, cek log di `storage/logs/laravel.log`:

```
[2025-11-14 10:00:00] Growth Monitoring Store - First Data Check
{
    "user_id": 123,
    "found": "YES",
    "child_id": "01-D-3905",
    "total_records": 2,
    "records_with_child_id": 2
}

[2025-11-14 10:00:01] Growth Monitoring Store - Using existing child_id (PERMANENT)
{
    "child_id": "01-D-3905",
    "photo": "photo1.jpg"
}
```

**Jika Log Menunjukkan "found": "NO":**
- Query tidak menemukan data lama
- Periksa apakah `users_id` benar
- Periksa apakah data lama punya `child_id`

## Testing Checklist

Setelah fix, test dengan checklist ini:

- [ ] Cek database: Semua record user punya child_id yang SAMA
- [ ] Input data baru: Modal menampilkan ID yang SAMA
- [ ] Simpan data: Notifikasi menampilkan ID yang SAMA
- [ ] Cek database lagi: Record baru punya child_id yang SAMA
- [ ] Cek log: Log menunjukkan "Using existing child_id (PERMANENT)"
- [ ] Cek grafik: Semua data muncul dalam satu grafik

## Troubleshooting

### Problem: ID Masih Berubah Setelah Fix

**Kemungkinan Penyebab:**
1. Cache browser - Clear cache dan reload
2. Session lama - Logout dan login lagi
3. Data lama tidak ter-update - Jalankan fix manual
4. Bug di controller - Cek log Laravel

**Solusi:**
1. Clear cache: `php artisan cache:clear`
2. Clear config: `php artisan config:clear`
3. Restart server
4. Logout dan login lagi
5. Test dengan user baru

### Problem: Modal Tidak Menampilkan ID

**Kemungkinan Penyebab:**
1. AJAX error - Cek browser console
2. Route tidak terdaftar - Cek `php artisan route:list`
3. JavaScript error - Cek browser console

**Solusi:**
1. Buka browser console (F12)
2. Cek Network tab untuk request `get-user-data`
3. Lihat response - harus ada `child_id`
4. Jika error, cek log Laravel

## Contact Support

Jika masih ada masalah, kirimkan:
1. Screenshot database query result
2. Screenshot log Laravel
3. Screenshot browser console
4. Screenshot modal form

---
Last Updated: 14 November 2025
