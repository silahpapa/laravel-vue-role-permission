<?php

namespace App\Console\Commands;

use App\Models\Core\Department;
use App\Models\Core\Permissions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class UpdatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permission_ids = [];
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            $uri = $route->uri();
            if (substr($uri, 0, 3) == "api") {
                $action = $route->getActionname();
                $_action = explode('@', $action);
                $controller = class_basename($_action[0]);
                $name = str_replace('-', '_', $this->formatString(str_replace(['api/', '/'], ['', '_'], $uri)));
                $permission_check = Permissions::where(['controller_namespace' => $_action[0], 'url' => $uri, 'method' => end($_action)])->first();
                if (!$permission_check) {
                    $permission = new Permissions();
                    $permission->name = $name;
                    $permission->controller_namespace = $_action[0];
                    $permission->controller = $controller;
                    $permission->method = end($_action);
                    $permission->url = $uri;
                    $permission->save();
                    $permission_ids[] = $permission->id;
                }
            }
        }
        $admin_role = Department::where('name', 'super_admin')->first();
        if ($admin_role)
            $admin_role->permissions()->attach($permission_ids);
    }

    public function formatString($string){
        return  preg_replace_callback('/[A-Z]/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $string);
    }
}
