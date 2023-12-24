<?php

namespace App\Http\Controllers\Api\Departments;

use App\Http\Controllers\Controller;
use App\Models\Core\Department;
use App\Models\Core\Permissions;

class DepartmentsController extends Controller
{
    public function store()
    {
        $user = request()->user();
        $data = request()->all();
        $editId = request('editId');
        $rules = [
            'description' => 'required',
            'name' => 'required|unique:departments,name'
        ];
        if ($editId) {
            $currentName = Department::find($editId)->name;
            $data['id'] = $editId;
            unset($data['editId']);
            if ($currentName == $data['name']) {
                $rules['name'] = 'required';
            }
        }
        $validator = validator()->make($data, $rules);
        if (count($validator->errors())) {
            return response([
                'errors' => $validator->errors()
            ], 422);
        }
//        $data['user_id'] = $user->id;
        $department =request('editId')
            ? $user->department()->update($data)
            : $user->department()->create($data);
       return response([
           'status' => 'success',
           'data' => $department
       ]);
    }
    public function list()
    {
        $departments = Department::where('status', 'active')->get();
        return response([
            'status' => 'success',
            'data' => $departments
        ]);
    }

    public function managePermission()
    {
        $departmentId = request('department_id');

        // Find the department by ID
        $department = Department::findOrFail($departmentId);

        // Get the permissions for the department
        $departmentPermissions = $department->permissions()->pluck('permissions_id')->toArray();

        // Get all permissions grouped by controller namespace
        $allPermissions = Permissions::all(['id','controller_namespace','name','controller'])->groupBy('controller_namespace');

        // Attach the 'has_permission' column to each permission
        $allPermissionsWithPermission = $allPermissions->map(function ($permissions) use ($departmentPermissions) {
            return $permissions->map(function ($permission) use ($departmentPermissions) {
                $permission['department_id'] = request('department_id');
                $permission['has_permission'] = in_array($permission['id'], $departmentPermissions);
                return $permission;
            });
        });

        return response([
            'status' => 'success',
            'all_permissions' => $allPermissionsWithPermission,
        ]);
    }

    public function updatePermission()
    {
        $allPermissions = request('allPermissions');
        $department = Department::findOrFail(request('department_id'));
        foreach ($allPermissions as $permissions) {
            foreach($permissions as $permission) {
                $permissionId = $permission['id'];
                $hasPermission = $permission['has_permission'];
                if ($hasPermission) {
                    // Attach permission to the department if it's not already attached
                    if (!$department->permissions()->where('permissions_id', $permissionId)->exists()) {
                        $department->permissions()->attach($permissionId);
                    }
                } else {
                    // Detach permission from the department if it exists
                    $department->permissions()->detach($permissionId);
                }
            }
        }
        return response([
            'status' => 'success',
            'data' => $department
        ]);
    }

    public function deactivateDepartment()
    {
        $departmentId = request('department_id');
        $department = Department::findOrFail($departmentId);
        $department->status = 'inactive';
        $department->save();
        return response([
            'status' => 'success',
            'data' => $department
        ]);
    }
}
