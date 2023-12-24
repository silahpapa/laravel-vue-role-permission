<?php
Route::controller(App\Http\Controllers\Api\Auth\AuthControllers::class)->group(function () {
        Route::get('auth/user', 'getUser')->name('index');
});
