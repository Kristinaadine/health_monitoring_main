# 📂 ADMIN GUIDE - FOOD CATEGORIES

## 🎯 OVERVIEW

Food Categories adalah fitur untuk mengelola kategori makanan dalam sistem. Kategori ini digunakan untuk mengorganisir database makanan agar lebih terstruktur.

---

## 🚀 CARA MENGGUNAKAN

### **1. TAMBAH KATEGORI BARU**

**Langkah-langkah:**

1. Login sebagai Admin
2. Menu: **Administration** → **Food Categories**
3. Klik tombol **"+ Add New"** (hijau, pojok kanan atas)
4. Modal "Add Food Categories" akan muncul
5. Isi form:
   - **Name:** Nama kategori (wajib diisi)
   - Contoh: "Buah", "Sayur", "Lauk", "Cemilan"
6. Klik **"Save"**

**Hasil:**
- ✅ Success: "Food Categories created successfully"
- ❌ Error: Lihat bagian [Error Handling](#error-handling)

---

### **2. EDIT KATEGORI**

**Langkah-langkah:**

1. Pada tabel Food Categories
2. Klik icon **edit** (pensil kuning) pada kategori yang ingin diubah
3. Modal "Edit Food Categories" akan muncul
4. Ubah nama kategori
5. Klik **"Save"**

**Hasil:**
- ✅ Success: "Data updated successfully"
- ❌ Error: Lihat bagian [Error Handling](#error-handling)

---

### **3. HAPUS KATEGORI**

**Langkah-langkah:**

1. Pada tabel Food Categories
2. Klik icon **delete** (trash merah) pada kategori yang ingin dihapus
3. Konfirmasi dialog akan muncul
4. Klik **"OK"** untuk konfirmasi

**Hasil:**
- ✅ Success: "Data deleted successfully"
- ❌ Error: "Failed to delete"

**Note:** 
- Hapus adalah soft delete (data tidak benar-benar terhapus)
- Data bisa di-restore jika diperlukan

---

## ⚠️ ERROR HANDLING

### **Error 1: Duplikasi Nama Kategori**

**Scenario:**
- Mencoba input kategori "Buah" padahal sudah ada "Buah" di database
- Mencoba edit "Sayur" menjadi "Buah" padahal "Buah" sudah ada

**Error Message:**
```
⚠️ Validation Error!
Kategori "Buah" sudah ada. Silakan gunakan nama lain.
```

**Solution:**
- Gunakan nama yang berbeda
- Contoh alternatif:
  - "Buah Segar"
  - "Buah Kering"
  - "Buah Impor"
  - "Buah Lokal"

**Why This Happens:**
- Sistem mencegah duplikasi untuk menjaga data integrity
- Setiap kategori harus memiliki nama yang unique

---

### **Error 2: Nama Kosong**

**Scenario:**
- Klik "Save" tanpa mengisi nama kategori

**Error Message:**
```
Name is required
```

**Solution:**
- Isi field "Name" dengan nama kategori yang valid

---

### **Error 3: Nama Terlalu Panjang**

**Scenario:**
- Input nama kategori lebih dari 255 karakter

**Error Message:**
```
The name may not be greater than 255 characters.
```

**Solution:**
- Gunakan nama yang lebih singkat (max 255 karakter)

---

## ✅ VALIDATION RULES

### **Aturan Input:**

1. **Required (Wajib Diisi)**
   - Field "Name" tidak boleh kosong
   - Minimal 1 karakter

2. **Unique (Tidak Boleh Duplikat)**
   - Nama kategori harus unique
   - Case-sensitive: "Buah" ≠ "buah" ≠ "BUAH"
   - Ignore soft-deleted records

3. **Max Length (Panjang Maksimal)**
   - Maksimal 255 karakter
   - Termasuk spasi dan special characters

---

## 📋 EXAMPLES

### **✅ VALID INPUT:**

```
✅ "Buah"
✅ "Sayur"
✅ "Lauk Pauk"
✅ "Cemilan Sehat"
✅ "Minuman"
✅ "Buah Segar"
✅ "Sayur Hijau"
```

### **❌ INVALID INPUT:**

```
❌ "" (kosong)
❌ "Buah" (jika sudah ada "Buah")
❌ "Nama kategori yang sangat panjang sekali..." (> 255 char)
```

---

## 🔄 WORKFLOW

### **Create New Category:**

```
User Input → Validation → Database Check → Save/Error

1. User klik "+ Add New"
2. User input nama: "Buah"
3. System validate:
   - Required? ✅
   - Unique? ✅
   - Max length? ✅
4. System save to database
5. Success message
6. Table auto-reload
```

### **Edit Existing Category:**

```
User Edit → Validation → Database Check → Update/Error

1. User klik edit icon
2. Modal show current data
3. User ubah nama: "Buah" → "Buah Segar"
4. System validate:
   - Required? ✅
   - Unique? ✅ (ignore current record)
   - Max length? ✅
5. System update database
6. Success message
7. Table auto-reload
```

---

## 🎨 USER INTERFACE

### **Main Page:**

```
┌─────────────────────────────────────────────────────┐
│ Food Categories                    [+ Add New]      │
│ Categories of food                                  │
├─────────────────────────────────────────────────────┤
│                                                     │
│ Show [10▼] entries              Search: [______]   │
│                                                     │
│ No │ Name        │ Actions                         │
│────┼─────────────┼─────────────────────────────────│
│ 1  │ Sarapan     │ [✏️] [🗑️]                       │
│ 2  │ Lauk        │ [✏️] [🗑️]                       │
│ 3  │ Cemilan     │ [✏️] [🗑️]                       │
│ 4  │ Sayur       │ [✏️] [🗑️]                       │
│ 5  │ Buah        │ [✏️] [🗑️]                       │
│                                                     │
└─────────────────────────────────────────────────────┘
```

### **Add Modal:**

```
┌─────────────────────────────────────┐
│ Add Food Categories            [×]  │
├─────────────────────────────────────┤
│                                     │
│ Name *                              │
│ [_____________________________]     │
│                                     │
│                                     │
│              [Save] [Close]         │
└─────────────────────────────────────┘
```

### **Edit Modal:**

```
┌─────────────────────────────────────┐
│ Edit Food Categories           [×]  │
├─────────────────────────────────────┤
│                                     │
│ Name *                              │
│ [Buah_________________________]     │
│                                     │
│                                     │
│              [Save] [Close]         │
└─────────────────────────────────────┘
```

---

## 🧪 TESTING CHECKLIST

### **Create (Add New):**

- [ ] Input nama baru → ✅ Success
- [ ] Input nama duplikat → ⚠️ Validation error
- [ ] Input nama kosong → ⚠️ Required error
- [ ] Input nama > 255 char → ⚠️ Max length error
- [ ] Cancel modal → Form reset
- [ ] Success → Table reload
- [ ] Success → Modal close

### **Update (Edit):**

- [ ] Edit tanpa ubah nama → ✅ Success
- [ ] Edit ke nama baru → ✅ Success
- [ ] Edit ke nama duplikat → ⚠️ Validation error
- [ ] Edit nama kosong → ⚠️ Required error
- [ ] Cancel modal → No changes
- [ ] Success → Table reload
- [ ] Success → Modal close

### **Delete:**

- [ ] Delete kategori → ✅ Success
- [ ] Confirm dialog → Show
- [ ] Cancel delete → No changes
- [ ] Success → Table reload
- [ ] Success → Data soft-deleted

---

## 💡 TIPS & BEST PRACTICES

### **Naming Convention:**

1. **Gunakan Nama yang Jelas**
   - ✅ "Buah Segar"
   - ❌ "BS" (terlalu singkat)

2. **Konsisten**
   - Semua huruf kapital: "BUAH", "SAYUR"
   - Atau title case: "Buah", "Sayur"
   - Pilih satu style dan konsisten

3. **Deskriptif**
   - ✅ "Lauk Pauk Hewani"
   - ✅ "Cemilan Sehat"
   - ❌ "Lain-lain" (terlalu umum)

4. **Hindari Duplikasi**
   - Cek dulu sebelum create
   - Gunakan variasi jika perlu

---

### **Organization:**

1. **Logical Grouping**
   ```
   ✅ Makanan Pokok
   ✅ Lauk Hewani
   ✅ Lauk Nabati
   ✅ Sayuran
   ✅ Buah-buahan
   ✅ Cemilan
   ✅ Minuman
   ```

2. **Avoid Over-categorization**
   - Jangan terlalu banyak kategori
   - Keep it simple and manageable

3. **Regular Review**
   - Review kategori secara berkala
   - Merge jika ada yang redundan
   - Delete yang tidak terpakai

---

## 🔧 TROUBLESHOOTING

### **Problem: Validation error tidak muncul**

**Check:**
1. Browser console (F12) untuk JavaScript error
2. Network tab untuk response status
3. Clear browser cache

**Solution:**
```bash
# Clear Laravel cache
php artisan cache:clear
php artisan route:clear

# Hard refresh browser
Ctrl + Shift + R
```

---

### **Problem: Modal tidak muncul**

**Check:**
1. JavaScript error di console
2. Bootstrap modal conflict
3. jQuery loaded properly

**Solution:**
- Refresh halaman
- Clear cache
- Try different browser

---

### **Problem: Table tidak reload setelah save**

**Check:**
1. DataTables initialized properly
2. AJAX response success
3. No JavaScript error

**Solution:**
```javascript
// Manual reload
$('#tablecategories').DataTable().ajax.reload();
```

---

### **Problem: Bisa create duplikat**

**Check:**
1. Validation rule di controller
2. Database constraint
3. Case sensitivity

**Verify:**
```sql
-- Check for duplicates
SELECT name, COUNT(*) 
FROM food_categories 
WHERE deleted_at IS NULL 
GROUP BY name 
HAVING COUNT(*) > 1;
```

---

## 📊 DATABASE STRUCTURE

### **Table: food_categories**

```sql
CREATE TABLE food_categories (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    login_created VARCHAR(255),
    login_edit VARCHAR(255),
    login_deleted VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- Indexes
CREATE INDEX idx_name ON food_categories(name);
CREATE INDEX idx_deleted_at ON food_categories(deleted_at);
```

### **Validation Query:**

```sql
-- Check unique (ignore soft-deleted)
SELECT COUNT(*) 
FROM food_categories 
WHERE name = 'Buah' 
  AND deleted_at IS NULL;
  
-- Should return 0 or 1
```

---

## ✅ SUMMARY

### **Key Features:**

- ✅ **CRUD Operations:** Create, Read, Update, Delete
- ✅ **Validation:** Unique, Required, Max Length
- ✅ **Error Handling:** User-friendly messages
- ✅ **Soft Delete:** Data tidak benar-benar terhapus
- ✅ **Auto Reload:** Table refresh otomatis
- ✅ **Audit Trail:** Track who created/edited/deleted

### **User Benefits:**

- 🎯 **Data Integrity:** No duplicate categories
- 🎯 **User Guidance:** Clear error messages
- 🎯 **Easy Management:** Simple CRUD interface
- 🎯 **Safe Delete:** Soft delete with restore option

---

**Last Updated:** November 2024  
**Version:** 1.0  
**Status:** ✅ Production Ready
