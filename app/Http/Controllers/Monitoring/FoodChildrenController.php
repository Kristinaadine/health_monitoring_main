<?php

namespace App\Http\Controllers\monitoring;

use Exception;
use Carbon\Carbon;
use App\Models\AlertModel;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\ChildrenModel;
use App\Models\FoodLogsModel;
use App\Http\Controllers\Controller;
use App\Models\NutritionTargetModel;
use Illuminate\Support\Facades\Http;

class FoodChildrenController extends Controller
{
    public function index($locale, $childId)
    {
        $setting = SettingModel::all();
        $child = ChildrenModel::findOrFail(decrypt($childId));
        $foods = FoodLogsModel::where('child_id', decrypt($childId))->orderBy('tanggal', 'desc')->get();
        return view('monitoring.nutrition-monitoring.food.index', compact('setting', 'child', 'foods'));
    }

    public function create($locale, $childId)
    {
        $setting = SettingModel::all();
        $child = ChildrenModel::findOrFail(decrypt($childId));
        return view('monitoring.nutrition-monitoring.food.form', compact('setting', 'child'));
    }

    public function tanggal($tgl)
    {
        $bulanIndonesia = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December',
        ];

        $tanggalIndo = $tgl;
        foreach ($bulanIndonesia as $indo => $eng) {
            $tanggalIndo = str_replace($indo, $eng, $tanggalIndo);
        }

        return $tanggalIndo;
    }

    public function store(Request $request, $locale, $childId)
    {
        $request->validate(
            [
                'tanggal' => 'required',
                'nama_makanan' => 'required|string|max:255',
                'porsi' => 'nullable|string',
                'foto' => 'nullable|image',
                'kalori' => 'nullable|numeric',
                'karbo' => 'nullable|numeric',
                'protein' => 'nullable|numeric',
                'lemak' => 'nullable|numeric',
            ],
            [
                'tanggal.required' => 'Tanggal wajib diisi.',

                'nama_makanan.required' => 'Nama makanan wajib diisi.',
                'nama_makanan.string' => 'Nama makanan harus berupa teks.',
                'nama_makanan.max' => 'Nama makanan maksimal 255 karakter.',

                'porsi.string' => 'Porsi harus berupa teks.',

                'foto.image' => 'File yang diunggah harus berupa gambar.',

                'kalori.numeric' => 'Kalori harus berupa angka.',
                'karbo.numeric' => 'Karbohidrat harus berupa angka.',
                'protein.numeric' => 'Protein harus berupa angka.',
                'lemak.numeric' => 'Lemak harus berupa angka.',
            ],
        );

        $data = [
            'child_id' => decrypt($childId),
            'tanggal' => Carbon::createFromFormat('d F Y', $this->tanggal($request->tanggal))->format('Y-m-d'),
            'nama_makanan' => $request->nama_makanan,
            'porsi' => $request->porsi,
            'foto' => $request->hasFile('foto') ? $request->file('foto')->store('food_photos', 'public') : null,
        ];

        if ($request->hasFile('foto')) {
            // --- Hit API Nutritionix pakai foto ---
            $nutritionData = $this->getNutritionFromImage($data['foto'], $request->nama_makanan);
            $data['kalori'] = $nutritionData['kalori'] ?? 0;
            $data['karbo'] = $nutritionData['karbo'] ?? 0;
            $data['protein'] = $nutritionData['protein'] ?? 0;
            $data['lemak'] = $nutritionData['lemak'] ?? 0;
        } else {
            // --- User input manual ---
            $data['kalori'] = $request->kalori;
            $data['karbo'] = $request->karbo;
            $data['protein'] = $request->protein;
            $data['lemak'] = $request->lemak;
        }

        FoodLogsModel::create($data);

        $totalToday = FoodLogsModel::where('child_id', $data['child_id'])->whereDate('tanggal', $data['tanggal'])->selectRaw('SUM(kalori) as kalori, SUM(karbo) as karbo, SUM(protein) as protein, SUM(lemak) as lemak')->first();

        $target = NutritionTargetModel::where('child_id', $data['child_id'])->first();

        if ($target) {
            $alerts = [];

            if ($totalToday->kalori < $target->kalori * 0.8) {
                $alerts[] = 'Asupan kalori hari ini masih di bawah 80% target';
            }

            if ($totalToday->protein < $target->protein * 0.8) {
                $alerts[] = 'Asupan protein hari ini masih di bawah 80% target';
            }

            if ($totalToday->lemak < $target->lemak * 0.8) {
                $alerts[] = 'Asupan lemak hari ini masih di bawah 80% target';
            }

            foreach ($alerts as $pesan) {
                AlertModel::create([
                    'child_id' => $data['child_id'],
                    'tipe' => 'Nutrisi',
                    'pesan' => $pesan,
                ]);
            }
        }

        return redirect()->to(locale_route('nutrition-monitoring.children.food.index', $childId))->with('success', 'Log makanan berhasil ditambahkan!');
    }

    private function getNutritionFromImage($path, $nama)
    {
        try {
            $filePath = storage_path('app/public/' . $path);
            // dd($filePath);
            $response = Http::withHeaders([
                'x-app-id' => env('NUTRITIONIX_APP_ID'),
                'x-app-key' => env('NUTRITIONIX_APP_KEY'),
            ])->post('https://trackapi.nutritionix.com/v2/natural/nutrients', [
                'query' => $nama,
            ]);

            if ($response->successful()) {
                $food = $response->json()['foods'][0];
                return [
                    'kalori' => $food['nf_calories'] ?? 0,
                    'karbo' => $food['nf_total_carbohydrate'] ?? 0,
                    'protein' => $food['nf_protein'] ?? 0,
                    'lemak' => $food['nf_total_fat'] ?? 0,
                ];
            }
            dd($response->status(), $response->body());
            return [];
        } catch (Exception $e) {
            return [];
        }
    }
}
