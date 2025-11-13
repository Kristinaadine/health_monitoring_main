<?php

namespace App\Http\Controllers\Admin;

use App\Models\FoodModel;
use App\Models\FoodCatModel;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FoodAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = SettingModel::all();
        $categories = FoodCatModel::all();
        if ($request->ajax()) {
            $data = FoodModel::with('categories')->orderBy('id', 'desc');

            return DataTables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('name_food'))) {
                        $name_food = $request->get('name_food');
                        $instance->where('name_food', 'LIKE', '%' . $name_food . '%');
                    }

                    if (!empty($request->get('id_categories'))) {
                        $id_categories = $request->get('id_categories');
                        $instance->where('id_categories', $id_categories);
                    }
                })
                ->editColumn('image', function ($data) {
                    if ($data->image_path == null) {
                        return '<img class="rounded showimages" src="' . asset('assets-admin/assets/img/noimage.png') . '" alt="gambar" width="100px">';
                    }
                    return '<img class="rounded showimages" src="' . Storage::url($data->image_path) . '" alt="gambar" width="100px">';
                })
                ->addColumn('action', function ($data) {
                    $btn = '<button type="button" class="btn btn-link text-warning editBtn" data-id="' . encrypt($data->id) . '"><i class="material-icons">edit</i></button>';
                    $btn .= ' <button type="button" class="btn btn-link text-danger deleteBtn" data-id="' . encrypt($data->id) . '"><i class="material-icons">delete</i></button>';

                    return $btn;
                })
                ->addColumn('composition', function ($data) {
                    $composition = '<span class="font-weight-bold">Protein : <span class="font-weight-light">' . $data->protein . 'g</span></span>';
                    $composition .= '<br><span class="font-weight-bold">Carbs : <span class="font-weight-light">' . $data->carbs . 'g</span></span>';
                    $composition .= '<br><span class="font-weight-bold">Fiber : <span class="font-weight-light">' . $data->fiber . 'g</span></span>';
                    $composition .= '<br><span class="font-weight-bold">Calories : <span class="font-weight-light">' . $data->calories . '</span></span>';
                    return $composition;
                })
                ->addColumn('description', function ($data) {
                    $description = '<span class="font-weight-light">' . $data->description . '</span>';
                    return $description;
                })
                ->addColumn('categories', function ($data) {
                    $categories = '<span class="badge badge-primary">' . $data->categories->name . '</span>';
                    return $categories;
                })
                ->rawColumns(['action', 'image', 'composition', 'description', 'categories'])
                ->make(true);
        }
        return view('admin.food.index', compact('setting', 'categories'));
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
        try {
            $validated = $request->validate([
                'name_food' => 'required|string|max:255',
                'id_categories' => 'required|exists:food_categories,id',
                'calories' => 'required|numeric|min:0',
                'protein' => 'required|numeric|min:0',
                'carbs' => 'required|numeric|min:0',
                'fiber' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'name_food.required' => 'Nama makanan wajib diisi.',
                'id_categories.required' => 'Kategori wajib dipilih.',
                'id_categories.exists' => 'Kategori tidak valid.',
                'calories.required' => 'Kalori wajib diisi.',
                'calories.numeric' => 'Kalori harus berupa angka.',
                'protein.required' => 'Protein wajib diisi.',
                'carbs.required' => 'Karbohidrat wajib diisi.',
                'fiber.required' => 'Serat wajib diisi.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'image.max' => 'Ukuran gambar maksimal 2MB.',
            ]);

            $data = $validated;
            $data['login_created'] = auth()->user()->email;
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = $image->getClientOriginalName();
                $path = $image->store('public/food');
                $data['image'] = $name;
                $data['image_path'] = $path;
            }

            FoodModel::create($data);

            return response()->json(['status' => 'success', 'message' => 'Food created successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak valid',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating food: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.',
            ], 500);
        }
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
    public function edit($locale, $food)
    {
        $data = FoodModel::find(decrypt($food));
        if (!$data) {
            return response()->json(['status' => 'error', 'message' => 'Data not found']);
        }
        
        $image = '';
        if ($data->image_path == null) {
            $image = asset('assets-admin/assets/img/noimage.png');
        } else {
            $image = Storage::url($data->image_path);
        }
        
        return response()->json(['status' => 'success', 'data' => $data, 'id' => $food, 'image' => $image]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $locale, $food)
    {
        $foodModel = FoodModel::find(decrypt($food));
        if (!$foodModel) {
            return response()->json(['status' => 'error', 'message' => 'Data not found']);
        }
        
        $data = $request->all();
        $data['login_edit'] = auth()->user()->email;
        
        if ($request->hasFile('image')) {
            if ($foodModel->image) {
                Storage::delete($foodModel->image_path);
            }
            $image = $request->file('image');
            $name = $image->getClientOriginalName();
            $path = $image->store('public/food');
            $data['image'] = $name;
            $data['image_path'] = $path;
        }
        
        $foodModel->update($data);
        return response()->json(['status' => 'success', 'message' => 'Data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale, $food)
    {
        $foodModel = FoodModel::find(decrypt($food));
        if (!$foodModel) {
            return response()->json(['status' => 'error', 'message' => 'Data not found']);
        }
        
        $data = ['login_deleted' => auth()->user()->email];
        $foodModel->update($data);
        $foodModel->delete();
        return response()->json(['status' => 'success', 'message' => 'Data deleted successfully']);
    }
}
