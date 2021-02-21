<?php

use App\Modules\Incentive\Http\Controllers\IncentiveController;
use App\Modules\Incentive\Http\Controllers\StaffIncentiveController;

Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    Route::group([
	    'prefix'     => 'incentive',
	    'as'         => 'incentive.',
	    'namespace'  => 'Incentive',
	    'middleware' => 'can:view backend',
	], function () {

		Route::get('/', function () {
	        dd('This is the Incentive module index page. Build something great!');
	    });

	    /*
	     * incentive
	     */

		Route::get('incentive', [IncentiveController::class, 'index'])->name('index');
	    Route::get('incentive/create', [IncentiveController::class, 'create'])->name('create');
	    Route::post('incentive', [IncentiveController::class, 'store'])->name('store');

	    Route::group(['prefix' => 'incentive/{incentive}'], function () {
	    	Route::get('list', [IncentiveController::class, 'incentivedetail'])->name('incentivedetail');
	    	Route::get('edit', [IncentiveController::class, 'edit'])->name('edit');
	    	Route::patch('/', [IncentiveController::class, 'update'])->name('update');
	    	Route::delete('/', [IncentiveController::class, 'destroy'])->name('destroy');
	    });

	    /*
	     * incentive details
	     */
	    /*Route::group(['namespace' => 'detail'], function () {

	        Route::get('detail', [GroupController::class, 'index'])->name('detail.index');

	        Route::get('detail/create', [GroupController::class, 'create'])->name('detail.create');
	        Route::post('detail', [GroupController::class, 'store'])->name('detail.store');

	        Route::group(['prefix' => 'detail/{detail}'], function () {
	            Route::get('edit', [GroupController::class, 'edit'])->name('detail.edit');
	            Route::patch('/', [GroupController::class, 'update'])->name('detail.update');
	            Route::delete('/', [GroupController::class, 'destroy'])->name('detail.destroy');
	        });
	    });*/

	    /*
	     * incentive staff
	     */
	    Route::group(['namespace' => 'staff'], function () {

	        Route::get('list', [StaffIncentiveController::class, 'staffList'])->name('staff.list');
	        Route::get('staff', [StaffIncentiveController::class, 'index'])->name('staff.index');
	        Route::get('staff/create', [StaffIncentiveController::class, 'create'])->name('staff.create');
	        Route::post('staff/store', [StaffIncentiveController::class, 'store'])->name('staff.store');

	        Route::group(['prefix' => 'incentive/{incentive}'], function () {
		    	Route::delete('/destroy', [StaffIncentiveController::class, 'destroy'])->name('destroy');
		    });
	    	
	    });
	});

	Route::group([
	    'prefix'     => 'reporting',
	    'as'         => 'reporting.',
	    'namespace'  => 'Reporting',
	    'middleware' => 'can:view backend',
	], function () {
		Route::get('incentive', [IncentiveController::class, 'reporting'])->name('incentive');
		Route::post('incentive/staff', [IncentiveController::class, 'reportingstaff'])->name('incentive.staff');
	});
});
