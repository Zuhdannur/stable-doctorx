<?php
use App\Modules\Core\Http\Controllers\GeneralSettingController;

Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {

	Route::group([
	    'prefix'     => 'setting',
	    'as'         => 'setting.',
	    'namespace'  => 'Core',
	    'middleware' => 'can:view backend',
	], function () {
	    /*
	     * General Setting
	     */
	    Route::group(['namespace' => 'GeneralSetting'], function () {
	        Route::group(['prefix' => 'general'], function () {
	            Route::get('/', [GeneralSettingController::class, 'index'])->name('general.index');
	            Route::post('/save', [GeneralSettingController::class, 'store'])->name('general.store');
	            Route::post('/save-image', [GeneralSettingController::class, 'storeImage'])->name('general.store.image');
	        });
	    });
	});

});