# 🌐 Cara Akses Aplikasi di Browser

## ✅ Server Status: RUNNING

Server Laravel sudah berjalan di:
```
http://localhost:8000
```

---

## 🚀 Cara Membuka di Browser:

### **Opsi 1: Buka URL Langsung**

1. **Buka browser** (Chrome, Firefox, Edge, dll)
2. **Ketik di address bar:**
   ```
   http://localhost:8000
   ```
3. **Tekan Enter**

### **Opsi 2: Dengan Bahasa Indonesia**

Untuk langsung ke bahasa Indonesia:
```
http://localhost:8000/id
```

---

## 📋 URL Penting untuk Testing:

### **1. Growth Monitoring (dengan Foto & ID)**
```
http://localhost:8000/id/growth-monitoring
```

**Cara Test:**
1. Buka URL di atas
2. Klik tombol **"Add"** atau **"Tambah"**
3. Modal akan muncul dengan:
   - 📋 **Identitas Anak** (card abu-abu)
   - 🏥 **ID Pengenal** (input text)
   - 📸 **Foto Anak** (file upload)
4. Upload foto (max 2MB, JPG/PNG)
5. Preview foto akan muncul
6. Isi data lainnya
7. Klik **"Save"**
8. Foto dan ID akan muncul di halaman index

### **2. Stunting Detection (dengan Foto & Medical ID)**
```
http://localhost:8000/id/growth-detection/stunting/create
```

**Cara Test:**
1. Buka URL di atas
2. Lihat card **"📋 Identitas Anak"** di atas form
3. Upload foto anak (opsional)
4. Input Medical ID (opsional)
5. Pilih tanggal lahir (usia auto-calculate)
6. Isi data lainnya
7. Submit form

### **3. Dashboard**
```
http://localhost:8000/id
```

### **4. Login (jika belum login)**
```
http://localhost:8000/id/login
```

---

## 🔐 Login Credentials

Jika diminta login, gunakan:
```
Email: admin@example.com
Password: password
```

Atau buat akun baru di:
```
http://localhost:8000/id/signup
```

---

## 📸 Yang Harus Terlihat:

### **Growth Monitoring - Modal Form:**
```
┌──────────────────────────────────────┐
│ Add Child Data                   [X] │
├──────────────────────────────────────┤
│ ┌────────────────────────────────┐  │
│ │ 📋 Identitas Anak              │  │ ← Card abu-abu
│ ├────────────────────────────────┤  │
│ │ 🏥 ID Pengenal (Opsional)      │  │
│ │ [GM-2025-001]                  │  │
│ │                                │  │
│ │ 📸 Foto Anak (Opsional)        │  │
│ │ [Choose File]                  │  │
│ │ Format: JPG, PNG (Max 2MB)     │  │
│ │                                │  │
│ │ [Preview Foto]  [Hapus]        │  │ ← Muncul setelah upload
│ └────────────────────────────────┘  │
│                                      │
│ Full Name * [...]                    │
│ Age (month) * [...]                  │
│ ... (form lainnya)                   │
└──────────────────────────────────────┘
```

### **Growth Monitoring - Index:**
```
┌──────────────────────────────────────┐
│ Growth Monitoring for Stunting       │
├──────────────────────────────────────┤
│ ┌────────────────────────────────┐  │
│ │  [👶]  John Doe      [Change]  │  │ ← Foto circular
│ │  📷    🏥 ID: GM-2025-001      │  │ ← ID muncul
│ └────────────────────────────────┘  │
└──────────────────────────────────────┘
```

### **Stunting Form:**
```
┌──────────────────────────────────────┐
│ Stunting Detection                   │
├──────────────────────────────────────┤
│ ┌────────────────────────────────┐  │
│ │ 📋 Identitas Anak              │  │ ← Card baru
│ ├────────────────────────────────┤  │
│ │ 📸 Foto Anak (Opsional)        │  │
│ │ [Choose File]                  │  │
│ │                                │  │
│ │ 🏥 ID Rekam Medis (Opsional)   │  │
│ │ [RM-2025-001]                  │  │
│ │                                │  │
│ │ 🎂 Tanggal Lahir *             │  │
│ │ [Date Picker]                  │  │
│ │ Usia akan dihitung otomatis    │  │
│ └────────────────────────────────┘  │
│                                      │
│ Nama anak * [...]                    │
│ Usia (bulan) * [12] (readonly)       │ ← Auto-filled
│ ... (form lainnya)                   │
└──────────────────────────────────────┘
```

---

## 🧪 Testing Checklist:

### **Growth Monitoring:**
- [ ] Buka `http://localhost:8000/id/growth-monitoring`
- [ ] Klik tombol "Add"
- [ ] Card "📋 Identitas Anak" muncul
- [ ] Input ID Pengenal berfungsi
- [ ] Upload foto berfungsi
- [ ] Preview foto muncul
- [ ] Tombol "Hapus" berfungsi
- [ ] Submit form berhasil
- [ ] Foto muncul di index (circular)
- [ ] ID muncul di bawah nama

### **Stunting Detection:**
- [ ] Buka `http://localhost:8000/id/growth-detection/stunting/create`
- [ ] Card "📋 Identitas Anak" muncul di atas
- [ ] Upload foto berfungsi
- [ ] Input Medical ID berfungsi
- [ ] Pilih tanggal lahir → usia terisi otomatis
- [ ] Submit form berhasil

---

## 🔧 Troubleshooting:

### **1. Halaman Tidak Muncul**
```bash
# Cek apakah server berjalan
netstat -ano | findstr :8000

# Jika tidak ada output, start server:
php artisan serve
```

### **2. Error 404**
```bash
# Clear cache
php artisan optimize:clear

# Restart server
# Ctrl+C (stop server)
php artisan serve
```

### **3. Form Lama Masih Muncul**
```
# Hard refresh browser
Windows: Ctrl + Shift + R
Mac: Cmd + Shift + R

# Atau buka Incognito Mode
Ctrl + Shift + N (Chrome/Edge)
Ctrl + Shift + P (Firefox)
```

### **4. Foto Tidak Bisa Upload**
```bash
# Pastikan folder ada
mkdir public\uploads\growth-monitoring
mkdir public\uploads\stunting

# Set permissions (jika di Linux/Mac)
chmod 755 public/uploads/growth-monitoring
chmod 755 public/uploads/stunting
```

### **5. Preview Foto Tidak Muncul**
```
# Buka Developer Tools (F12)
# Lihat Console untuk error JavaScript
# Lihat Network tab untuk file yang gagal load
```

---

## 📱 Browser yang Disarankan:

- ✅ **Google Chrome** (Recommended)
- ✅ **Microsoft Edge**
- ✅ **Mozilla Firefox**
- ✅ **Safari** (Mac)

---

## 🎯 Quick Start:

**Langkah Cepat:**
1. Buka browser
2. Ketik: `http://localhost:8000/id/growth-monitoring`
3. Login jika diminta
4. Klik tombol "Add"
5. Upload foto dan input ID
6. Test fitur!

---

## 📞 Jika Ada Masalah:

1. **Cek server berjalan:**
   ```bash
   netstat -ano | findstr :8000
   ```

2. **Cek log Laravel:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Cek console browser:**
   - Tekan F12
   - Lihat tab Console
   - Lihat tab Network

4. **Clear semua cache:**
   ```bash
   php artisan optimize:clear
   ```

5. **Restart server:**
   ```bash
   # Ctrl+C untuk stop
   php artisan serve
   ```

---

## ✅ Server Info:

- **Status:** ✅ RUNNING
- **URL:** http://localhost:8000
- **Port:** 8000
- **Process ID:** 18764

---

**Selamat mencoba! 🚀**

Jika ada pertanyaan atau masalah, screenshot error dan kirim untuk debugging.
