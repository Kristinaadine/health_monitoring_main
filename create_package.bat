@echo off
REM ============================================================================
REM Script untuk Membuat Package Calorie Calculator Update (Windows)
REM ============================================================================
REM Usage: create_package.bat
REM Output: calorie_calculator_update.zip
REM ============================================================================

echo ==================================================
echo   Calorie Calculator - Package Creator
echo ==================================================
echo.

SET PACKAGE_NAME=calorie_calculator_update
SET OUTPUT_ZIP=%PACKAGE_NAME%.zip

REM Hapus folder lama jika ada
if exist "%PACKAGE_NAME%" (
    echo Menghapus folder lama...
    rmdir /s /q "%PACKAGE_NAME%"
)

REM Hapus zip lama jika ada
if exist "%OUTPUT_ZIP%" (
    echo Menghapus zip lama...
    del /q "%OUTPUT_ZIP%"
)

REM Buat struktur folder
echo Membuat struktur folder...
mkdir "%PACKAGE_NAME%\app\Http\Controllers\Home"
mkdir "%PACKAGE_NAME%\app\Models"
mkdir "%PACKAGE_NAME%\database\migrations"
mkdir "%PACKAGE_NAME%\resources\views\home\caloricalc"
mkdir "%PACKAGE_NAME%\docs"

REM Copy file controller
echo Copying CaloriCalcController.php...
copy "app\Http\Controllers\Home\CaloriCalcController.php" "%PACKAGE_NAME%\app\Http\Controllers\Home\" >nul

REM Copy file model
echo Copying CalorieHistoryModel.php...
copy "app\Models\CalorieHistoryModel.php" "%PACKAGE_NAME%\app\Models\" >nul

REM Copy migration
echo Copying migration file...
copy "database\migrations\*calorie_history*.php" "%PACKAGE_NAME%\database\migrations\" >nul

REM Copy views
echo Copying view files...
copy "resources\views\home\caloricalc\index.blade.php" "%PACKAGE_NAME%\resources\views\home\caloricalc\" >nul
copy "resources\views\home\caloricalc\form.blade.php" "%PACKAGE_NAME%\resources\views\home\caloricalc\" >nul

REM Copy dokumentasi
echo Copying documentation...
copy "database_changes_calorie_calculator.sql" "%PACKAGE_NAME%\docs\" >nul
copy "CHANGELOG_CALORIE_CALCULATOR.md" "%PACKAGE_NAME%\docs\" >nul
copy "COMPARISON_OLD_VS_NEW.md" "%PACKAGE_NAME%\docs\" >nul
copy "MIGRATION_GUIDE.md" "%PACKAGE_NAME%\docs\" >nul

REM Buat routes snippet
echo Creating routes snippet...
(
echo // ============================================================================
echo // CALORIE CALCULATOR ROUTES
echo // ============================================================================
echo // Tambahkan/ganti route ini di routes/web.php
echo // Cari bagian "CALORI CALCULATOR" dan ganti dengan ini:
echo.
echo // CALORI CALCULATOR
echo Route::get^('/caloric', [CaloriCalcController::class, 'index']^)->name^('caloric'^);
echo Route::get^('/caloric/create', [CaloriCalcController::class, 'create']^)->name^('caloric.create'^);
echo Route::post^('/caloric', [CaloriCalcController::class, 'store']^)->name^('caloric.store'^);
echo Route::delete^('/caloric/{id}', [CaloriCalcController::class, 'destroy']^)->name^('caloric.destroy'^);
echo.
echo // ============================================================================
) > "%PACKAGE_NAME%\routes_snippet.txt"

REM Buat README
echo Creating README...
(
echo =================================================================
echo CALORIE CALCULATOR - HISTORY FEATURE UPDATE
echo =================================================================
echo.
echo VERSION: 2.0
echo DATE: 13 November 2025
echo.
echo =================================================================
echo QUICK START
echo =================================================================
echo.
echo 1. BACKUP DATABASE
echo    mysqldump -u root -p database_name ^> backup.sql
echo.
echo 2. COPY FILE
echo    Copy semua file sesuai struktur folder
echo.
echo 3. UPDATE ROUTES
echo    Lihat routes_snippet.txt
echo.
echo 4. MIGRATE
echo    php artisan migrate
echo.
echo 5. CLEAR CACHE
echo    php artisan optimize:clear
echo.
echo =================================================================
echo DOKUMENTASI
echo =================================================================
echo.
echo Lihat folder docs/ untuk dokumentasi lengkap:
echo - MIGRATION_GUIDE.md
echo - CHANGELOG_CALORIE_CALCULATOR.md
echo - COMPARISON_OLD_VS_NEW.md
echo - database_changes_calorie_calculator.sql
echo.
echo =================================================================
) > "%PACKAGE_NAME%\README.txt"

REM Buat VERSION info
echo Creating version info...
(
echo Package: Calorie Calculator - History Feature
echo Version: 2.0.0
echo Release Date: 2025-11-13
echo Laravel Version: ^>= 9.x
echo PHP Version: ^>= 8.0
echo.
echo Changes:
echo - Added database storage
echo - Added history tracking
echo - Added empty state
echo - Added delete functionality
echo - Improved UX with AJAX
) > "%PACKAGE_NAME%\VERSION.txt"

REM Compress ke zip (menggunakan PowerShell)
echo Creating zip file...
powershell -command "Compress-Archive -Path '%PACKAGE_NAME%' -DestinationPath '%OUTPUT_ZIP%' -Force"

echo.
echo ==================================================
echo   Package Created Successfully!
echo ==================================================
echo.
echo Package: %OUTPUT_ZIP%
echo.
echo Files included:
echo   - Controller: CaloriCalcController.php
echo   - Model: CalorieHistoryModel.php
echo   - Migration: create_calorie_history_models_table.php
echo   - Views: index.blade.php, form.blade.php
echo   - Docs: 4 documentation files
echo   - Routes: routes_snippet.txt
echo   - README: README.txt
echo.
echo Next steps:
echo   1. Share %OUTPUT_ZIP% dengan tim
echo   2. Baca README.txt untuk instruksi
echo   3. Follow MIGRATION_GUIDE.md
echo.
echo ==================================================
echo.
echo Done! Package ready to share.
echo.
pause
