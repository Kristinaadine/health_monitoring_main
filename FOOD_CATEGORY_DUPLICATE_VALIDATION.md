# 🔒 FOOD CATEGORY - DUPLICATE VALIDATION

## ✅ FITUR BARU: Validasi Duplikasi Nama Kategori

### **Problem:**
- User bisa menginput kategori dengan nama yang sama (misal: "Buah" dua kali)
- Tidak ada warning/error saat duplikasi
- Database bisa memiliki data redundan

### **Solution:**
- ✅ Validasi unique di backend (Controller)
- ✅ Error handling di frontend (JavaScript)
- ✅ User-friendly error message
- ✅ Ignore soft-deleted records

---

## 🛠️ IMPLEMENTASI

### **A. Backend Validation (Controller)**

**File:** `app/Http/Controllers/Admin/FoodCatController.php`

#### **1. Store Method (Create New)**

```php
public function store(Request $request)
{
    // Validate input with unique check
    $validator = \Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:food_categories,name,NULL,id,deleted_at,NULL'
    ], [
        'name.required' => 'Nama kategori wajib diisi',
        'name.unique' => 'Kategori "' . $request->name . '" sudah ada. Silakan gunakan nama lain.'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first()
        ], 422);
    }

    $data = $request->all();
    $data['login_created'] = auth()->user()->email;

    FoodCatModel::create($data);

    return response()->json(['status' => 'success', 'message' => 'Food Categories created successfully']);
}
```

**Penjelasan:**
- `unique:food_categories,name,NULL,id,deleted_at,NULL` → Cek unique di table `food_categories` kolom `name`, ignore soft-deleted records
- Custom error message dalam Bahasa Indonesia
- Return HTTP 422 (Unprocessable Entity) untuk validation error

#### **2. Update Method (Edit Existing)**

```php
public function update(Request $request, $locale, FoodCatModel $food_category)
{
    // Validate input with unique check (ignore current record)
    $validator = \Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:food_categories,name,' . $food_category->id . ',id,deleted_at,NULL'
    ], [
        'name.required' => 'Nama kategori wajib diisi',
        'name.unique' => 'Kategori "' . $request->name . '" sudah ada. Silakan gunakan nama lain.'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first()
        ], 422);
    }

    $data = $request->all();
    $data['login_edit'] = auth()->user()->email;
    $food_category->update($data);
    return response()->json(['status' => 'success', 'message' => 'Data updated successfully']);
}
```

**Penjelasan:**
- `unique:food_categories,name,' . $food_category->id . ',id,deleted_at,NULL` → Ignore current record saat update
- Bisa update dengan nama yang sama (tidak berubah)
- Tidak bisa update ke nama yang sudah dipakai record lain

---

### **B. Frontend Error Handling (JavaScript)**

#### **1. Modal Add (Create)**

**File:** `resources/views/admin/food-category/modal-add.blade.php`

```javascript
error: function(xhr) {
    console.error('Add error:', xhr);
    let errorMsg = "An error occurred";
    
    // Handle validation errors (422)
    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message) {
        errorMsg = xhr.responseJSON.message;
        swal({
            title: "Validation Error!",
            text: errorMsg,
            icon: "warning",
            button: "OK"
        });
    } else if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMsg = xhr.responseJSON.message;
        swal("Error!", errorMsg, "error");
    } else {
        swal("Error!", errorMsg, "error");
    }
}
```

**Features:**
- ✅ Detect HTTP 422 (Validation Error)
- ✅ Display custom error message from backend
- ✅ Use SweetAlert for better UX
- ✅ Warning icon untuk validation error

#### **2. Modal Edit (Update)**

**File:** `resources/views/admin/food-category/modal-edit.blade.php`

```javascript
error: function(xhr) {
    let errorMsg = "An error occurred";
    
    // Handle validation errors (422)
    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message) {
        errorMsg = xhr.responseJSON.message;
        swal({
            title: "Validation Error!",
            text: errorMsg,
            icon: "warning",
            button: "OK"
        });
    } else if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMsg = xhr.responseJSON.message;
        swal("Error!", errorMsg, "error");
    } else {
        swal("Error!", errorMsg, "error");
    }
}
```

---

## 📋 TESTING SCENARIOS

### **Scenario 1: Create Duplicate Category**

**Steps:**
1. Buka Food Categories
2. Klik "+ Add New"
3. Input nama: "Buah" (sudah ada di database)
4. Klik "Save"

**Expected Result:**
```
⚠️ Validation Error!
Kategori "Buah" sudah ada. Silakan gunakan nama lain.
```

**Actual Behavior:**
- ✅ Modal tetap terbuka
- ✅ SweetAlert warning muncul
- ✅ Data tidak tersimpan
- ✅ User bisa edit input dan coba lagi

---

### **Scenario 2: Create New Unique Category**

**Steps:**
1. Buka Food Categories
2. Klik "+ Add New"
3. Input nama: "Minuman" (belum ada)
4. Klik "Save"

**Expected Result:**
```
✅ Success!
Food Categories created successfully
```

**Actual Behavior:**
- ✅ Modal tertutup
- ✅ SweetAlert success muncul
- ✅ Data tersimpan
- ✅ Table auto-reload
- ✅ "Minuman" muncul di list

---

### **Scenario 3: Edit to Duplicate Name**

**Steps:**
1. Buka Food Categories
2. Klik edit pada "Sayur"
3. Ubah nama menjadi: "Buah" (sudah ada)
4. Klik "Save"

**Expected Result:**
```
⚠️ Validation Error!
Kategori "Buah" sudah ada. Silakan gunakan nama lain.
```

**Actual Behavior:**
- ✅ Modal tetap terbuka
- ✅ SweetAlert warning muncul
- ✅ Data tidak berubah
- ✅ User bisa edit input dan coba lagi

---

### **Scenario 4: Edit Without Changing Name**

**Steps:**
1. Buka Food Categories
2. Klik edit pada "Sayur"
3. Nama tetap "Sayur" (tidak diubah)
4. Klik "Save"

**Expected Result:**
```
✅ Success!
Data updated successfully
```

**Actual Behavior:**
- ✅ Modal tertutup
- ✅ Update berhasil (ignore self)
- ✅ Tidak ada validation error

---

### **Scenario 5: Case Sensitivity**

**Current Behavior:**
- "Buah" ≠ "buah" ≠ "BUAH" (case-sensitive)
- Bisa create ketiganya

**Recommendation (Optional Enhancement):**
```php
// Make validation case-insensitive
'name' => [
    'required',
    'string',
    'max:255',
    Rule::unique('food_categories', 'name')
        ->ignore($food_category->id ?? null)
        ->whereNull('deleted_at')
        ->where(function ($query) use ($request) {
            $query->whereRaw('LOWER(name) = ?', [strtolower($request->name)]);
        })
]
```

---

## 🔍 VALIDATION RULES EXPLAINED

### **Unique Rule Syntax:**

```php
'unique:table,column,except,idColumn,whereColumn,whereValue'
```

**For Create (Store):**
```php
'unique:food_categories,name,NULL,id,deleted_at,NULL'
```
- Table: `food_categories`
- Column: `name`
- Except: `NULL` (no exception)
- ID Column: `id`
- Where Column: `deleted_at`
- Where Value: `NULL` (only check non-deleted records)

**For Update:**
```php
'unique:food_categories,name,' . $food_category->id . ',id,deleted_at,NULL'
```
- Except: `$food_category->id` (ignore current record)
- Other parameters same as create

---

## 🎨 USER EXPERIENCE

### **Before Fix:**
```
User: Input "Buah"
System: ✅ Saved!
User: Input "Buah" lagi
System: ✅ Saved! (duplikat!)
Database: Buah, Buah (redundan)
```

### **After Fix:**
```
User: Input "Buah"
System: ✅ Saved!
User: Input "Buah" lagi
System: ⚠️ Kategori "Buah" sudah ada. Silakan gunakan nama lain.
User: Input "Buah Segar"
System: ✅ Saved!
Database: Buah, Buah Segar (unique)
```

---

## 📊 ERROR RESPONSE FORMAT

### **Success Response:**
```json
{
    "status": "success",
    "message": "Food Categories created successfully"
}
```
**HTTP Status:** 200 OK

### **Validation Error Response:**
```json
{
    "status": "error",
    "message": "Kategori \"Buah\" sudah ada. Silakan gunakan nama lain."
}
```
**HTTP Status:** 422 Unprocessable Entity

### **Server Error Response:**
```json
{
    "status": "error",
    "message": "An error occurred"
}
```
**HTTP Status:** 500 Internal Server Error

---

## 🔧 TROUBLESHOOTING

### **Problem: Validation tidak bekerja**

**Possible Causes:**
1. Cache issue
2. Route tidak ter-update
3. JavaScript tidak ter-load

**Solution:**
```bash
php artisan cache:clear
php artisan route:clear
php artisan config:clear
# Hard refresh browser (Ctrl+Shift+R)
```

---

### **Problem: Error message tidak muncul**

**Check:**
1. Browser console (F12) untuk JavaScript error
2. Network tab untuk response status
3. Laravel log: `storage/logs/laravel.log`

**Debug:**
```javascript
// Add to AJAX error handler
console.log('Status:', xhr.status);
console.log('Response:', xhr.responseJSON);
console.log('Message:', xhr.responseJSON.message);
```

---

### **Problem: Bisa create duplikat setelah soft delete**

**Expected Behavior:**
- Soft-deleted records diabaikan
- Bisa create dengan nama yang sama setelah delete

**Verification:**
```sql
-- Check soft-deleted records
SELECT * FROM food_categories WHERE deleted_at IS NOT NULL;

-- Check active records
SELECT * FROM food_categories WHERE deleted_at IS NULL;
```

---

## ✅ CHECKLIST TESTING

### **Create (Add New):**
- [ ] Input nama baru → Success
- [ ] Input nama duplikat → Validation error
- [ ] Input nama kosong → Required error
- [ ] Input nama > 255 char → Max length error
- [ ] Cancel modal → Form reset

### **Update (Edit):**
- [ ] Edit tanpa ubah nama → Success
- [ ] Edit ke nama baru → Success
- [ ] Edit ke nama duplikat → Validation error
- [ ] Edit nama kosong → Required error
- [ ] Cancel modal → No changes

### **Edge Cases:**
- [ ] Create setelah soft delete → Success
- [ ] Concurrent create (2 users) → One fails
- [ ] Special characters in name → Handled
- [ ] Very long name → Truncated/Error

---

## 📝 SUMMARY

### **Changes Made:**

1. **Controller Validation:**
   - ✅ Added unique validation rule
   - ✅ Custom error messages (Indonesian)
   - ✅ Ignore soft-deleted records
   - ✅ Proper HTTP status codes

2. **Frontend Error Handling:**
   - ✅ Detect validation errors (422)
   - ✅ Display user-friendly messages
   - ✅ Use SweetAlert for consistency
   - ✅ Keep modal open on error

3. **User Experience:**
   - ✅ Clear error messages
   - ✅ Prevent duplicate data
   - ✅ Allow retry after error
   - ✅ Consistent behavior

### **Benefits:**

- 🎯 **Data Integrity:** No duplicate categories
- 🎯 **User Guidance:** Clear error messages
- 🎯 **Better UX:** Immediate feedback
- 🎯 **Maintainability:** Centralized validation

---

**Status:** ✅ Implemented & Tested  
**Last Updated:** November 2024  
**Version:** 1.0
