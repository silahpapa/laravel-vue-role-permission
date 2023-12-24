<?php

namespace App\Repositories;

use App\Models\Core\Department;
use Illuminate\Support\Facades\Route;

class CheckRolesPermission
{
    public function __construct($request_route)
    {
        $this->actionName = $request_route->action['controller'];
        $this->current_path = Route::getFacadeRoot()->current()->uri();
        $this->user = \request()->user();
        $this->checkPermission();
    }
    public function checkPermission()
    {
        $department = Department::where('status','active')->where('id',auth()->user()->department_id)->first();
        if ($department) {
            $permissions = $department->permissions;
            if ($permissions) {
                foreach ($permissions as $permission) {
                    if ($this->actionName == $permission->controller_namespace . '@' . $permission->method) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
