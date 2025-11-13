# 🔧 FIX: Undefined Array Key 0

## 🐛 ERROR
```
ErrorException
Undefined array key 0

File: resources/views/monitoring/growth-monitoring/show.blade.php:96
```

## 🔍 ROOT CAUSE

Kode mengakses `$data->history[0]` dan `$data->history[1]` tanpa memeriksa apakah array key tersebut ada.

**Skenario Error:**
1. User baru membuat data growth monitoring
2. Data history belum ter-generate dengan benar
3. Array `$data->history` kosong atau tidak memiliki index 0 dan 1
4. Akses `$data->history[0]` → Error "Undefined array key 0"

---

## ✅ SOLUSI

### **1. Null Safety Check**

**SEBELUM ❌**
```php
@php
    $color = '';
    if ($data->history[0]->hasil_diagnosa == 'Tinggi Badan Sangat Tinggi') {
        $color = 'danger';
    }
@endphp
<div class="alert alert-{{ $color }}">
    <h4>{{ $data->history[0]->hasil_diagnosa }}</h4>
    <p>{{ $data->history[0]->deskripsi_diagnosa }}</p>
</div>
```

**SESUDAH ✅**
```php
@php
    $history0 = $data->history[0] ?? null;  // ✅ Null coalescing
    $color = 'info';
    
    if ($history0) {  // ✅ Check if exists
        if ($history0->hasil_diagnosa == 'Tinggi Badan Sangat Tinggi') {
            $color = 'danger';
        }
    }
@endphp
@if($history0)  // ✅ Conditional rendering
    <div class="alert alert-{{ $color }}">
        <h4>{{ $history0->hasil_diagnosa }}</h4>
        <p>{{ $history0->deskripsi_diagnosa }}</p>
    </div>
@else
    <div class="alert alert-warning">
        <p class="mb-0">Data diagnosis TB/U tidak tersedia.</p>
    </div>
@endif
```

---

### **2. JavaScript Z-Score Data**

**SEBELUM ❌**
```javascript
series: [{
    name: 'Z-Score',
    data: [parseFloat('<?= $data->history[0]->zscore ?>')],  // ❌ Error if not exists
    ...
}]
```

**SESUDAH ✅**
```javascript
series: [{
    name: 'Z-Score',
    data: [parseFloat('<?= $history0->zscore ?? 0 ?>')],  // ✅ Default to 0
    ...
}]
```

---

## 📝 CHANGES MADE

### **File: show.blade.php**

**1. TB/U (Height) Section**
```php
// Line ~96
@php
    $history0 = $data->history[0] ?? null;  // ✅ Added
    $color = 'info';  // ✅ Default color
    
    if ($history0) {  // ✅ Added check
        // ... color logic
    }
@endphp
@if($history0)  // ✅ Added conditional
    <div class="alert alert-{{ $color }}">
        <h4>{{ $history0->hasil_diagnosa }}</h4>
        <p>{{ $history0->deskripsi_diagnosa }}</p>
        <hr>
        <p class="mb-0">{{__('monitoring.recomendation')}} : {{ $history0->penanganan }}</p>
    </div>
@else  // ✅ Added fallback
    <div class="alert alert-warning">
        <p class="mb-0">Data diagnosis TB/U tidak tersedia.</p>
    </div>
@endif
```

**2. BB/U (Weight) Section**
```php
// Line ~130
@php
    $history1 = $data->history[1] ?? null;  // ✅ Added
    $color2 = 'info';  // ✅ Default color
    
    if ($history1) {  // ✅ Added check
        // ... color logic
    }
@endphp
@if($history1)  // ✅ Added conditional
    <div class="alert alert-{{ $color2 }}">
        <h4>{{ $history1->hasil_diagnosa }}</h4>
        <p>{{ $history1->deskripsi_diagnosa }}</p>
        <hr>
        <p class="mb-0">{{__('monitoring.recomendation')}} : {{ $history1->penanganan }}</p>
    </div>
@else  // ✅ Added fallback
    <div class="alert alert-warning">
        <p class="mb-0">Data diagnosis BB/U tidak tersedia.</p>
    </div>
@endif
```

**3. JavaScript Graph 1 (TB/U)**
```javascript
// Line ~305
series: [{
    name: 'Z-Score',
    data: [parseFloat('<?= $history0->zscore ?? 0 ?>')],  // ✅ Changed
    ...
}]
```

**4. JavaScript Graph 2 (BB/U)**
```javascript
// Line ~460
series: [{
    name: 'Z-Score',
    data: [parseFloat('<?= $history1->zscore ?? 0 ?>')],  // ✅ Changed
    ...
}]
```

---

## 🎯 PATTERN YANG BENAR

### **Untuk Array Access:**
```php
// ❌ SALAH - Langsung akses tanpa check
$value = $array[0]->property;

// ✅ BENAR - Null coalescing operator
$item = $array[0] ?? null;
if ($item) {
    $value = $item->property;
}

// ✅ BENAR - Inline null coalescing
$value = $array[0]->property ?? 'default';
```

### **Untuk Blade Conditional:**
```blade
{{-- ❌ SALAH - Assume data exists --}}
<div>{{ $data->history[0]->value }}</div>

{{-- ✅ BENAR - Check first --}}
@php
    $history = $data->history[0] ?? null;
@endphp
@if($history)
    <div>{{ $history->value }}</div>
@else
    <div>Data tidak tersedia</div>
@endif
```

### **Untuk JavaScript:**
```javascript
// ❌ SALAH
data: [parseFloat('<?= $data->history[0]->zscore ?>')]

// ✅ BENAR
data: [parseFloat('<?= $history0->zscore ?? 0 ?>')]
```

---

## 🧪 TESTING

### **Test Case 1: Data Lengkap**
- ✅ History[0] dan History[1] ada
- ✅ Diagnosis TB/U dan BB/U muncul
- ✅ Grafik menampilkan Z-Score dengan benar

### **Test Case 2: Data Kosong**
- ✅ History kosong
- ✅ Pesan "Data tidak tersedia" muncul
- ✅ Grafik menampilkan 0 (tidak error)

### **Test Case 3: Data Partial**
- ✅ Hanya History[0] ada
- ✅ TB/U muncul, BB/U menampilkan pesan "tidak tersedia"
- ✅ Grafik 1 OK, Grafik 2 menampilkan 0

---

## 💡 BEST PRACTICES

### **1. Always Check Array Keys**
```php
// ✅ BENAR
$item = $array[$key] ?? null;
if ($item) {
    // Use $item
}
```

### **2. Provide Fallback UI**
```blade
@if($data)
    {{-- Show data --}}
@else
    <div class="alert alert-warning">
        Data tidak tersedia
    </div>
@endif
```

### **3. Default Values**
```php
// ✅ BENAR
$value = $object->property ?? 'default';
$number = $array[0] ?? 0;
$text = $data->text ?? 'N/A';
```

### **4. Defensive Programming**
```php
// ✅ Check at multiple levels
$history = $data->history ?? [];
$item = $history[0] ?? null;
$value = $item->property ?? 'default';
```

---

## ✅ STATUS

**Error:** `Undefined array key 0` ❌  
**Status:** FIXED ✅

**Changes:**
- ✅ Added null safety checks
- ✅ Added conditional rendering
- ✅ Added fallback messages
- ✅ Fixed JavaScript data access

**Result:**
- ✅ No more "Undefined array key" errors
- ✅ Graceful handling of missing data
- ✅ User-friendly fallback messages
- ✅ Graphs display 0 instead of error

---

**Refresh halaman Growth Monitoring Show sekarang - error sudah teratasi!** 🎉
