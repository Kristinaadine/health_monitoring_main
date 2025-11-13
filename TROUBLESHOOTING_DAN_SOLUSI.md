# Troubleshooting dan Solusi Error

## ANALISIS MASALAH UTAMA

### Masalah: Parameter `$locale` yang Hilang

**Root Cause:**
Semua route dibungkus dengan prefix `{locale}` di `routes/web.php`:
```php
Route::prefix('{locale}')
    ->where(['locale' => 'en|id'])
    ->middleware(['setLocale'])
    ->group(function () {
        // Semua route admin dan user
    });
```

Ini menyebabkan Laravel mengirim parameter `$locale` sebagai parameter PERTAMA ke semua controller method.

**Contoh Route:**
```
URL: /id/administration/food-categories/4
Parameters yang dikirim Laravel:
1. $locale = "id"
2. $food_category = 4
```

**Dampak:**
Jika controller method hanya menerima 1 parameter, maka:
- Parameter pertama akan menerima "id" (locale)
- Parameter kedua (ID yang sebenarnya) tidak tertangkap
- Hasil: Error "Data not found with ID: id"

---

## SOLUSI YANG SUDAH DITERAPKAN

### 1. Food Categories Controller
**File:** `app/Http/Controllers/Admin/FoodCatController.php`

**Sebelum (ERROR):**
```php
public function edit(string $id)
{
    $data = FoodCatModel::find(decrypt($id));
    // ...
}

public function update(Request $request, string $id)
{
    $foodcat = FoodCatModel::find(decrypt($id));
    // ...
}

public function destroy(string $id)
{
    $foodcat = FoodCatModel::find(decrypt($id));
    // ...
}
```

**Sesudah (FIXED):**
```php
public function edit($locale, FoodCatModel $food_category)
{
    return response()->json([
        'status' => 'success', 
        'data' => $food_category, 
        'id' => $food_category->id
    ]);
}

public function update(Request $request, $locale, FoodCatModel $food_category)
{
    $data = $request->all();
    $data['login_edit'] = auth()->user()->email;
    $food_category->update($data);
    return response()->json([
        'status' => 'success', 
        'message' => 'Data updated successfully'
    ]);
}

public function destroy($locale, FoodCatModel $food_category)
{
    $food_category->login_deleted = auth()->user()->email;
    $food_category->save();
    $food_category->delete();
    return response()->json([
        'status' => 'success', 
        'message' => 'Data deleted successfully'
    ]);
}
```

**Perubahan:**
- ✅ Tambah parameter `$locale` di posisi pertama
- ✅ Gunakan Route Model Binding (FoodCatModel)
- ✅ Laravel otomatis resolve ID menjadi model instance

---

### 2. Nutrient Ratio Controller
**File:** `app/Http/Controllers/Admin/NutrientAdminController.php`

**Sebelum (ERROR):**
```php
public function edit(string $id)
{
    $data = NutrientRatioModel::find(decrypt($id));
    // ...
}
```

**Sesudah (FIXED):**
```php
public function edit($locale, $nutrient)
{
    $data = NutrientRatioModel::find(decrypt($nutrient));
    if ($data) {
        return response()->json([
            'status' => 'success', 
            'data' => $data, 
            'id' => $nutrient
        ]);
    } else {
        return response()->json([
            'status' => 'error', 
            'message' => 'Data not found'
        ]);
    }
}

public function update(Request $request, $locale, $nutrient)
{
    $nutrientModel = NutrientRatioModel::find(decrypt($nutrient));
    if (!$nutrientModel) {
        return response()->json([
            'status' => 'error', 
            'message' => 'Data not found'
        ]);
    }
    
    $data = $request->all();
    $data['login_edit'] = auth()->user()->email;
    $nutrientModel->update($data);
    return response()->json([
        'status' => 'success', 
        'message' => 'Data updated successfully'
    ]);
}

public function destroy($locale, $nutrient)
{
    $nutrientModel = NutrientRatioModel::find(decrypt($nutrient));
    if (!$nutrientModel) {
        return response()->json([
            'status' => 'error', 
            'message' => 'Data not found'
        ]);
    }
    
    $data = ['login_deleted' => auth()->user()->email];
    $nutrientModel->update($data);
    $nutrientModel->delete();
    return response()->json([
        'status' => 'success', 
        'message' => 'Data deleted successfully'
    ]);
}
```

---

### 3. Food Controller
**File:** `app/Http/Controllers/Admin/FoodAdminController.php`

**Sesudah (FIXED):**
```php
public function edit($locale, $food)
{
    $data = FoodModel::find(decrypt($food));
    if (!$data) {
        return response()->json([
            'status' => 'error', 
            'message' => 'Data not found'
        ]);
    }
    
    $image = '';
    if ($data->image_path == null) {
        $image = asset('assets-admin/assets/img/noimage.png');
    } else {
        $image = Storage::url($data->image_path);
    }
    
    return response()->json([
        'status' => 'success', 
        'data' => $data, 
        'id' => $food, 
        'image' => $image
    ]);
}

public function update(Request $request, $locale, $food)
{
    $foodModel = FoodModel::find(decrypt($food));
    if (!$foodModel) {
        return response()->json([
            'status' => 'error', 
            'message' => 'Data not found'
        ]);
    }
    
    $data = $request->all();
    $data['login_edit'] = auth()->user()->email;
    
    if ($request->hasFile('image')) {
        if ($foodModel->image) {
            Storage::delete($foodModel->image_path);
        }
        $image = $request->file('image');
        $name = $image->getClientOriginalName();
        $path = $image->store('public/food');
        $data['image'] = $name;
        $data['image_path'] = $path;
    }
    
    $foodModel->update($data);
    return response()->json([
        'status' => 'success', 
        'message' => 'Data updated successfully'
    ]);
}

public function destroy($locale, $food)
{
    $foodModel = FoodModel::find(decrypt($food));
    if (!$foodModel) {
        return response()->json([
            'status' => 'error', 
            'message' => 'Data not found'
        ]);
    }
    
    $data = ['login_deleted' => auth()->user()->email];
    $foodModel->update($data);
    $foodModel->delete();
    return response()->json([
        'status' => 'success', 
        'message' => 'Data deleted successfully'
    ]);
}
```

---

## CONTROLLER YANG BELUM DIPERBAIKI

### 4. User Controller
**File:** `app/Http/Controllers/Admin/UserAdminController.php`

**Status:** ⚠️ PERLU DIPERBAIKI

**Method yang perlu diubah:**
```php
// SEBELUM
public function edit(string $id) { ... }
public function update(Request $request, string $id) { ... }
public function destroy(string $id) { ... }

// HARUS JADI
public function edit($locale, $user) { ... }
public function update(Request $request, $locale, $user) { ... }
public function destroy($locale, $user) { ... }
```

---

## SOLUSI UNTUK CONTROLLER LAIN

### Pattern yang Harus Diikuti:

#### Untuk Resource Controller dengan Route::resource():
```php
// 1. Edit Method
public function edit($locale, $modelParameter)
{
    $data = Model::find(decrypt($modelParameter));
    if (!$data) {
        return response()->json(['status' => 'error', 'message' => 'Data not found']);
    }
    return response()->json(['status' => 'success', 'data' => $data, 'id' => $modelParameter]);
}

// 2. Update Method
public function update(Request $request, $locale, $modelParameter)
{
    $model = Model::find(decrypt($modelParameter));
    if (!$model) {
        return response()->json(['status' => 'error', 'message' => 'Data not found']);
    }
    
    $data = $request->all();
    $data['login_edit'] = auth()->user()->email;
    $model->update($data);
    return response()->json(['status' => 'success', 'message' => 'Data updated successfully']);
}

// 3. Destroy Method
public function destroy($locale, $modelParameter)
{
    $model = Model::find(decrypt($modelParameter));
    if (!$model) {
        return response()->json(['status' => 'error', 'message' => 'Data not found']);
    }
    
    $data = ['login_deleted' => auth()->user()->email];
    $model->update($data);
    $model->delete();
    return response()->json(['status' => 'success', 'message' => 'Data deleted successfully']);
}
```

---

## CHECKLIST PERBAIKAN

### ✅ Sudah Diperbaiki:
- [x] FoodCatController (Food Categories)
- [x] NutrientAdminController (Nutrient Ratio)
- [x] FoodAdminController (Food)
- [x] GrowthMonitoringController (Grafik Z-Score)
- [x] HomeController (Dashboard grafik)

### ⚠️ Perlu Diperbaiki:
- [ ] UserAdminController (User Management)
- [ ] Controller lain yang menggunakan Route::resource()

---

## CARA MENGIDENTIFIKASI CONTROLLER YANG BERMASALAH

### 1. Cek Route List
```bash
php artisan route:list --name=administration
```

Lihat parameter route, contoh:
```
{locale}/administration/user/{user}
```

### 2. Cek Controller Method
Jika method hanya punya 1 parameter:
```php
public function edit(string $id) // ❌ SALAH
```

Harus diubah jadi 2 parameter:
```php
public function edit($locale, $user) // ✅ BENAR
```

---

## SOLUSI ALTERNATIF (Jika Tidak Ingin Ubah Semua Controller)

### Opsi 1: Hapus Locale Prefix (Tidak Direkomendasikan)
```php
// routes/web.php
// SEBELUM
Route::prefix('{locale}')->group(function () {
    // routes
});

// SESUDAH
Route::group(function () {
    // routes
});
```

**Dampak:**
- ❌ Hilang fitur multi-bahasa
- ❌ URL berubah dari `/id/home` jadi `/home`
- ❌ Perlu update semua link di view

### Opsi 2: Gunakan Middleware untuk Handle Locale (Direkomendasikan)
Tetap gunakan locale prefix tapi pastikan semua controller method menerima parameter `$locale`.

---

## TESTING SETELAH PERBAIKAN

### 1. Test CRUD Operations:
```
✅ Create - Tambah data baru
✅ Read - Lihat data
✅ Update - Edit data
✅ Delete - Hapus data
```

### 2. Test dengan Browser Console:
Buka F12 → Console, lihat:
- Request URL harus benar
- Response harus JSON dengan status success
- Tidak ada error 404 atau 500

### 3. Test dengan Different Locale:
```
/id/administration/food-categories  ✅
/en/administration/food-categories  ✅
```

---

## KESIMPULAN

**Penyebab Utama Error:**
- Parameter `$locale` tidak ditangkap di controller method
- Laravel mengirim locale sebagai parameter pertama
- Controller hanya menerima 1 parameter (ID)
- Hasil: ID tidak tertangkap, error "Data not found"

**Solusi:**
- Tambahkan parameter `$locale` di posisi pertama semua method
- Gunakan Route Model Binding untuk simplifikasi
- Pastikan error handling yang baik

**Status Saat Ini:**
- 5 controller sudah diperbaiki ✅
- 1+ controller mungkin masih perlu diperbaiki ⚠️
- Sistem sudah bisa digunakan untuk fitur yang sudah diperbaiki

---

**Rekomendasi:**
1. Test semua fitur satu per satu
2. Jika ada error, cek controller method signature
3. Tambahkan parameter `$locale` jika belum ada
4. Clear cache setelah perubahan: `php artisan config:clear`
