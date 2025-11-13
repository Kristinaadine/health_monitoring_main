<?php

namespace App\Http\Controllers\monitoring;

use Carbon\Carbon;
use App\Models\AlertModel;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use App\Models\ChildrenModel;
use App\Models\GrowthLogsModel;
use App\Http\Controllers\Controller;

class GrowthChildrenController extends Controller
{
    public function index($locale,$childId)
    {
        $setting = SettingModel::all();
        $child = ChildrenModel::findOrFail(decrypt($childId));
        $growths = GrowthLogsModel::where('child_id', decrypt($childId))->orderBy('tanggal', 'desc')->get();
        return view('monitoring.nutrition-monitoring.growth.index', compact('setting', 'child', 'growths'));
    }

    public function create($locale, $childId)
    {
        $setting = SettingModel::all();
        $child = ChildrenModel::findOrFail(decrypt($childId));
        return view('monitoring.nutrition-monitoring.growth.form', compact('setting', 'child'));
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
                'berat' => 'required|numeric',
                'tinggi' => 'required|numeric',
            ],
            [
                'tanggal.required' => 'Tanggal pengukuran wajib diisi.',

                'berat.required' => 'Berat badan wajib diisi.',
                'berat.numeric' => 'Berat badan harus berupa angka.',

                'tinggi.required' => 'Tinggi badan wajib diisi.',
                'tinggi.numeric' => 'Tinggi badan harus berupa angka.',
            ],
        );

        GrowthLogsModel::create([
            'child_id' => decrypt($childId),
            'tanggal' => Carbon::createFromFormat('d F Y', $this->tanggal($request->tanggal))->format('Y-m-d'),
            'berat' => $request->berat,
            'tinggi' => $request->tinggi,
        ]);

        $lastGrowth = GrowthLogsModel::where('child_id', decrypt($childId))
            ->whereDate('tanggal', '<', Carbon::createFromFormat('d F Y', $this->tanggal($request->tanggal))->format('Y-m-d'))
            ->latest('tanggal')
            ->first();

        if ($lastGrowth) {
            if ($request->berat <= $lastGrowth->berat) {
                AlertModel::create([
                    'child_id' => decrypt($childId),
                    'tipe' => 'Pertumbuhan',
                    'pesan' => 'Berat badan tidak mengalami kenaikan dibanding log sebelumnya',
                ]);
            }

            if ($request->tinggi <= $lastGrowth->tinggi) {
                AlertModel::create([
                    'child_id' => decrypt($childId),
                    'tipe' => 'Pertumbuhan',
                    'pesan' => 'Tinggi badan tidak mengalami kenaikan dibanding log sebelumnya',
                ]);
            }
        }

        return redirect()->to(locale_route('nutrition-monitoring.children.growth.index', $childId))->with('success', 'Data pertumbuhan tersimpan');
    }

    /**
     * Update growth log
     */
    public function update(Request $request, $locale, $childId, $id)
    {
        try {
            $child = ChildrenModel::findOrFail(decrypt($childId));
            $growth = GrowthLogsModel::where('child_id', $child->id)->findOrFail($id);
            
            $request->validate([
                'tanggal' => 'required|date',
                'berat' => 'required|numeric|min:2|max:50',
                'tinggi' => 'required|numeric|min:40|max:130',
            ], [
                'berat.min' => 'Berat badan minimal 2 kg',
                'berat.max' => 'Berat badan maksimal 50 kg',
                'tinggi.min' => 'Tinggi badan minimal 40 cm',
                'tinggi.max' => 'Tinggi badan maksimal 130 cm',
            ]);
            
            $growth->update([
                'tanggal' => $request->tanggal,
                'berat' => $request->berat,
                'tinggi' => $request->tinggi,
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diupdate'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete growth log
     */
    public function destroy($locale, $childId, $id)
    {
        try {
            $child = ChildrenModel::findOrFail(decrypt($childId));
            $growth = GrowthLogsModel::where('child_id', $child->id)->findOrFail($id);
            
            $growth->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
