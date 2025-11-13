# 🛡️ ERROR HANDLING & VALIDATION IMPLEMENTATION

## 📋 OVERVIEW

Implementasi error handling dan validation yang komprehensif di seluruh aplikasi untuk memastikan:
- ✅ Input data valid sebelum diproses
- ✅ Error message yang jelas dan informatif
- ✅ User-friendly notification
- ✅ Logging untuk debugging
- ✅ Graceful error recovery

---

## 🎯 KOMPONEN YANG DITAMBAHKAN

### **1. Alert Component** ✅
**File:** `resources/views/components/alert.blade.php`

Komponen universal untuk menampilkan pesan:
- ✅ Success (hijau)
- ✅ Error (merah)
- ✅ Warning (kuning)
- ✅ Info (biru)
- ✅ Validation errors (list)

**Usage:**
```blade
@include('components.alert')
```

**Features:**
- Auto-dismissible dengan tombol close
- Icon yang sesuai dengan tipe pesan
- Support multiple error messages
- Bootstrap 5 styling

---

## 🔧 CONTROLLERS YANG DIPERBAIKI

### **1. StuntingGrowthController** ✅

**Method:** `store()`

**Error Handling:**
```php
try {
    $data = $request->validated(); // ✅ Validasi otomatis
    
    // Cek Z-Score data availability
    if ($haz === 0 && $whz === 0) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Data Z-Score tidak ditemukan...');
    }
    
    // Process & save data
    
} catch (\Exception $e) {
    \Log::error('Error storing stunting data: ' . $e->getMessage());
    return redirect()->back()
        ->withInput()
        ->with('error', 'Terjadi kesalahan...');
}
```

**Validations:**
- ✅ Nama: required, string, max 120 karakter
- ✅ Usia: required, integer, 0-60 bulan
- ✅ Jenis Kelamin: required, L atau P
- ✅ Berat Badan: required, numeric, 1-40 kg
- ✅ Tinggi Badan: required, numeric, 40-130 cm
- ✅ Lingkar Lengan: optional, numeric, 5-25 cm
- ✅ Dan 20+ validasi lainnya

---

### **2. GrowthMonitoringController** ✅

**Method:** `store()`

**Error Handling:**
```php
try {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'age' => 'required|integer|min:0|max:60',
        'gender' => 'required|in:L,P',
        'height' => 'required|numeric|min:40|max:130',
        'weight' => 'required|numeric|min:1|max:40',
    ], [
        'name.required' => 'Nama wajib diisi.',
        // ... custom messages
    ]);
    
    // Process data
    
} catch (\Illuminate\Validation\ValidationException $e) {
    return response()->json([
        'status' => 'error',
        'message' => 'Data tidak valid',
        'errors' => $e->errors(),
    ], 422);
} catch (\Exception $e) {
    \Log::error('Error: ' . $e->getMessage());
    return response()->json([
        'status' => 'error',
        'message' => 'Terjadi kesalahan...',
    ], 500);
}
```

**Validations:**
- ✅ Name: required, string, max 255
- ✅ Age: required, integer, 0-60 bulan
- ✅ Gender: required, L atau P
- ✅ Height: required, numeric, 40-130 cm
- ✅ Weight: required, numeric, 1-40 kg

---

### **3. DietUserController** ✅

**Method:** `store()`

**Error Handling:**
```php
try {
    $data = $request->validated();
    
    // Calculate BMI & status
    // Save data
    
    return redirect()->to(locale_route(...))
        ->with('success', 'Data diet berhasil disimpan...');
        
} catch (\Exception $e) {
    \Log::error('Error storing diet user: ' . $e->getMessage());
    return redirect()->back()
        ->withInput()
        ->with('error', 'Terjadi kesalahan...');
}
```

**Validations (DietRequest):**
- ✅ Nama: required, string
- ✅ Usia: required, integer, min 1
- ✅ Jenis Kelamin: required, L atau P
- ✅ Berat Badan: required, numeric, min 1
- ✅ Tinggi Badan: required, numeric, min 1
- ✅ Frekuensi Sayur: required, integer, 1-5
- ✅ Konsumsi Protein: required, integer, 1-5
- ✅ Konsumsi Karbo: required, integer, 1-5
- ✅ Konsumsi Gula: required, integer, 1-5
- ✅ Frekuensi Jajan: required, integer, min 0

---

### **4. MealPlannerController** ✅

**Method:** `updateNutrition()`

**Error Handling:**
```php
try {
    $validated = $request->validated();
    
    // Update user nutrition
    
    return response()->json([
        'status' => 'success',
        'message' => 'Nutrition updated successfully.',
    ]);
    
} catch (\Illuminate\Validation\ValidationException $e) {
    return response()->json([
        'status' => 'error',
        'message' => 'Data tidak valid',
        'errors' => $e->errors(),
    ], 422);
} catch (\Exception $e) {
    \Log::error('Error: ' . $e->getMessage());
    return response()->json([
        'status' => 'error',
        'message' => 'Terjadi kesalahan...',
    ], 500);
}
```

**Method:** `getMeal()`

**Additional Validation:**
```php
// Validasi user sudah set nutrition target
if (!auth()->user()->calorie_target || !auth()->user()->nutrient_ration) {
    return response()->json([
        'status' => 'error',
        'message' => 'Please set your nutrition target first...',
    ], 400);
}

// Cek apakah ada data food
$foodCount = FoodModel::count();
if ($foodCount == 0) {
    return response()->json([
        'status' => 'error',
        'message' => 'No food data available...',
    ], 404);
}
```

**Validations (UpdateNutritionRequest):**
- ✅ Calorie Target: required, numeric, 500-5000 kcal
- ✅ Nutrient Ration: required, exists in nutrient_ratios table

---

### **5. FoodAdminController** ✅

**Method:** `store()`

**Error Handling:**
```php
try {
    $validated = $request->validate([
        'name_food' => 'required|string|max:255',
        'id_categories' => 'required|exists:food_categories,id',
        'calories' => 'required|numeric|min:0',
        'protein' => 'required|numeric|min:0',
        'carbs' => 'required|numeric|min:0',
        'fiber' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        // Custom error messages
    ]);
    
    // Process & save
    
} catch (\Illuminate\Validation\ValidationException $e) {
    return response()->json([
        'status' => 'error',
        'message' => 'Data tidak valid',
        'errors' => $e->errors(),
    ], 422);
} catch (\Exception $e) {
    \Log::error('Error: ' . $e->getMessage());
    return response()->json([
        'status' => 'error',
        'message' => 'Terjadi kesalahan...',
    ], 500);
}
```

**Validations:**
- ✅ Name Food: required, string, max 255
- ✅ Category: required, exists in food_categories
- ✅ Calories: required, numeric, min 0
- ✅ Protein: required, numeric, min 0
- ✅ Carbs: required, numeric, min 0
- ✅ Fiber: required, numeric, min 0
- ✅ Image: optional, image, jpeg/png/jpg/gif, max 2MB

---

## 📝 VALIDATION RULES SUMMARY

### **Growth Detection - Stunting**
| Field | Rules | Error Message |
|-------|-------|---------------|
| nama | required, string, max:120 | Nama wajib diisi |
| usia | required, integer, 0-60 | Usia wajib diisi, 0-60 bulan |
| jenis_kelamin | required, in:L,P | Jenis kelamin wajib dipilih |
| berat_badan | required, numeric, 1-40 | Berat badan wajib diisi, 1-40 kg |
| tinggi_badan | required, numeric, 40-130 | Tinggi badan wajib diisi, 40-130 cm |
| lingkar_lengan | nullable, numeric, 5-25 | Lingkar lengan 5-25 cm |

### **Growth Monitoring**
| Field | Rules | Error Message |
|-------|-------|---------------|
| name | required, string, max:255 | Nama wajib diisi |
| age | required, integer, 0-60 | Usia wajib diisi, 0-60 bulan |
| gender | required, in:L,P | Jenis kelamin wajib dipilih |
| height | required, numeric, 40-130 | Tinggi badan wajib diisi, 40-130 cm |
| weight | required, numeric, 1-40 | Berat badan wajib diisi, 1-40 kg |

### **Diet User**
| Field | Rules | Error Message |
|-------|-------|---------------|
| nama | required, string | Nama wajib diisi |
| usia | required, integer, min:1 | Usia wajib diisi, minimal 1 tahun |
| jenis_kelamin | required, in:L,P | Jenis kelamin wajib dipilih |
| berat_badan | required, numeric, min:1 | Berat badan wajib diisi |
| tinggi_badan | required, numeric, min:1 | Tinggi badan wajib diisi |
| frekuensi_sayur | required, integer, 1-5 | Frekuensi sayur wajib diisi, 1-5 |

### **Meal Planner - Nutrition**
| Field | Rules | Error Message |
|-------|-------|---------------|
| calorie_target | required, numeric, 500-5000 | Target kalori wajib diisi, 500-5000 kcal |
| nutrient_ration | required, exists | Rasio nutrisi wajib dipilih |

### **Admin - Food**
| Field | Rules | Error Message |
|-------|-------|---------------|
| name_food | required, string, max:255 | Nama makanan wajib diisi |
| id_categories | required, exists | Kategori wajib dipilih |
| calories | required, numeric, min:0 | Kalori wajib diisi |
| protein | required, numeric, min:0 | Protein wajib diisi |
| carbs | required, numeric, min:0 | Karbohidrat wajib diisi |
| fiber | required, numeric, min:0 | Serat wajib diisi |
| image | nullable, image, max:2048 | Gambar max 2MB |

---

## 🎨 ERROR DISPLAY PATTERNS

### **1. Form Validation Errors (Blade)**
```blade
<input type="text" name="nama" 
    class="form-control @error('nama') is-invalid @enderror"
    value="{{ old('nama') }}">
@error('nama')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
```

### **2. Session Flash Messages (Blade)**
```blade
@include('components.alert')
```

### **3. AJAX Response Errors (JavaScript)**
```javascript
$.ajax({
    // ...
    success: function(response) {
        if (response.status == 'success') {
            $.notify(response.message, "success");
        } else {
            $.notify(response.message, "error");
        }
    },
    error: function(xhr) {
        if (xhr.status === 422) {
            // Validation errors
            let errors = xhr.responseJSON.errors;
            for (let field in errors) {
                $.notify(errors[field][0], "error");
            }
        } else {
            $.notify("Terjadi kesalahan. Silakan coba lagi.", "error");
        }
    }
});
```

---

## 🧪 TESTING CHECKLIST

### **Test Validation:**
- [ ] Submit form kosong → Error "wajib diisi"
- [ ] Input angka di luar range → Error "minimal/maksimal"
- [ ] Input format salah → Error "harus berupa angka/teks"
- [ ] Upload file > 2MB → Error "maksimal 2MB"
- [ ] Upload file bukan gambar → Error "harus berupa gambar"

### **Test Error Handling:**
- [ ] Database error → Error message + log
- [ ] Network error → Error message user-friendly
- [ ] Missing data → Error message spesifik
- [ ] Invalid ID → Error "data tidak ditemukan"

### **Test Success Messages:**
- [ ] Create data → Success "berhasil ditambahkan"
- [ ] Update data → Success "berhasil diperbarui"
- [ ] Delete data → Success "berhasil dihapus"

---

## 📊 SUMMARY

**Total Controllers Enhanced:** 5 controllers
- ✅ StuntingGrowthController
- ✅ GrowthMonitoringController
- ✅ DietUserController
- ✅ MealPlannerController
- ✅ FoodAdminController

**Total Request Classes:** 3 classes
- ✅ StuntingUserRequest (30+ validation rules)
- ✅ DietRequest (12 validation rules)
- ✅ UpdateNutritionRequest (2 validation rules + enhanced)

**New Components:** 1 component
- ✅ Alert Component (universal error/success display)

**Error Handling Features:**
- ✅ Try-catch blocks di semua store/update methods
- ✅ Validation dengan custom error messages
- ✅ Logging untuk debugging
- ✅ User-friendly error messages
- ✅ Graceful error recovery (redirect back with input)
- ✅ HTTP status codes yang tepat (422, 500, 400, 404)

---

## 💡 BEST PRACTICES IMPLEMENTED

### **1. Validation First**
```php
// ✅ BENAR: Validasi dulu
$validated = $request->validated();
// Atau
$validated = $request->validate([...]);
```

### **2. Try-Catch Everywhere**
```php
try {
    // Process data
} catch (\Illuminate\Validation\ValidationException $e) {
    // Handle validation errors
} catch (\Exception $e) {
    // Handle general errors
    \Log::error('Error: ' . $e->getMessage());
}
```

### **3. Custom Error Messages**
```php
$request->validate([...], [
    'field.required' => 'Pesan error dalam Bahasa Indonesia',
    'field.min' => 'Minimal :min karakter',
]);
```

### **4. Redirect with Input**
```php
return redirect()->back()
    ->withInput()  // ✅ Preserve user input
    ->with('error', 'Error message');
```

### **5. Logging**
```php
\Log::error('Context: ' . $e->getMessage());
// Log tersimpan di storage/logs/laravel.log
```

---

## 🚀 NEXT STEPS

### **Untuk Developer:**
1. Test semua form dengan input invalid
2. Cek log file untuk error yang ter-log
3. Pastikan error message muncul dengan benar
4. Test AJAX form dengan network error

### **Untuk User:**
1. Isi form dengan data yang benar
2. Jika ada error, baca pesan error dengan teliti
3. Perbaiki input sesuai petunjuk error
4. Submit ulang form

---

**Status:** Error handling & validation sudah diimplementasikan di semua fitur utama ✅
