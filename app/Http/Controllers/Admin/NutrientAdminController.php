<?php

namespace App\Http\Controllers\Admin;

use App\Models\SettingModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\NutrientRatioModel;
use App\Http\Controllers\Controller;

class NutrientAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = SettingModel::all();
        if ($request->ajax()) {
            $data = NutrientRatioModel::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<button type="button" class="btn btn-link text-warning editBtn" data-id="' . encrypt($data->id) . '"><i class="material-icons">edit</i></button>';
                    $btn .= ' <button type="button" class="btn btn-link text-danger deleteBtn" data-id="' . encrypt($data->id) . '"><i class="material-icons">delete</i></button>';

                    return $btn;
                })
                ->editColumn('name', function ($data) {
                    // Remove any @t() wrapper or translation syntax
                    $name = $data->name;
                    $name = preg_replace('/@t\([\'"](.+?)[\'"]\)/', '$1', $name);
                    return $name;
                })
                ->editColumn('protein', function ($data) {
                    return $data->protein . '%';
                })
                ->editColumn('carbs', function ($data) {
                    return $data->carbs . '%';
                })
                ->editColumn('fat', function ($data) {
                    return $data->fat . '%';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.nutrient.index', compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['login_created'] = auth()->user()->email;

        NutrientRatioModel::create($data);

        return response()->json(['status' => 'success', 'message' => 'Nutrient Ratio created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($locale, $nutrient)
    {
        $data = NutrientRatioModel::find(decrypt($nutrient));
        if ($data) {
            return response()->json(['status' => 'success', 'data' => $data, 'id' => $nutrient]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $locale, $nutrient)
    {
        $nutrientModel = NutrientRatioModel::find(decrypt($nutrient));
        if (!$nutrientModel) {
            return response()->json(['status' => 'error', 'message' => 'Data not found']);
        }
        
        $data = $request->all();
        $data['login_edit'] = auth()->user()->email;
        $nutrientModel->update($data);
        return response()->json(['status' => 'success', 'message' => 'Data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale, $nutrient)
    {
        $nutrientModel = NutrientRatioModel::find(decrypt($nutrient));
        if (!$nutrientModel) {
            return response()->json(['status' => 'error', 'message' => 'Data not found']);
        }
        
        $data = ['login_deleted' => auth()->user()->email];
        $nutrientModel->update($data);
        $nutrientModel->delete();
        return response()->json(['status' => 'success', 'message' => 'Data deleted successfully']);
    }
}
