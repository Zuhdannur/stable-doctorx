<?php
use App\Modules\Humanresource\Http\Controllers\DepartmentController;
use App\Modules\Humanresource\Http\Controllers\DesignationController;
use App\Modules\Humanresource\Http\Controllers\StaffController;

Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {

	Route::group([
	    'prefix'     => 'humanresource',
	    'as'         => 'humanresource.',
	    'namespace'  => 'Humanresource',
	    'middleware' => 'can:view backend',
	], function () {

		Route::get('/', function () {
	        dd('This is the Humanresource module index page. Build something great!');
	    });

	    /*
	     * Departemen
	     */

		Route::group(['namespace' => 'department'], function () {

	        Route::get('department', [DepartmentController::class, 'index'])->name('department.index');

	        Route::get('department/create', [DepartmentController::class, 'create'])->name('department.create');
	        Route::post('department', [DepartmentController::class, 'store'])->name('department.store');

	        Route::group(['prefix' => 'department/{department}'], function () {
	            Route::get('edit', [DepartmentController::class, 'edit'])->name('department.edit');
	            Route::patch('/', [DepartmentController::class, 'update'])->name('department.update');
	            Route::delete('/', [DepartmentController::class, 'destroy'])->name('department.destroy');
	        });
	    });

	    /*
	     * Designation
	     */

		Route::group(['namespace' => 'designation'], function () {

	        Route::get('designation', [DesignationController::class, 'index'])->name('designation.index');

	        Route::get('designation/create', [DesignationController::class, 'create'])->name('designation.create');
	        Route::post('designation', [DesignationController::class, 'store'])->name('designation.store');

	        Route::group(['prefix' => 'designation/{designation}'], function () {
	            Route::get('edit', [DesignationController::class, 'edit'])->name('designation.edit');
	            Route::patch('/', [DesignationController::class, 'update'])->name('designation.update');
	            Route::delete('/', [DesignationController::class, 'destroy'])->name('designation.destroy');
	        });
	    });

	    /*
	     * Staff
	     */

		Route::group(['namespace' => 'staff'], function () {

	        Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
	        Route::get('staff/getbyrole/{id}', [StaffController::class, 'getByRole'])->name('staff.getbyrole');

	        Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
	        Route::post('staff', [StaffController::class, 'store'])->name('staff.store');

	        Route::group(['prefix' => 'staff/{staff}'], function () {
	            Route::get('edit', [StaffController::class, 'edit'])->name('staff.edit');
	            Route::patch('/', [StaffController::class, 'update'])->name('staff.update');
	            Route::delete('/', [StaffController::class, 'destroy'])->name('staff.destroy');
	        });
	    });

	});

});