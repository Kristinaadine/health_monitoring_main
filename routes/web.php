<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\BMICalculator;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Admin\FoodCatController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Home\StuntingController;
use App\Http\Controllers\Home\FoodGuideController;
use App\Http\Controllers\Admin\FoodAdminController;
use App\Http\Controllers\Admin\HomeAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Home\CaloriCalcController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Home\MealPlannerController;
use App\Http\Controllers\Admin\NutrientAdminController;
use App\Http\Controllers\Monitoring\ChildrenController;
use App\Http\Controllers\Monitoring\DietUserController;
use App\Http\Controllers\Monitoring\FoodChildrenController;
use App\Http\Controllers\Monitoring\GrowthChildrenController;
use App\Http\Controllers\Monitoring\StuntingGrowthController;
use App\Http\Controllers\Monitoring\GrowthDetectionController;
use App\Http\Controllers\Monitoring\GrowthMonitoringController;
use App\Http\Controllers\Monitoring\NutritionMonitoringController;
use App\Http\Controllers\Monitoring\PreStuntingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route dibungkus prefix {locale} agar mendukung multi bahasa
| seperti /en/home dan /id/home
|
*/

// Jika user akses tanpa prefix bahasa, otomatis redirect ke /id/...
Route::fallback(function () {
    $uri = request()->path();
    return redirect('/id/' . ltrim($uri, '/'));
});

Route::prefix('{locale}')
    ->where(['locale' => 'en|id'])
    ->middleware(['setLocale'])
    ->group(function () {

    // DASHBOARD
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // AUTH
    Route::get('/login', [AuthController::class, 'login_index'])->name('login');
    Route::post('/login', [AuthController::class, 'login_store'])->name('login.login');
    Route::get('/signup', [AuthController::class, 'signup_index'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup_store'])->name('signup.store');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth'])->group(function () {
        // HOME
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        // BMI CALCULATOR
        Route::get('/bmi', [BMICalculator::class, 'index'])->name('bmi');

        // CALORI CALCULATOR
        Route::get('/caloric', [CaloriCalcController::class, 'index'])->name('caloric');
        Route::get('/caloric/create', [CaloriCalcController::class, 'create'])->name('caloric.create');
        Route::post('/caloric', [CaloriCalcController::class, 'store'])->name('caloric.store');
        Route::delete('/caloric/{id}', [CaloriCalcController::class, 'destroy'])->name('caloric.destroy');

        // STUNTING INFO
        Route::get('/stunting-info', [StuntingController::class, 'index'])->name('stunting');

        // PROFILE
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.profile-edit');
        Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.profile-update');
        Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.profile-change-password');
        Route::post('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.profile-change-password-update');
        Route::get('/profile/nutrition', [ProfileController::class, 'nutrition'])->name('profile.nutrition');
        Route::post('/profile/nutrition', [ProfileController::class, 'nutritionUpdate'])->name('profile.nutrition-update');
        Route::get('/profile/help', [ProfileController::class, 'help'])->name('profile.help');

        // MEAL PLANNER
        Route::get('/meal-planner', [MealPlannerController::class, 'index'])->name('meal-planner');
        Route::post('/meal-planner', [MealPlannerController::class, 'updateNutrition'])->name('meal-planner.update-nutrition');
        Route::get('/meal-planner/get-meal', [MealPlannerController::class, 'getMeal'])->name('meal-planner.get-meal');

        // FOOD GUIDE
        Route::get('/food-guide', [FoodGuideController::class, 'index'])->name('food-guide');
        Route::post('/food-guide', [FoodGuideController::class, 'search'])->name('food-guide.search');
        Route::get('/food-guide/recommend/{dietUserId}', [FoodGuideController::class, 'recommend'])->name('food-guide.recommend');

        // GROWTH MONITORING
        Route::get('/growth-monitoring', [GrowthMonitoringController::class, 'index'])->name('growth-monitoring.index');
        Route::post('/growth-monitoring', [GrowthMonitoringController::class, 'store'])->name('growth-monitoring.store');
        Route::get('/growth-monitoring/{id}', [GrowthMonitoringController::class, 'show'])->name('growth-monitoring.show');
        Route::get('/growth-monitoring/{id}/download-report', [GrowthMonitoringController::class, 'downloadReport'])->name('growth-monitoring.download-report');
        Route::delete('/growth-monitoring/{id}', [GrowthMonitoringController::class, 'destroy'])->name('growth-monitoring.destroy');

        // GROWTH DETECTION
        Route::get('/growth-detection', [GrowthDetectionController::class, 'index'])->name('growth-detection.index');
        Route::get('/growth-detection/diet-user', [GrowthDetectionController::class, 'dietUser'])->name('growth-detection.diet-user');
        Route::post('/diet-user', [DietUserController::class, 'store'])->name('growth-detection.diet-user.store');
        Route::get('/diet-user/{dietUser}', [DietUserController::class, 'show'])->name('growth-detection.diet-user.show');
        Route::get('/growth-detection/list', [DietUserController::class, 'list'])->name('growth-detection.diet-user.list');
        Route::get('/growth-detection/stunting', [GrowthDetectionController::class, 'stunting'])->name('growth-detection.stunting');
        Route::get('/growth-detection/stunting/create', [StuntingGrowthController::class, 'create'])->name('growth-detection.stunting.create');
        Route::post('/growth-detection/stunting', [StuntingGrowthController::class, 'store'])->name('growth-detection.stunting.store');
        Route::get('/growth-detection/stunting/result/{id}', [StuntingGrowthController::class, 'result'])->name('growth-detection.stunting.result');

        /*
        |--------------------------------------------------------------------------
        | PRE-STUNTING MODULE
        |--------------------------------------------------------------------------
        */
        Route::prefix('/growth-detection/pre-stunting')->group(function () {
            Route::get('/', [PreStuntingController::class, 'index'])
                ->name('growth-detection.pre-stunting.index');

            Route::get('/create', [PreStuntingController::class, 'create'])
                ->name('growth-detection.pre-stunting.create');

            Route::post('/calculate', [PreStuntingController::class, 'calculateRiskScore'])
                ->name('growth-detection.pre-stunting.calculate');

            Route::get('/{id}/edit', [PreStuntingController::class, 'edit'])
                ->where('id', '.*')
                ->name('growth-detection.pre-stunting.edit');

            Route::put('/{id}', [PreStuntingController::class, 'update'])
                ->where('id', '.*')
                ->name('growth-detection.pre-stunting.update');

            Route::delete('/{id}', [PreStuntingController::class, 'destroy'])
                ->where('id', '.*')
                ->name('growth-detection.pre-stunting.destroy');

            Route::get('/{id}', [PreStuntingController::class, 'show'])
                ->where('id', '.*')
                ->name('growth-detection.pre-stunting.result');
        });

        // NUTRITION MONITORING
        Route::get('/nutrition-monitoring', [NutritionMonitoringController::class, 'index'])->name('nutrition-monitoring.index');
        Route::group(['as' => 'nutrition-monitoring.', 'prefix' => 'nutrition-monitoring'], function () {
            Route::resource('children', ChildrenController::class);
            Route::prefix('children/{child}')->group(function(){
                Route::get('growth', [GrowthChildrenController::class,'index'])->name('children.growth.index');
                Route::get('growth/create', [GrowthChildrenController::class,'create'])->name('children.growth.create');
                Route::post('growth', [GrowthChildrenController::class,'store'])->name('children.growth.store');
                Route::put('growth/{id}', [GrowthChildrenController::class,'update'])->name('children.growth.update');
                Route::delete('growth/{id}', [GrowthChildrenController::class,'destroy'])->name('children.growth.destroy');

                Route::get('food', [FoodChildrenController::class,'index'])->name('children.food.index');
                Route::get('food/create', [FoodChildrenController::class,'create'])->name('children.food.create');
                Route::post('food', [FoodChildrenController::class,'store'])->name('children.food.store');
            });
        });

        // ADMIN
        Route::group(['as' => 'administration.', 'prefix' => 'administration'], function () {
            Route::get('/', [HomeAdminController::class, 'index'])->name('home');
            Route::resource('nutrient', NutrientAdminController::class);
            Route::resource('food', FoodAdminController::class);
            Route::resource('food-categories', FoodCatController::class);
            Route::resource('user', UserAdminController::class);
            Route::group(['as' => 'setting.', 'prefix' => 'setting'], function () {
                Route::get('/', [SettingController::class, 'index'])->name('index');
                Route::put('/', [SettingController::class, 'update'])->name('update');
            });
        });
    });
});