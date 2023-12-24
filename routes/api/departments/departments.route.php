<?php
$controller = \App\Http\Controllers\Api\Departments\DepartmentsController::class;
Route::controller($controller)->group(function () {
        Route::post('store', 'store')->whereIn('roles', ['admin']);
        Route::get('list', 'list')->whereIn('roles', ['admin']);
        Route::get('manage-permission', 'managePermission')->whereIn('roles', ['admin']);
        Route::post('update-permission', 'updatePermission')->whereIn('roles', ['admin']);
        Route::post('deactivate', 'deactivateDepartment')->whereIn('roles', ['admin']);
});
