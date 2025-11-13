# 🧪 Testing Form Stunting dengan Foto & Medical ID

## ✅ Checklist Perubahan yang Sudah Ada:

### 1. **HTML Form** ✅
- [x] Form tag sudah ada `enctype="multipart/form-data"`
- [x] Card "Identitas Anak" sudah ditambahkan
- [x] Input foto dengan accept image
- [x] Input medical_id
- [x] Input tanggal_lahir
- [x] Preview foto container
- [x] Tombol hapus foto

### 2. **JavaScript** ✅
- [x] Photo preview function
- [x] Remove photo function
- [x] Auto-calculate age from birth date
- [x] Validation untuk file size (2MB)
- [x] Validation untuk file type (JPG, PNG)

### 3. **Database** ✅
- [x] Migration sudah dijalankan
- [x] Kolom `medical_id`, `photo`, `tanggal_lahir` sudah ada

## 🔍 Troubleshooting

### Jika Form Masih Terlihat Lama:

#### 1. **Hard Refresh Browser**
```
Windows: Ctrl + Shift + R atau Ctrl + F5
Mac: Cmd + Shift + R
```

#### 2. **Clear Browser Cache**
- Chrome: Settings → Privacy → Clear browsing data
- Firefox: Options → Privacy → Clear Data
- Edge: Settings → Privacy → Clear browsing data

#### 3. **Incognito/Private Mode**
Buka browser dalam mode incognito untuk memastikan tidak ada cache

#### 4. **Check Console untuk Error**
- Buka Developer Tools (F12)
- Lihat tab Console
- Lihat tab Network untuk memastikan file CSS/JS ter-load

### Jika Masih Tidak Muncul:

#### Cek File Secara Manual:

```bash
# Cek apakah perubahan ada di file
cat resources/views/monitoring/growth-detection/stunting/form.blade.php | grep "Identitas Anak"

# Atau buka file langsung di editor
```

#### Pastikan Route Benar:

```bash
php artisan route:list --name=stunting
```

Output yang diharapkan:
```
GET|HEAD  {locale}/growth-detection/stunting/create
POST      {locale}/growth-detection/stunting
GET|HEAD  {locale}/growth-detection/stunting/result/{id}
```

## 📸 Cara Test Form:

### Step 1: Akses Form
```
http://localhost/id/growth-detection/stunting/create
```

### Step 2: Cek Tampilan
Anda harus melihat:
```
┌─────────────────────────────────────┐
│ 📋 Identitas Anak                   │
├─────────────────────────────────────┤
│ 📸 Foto Anak (Opsional)             │
│ [Choose File] No file chosen        │
│ Format: JPG, PNG (Maksimal 2MB)     │
│                                     │
│ 🏥 ID Rekam Medis (Opsional)        │
│ [RM-2025-001]                       │
│ ID untuk sistem internal (opsional) │
│                                     │
│ 🎂 Tanggal Lahir *                  │
│ [Date Picker]                       │
│ Usia akan dihitung otomatis         │
└─────────────────────────────────────┘
```

### Step 3: Test Upload Foto
1. Klik "Choose File"
2. Pilih foto (JPG/PNG, < 2MB)
3. Preview foto harus muncul
4. Tombol "Hapus Foto" harus muncul

### Step 4: Test Medical ID
1. Ketik ID: `RM-2025-001`
2. Field harus menerima input

### Step 5: Test Tanggal Lahir
1. Pilih tanggal lahir (misal: 01/01/2023)
2. Field "Usia" harus terisi otomatis (misal: 22 bulan)
3. Jika tanggal > hari ini, harus ada alert

### Step 6: Submit Form
1. Isi semua field required
2. Klik "Simpan & Analisis"
3. Data harus tersimpan dengan foto

## 🐛 Debug Mode

### Tambahkan di form untuk debug:

```html
<!-- Tambahkan sebelum </form> -->
<div class="alert alert-info">
    <strong>Debug Info:</strong><br>
    Form enctype: {{ request()->header('Content-Type') }}<br>
    Has file: {{ request()->hasFile('photo') ? 'Yes' : 'No' }}
</div>
```

### Check di Controller:

```php
public function store(Request $request)
{
    // Debug
    \Log::info('Form Data:', $request->all());
    \Log::info('Has Photo:', ['has' => $request->hasFile('photo')]);
    
    if ($request->hasFile('photo')) {
        \Log::info('Photo Info:', [
            'name' => $request->file('photo')->getClientOriginalName(),
            'size' => $request->file('photo')->getSize(),
            'mime' => $request->file('photo')->getMimeType(),
        ]);
    }
    
    // ... rest of code
}
```

## 📝 Verification Checklist

Setelah refresh, pastikan:

- [ ] Card "📋 Identitas Anak" muncul di atas form
- [ ] Ada input file untuk foto
- [ ] Ada input text untuk Medical ID
- [ ] Ada input date untuk Tanggal Lahir
- [ ] Field Usia ada dan readonly
- [ ] Saat pilih foto, preview muncul
- [ ] Saat pilih tanggal lahir, usia terisi otomatis
- [ ] Tombol "Hapus Foto" berfungsi
- [ ] Validasi file size berfungsi (alert jika > 2MB)
- [ ] Validasi file type berfungsi (alert jika bukan JPG/PNG)

## 🔧 Quick Fix Commands

```bash
# Clear all cache
php artisan optimize:clear

# Clear specific cache
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Restart server (jika pakai artisan serve)
# Ctrl+C lalu
php artisan serve
```

## 📱 Test di Browser Berbeda

Jika masih tidak muncul di satu browser, coba:
- Chrome
- Firefox
- Edge
- Safari (Mac)

## 🎯 Expected Result

Setelah semua langkah di atas, form harus terlihat seperti ini:

```
┌──────────────────────────────────────────────┐
│ Stunting - Growth Detection & Risk Prediction│
├──────────────────────────────────────────────┤
│                                              │
│ ┌──────────────────────────────────────────┐│
│ │ 📋 Identitas Anak                        ││
│ ├──────────────────────────────────────────┤│
│ │ 📸 Foto Anak (Opsional)                  ││
│ │ [Choose File] No file chosen             ││
│ │ Format: JPG, PNG (Maksimal 2MB)          ││
│ │                                          ││
│ │ [Preview akan muncul di sini]            ││
│ │                                          ││
│ │ 🏥 ID Rekam Medis (Opsional)             ││
│ │ [RM-2025-001]                            ││
│ │ ID untuk sistem internal (opsional)      ││
│ │                                          ││
│ │ 🎂 Tanggal Lahir *                       ││
│ │ [📅 01/01/2023]                          ││
│ │ Usia akan dihitung otomatis              ││
│ └──────────────────────────────────────────┘│
│                                              │
│ Nama anak *          Usia (bulan) *         │
│ [Ahmad]              [22] (readonly)        │
│                                              │
│ ... (rest of form)                           │
│                                              │
│ [Simpan & Analisis]                          │
└──────────────────────────────────────────────┘
```

## ✅ Success Indicators

Form berhasil jika:
1. ✅ Card "Identitas Anak" muncul di atas
2. ✅ Bisa upload foto dan lihat preview
3. ✅ Bisa input Medical ID
4. ✅ Pilih tanggal lahir → usia terisi otomatis
5. ✅ Submit form → data tersimpan dengan foto

---

**Jika masih tidak muncul setelah semua langkah di atas, screenshot error dan kirim untuk debugging lebih lanjut.**
