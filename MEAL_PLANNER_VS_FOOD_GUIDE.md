# 🍽️ MEAL PLANNER vs FOOD GUIDE - COMPARISON

## 📊 QUICK COMPARISON

| Aspek | **Meal Planner** 🍽️ | **Food Guide** 📖 |
|-------|---------------------|-------------------|
| **Tujuan** | Generate meal plan otomatis | Browse & search food database |
| **Fungsi Utama** | Rekomendasi menu harian | Informasi nutrisi makanan |
| **User Input** | Set nutrition target | Search by name/category |
| **Output** | 3 meals (breakfast, lunch, dinner) | List of foods with details |
| **Personalisasi** | ✅ Berdasarkan target kalori user | ❌ Tidak personal |
| **Algoritma** | ✅ Smart recommendation | ❌ Simple search/filter |
| **Interaktif** | ✅ Generate on-demand | ❌ Static display |

---

## 🎯 MEAL PLANNER

### **Konsep:**
Sistem rekomendasi menu harian yang **personalized** berdasarkan target nutrisi user.

### **Fitur Utama:**

1. **Nutrition Goal Setting**
   - User set target kalori (misal: 2000 kcal)
   - User pilih rasio nutrisi (protein, carbs, fat)
   - Tersimpan di profile user

2. **Smart Meal Generation**
   - Klik "Get Meal Plan"
   - Sistem generate 3 meals otomatis:
     - **Breakfast:** 20-25% dari total kalori
     - **Lunch:** 30-40% dari total kalori
     - **Dinner:** 20-35% dari total kalori
   - Random selection dari database

3. **Personalized Results**
   - Sesuai dengan target kalori user
   - Menampilkan total protein, carbs, fiber
   - Compare dengan nutrition goals

### **Workflow:**

```
User → Set Nutrition Target → Click "Get Meal Plan" → System Generate → Display 3 Meals
```

### **Example Output:**

```
Nutrition Goal:
- Calorie: 2000 kcal
- Protein: 30% goals
- Carbs: 50% goals
- Fat: 20% goals

Generated Meal Plan:
┌─────────────────────────────────────┐
│ BREAKFAST (400 kcal)                │
│ - Nasi Goreng                       │
│ - Protein: 15g, Carbs: 60g          │
├─────────────────────────────────────┤
│ LUNCH (700 kcal)                    │
│ - Ayam Bakar + Nasi                 │
│ - Protein: 35g, Carbs: 80g          │
├─────────────────────────────────────┤
│ DINNER (500 kcal)                   │
│ - Ikan Panggang + Sayur             │
│ - Protein: 30g, Carbs: 50g          │
└─────────────────────────────────────┘

Total: Protein 80g, Carbs 190g, Fiber 15g
```

### **Use Case:**

- ✅ User butuh rekomendasi menu harian
- ✅ User ingin mencapai target kalori tertentu
- ✅ User ingin variasi menu otomatis
- ✅ User sedang diet/program nutrisi

---

## 📖 FOOD GUIDE

### **Konsep:**
Database makanan yang bisa di-browse dan di-search untuk **informasi nutrisi**.

### **Fitur Utama:**

1. **Search & Filter**
   - Search by food name
   - Filter by category (Buah, Sayur, Lauk, dll)
   - Kombinasi search + filter

2. **Food Information Display**
   - Nama makanan
   - Kategori
   - Gambar
   - Nutrisi detail:
     - Protein (g)
     - Carbs (g)
     - Fiber (g)
     - Calories (kcal)
   - Deskripsi

3. **Browse All Foods**
   - Lihat semua makanan di database
   - Grid layout dengan card
   - No personalization

### **Workflow:**

```
User → Search/Filter → View Results → Read Information
```

### **Example Output:**

```
Search: "Ayam"
Filter: "Lauk"

Results:
┌─────────────────────────────────────┐
│ [IMG] Ayam Goreng                   │
│ Category: Lauk                      │
│ Protein: 25g                        │
│ Carbs: 10g                          │
│ Fiber: 2g                           │
│ Calorie: 250 kcal                   │
│ Description: Ayam goreng crispy...  │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│ [IMG] Ayam Bakar                    │
│ Category: Lauk                      │
│ Protein: 30g                        │
│ Carbs: 5g                           │
│ Fiber: 1g                           │
│ Calorie: 200 kcal                   │
│ Description: Ayam bakar bumbu...    │
└─────────────────────────────────────┘
```

### **Use Case:**

- ✅ User ingin tahu nutrisi makanan tertentu
- ✅ User browsing food database
- ✅ User cari alternatif makanan
- ✅ User butuh informasi untuk manual planning

---

## 🔍 KEY DIFFERENCES

### **1. PERSONALIZATION**

**Meal Planner:**
- ✅ **Personal:** Berdasarkan target kalori user
- ✅ **Dynamic:** Generate sesuai kebutuhan
- ✅ **Goal-oriented:** Mencapai target nutrisi

**Food Guide:**
- ❌ **Generic:** Sama untuk semua user
- ❌ **Static:** Hanya display data
- ❌ **Information-only:** Tidak ada goal

---

### **2. FUNCTIONALITY**

**Meal Planner:**
- ✅ **Active:** User klik → System generate
- ✅ **Smart Algorithm:** Calculate portion & distribution
- ✅ **Recommendation:** System suggest meals

**Food Guide:**
- ❌ **Passive:** User search → System display
- ❌ **Simple Query:** Basic search/filter
- ❌ **Information:** User decide sendiri

---

### **3. USER INTERACTION**

**Meal Planner:**
```
1. Set nutrition target (one-time)
2. Click "Get Meal Plan"
3. Receive 3 meals recommendation
4. Can regenerate for different options
```

**Food Guide:**
```
1. Enter search keyword (optional)
2. Select category (optional)
3. Click "Search"
4. Browse results
5. Read information
```

---

### **4. OUTPUT**

**Meal Planner:**
- **Structured:** Always 3 meals (breakfast, lunch, dinner)
- **Calculated:** Total nutrition displayed
- **Balanced:** Distribution sesuai meal timing
- **Complete:** Full day meal plan

**Food Guide:**
- **Flexible:** 0 to many results
- **Individual:** Per-food information
- **Unstructured:** No meal planning
- **Reference:** Just data

---

## 💡 WHEN TO USE WHICH?

### **Use MEAL PLANNER when:**

✅ User butuh **rekomendasi menu harian**
✅ User punya **target kalori/nutrisi** tertentu
✅ User ingin **meal planning otomatis**
✅ User sedang **diet/program nutrisi**
✅ User ingin **variasi menu** tanpa mikir

**Example Scenario:**
> "Saya target 2000 kalori per hari, tolong buatkan menu untuk hari ini"

---

### **Use FOOD GUIDE when:**

✅ User ingin **cari informasi nutrisi** makanan tertentu
✅ User **browsing** food database
✅ User butuh **referensi** untuk manual planning
✅ User ingin **compare** nutrisi antar makanan
✅ User **explore** food options

**Example Scenario:**
> "Berapa kalori ayam goreng? Apa alternatif protein tinggi lainnya?"

---

## 🔄 RELATIONSHIP

### **Complementary Features:**

```
Food Guide → Meal Planner
   ↓              ↓
Browse Food → Set Target → Generate Plan
   ↓              ↓              ↓
Learn Info → Define Goal → Get Recommendation
```

### **User Journey:**

1. **Discovery (Food Guide)**
   - User browse food database
   - Learn about nutrition
   - Understand food options

2. **Planning (Meal Planner)**
   - User set nutrition target
   - Generate meal plan
   - Follow recommendations

3. **Execution**
   - User follow meal plan
   - Track nutrition
   - Adjust as needed

---

## 📊 TECHNICAL COMPARISON

### **Database Usage:**

**Meal Planner:**
```php
// Smart query with calorie range
$breakfast = FoodModel::where('calories', '>=', $target * 0.2)
                      ->where('calories', '<=', $target * 0.25)
                      ->get();
// Random selection
$selected = $breakfast[array_rand($breakfast->toArray())];
```

**Food Guide:**
```php
// Simple search/filter
$food = FoodModel::where('name_food', 'LIKE', '%' . $search . '%')
                 ->where('id_categories', $category)
                 ->get();
```

---

### **User Data Dependency:**

**Meal Planner:**
- ✅ **Requires:** User nutrition target
- ✅ **Stores:** User preferences
- ✅ **Uses:** User profile data

**Food Guide:**
- ❌ **No requirement:** Works without login
- ❌ **No storage:** No user data needed
- ❌ **Generic:** Same for everyone

---

## ✅ SUMMARY

### **MEAL PLANNER** 🍽️

**Purpose:** Personalized meal recommendation system

**Key Features:**
- Smart meal generation
- Calorie-based distribution
- Nutrition goal tracking
- 3-meal daily plan

**Best For:**
- Diet programs
- Nutrition planning
- Goal achievement
- Automated recommendations

---

### **FOOD GUIDE** 📖

**Purpose:** Food database & nutrition information

**Key Features:**
- Search & filter foods
- Nutrition information
- Category browsing
- Food comparison

**Best For:**
- Learning about foods
- Manual meal planning
- Nutrition research
- Food exploration

---

## 🎯 CONCLUSION

**Meal Planner** dan **Food Guide** adalah **dua fitur berbeda** dengan tujuan yang **complementary**:

- **Meal Planner** = **"Buatkan saya menu"** (Active, Personal, Recommendation)
- **Food Guide** = **"Tunjukkan info makanan"** (Passive, Generic, Information)

Keduanya bekerja sama untuk memberikan **complete nutrition management experience**:
1. **Learn** (Food Guide) → **Plan** (Meal Planner) → **Execute** (Nutrition Monitoring)

---

**Last Updated:** November 12, 2024  
**Version:** 1.0
