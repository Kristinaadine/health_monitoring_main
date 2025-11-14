# Perubahan ID User dan Foto - Growth Monitoring & Stunting Detection

## Ringkasan Perubahan

Perubahan ini mengoptimalkan pengalaman pengguna dengan membuat:
1. **ID User PERMANEN per user** - Sekali dibuat, tidak akan berubah selamanya untuk user tersebut
2. **Foto dapat diedit** - Untuk input kedua dan seterusnya, user dapat melihat dan mengubah foto jika diperlukan

## Perubahan yang Dilakukan

### 1. Growth Monitoring (Pemantauan Pertumbuhan)

#### A. Perubahan pada Form Modal (`modalform.blade.php`)
- **Sebelum**: User harus input ID dan foto setiap kali menambah data
- **Sesudah**: 
  - **ID User ditampilkan** dengan tombol copy (readonly, tidak bisa diubah)
  - **Foto untuk data pertama**: Input foto biasa (opsional)
  - **Foto untuk data kedua dst**: Tampilkan foto yang sudah ada + tombol "Ubah Foto"
  - User dapat mengubah foto jika diperlukan dengan klik tombol "Ubah Foto"

#### B. Perubahan pada Controller (`GrowthMonitoringController.php`)
- **Method `store()`**:
  - Cek apakah user sudah pernah input data (berdasarkan `users_id`)
  - **Jika sudah pernah input**: 
    - Gunakan `child_id` yang SAMA (PERMANEN)
    - Jika upload foto baru: update foto, hapus foto lama
    - Jika tidak upload foto: gunakan foto lama
  - **Jika belum pernah input**: 
    - Generate `child_id` baru dengan format `01-A-XXXX` (PERMANEN untuk user ini)
    - Upload foto jika ada
  
- **Method baru `getUserData()`**:
  - Endpoint untuk mendapatkan data user (child_id dan photo)
  - Mengambil data PERTAMA user (paling lama) untuk memastikan ID yang sama
  - Return: has_data, child_id, photo, photo_url

#### C. Perubahan pada Routes (`web.php`)
- Menambah route baru: `GET /growth-monitoring/get-user-data`
- Route ini digunakan untuk mengambil data user yang sudah ada

### 2. Stunting Detection (Deteksi Stunting)

#### A. Perubahan pada Form (`form.blade.php`)
- **Sebelum**: User harus input foto dan medical ID setiap kali
- **Sesudah**:
  - Input foto dihilangkan dari form
  - Medical ID disembunyikan (hidden field)
  - Hanya menampilkan tanggal lahir dengan pesan informasi
  - JavaScript untuk preview foto dihapus

#### B. Perubahan pada Controller (`StuntingGrowthController.php`)
- **Method `store()`**:
  - Menghapus logika upload foto
  - Menambah logika untuk cek data anak yang sudah ada
  - Jika anak sudah pernah didata: gunakan `medical_id` dan `photo` yang sama
  - Jika anak baru: generate `medical_id` baru dengan format `RM-YYYY-XXX`

## Alur Kerja Baru

### Skenario 1: User Pertama Kali Input Data
1. User membuka form "Tambah Data Anak"
2. Sistem generate ID unik PERMANEN:
   - Growth Monitoring: `child_id` (format: 01-A-1234)
   - Stunting: `medical_id` (format: RM-2025-001)
3. User mengisi form lengkap:
   - Nama anak
   - Usia
   - Jenis kelamin
   - Tinggi badan
   - Berat badan
   - Foto (opsional)
4. Data disimpan dengan ID PERMANEN dan foto

### Skenario 2: User Input Data Kesehatan Lagi (Progress Kedua, Ketiga, dst)
1. User membuka form "Tambah Data Anak"
2. Sistem otomatis:
   - Menampilkan ID yang SAMA (01-D-3905) - TIDAK BERUBAH
   - Menampilkan foto yang sudah ada dengan tombol "Ubah Foto"
3. User mengisi data kesehatan terbaru:
   - Nama anak (bisa sama atau berbeda)
   - Usia (update)
   - Jenis kelamin
   - Tinggi badan (update)
   - Berat badan (update)
   - Foto: bisa diubah dengan klik "Ubah Foto" atau biarkan foto lama
4. Data disimpan dengan ID yang SAMA, foto bisa diupdate atau tetap sama

### Poin Penting:
- **ID User (child_id) PERMANEN per user**, bukan per anak
- Satu user = satu ID selamanya
- ID format: `01-D-3905` tidak akan berubah meskipun input data berkali-kali
- Foto dapat diubah kapan saja dengan klik tombol "Ubah Foto"

## Keuntungan Perubahan

1. **User Experience Lebih Baik**:
   - User tidak perlu input ulang foto setiap kali
   - Form lebih sederhana dan cepat diisi
   - Fokus pada data kesehatan yang penting

2. **Konsistensi Data**:
   - ID User tidak berubah-ubah
   - Mudah tracking riwayat pertumbuhan anak
   - Foto anak tetap konsisten

3. **Efisiensi Storage**:
   - Tidak ada duplikasi foto
   - Hemat ruang penyimpanan

4. **Tracking Lebih Mudah**:
   - Satu anak = satu ID
   - Mudah membuat laporan dan grafik pertumbuhan
   - Riwayat data lebih terorganisir

## File yang Diubah

1. `app/Http/Controllers/Monitoring/GrowthMonitoringController.php`
2. `app/Http/Controllers/Monitoring/StuntingGrowthController.php`
3. `resources/views/monitoring/growth-monitoring/modalform.blade.php`
4. `resources/views/monitoring/growth-detection/stunting/form.blade.php`
5. `routes/web.php`

## Testing yang Disarankan

1. **Test Input Data Pertama Kali**:
   - Pastikan ID ter-generate dengan benar
   - Pastikan foto tersimpan (jika diupload)

2. **Test Input Data Kedua dan Seterusnya**:
   - Pastikan ID tidak berubah
   - Pastikan foto tetap sama dengan data pertama
   - Pastikan data kesehatan tersimpan dengan benar

3. **Test Multiple Children**:
   - Pastikan setiap anak punya ID unik
   - Pastikan tidak ada konflik ID

## Catatan Penting

- **ID User (child_id) PERMANEN per user**, bukan per anak
- Satu user hanya punya satu ID selamanya (contoh: 01-D-3905)
- ID tidak akan berubah meskipun user input data berkali-kali
- Foto dapat diubah kapan saja melalui tombol "Ubah Foto" di form
- Sistem mengambil data PERTAMA user untuk memastikan ID yang konsisten
- Nama anak bisa berbeda-beda (untuk tracking anak yang berbeda), tapi ID tetap sama per user

## Tanggal Perubahan
14 November 2025
