# 🔍 DEBUG: Growth Monitoring Save Error

## 🎯 LANGKAH DEBUGGING

### **1. Cek Browser Console**
1. Buka halaman Growth Monitoring
2. Tekan F12 untuk buka Developer Tools
3. Klik tab "Console"
4. Submit form
5. Lihat error message yang muncul

**Yang Harus Dicari:**
```javascript
AJAX Error: {
    status: "error",
    message: "...",
    debug: {...}
}
```

---

### **2. Cek Laravel Log**
```bash
# Windows
type storage\logs\laravel.log | findstr "Growth Monitoring"

# Atau buka file langsung
storage/logs/laravel.log
```

**Cari Log Entries:**
```
[timestamp] local.INFO: Growth Monitoring Store - Start
[timestamp] local.INFO: Growth Monitoring Store - Validation passed
[timestamp] local.INFO: Growth Monitoring Store - Growth created
[timestamp] local.INFO: Growth Monitoring Store - LHFA completed
[timestamp] local.INFO: Growth Monitoring Store - WFA completed
[timestamp] local.INFO: Growth Monitoring Store - Success
```

**Jika Ada Error:**
```
[timestamp] local.ERROR: Growth Monitoring Store - Exception
```

---

### **3. Test Manual**

**A. Test Route:**
```bash
php artisan route:list | grep growth-monitoring.show
```

**Expected Output:**
```
GET|HEAD  {locale}/growth-monitoring/{id}  growth-monitoring.show
```

**B. Test Encryption:**
```php
// Di tinker
php artisan tinker

>>> encrypt(1)
=> "eyJpdiI6..."

>>> decrypt(encrypt(1))
=> 1
```

**C. Test locale_route:**
```php
// Di tinker
php artisan tinker

>>> locale_route('growth-monitoring.show', encrypt(1))
=> "http://localhost:8080/id/growth-monitoring/eyJpdiI6..."
```

---

## 🔧 PERBAIKAN YANG SUDAH DITERAPKAN

### **1. Enhanced Logging**
```php
\Log::info('Growth Monitoring Store - Start', $request->all());
\Log::info('Growth Monitoring Store - Validation passed');
\Log::info('Growth Monitoring Store - Growth created', ['id' => $growth->id]);
\Log::info('Growth Monitoring Store - LHFA completed');
\Log::info('Growth Monitoring Store - WFA completed');
\Log::info('Growth Monitoring Store - Success', ['redirect' => $redirectUrl]);
```

### **2. Detailed Error Response**
```php
catch (\Exception $e) {
    \Log::error('Growth Monitoring Store - Exception', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    
    return response()->json([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
        'debug' => config('app.debug') ? [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ] : null,
    ], 500);
}
```

### **3. Fallback Redirect URL**
```php
try {
    $redirectUrl = locale_route('growth-monitoring.show', encrypt($growth->id));
} catch (\Exception $e) {
    \Log::error('Error generating redirect URL: ' . $e->getMessage());
    $redirectUrl = locale_route('growth-monitoring.index');
}
```

### **4. Enhanced AJAX Error Handler**
```javascript
error: function(xhr, status, error) {
    console.error('AJAX Error:', xhr.responseJSON);
    
    if (xhr.status === 422) {
        // Validation errors
        let errors = xhr.responseJSON.errors;
        for (let field in errors) {
            $.notify(errors[field][0], "error");
        }
    } else if (xhr.status === 500) {
        let errorMsg = xhr.responseJSON?.message || "Terjadi kesalahan server.";
        $.notify(errorMsg, "error");
        
        // Show debug info if available
        if (xhr.responseJSON?.debug) {
            console.error('Debug Info:', xhr.responseJSON.debug);
        }
    }
}
```

---

## 🧪 TESTING STEPS

### **Step 1: Clear Cache**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### **Step 2: Check Database**
```sql
-- Cek apakah z_scores table ada data
SELECT COUNT(*) FROM z_scores;

-- Cek data untuk usia tertentu
SELECT * FROM z_scores WHERE month = 12 AND gender = 'L';
```

### **Step 3: Test Save**
1. Buka Growth Monitoring
2. Klik "Add" button
3. Isi form:
   - Name: Test
   - Age: 12 (bulan)
   - Gender: Male
   - Height: 75 (cm)
   - Weight: 10 (kg)
4. Klik "Save"
5. Lihat:
   - Browser console (F12)
   - Network tab (lihat response)
   - Laravel log

### **Step 4: Check Result**
```sql
-- Cek apakah data tersimpan
SELECT * FROM growth_monitoring ORDER BY id DESC LIMIT 1;

-- Cek history
SELECT * FROM growth_monitoring_history 
WHERE id_growth = (SELECT MAX(id) FROM growth_monitoring);
```

---

## 🔍 KEMUNGKINAN PENYEBAB ERROR

### **1. Z-Score Data Tidak Ada**
**Gejala:** Error "Attempt to read property L on null"

**Solusi:** Sudah ditambahkan null check di lhfa() dan wfa()

**Verify:**
```sql
SELECT COUNT(*) FROM z_scores;
-- Harus > 0
```

### **2. Encryption Error**
**Gejala:** Error saat encrypt($growth->id)

**Solusi:** Pastikan APP_KEY di .env sudah di-set

**Verify:**
```bash
php artisan key:generate
```

### **3. Route Not Found**
**Gejala:** Error "Route [growth-monitoring.show] not defined"

**Solusi:** Clear route cache

**Verify:**
```bash
php artisan route:clear
php artisan route:list | grep growth-monitoring
```

### **4. Locale Helper Error**
**Gejala:** Error di locale_route() function

**Solusi:** Cek app/Helpers.php

**Verify:**
```php
// Di tinker
>>> locale_route('growth-monitoring.index')
```

### **5. Database Connection**
**Gejala:** Error saat save data

**Solusi:** Cek database connection

**Verify:**
```bash
php artisan migrate:status
```

---

## 📊 EXPECTED FLOW

### **Success Flow:**
```
1. User submit form
   ↓
2. AJAX POST to /growth-monitoring/store
   ↓
3. Validation passed ✅
   ↓
4. Create growth record ✅
   ↓
5. Call lhfa() → Create history[0] ✅
   ↓
6. Call wfa() → Create history[1] ✅
   ↓
7. Generate redirect URL ✅
   ↓
8. Return JSON response ✅
   ↓
9. AJAX success handler ✅
   ↓
10. Show success notification ✅
    ↓
11. Redirect to show page ✅
```

### **Error Flow (Current Issue):**
```
1. User submit form
   ↓
2. AJAX POST to /growth-monitoring/store
   ↓
3. ??? (Something fails here)
   ↓
4. Return error response ❌
   ↓
5. AJAX error handler ❌
   ↓
6. Show "An error occurred" ❌
```

---

## 💡 NEXT STEPS

### **Untuk User:**
1. Submit form sekali lagi
2. Buka browser console (F12)
3. Screenshot error message
4. Share screenshot atau copy error message

### **Untuk Developer:**
1. Check `storage/logs/laravel.log`
2. Cari log entry "Growth Monitoring Store"
3. Lihat error message lengkap
4. Share log entry

### **Quick Fix (Temporary):**
Jika masih error, gunakan redirect langsung tanpa encrypt:

```php
// Di GrowthMonitoringController.php
return response()->json([
    'status' => 'success',
    'message' => 'Hasil Z-Score berhasil disimpan',
    'redirect' => locale_route('growth-monitoring.index'), // Redirect ke index
]);
```

---

## 📝 CHECKLIST

- [ ] Browser console checked
- [ ] Laravel log checked
- [ ] Database has z_scores data
- [ ] APP_KEY is set
- [ ] Routes are cached
- [ ] Cache cleared
- [ ] Test with simple data (age 12, gender L)

---

**Silakan test sekali lagi dan share:**
1. Screenshot browser console (F12)
2. Log entry dari storage/logs/laravel.log
3. Error message yang muncul

Dengan informasi ini saya bisa identify exact problem! 🔍
