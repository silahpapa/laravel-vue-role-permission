<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::controller(\App\Http\Controllers\Api\Auth\AuthControllers::class)->group(function(){
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
});

Route::group(['middleware' => ['auth:sanctum','check.roles.permission']], function () {
    $routesPath = base_path('routes/api');
    if (file_exists($routesPath)) {
        $routeFiles = File::allFiles($routesPath);
        foreach ($routeFiles as $file) {
            $filePath = $file->getPathName();
            $fileName = $file->getFileName();
            $directoryPath = $file->getRelativePath();
            $extRoute = strtolower(str_replace('.route.php', '', $fileName));
            $directoryName = strtolower(basename($file->getPath()));
            $pathSegments = explode('/', $file->getRelativePath());
            $prefix = '';
            if ($directoryName === $extRoute) {
                foreach ($pathSegments as $segment) {
                    $prefix .= strtolower($segment) . '/';
                }
            } else {
                $prefix .= strtolower($directoryPath . '/' . $extRoute);
            }
            Route::group(['prefix' => $prefix], function () use ($filePath) {
                require $filePath;
            });
        }
    }
});
