<?php

Route::controller(App\Http\Controllers\Api\Categories\CategoriesController::class)->group(function () {
        Route::get('', 'index')->name('index')
            ->whereIn('roles', ['user', 'admin', 'client']);
});
 $categoriesControler = App\Http\Controllers\Api\Categories\CategoriesController::class;
Route::get('/about', [$categoriesControler, 'about'])
    ->whereIn('roles', ['common']);
