<?php

namespace App\Http\Controllers\Api\Roles;

use App\Http\Controllers\Controller;
use App\Models\Core\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::where('status', 'active')->get();
        return response([
            'status' => 'success',
            'data' => $roles
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $editId = request('editId');
        $rules = [
            'description' => 'required',
            'name' => 'required|unique:roles,name'
        ];
        if ($editId) {
            $currentName = Role::find($editId)->name;
            $data['id'] = $editId;
            unset($data['editId']);
            if ($currentName == $data['name']) {
                $rules['name'] = 'required';
            }
        }
        $valid = validator()->make($data, $rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'error',
                'errors' => $valid->errors()
            ],422);
        }
        $role = $editId ?  Role::find($editId)->update($data) : Role::create($data);
        return response([
            'status' => 'success',
            'data' => $role
        ]);
    }

    public function deactivate()
    {
        $role = Role::find(request('id'));
        $role->update(['status' => 'inactive']);
        return response([
            'status' => 'success',
            'data' => $role
        ]);
    }
}
