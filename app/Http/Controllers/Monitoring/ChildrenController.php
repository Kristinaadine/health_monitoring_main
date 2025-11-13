<?php

namespace App\Http\Controllers\monitoring;

use Carbon\Carbon;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\ChildrenModel;
use App\Models\FoodLogsModel;
use App\Models\GrowthLogsModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\NutritionTargetModel;

class ChildrenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = SettingModel::all();
        $children = ChildrenModel::where('user_id', auth()->user()->id)->get();
        return view('monitoring.nutrition-monitoring.children.index', compact('children', 'setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setting = SettingModel::all();
        return view('monitoring.nutrition-monitoring.children.form', compact('setting'));
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama' => 'required|string|max:255',
                'tanggal_lahir' => 'required',
                'jenis_kelamin' => 'required|in:L,P',
                'kalori' => 'nullable|numeric',
                'karbo' => 'nullable|numeric',
                'protein' => 'nullable|numeric',
                'lemak' => 'nullable|numeric',
            ],
            [
                'nama.required' => 'Nama wajib diisi.',
                'nama.string' => 'Nama harus berupa teks.',
                'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'jenis_kelamin.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
                'kalori.numeric' => 'Kalori harus berupa angka.',
                'karbo.numeric' => 'Karbohidrat harus berupa angka.',
                'protein.numeric' => 'Protein harus berupa angka.',
                'lemak.numeric' => 'Lemak harus berupa angka.',
            ],
        );

        $data = $request->all();

        DB::transaction(function () use ($data) {
            $child = ChildrenModel::create(
                array_merge($data, [
                    'tanggal_lahir' => Carbon::createFromFormat('d F Y', $this->tanggal($data['tanggal_lahir']))->format('Y-m-d'),
                    'user_id' => auth()->user()->id,
                    'login_created' => auth()->user()->email,
                ]),
            );

            // Simpan nutrition target kalau ada input
            if ($data['kalori'] || $data['karbo'] || $data['protein'] || $data['lemak']) {
                NutritionTargetModel::create([
                    'child_id' => $child->id,
                    'kalori' => $data['kalori'],
                    'karbo' => $data['karbo'],
                    'protein' => $data['protein'],
                    'lemak' => $data['lemak'],
                ]);
            } else {
                // Kalau kosong → kasih default sesuai umur
                $umurTahun = \Carbon\Carbon::parse($data['tanggal_lahir'])->age;

                if ($umurTahun < 5) {
                    // contoh kebutuhan anak balita
                    $default = [
                        'kalori' => 1200,
                        'karbo' => 150,
                        'protein' => 20,
                        'lemak' => 30,
                    ];
                } elseif ($umurTahun < 13) {
                    // anak usia sekolah
                    $default = [
                        'kalori' => 1600,
                        'karbo' => 220,
                        'protein' => 30,
                        'lemak' => 45,
                    ];
                } else {
                    // remaja (default lebih tinggi)
                    $default = [
                        'kalori' => 2000,
                        'karbo' => 275,
                        'protein' => 50,
                        'lemak' => 70,
                    ];
                }

                NutritionTargetModel::create(array_merge(['child_id' => $child->id], $default));
            }
        });

        return redirect()->to(locale_route('nutrition-monitoring.children.index'))->with('success', 'Data anak berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $locale, $child)
    {
        $setting = SettingModel::all();
        $childModel = ChildrenModel::with('nutritionTarget')->findOrFail(decrypt($child));
        $nutrition = NutritionTargetModel::where('child_id', $childModel->id)->first();

        // Data pertumbuhan (growth logs)
        $growthData = GrowthLogsModel::where('child_id', $childModel->id)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($g) {
                return [
                    'tanggal' => $g->tanggal,
                    'berat' => $g->berat,
                    'tinggi' => $g->tinggi,
                ];
            });

        // Data konsumsi makanan (nutrisi harian)
        $foodData = FoodLogsModel::where('child_id', $childModel->id)->selectRaw('tanggal, SUM(kalori) as kalori, SUM(karbo) as karbo, SUM(protein) as protein, SUM(lemak) as lemak')->groupBy('tanggal')->orderBy('tanggal', 'asc')->get();

        $target = $childModel->nutritionTarget;

        return view('monitoring.nutrition-monitoring.children.show', compact('childModel', 'setting', 'nutrition', 'growthData', 'foodData', 'target'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, $child)
    {
        $setting = SettingModel::all();
        $childModel = ChildrenModel::findOrFail(decrypt($child));
        $nutrition = NutritionTargetModel::where('child_id', $childModel->id)->first();
        return view('monitoring.nutrition-monitoring.children.edit', compact('childModel', 'setting', 'nutrition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $locale, $child)
    {
        $childModel = ChildrenModel::findOrFail(decrypt($child));

        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required',
            'kalori' => 'nullable|numeric',
            'karbo' => 'nullable|numeric',
            'protein' => 'nullable|numeric',
            'lemak' => 'nullable|numeric',
        ]);

        $data = $request->all();
        $data['tanggal_lahir'] = Carbon::createFromFormat('d F Y', $this->tanggal($request->tanggal_lahir))->format('Y-m-d');
        $data['login_edit'] = auth()->user()->email;

        DB::transaction(function () use ($childModel, $data, $request) {
            // Update anak
            $childModel->update($data);

            // Update / Create nutrition target
            $target = $childModel->nutritionTarget; // relasi hasOne

            if ($request->kalori || $request->karbo || $request->protein || $request->lemak) {
                if ($target) {
                    $target->update([
                        'kalori' => $request->kalori,
                        'karbo' => $request->karbo,
                        'protein' => $request->protein,
                        'lemak' => $request->lemak,
                    ]);
                } else {
                    NutritionTargetModel::create([
                        'child_id' => $childModel->id,
                        'kalori' => $request->kalori,
                        'karbo' => $request->karbo,
                        'protein' => $request->protein,
                        'lemak' => $request->lemak,
                    ]);
                }
            }
        });

        return redirect()->to(locale_route('nutrition-monitoring.children.index'))->with('success', 'Data anak & target nutrisi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale, $child)
    {
        $childModel = ChildrenModel::find(decrypt($child));
        $data['login_deleted'] = auth()->user()->email;
        if ($childModel) {
            $childModel->update($data);
            $childModel->delete();
            return response()->json(['status' => 'success', 'message' => 'Data deleted successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data not found']);
        }
    }
}
