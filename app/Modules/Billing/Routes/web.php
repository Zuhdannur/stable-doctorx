<?php
use App\Modules\Billing\Http\Controllers\BillingController;

Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {

	Route::group([
	    'prefix'     => 'billing',
	    'as'         => 'billing.',
	    'namespace'  => 'Billing',
	    'middleware' => 'can:view backend',
	], function () {

		Route::get('/', function () {
	        dd('This is the Billing module index page. Build something great!');
	    });

	    /*
	     * Billing
	     */

		Route::get('list', [BillingController::class, 'index'])->name('index');

	    Route::get('{patientId}/{qId}/create', [BillingController::class, 'create'])->name('create');
	    Route::post('store', [BillingController::class, 'store'])->name('store');
	    Route::post('update', [BillingController::class, 'update'])->name('update');
	    Route::post('storepaid/{id}', [BillingController::class, 'storepaid'])->name('storepaid');

	    Route::group(['prefix' => '{billing}'], function () {
	    	Route::get('show', [BillingController::class, 'show'])->name('show');
	    	Route::get('edit', [BillingController::class, 'edit'])->name('edit');
	    	Route::patch('/', [BillingController::class, 'update'])->name('update');
	    	Route::delete('/', [BillingController::class, 'destroy'])->name('destroy');

	    	Route::get('pdf', [BillingController::class, 'pdf'])->name('print.pdf');
	    	Route::get('html', [BillingController::class, 'html'])->name('print.html');
	    });

	    Route::get('printepson', [BillingController::class, 'textprint'])->name('printepson');
	});

});