<?php
$controller = \App\Http\Controllers\Api\Departments\DepartmentsController::class;
Route::controller($controller)->group(function () {
        Route::post('store', 'store')
            ->whereIn('roles', ['user', 'admin', 'client']);
});
