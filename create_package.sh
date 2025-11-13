#!/bin/bash

# ============================================================================
# Script untuk Membuat Package Calorie Calculator Update
# ============================================================================
# Usage: bash create_package.sh
# Output: calorie_calculator_update.zip
# ============================================================================

echo "=================================================="
echo "  Calorie Calculator - Package Creator"
echo "=================================================="
echo ""

# Nama folder dan file output
PACKAGE_NAME="calorie_calculator_update"
OUTPUT_ZIP="${PACKAGE_NAME}.zip"

# Hapus folder lama jika ada
if [ -d "$PACKAGE_NAME" ]; then
    echo "🗑️  Menghapus folder lama..."
    rm -rf "$PACKAGE_NAME"
fi

# Hapus zip lama jika ada
if [ -f "$OUTPUT_ZIP" ]; then
    echo "🗑️  Menghapus zip lama..."
    rm -f "$OUTPUT_ZIP"
fi

# Buat struktur folder
echo "📁 Membuat struktur folder..."
mkdir -p "$PACKAGE_NAME/app/Http/Controllers/Home"
mkdir -p "$PACKAGE_NAME/app/Models"
mkdir -p "$PACKAGE_NAME/database/migrations"
mkdir -p "$PACKAGE_NAME/resources/views/home/caloricalc"
mkdir -p "$PACKAGE_NAME/docs"

# Copy file controller
echo "📄 Copying CaloriCalcController.php..."
cp app/Http/Controllers/Home/CaloriCalcController.php "$PACKAGE_NAME/app/Http/Controllers/Home/"

# Copy file model
echo "📄 Copying CalorieHistoryModel.php..."
cp app/Models/CalorieHistoryModel.php "$PACKAGE_NAME/app/Models/"

# Copy migration
echo "📄 Copying migration file..."
cp database/migrations/*calorie_history*.php "$PACKAGE_NAME/database/migrations/"

# Copy views
echo "📄 Copying view files..."
cp resources/views/home/caloricalc/index.blade.php "$PACKAGE_NAME/resources/views/home/caloricalc/"
cp resources/views/home/caloricalc/form.blade.php "$PACKAGE_NAME/resources/views/home/caloricalc/"

# Copy dokumentasi
echo "📄 Copying documentation..."
cp database_changes_calorie_calculator.sql "$PACKAGE_NAME/docs/"
cp CHANGELOG_CALORIE_CALCULATOR.md "$PACKAGE_NAME/docs/"
cp COMPARISON_OLD_VS_NEW.md "$PACKAGE_NAME/docs/"
cp MIGRATION_GUIDE.md "$PACKAGE_NAME/docs/"

# Buat routes snippet
echo "📄 Creating routes snippet..."
cat > "$PACKAGE_NAME/routes_snippet.txt" << 'EOF'
// ============================================================================
// CALORIE CALCULATOR ROUTES
// ============================================================================
// Tambahkan/ganti route ini di routes/web.php
// Cari bagian "CALORI CALCULATOR" dan ganti dengan ini:

// CALORI CALCULATOR
Route::get('/caloric', [CaloriCalcController::class, 'index'])->name('caloric');
Route::get('/caloric/create', [CaloriCalcController::class, 'create'])->name('caloric.create');
Route::post('/caloric', [CaloriCalcController::class, 'store'])->name('caloric.store');
Route::delete('/caloric/{id}', [CaloriCalcController::class, 'destroy'])->name('caloric.destroy');

// ============================================================================
EOF

# Buat README
echo "📄 Creating README..."
cat > "$PACKAGE_NAME/README.txt" << 'EOF'
=================================================================
CALORIE CALCULATOR - HISTORY FEATURE UPDATE
=================================================================

VERSION: 2.0
DATE: 13 November 2025
AUTHOR: Development Team

=================================================================
DESKRIPSI
=================================================================

Update ini menambahkan fitur history untuk Calorie Calculator.
User sekarang bisa menyimpan dan melihat riwayat perhitungan
kalori mereka.

FITUR BARU:
✓ Penyimpanan data ke database
✓ History perhitungan lengkap
✓ Empty state untuk user baru
✓ Delete history dengan konfirmasi
✓ AJAX operations untuk UX yang smooth

=================================================================
QUICK START (5 LANGKAH)
=================================================================

1. BACKUP DATABASE
   mysqldump -u root -p database_name > backup.sql

2. COPY FILE
   Copy semua file sesuai struktur folder ke project Anda

3. UPDATE ROUTES
   Buka routes/web.php
   Lihat routes_snippet.txt untuk kode yang harus ditambahkan

4. MIGRATE DATABASE
   php artisan migrate

5. CLEAR CACHE & TEST
   php artisan optimize:clear
   Akses: http://localhost/id/caloric

=================================================================
STRUKTUR FILE
=================================================================

app/
├── Http/Controllers/Home/
│   └── CaloriCalcController.php (MODIFIED)
└── Models/
    └── CalorieHistoryModel.php (NEW)

database/migrations/
└── 2025_11_13_045344_create_calorie_history_models_table.php (NEW)

resources/views/home/caloricalc/
├── index.blade.php (NEW)
└── form.blade.php (NEW)

docs/
├── MIGRATION_GUIDE.md (Panduan lengkap)
├── CHANGELOG_CALORIE_CALCULATOR.md (Detail perubahan)
├── COMPARISON_OLD_VS_NEW.md (Perbandingan versi)
└── database_changes_calorie_calculator.sql (SQL reference)

=================================================================
DOKUMENTASI LENGKAP
=================================================================

Untuk panduan lengkap, baca file di folder docs/:

1. MIGRATION_GUIDE.md
   - Step-by-step installation
   - Troubleshooting
   - Verification checklist

2. CHANGELOG_CALORIE_CALCULATOR.md
   - Perubahan detail
   - Breaking changes
   - Migration notes

3. COMPARISON_OLD_VS_NEW.md
   - Perbandingan visual
   - Code comparison
   - Feature comparison

4. database_changes_calorie_calculator.sql
   - Database structure
   - Sample queries
   - Rollback instructions

=================================================================
REQUIREMENTS
=================================================================

- PHP >= 8.0
- Laravel >= 9.x
- MySQL/MariaDB
- Tabel 'users' sudah ada (untuk foreign key)

=================================================================
TROUBLESHOOTING
=================================================================

Problem: Migration error "Table already exists"
Solution: php artisan migrate:rollback --step=1

Problem: Class not found
Solution: composer dump-autoload

Problem: Route not found
Solution: php artisan route:clear

Problem: View not found
Solution: php artisan view:clear

Untuk troubleshooting lengkap, lihat MIGRATION_GUIDE.md

=================================================================
ROLLBACK (Jika Diperlukan)
=================================================================

1. Rollback migration:
   php artisan migrate:rollback --step=1

2. Restore file lama dari backup

3. Clear cache:
   php artisan optimize:clear

=================================================================
SUPPORT
=================================================================

Jika mengalami masalah:
1. Cek MIGRATION_GUIDE.md bagian Troubleshooting
2. Cek Laravel log: storage/logs/laravel.log
3. Cek migration status: php artisan migrate:status

=================================================================
TESTING CHECKLIST
=================================================================

Setelah instalasi, test fitur berikut:

[ ] Akses /id/caloric → Muncul empty state atau history
[ ] Klik "Tambah Data" → Redirect ke form
[ ] Isi form → Hasil kalkulasi muncul
[ ] Klik "Simpan" → Data tersimpan
[ ] Lihat history → Data muncul di list
[ ] Klik "Hapus" → Konfirmasi muncul
[ ] Konfirmasi hapus → Data terhapus

=================================================================
CHANGELOG SUMMARY
=================================================================

ADDED:
+ Tabel database: calorie_history_models
+ Model: CalorieHistoryModel
+ Controller methods: create(), store(), destroy()
+ Views: index.blade.php, form.blade.php
+ Routes: 3 new routes (create, store, destroy)
+ Features: History, Empty state, Delete confirmation

MODIFIED:
~ CaloriCalcController::index() - now shows history
~ routes/web.php - added new routes

REMOVED:
- resources/views/home/caloricalc.blade.php (moved to folder)

=================================================================

Estimasi waktu instalasi: 10-15 menit

Happy Coding! 🚀

=================================================================
EOF

# Buat file version info
echo "📄 Creating version info..."
cat > "$PACKAGE_NAME/VERSION.txt" << 'EOF'
Package: Calorie Calculator - History Feature
Version: 2.0.0
Release Date: 2025-11-13
Laravel Version: >= 9.x
PHP Version: >= 8.0

Changes:
- Added database storage for calorie calculations
- Added history tracking feature
- Added empty state for new users
- Added delete functionality with confirmation
- Improved UX with AJAX operations

Files Modified: 2
Files Added: 6
Database Tables Added: 1
Routes Added: 3
EOF

# Compress ke zip
echo "📦 Creating zip file..."
zip -r "$OUTPUT_ZIP" "$PACKAGE_NAME" > /dev/null 2>&1

# Hitung ukuran
SIZE=$(du -h "$OUTPUT_ZIP" | cut -f1)

echo ""
echo "=================================================="
echo "  ✅ Package Created Successfully!"
echo "=================================================="
echo ""
echo "📦 Package: $OUTPUT_ZIP"
echo "📊 Size: $SIZE"
echo "📁 Files included:"
echo "   - Controller: CaloriCalcController.php"
echo "   - Model: CalorieHistoryModel.php"
echo "   - Migration: create_calorie_history_models_table.php"
echo "   - Views: index.blade.php, form.blade.php"
echo "   - Docs: 4 documentation files"
echo "   - Routes: routes_snippet.txt"
echo "   - README: README.txt"
echo ""
echo "📝 Next steps:"
echo "   1. Share $OUTPUT_ZIP dengan tim"
echo "   2. Instruksikan mereka untuk baca README.txt"
echo "   3. Follow MIGRATION_GUIDE.md untuk instalasi"
echo ""
echo "=================================================="
echo ""

# List isi package
echo "📋 Package contents:"
unzip -l "$OUTPUT_ZIP" | tail -n +4 | head -n -2

echo ""
echo "✨ Done! Package ready to share."
echo ""
