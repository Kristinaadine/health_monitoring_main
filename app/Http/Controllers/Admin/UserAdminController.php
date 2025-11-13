<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;

class UserAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = SettingModel::all();
        if ($request->ajax()) {
            $data = User::orderBy('id', 'desc');

            return DataTables::of($data)
                // ->filter(function ($instance) use ($request) {
                //     if (!empty($request->get('name'))) {
                //         $name = $request->get('name');
                //         $instance->where('name', 'LIKE', '%' . $name . '%');
                //     }
                // })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<button type="button" class="btn btn-link text-warning editBtn" data-id="' . encrypt($data->id) . '"><i class="material-icons">edit</i></button>';
                    $btn .= ' <button type="button" class="btn btn-link text-danger deleteBtn" data-id="' . encrypt($data->id) . '"><i class="material-icons">delete</i></button>';

                    return $btn;
                })
                ->editColumn('phone', function ($data) {
                    return $data->phone ? $data->phone : '-';
                })
                ->editColumn('roles', function ($data) {
                    if ($data->roles == 'User') {
                        return '<span class="badge badge-primary">User</span>';
                    } elseif ($data->roles == 'Admin') {
                        return '<span class="badge badge-success">Admin</span>';
                    } else {
                        return '<span class="badge badge-danger">Unknown</span>';
                    }
                })
                ->rawColumns(['action', 'roles'])
                ->make(true);
        }
        return view('admin.user.index', compact('setting'));
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
    public function store(RegisterRequest $request)
    {
        if ($request->validated()) {
            $data = $request->all();
            $data['login_created'] = auth()->user()->email;
            $data['password'] = Hash::make($data['password']);

            $create = User::create($data);
            return response()->json(['status' => 'success', 'message' => 'Data updated successfully']);
        }
        return redirect()
            ->back()
            ->withErrors(['errors' => $request->errors()]);
    }

    /**
     * Display the specified resource.
     */
    public function show($locale, $user)
    {
        $setting = SettingModel::all();
        $userData = User::find(decrypt($user));
        
        if (!$userData) {
            abort(404, 'User not found');
        }

        return view('admin.user.profile', compact('setting', 'userData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($locale, $user)
    {
        $data = User::find(decrypt($user));
        if ($data) {
            return response()->json(['status' => 'success', 'data' => $data, 'id' => $user]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $locale, $user)
    {
        $userModel = User::find(decrypt($user));
        
        if (!$userModel) {
            return response()->json(['status' => 'error', 'message' => 'Data not found']);
        }

        if ($request->password != null) {
            $request->validate([
                'password' => 'required|min:6|same:password2',
            ]);
            $request->merge(['password' => Hash::make($request->password)]);
        } else {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $userModel->id,
                'roles' => 'required|in:User,Admin',
            ]);
            $request->merge(['password' => $userModel->password]);
        }

        $data = $request->all();
        $data['login_edit'] = auth()->user()->email;
        $userModel->update($data);
        return response()->json(['status' => 'success', 'message' => 'Data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale, $user)
    {
        $userModel = User::find(decrypt($user));
        if (!$userModel) {
            return response()->json(['status' => 'error', 'message' => 'Data not found']);
        }
        
        $data = ['login_deleted' => auth()->user()->email];
        $userModel->update($data);
        $userModel->delete();
        return response()->json(['status' => 'success', 'message' => 'Data deleted successfully']);
    }
}
