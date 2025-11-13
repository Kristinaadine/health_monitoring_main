# 🍎 FOOD GUIDE - DIET USER INTEGRATION

## 🎯 FITUR BARU: Smart Food Recommendation Based on Diet Analysis

### **Overview:**
Food Guide sekarang terintegrasi dengan Diet User untuk memberikan rekomendasi makanan yang **personalized** berdasarkan hasil analisis BMI dan status gizi user.

---

## ✨ WHAT'S NEW

### **Before (Old Food Guide):**
```
User → Search Food Manually → View Results
```
- ❌ Generic search only
- ❌ No personalization
- ❌ User harus tahu apa yang dicari

### **After (New Food Guide):**
```
User → Select Diet Analysis → System Recommend Foods → View Personalized Results
```
- ✅ Smart recommendation based on diet
- ✅ Personalized untuk setiap user
- ✅ Automatic keyword extraction
- ✅ Filtered by nutritional needs

---

## 🔧 HOW IT WORKS

### **Step 1: User Creates Diet Analysis**
```
Menu: Growth Detection → Diet User
Input: Nama, Usia, Tinggi, Berat
System Calculate: BMI, Status Gizi, Rekomendasi
```

**Example Output:**
```
Nama: C
Usia: 22
BMI: 25.1
Status: Overweight
Rekomendasi: Kurangi gula dan lemak, perbanyak sayur
```

---

### **Step 2: System Extracts Keywords**

**From Recommendation Text:**
```
"Kurangi gula dan lemak, perbanyak sayur"
```

**Extracted Keywords:**
```php
[
    'include' => ['sayur', 'buah', 'serat', 'rendah kalori'],
    'exclude' => ['gula', 'lemak', 'gorengan', 'manis'],
    'categories' => ['Sayur', 'Buah']
]
```

**Keyword Mapping by Status:**

| Status | Include Keywords | Exclude Keywords | Categories |
|--------|-----------------|------------------|------------|
| **Kurus** | protein, kalori, daging, telur, susu, kacang, nasi | - | Lauk, Sarapan |
| **Normal** | sayur, buah, protein, seimbang | - | Sayur, Buah, Lauk |
| **Overweight** | sayur, buah, serat, rendah kalori | gula, lemak, gorengan, manis | Sayur, Buah |
| **Obesitas** | sayur, buah, serat, rendah kalori, rendah lemak | gula, lemak, gorengan, manis, tinggi kalori | Sayur, Buah |

---

### **Step 3: System Filters Foods**

**Query Logic:**
```php
// 1. Filter by categories
WHERE id_categories IN (Sayur, Buah)

// 2. Include foods with keywords
AND (name_food LIKE '%sayur%' 
     OR name_food LIKE '%buah%'
     OR description LIKE '%serat%')

// 3. Exclude foods with keywords
AND name_food NOT LIKE '%gula%'
AND name_food NOT LIKE '%lemak%'
AND name_food NOT LIKE '%goreng%'

// 4. Filter by calories (for Overweight/Obesitas)
AND calories <= 300

// 5. Order by fiber (prioritize high fiber)
ORDER BY fiber DESC
```

---

### **Step 4: Display Recommendations**

**UI Components:**

1. **Diet Selection Dropdown**
   ```
   [Dropdown: Select Diet Data]
   - C - BMI: 25.1 (Overweight) - 12 Nov 2024
   - John - BMI: 22.5 (Normal) - 10 Nov 2024
   ```

2. **Analysis Summary**
   ```
   ℹ️ Hasil Analisis
   Nama: C
   BMI: 25.1 (Overweight)
   Rekomendasi: Kurangi gula dan lemak, perbanyak sayur
   
   Kata Kunci Rekomendasi:
   ✓ Sayur  ✓ Buah  ✓ Serat  ✓ Rendah kalori
   ✗ Gula  ✗ Lemak  ✗ Goreng
   
   Makanan Ditemukan: 15 item
   ```

3. **Recommended Foods Grid**
   ```
   [Card: Sayur Bayam]
   Kategori: Sayur
   Protein: 3g
   Carbs: 5g
   Fiber: 4g
   Kalori: 25 kcal
   
   [Card: Buah Apel]
   Kategori: Buah
   Protein: 0.5g
   Carbs: 14g
   Fiber: 2.5g
   Kalori: 52 kcal
   ```

---

## 📋 FEATURES

### **1. Diet User Selection**
- Dropdown list semua diet analysis user
- Sorted by date (newest first)
- Display: Nama, BMI, Status, Date

### **2. Smart Keyword Extraction**
- Automatic dari recommendation text
- Mapping berdasarkan status gizi
- Include & exclude keywords
- Category filtering

### **3. Intelligent Food Filtering**
- Multi-criteria filtering
- Calorie-based for weight management
- Fiber prioritization
- Exclude unhealthy options

### **4. Analysis Display**
- Clear summary of diet analysis
- Visual keyword badges
- Food count indicator
- User-friendly layout

### **5. Manual Search (Still Available)**
- Original search functionality preserved
- Search by food name
- Filter by category
- Complementary to smart recommendation

---

## 🎨 USER INTERFACE

### **Page Layout:**

```
┌─────────────────────────────────────────────────┐
│ ← Food Guide                                    │
├─────────────────────────────────────────────────┤
│                                                 │
│ 📊 Rekomendasi Berdasarkan Diet                │
│ ┌─────────────────────────────────────────────┐│
│ │ Pilih Analisis Diet Anda:                   ││
│ │ [Dropdown: C - BMI: 25.1 (Overweight) ▼]   ││
│ │                                             ││
│ │ ℹ️ Hasil Analisis                           ││
│ │ Nama: C                                     ││
│ │ BMI: 25.1 (Overweight)                      ││
│ │ Rekomendasi: Kurangi gula dan lemak...      ││
│ │                                             ││
│ │ Kata Kunci Rekomendasi:                     ││
│ │ [✓ Sayur] [✓ Buah] [✗ Gula] [✗ Lemak]     ││
│ │                                             ││
│ │ Makanan Ditemukan: 15 item                  ││
│ └─────────────────────────────────────────────┘│
│                                                 │
│ 🔍 Pencarian Manual                            │
│ ┌─────────────────────────────────────────────┐│
│ │ [Search Food] [Category ▼] [🔍 Search]     ││
│ └─────────────────────────────────────────────┘│
│                                                 │
│ 📋 Hasil Rekomendasi                           │
│ ┌───────┐ ┌───────┐ ┌───────┐                │
│ │ Sayur │ │ Buah  │ │ Lauk  │                │
│ │ Bayam │ │ Apel  │ │ Ikan  │                │
│ │ 25kcal│ │ 52kcal│ │180kcal│                │
│ └───────┘ └───────┘ └───────┘                │
│                                                 │
└─────────────────────────────────────────────────┘
```

---

## 🔄 WORKFLOW

### **Complete User Journey:**

```
1. CREATE DIET ANALYSIS
   ↓
   User → Growth Detection → Diet User
   Input: Personal data
   Output: BMI, Status, Recommendation
   
2. SELECT DIET IN FOOD GUIDE
   ↓
   User → Food Guide
   Select: Diet analysis from dropdown
   
3. SYSTEM PROCESSES
   ↓
   Extract keywords from recommendation
   Filter foods based on keywords
   Apply nutritional criteria
   
4. VIEW RECOMMENDATIONS
   ↓
   Display: Analysis summary
   Display: Recommended foods
   User: Browse & learn
   
5. OPTIONAL: MANUAL SEARCH
   ↓
   User: Search specific food
   User: Filter by category
```

---

## 💡 EXAMPLE SCENARIOS

### **Scenario 1: Overweight User**

**Input:**
```
Nama: C
Usia: 22
Tinggi: 170 cm
Berat: 73 kg
BMI: 25.1
Status: Overweight
```

**System Analysis:**
```
Rekomendasi: Kurangi gula dan lemak, perbanyak sayur
```

**Extracted Keywords:**
```
Include: sayur, buah, serat, rendah kalori
Exclude: gula, lemak, gorengan, manis
Categories: Sayur, Buah
```

**Recommended Foods:**
```
✅ Sayur Bayam (25 kcal, 4g fiber)
✅ Buah Apel (52 kcal, 2.5g fiber)
✅ Salad Sayur (45 kcal, 3g fiber)
✅ Buah Jeruk (47 kcal, 2.4g fiber)
❌ Nasi Goreng (excluded: goreng)
❌ Kue Manis (excluded: manis, gula)
```

---

### **Scenario 2: Underweight User**

**Input:**
```
Nama: John
Usia: 25
Tinggi: 175 cm
Berat: 55 kg
BMI: 17.9
Status: Kurus
```

**System Analysis:**
```
Rekomendasi: Perbanyak konsumsi protein dan kalori sehat
```

**Extracted Keywords:**
```
Include: protein, kalori, daging, telur, susu, kacang, nasi
Exclude: -
Categories: Lauk, Sarapan
```

**Recommended Foods:**
```
✅ Ayam Bakar (250 kcal, 30g protein)
✅ Telur Rebus (155 kcal, 13g protein)
✅ Nasi Putih (200 kcal, 4g protein)
✅ Susu Full Cream (150 kcal, 8g protein)
✅ Kacang Almond (160 kcal, 6g protein)
```

---

### **Scenario 3: Normal Weight User**

**Input:**
```
Nama: Sarah
Usia: 28
Tinggi: 160 cm
Berat: 55 kg
BMI: 21.5
Status: Normal
```

**System Analysis:**
```
Rekomendasi: Pertahankan pola makan seimbang
```

**Extracted Keywords:**
```
Include: sayur, buah, protein, seimbang
Exclude: -
Categories: Sayur, Buah, Lauk
```

**Recommended Foods:**
```
✅ Sayur Brokoli (35 kcal, 3g protein)
✅ Buah Pisang (105 kcal, 1.3g protein)
✅ Ikan Salmon (200 kcal, 22g protein)
✅ Salad Mix (50 kcal, 2g protein)
✅ Ayam Panggang (180 kcal, 25g protein)
```

---

## 🔧 TECHNICAL IMPLEMENTATION

### **Controller Methods:**

**1. index() - Enhanced**
```php
public function index(Request $request)
{
    // Get user's diet data
    $dietUsers = DietUserModel::where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'desc')
        ->get();
    
    return view('home.food-guide', compact('dietUsers', ...));
}
```

**2. recommend() - New**
```php
public function recommend(Request $request, $locale, $dietUserId)
{
    // Get diet user data
    $dietUser = DietUserModel::findOrFail($dietUserId);
    
    // Extract keywords
    $keywords = $this->extractKeywords($dietUser->rekomendasi, $dietUser->status_gizi);
    
    // Get recommended foods
    $food = $this->getFoodRecommendations($keywords, $dietUser->status_gizi);
    
    // Return view with analysis
    return view('home.food-guide', compact('food', 'analysis', ...));
}
```

**3. extractKeywords() - Private**
```php
private function extractKeywords(string $recommendation, string $status): array
{
    // Keyword mapping by status
    $keywordMap = [
        'Kurus' => [...],
        'Normal' => [...],
        'Overweight' => [...],
        'Obesitas' => [...]
    ];
    
    // Extract from recommendation text
    // Return include, exclude, categories
}
```

**4. getFoodRecommendations() - Private**
```php
private function getFoodRecommendations(array $keywords, string $status)
{
    $query = FoodModel::query();
    
    // Filter by categories
    // Include keywords
    // Exclude keywords
    // Apply nutritional criteria
    
    return $query->get();
}
```

---

### **Route:**

```php
Route::get('/food-guide/recommend/{dietUserId}', [FoodGuideController::class, 'recommend'])
    ->name('food-guide.recommend');
```

---

### **View Components:**

**1. Diet Selection**
```blade
<select class="form-control" id="dietUserSelect">
    <option value="">-- Select Diet Data --</option>
    @foreach($dietUsers as $du)
        <option value="{{ $du->id }}">
            {{ $du->nama }} - BMI: {{ $du->bmi }} ({{ $du->status_gizi }})
        </option>
    @endforeach
</select>
```

**2. Analysis Display**
```blade
@if(isset($analysis))
<div class="alert alert-info">
    <h6>Analysis Result</h6>
    <p>Name: {{ $analysis['name'] }}</p>
    <p>BMI: {{ $analysis['bmi'] }} ({{ $analysis['status'] }})</p>
    <p>Recommendation: {{ $analysis['recommendation'] }}</p>
    
    <p>Keywords:</p>
    @foreach($analysis['keywords']['include'] as $keyword)
        <span class="badge bg-success">✓ {{ $keyword }}</span>
    @endforeach
    @foreach($analysis['keywords']['exclude'] as $keyword)
        <span class="badge bg-danger">✗ {{ $keyword }}</span>
    @endforeach
</div>
@endif
```

**3. JavaScript Handler**
```javascript
$('#dietUserSelect').on('change', function() {
    var dietUserId = $(this).val();
    if (dietUserId) {
        var url = '/food-guide/recommend/' + dietUserId;
        window.location.href = url;
    }
});
```

---

## ✅ BENEFITS

### **For Users:**
- ✅ **Personalized recommendations** based on their health status
- ✅ **Automatic filtering** - no need to guess what to eat
- ✅ **Clear guidance** - know what to include/exclude
- ✅ **Time-saving** - instant recommendations
- ✅ **Educational** - learn about suitable foods

### **For System:**
- ✅ **Integrated features** - Diet User + Food Guide work together
- ✅ **Smart algorithm** - keyword extraction & filtering
- ✅ **Scalable** - easy to add more keywords/rules
- ✅ **Maintainable** - clean code structure
- ✅ **Flexible** - manual search still available

---

## 🧪 TESTING

### **Test Cases:**

1. **User with no diet data**
   - Expected: Only manual search available
   - No diet selection dropdown

2. **User with diet data**
   - Expected: Diet selection dropdown appears
   - Can select diet analysis

3. **Select Overweight diet**
   - Expected: Recommend low-calorie, high-fiber foods
   - Exclude high-sugar, high-fat foods

4. **Select Underweight diet**
   - Expected: Recommend high-calorie, high-protein foods
   - No exclusions

5. **Select Normal diet**
   - Expected: Balanced food recommendations
   - All categories included

6. **Manual search still works**
   - Expected: Can search independently
   - Not affected by diet selection

---

## 📝 FILES MODIFIED

### **1. Controller**
- `app/Http/Controllers/Home/FoodGuideController.php`
  - Added `recommend()` method
  - Added `extractKeywords()` method
  - Added `getFoodRecommendations()` method
  - Enhanced `index()` and `search()` methods

### **2. Routes**
- `routes/web.php`
  - Added route for `food-guide.recommend`

### **3. View**
- `resources/views/home/food-guide.blade.php`
  - Added diet selection section
  - Added analysis display
  - Added JavaScript handler
  - Preserved manual search

### **4. Translations**
- `resources/lang/id/home.php`
- `resources/lang/en/home.php`
  - Added new translation keys

---

## 🚀 FUTURE ENHANCEMENTS

### **Possible Improvements:**

1. **More Sophisticated Keyword Extraction**
   - NLP-based keyword extraction
   - Machine learning for better matching

2. **Meal Plan Integration**
   - Auto-generate meal plan from recommended foods
   - One-click to Meal Planner

3. **Nutrition Score**
   - Score each food based on user's needs
   - Rank by relevance

4. **Favorite Foods**
   - Save favorite recommendations
   - Quick access to preferred foods

5. **Shopping List**
   - Generate shopping list from recommendations
   - Export to PDF/print

---

## ✅ CONCLUSION

Food Guide sekarang **lebih dari sekedar search tool** - menjadi **smart recommendation system** yang:

- 🎯 **Personalized** untuk setiap user
- 🧠 **Intelligent** dengan keyword extraction
- 🔗 **Integrated** dengan Diet User
- 📊 **Data-driven** berdasarkan BMI & status gizi
- 👥 **User-friendly** dengan clear guidance

**Result:** User mendapatkan rekomendasi makanan yang **tepat** sesuai kebutuhan kesehatan mereka!

---

**Last Updated:** November 12, 2024  
**Version:** 2.0  
**Status:** ✅ Implemented & Ready for Testing
