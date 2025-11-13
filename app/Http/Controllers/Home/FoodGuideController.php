<?php

namespace App\Http\Controllers\Home;

use App\Models\FoodModel;
use App\Models\FoodCatModel;
use App\Models\SettingModel;
use App\Models\DietUserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FoodGuideController extends Controller
{
    public function index(Request $request)
    {
        $setting = SettingModel::all();
        $categories = FoodCatModel::all();
        $food = [];
        
        // Get user's diet data for recommendations
        $dietUsers = DietUserModel::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home.food-guide', compact('setting', 'categories', 'food', 'dietUsers'));
    }

    public function search(Request $request)
    {
        $setting = SettingModel::all();
        $categories = FoodCatModel::all();
        $food = [];
        
        // Get user's diet data for recommendations
        $dietUsers = DietUserModel::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        if (!empty($request->get('name_food')) || !empty($request->get('id_categories'))) {
            $food = FoodModel::orderBy('id', 'asc');
            if (!empty($request->get('name_food'))) {
                $food->where('name_food', 'LIKE', '%' . $request->get('name_food') . '%');
            }

            if (!empty($request->get('id_categories'))) {
                $food->where('id_categories', $request->get('id_categories'));
            }
            $food = $food->get();
        }
        return view('home.food-guide', compact('setting', 'categories', 'food', 'dietUsers'));
    }
    
    /**
     * Get food recommendations based on diet user analysis
     */
    public function recommend(Request $request, $locale, $dietUserId)
    {
        try {
            $setting = SettingModel::all();
            $categories = FoodCatModel::all();
            
            // Get diet user data
            $dietUser = DietUserModel::where('id', $dietUserId)
                ->where('user_id', auth()->user()->id)
                ->firstOrFail();
            
            // Extract keywords from recommendation
            $keywords = $this->extractKeywords($dietUser->rekomendasi, $dietUser->status_gizi);
            
            // Get recommended foods based on keywords
            $food = $this->getFoodRecommendations($keywords, $dietUser->status_gizi);
            
            // Analysis summary
            $analysis = [
                'name' => $dietUser->nama,
                'bmi' => $dietUser->bmi,
                'status' => $dietUser->status_gizi,
                'recommendation' => $dietUser->rekomendasi,
                'keywords' => $keywords,
                'food_count' => $food->count()
            ];
            
            $dietUsers = DietUserModel::where('user_id', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('home.food-guide', compact('setting', 'categories', 'food', 'dietUsers', 'analysis', 'dietUser'));
            
        } catch (\Exception $e) {
            \Log::error('Error getting food recommendations: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mengambil rekomendasi makanan.');
        }
    }
    
    /**
     * Extract keywords from recommendation text
     */
    private function extractKeywords(string $recommendation, string $status): array
    {
        $keywords = [
            'include' => [],  // Foods to recommend
            'exclude' => [],  // Foods to avoid
            'categories' => [] // Food categories to focus on
        ];
        
        // Keyword mapping based on nutritional status
        $keywordMap = [
            'Kurus' => [
                'include' => ['protein', 'kalori', 'daging', 'telur', 'susu', 'kacang', 'nasi'],
                'categories' => ['Lauk', 'Sarapan']
            ],
            'Normal' => [
                'include' => ['sayur', 'buah', 'protein', 'seimbang'],
                'categories' => ['Sayur', 'Buah', 'Lauk']
            ],
            'Overweight' => [
                'include' => ['sayur', 'buah', 'serat', 'rendah kalori'],
                'exclude' => ['gula', 'lemak', 'gorengan', 'manis'],
                'categories' => ['Sayur', 'Buah']
            ],
            'Obesitas' => [
                'include' => ['sayur', 'buah', 'serat', 'rendah kalori', 'rendah lemak'],
                'exclude' => ['gula', 'lemak', 'gorengan', 'manis', 'tinggi kalori'],
                'categories' => ['Sayur', 'Buah']
            ]
        ];
        
        // Get keywords based on status
        if (isset($keywordMap[$status])) {
            $keywords = array_merge($keywords, $keywordMap[$status]);
        }
        
        // Additional keyword extraction from recommendation text
        $recLower = strtolower($recommendation);
        
        // Check for specific food mentions
        if (strpos($recLower, 'protein') !== false) {
            $keywords['include'][] = 'protein';
        }
        if (strpos($recLower, 'sayur') !== false) {
            $keywords['include'][] = 'sayur';
            $keywords['categories'][] = 'Sayur';
        }
        if (strpos($recLower, 'buah') !== false) {
            $keywords['include'][] = 'buah';
            $keywords['categories'][] = 'Buah';
        }
        if (strpos($recLower, 'gula') !== false || strpos($recLower, 'manis') !== false) {
            $keywords['exclude'][] = 'gula';
            $keywords['exclude'][] = 'manis';
        }
        if (strpos($recLower, 'lemak') !== false || strpos($recLower, 'gorengan') !== false) {
            $keywords['exclude'][] = 'lemak';
            $keywords['exclude'][] = 'goreng';
        }
        
        // Remove duplicates
        $keywords['include'] = array_unique($keywords['include']);
        $keywords['exclude'] = array_unique($keywords['exclude']);
        $keywords['categories'] = array_unique($keywords['categories']);
        
        return $keywords;
    }
    
    /**
     * Get food recommendations based on extracted keywords
     */
    private function getFoodRecommendations(array $keywords, string $status)
    {
        $query = FoodModel::query();
        
        // Filter by categories if specified
        if (!empty($keywords['categories'])) {
            $categoryIds = FoodCatModel::whereIn('name', $keywords['categories'])->pluck('id');
            if ($categoryIds->isNotEmpty()) {
                $query->whereIn('id_categories', $categoryIds);
            }
        }
        
        // Include foods with specific keywords
        if (!empty($keywords['include'])) {
            $query->where(function($q) use ($keywords) {
                foreach ($keywords['include'] as $keyword) {
                    $q->orWhere('name_food', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('description', 'LIKE', '%' . $keyword . '%');
                }
            });
        }
        
        // Exclude foods with specific keywords
        if (!empty($keywords['exclude'])) {
            foreach ($keywords['exclude'] as $keyword) {
                $query->where('name_food', 'NOT LIKE', '%' . $keyword . '%')
                      ->where('description', 'NOT LIKE', '%' . $keyword . '%');
            }
        }
        
        // Additional filtering based on nutritional status
        switch ($status) {
            case 'Kurus':
                // Prioritize high-calorie foods
                $query->where('calories', '>=', 200);
                break;
                
            case 'Overweight':
            case 'Obesitas':
                // Prioritize low-calorie, high-fiber foods
                $query->where('calories', '<=', 300)
                      ->orderBy('fiber', 'desc');
                break;
                
            case 'Normal':
                // Balanced selection
                $query->whereBetween('calories', [100, 400]);
                break;
        }
        
        return $query->orderBy('calories', 'asc')->get();
    }
}
