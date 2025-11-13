<?php

namespace App\Http\Controllers\Admin;

use App\Models\FoodCatModel;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class FoodCatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = SettingModel::all();
        if ($request->ajax()) {
            // Only get non-deleted data
            $data = FoodCatModel::whereNull('deleted_at')->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    // Use plain ID instead of encrypted for DataTables
                    $btn = '<button type="button" class="btn btn-link text-warning editBtn" data-id="' . $data->id . '"><i class="material-icons">edit</i></button>';
                    $btn .= ' <button type="button" class="btn btn-link text-danger deleteBtn" data-id="' . $data->id . '"><i class="material-icons">delete</i></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.food-category.index', compact('setting'));
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
        // Validate input with unique check
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:food_categories,name,NULL,id,deleted_at,NULL'
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique' => 'Kategori "' . $request->name . '" sudah ada. Silakan gunakan nama lain.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $request->all();
        $data['login_created'] = auth()->user()->email;

        FoodCatModel::create($data);

        return response()->json(['status' => 'success', 'message' => 'Food Categories created successfully']);
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
    public function edit($locale, FoodCatModel $food_category)
    {
        return response()->json(['status' => 'success', 'data' => $food_category, 'id' => $food_category->id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $locale, FoodCatModel $food_category)
    {
        // Validate input with unique check (ignore current record)
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:food_categories,name,' . $food_category->id . ',id,deleted_at,NULL'
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique' => 'Kategori "' . $request->name . '" sudah ada. Silakan gunakan nama lain.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $request->all();
        $data['login_edit'] = auth()->user()->email;
        $food_category->update($data);
        return response()->json(['status' => 'success', 'message' => 'Data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale, FoodCatModel $food_category)
    {
        \Log::info('Delete request', ['locale' => $locale, 'id' => $food_category->id, 'name' => $food_category->name]);
        
        try {
            // Update audit trail before soft delete
            $food_category->login_deleted = auth()->user()->email;
            $food_category->save();
            
            // Soft delete
            $food_category->delete();
            
            \Log::info('Food category deleted successfully');
            return response()->json(['status' => 'success', 'message' => 'Data deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Delete food category error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to delete: ' . $e->getMessage()], 500);
        }
    }
}
