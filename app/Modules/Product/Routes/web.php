<?php

use App\Modules\Product\Http\Controllers\ProductController;
use App\Modules\Product\Http\Controllers\ServiceController;
use App\Modules\Product\Http\Controllers\CategoryController;
use App\Modules\Product\Http\Controllers\SupplierController;
use App\Modules\Product\Http\Controllers\ProductPackageController;
use App\Modules\Product\Http\Controllers\ServiceCategoryController;
use App\Modules\Product\Http\Controllers\ServicesPackagesController;

Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
	/*
	 * All route names are prefixed with 'admin.auth'.
	 */
	Route::group([
	    'prefix'     => 'masterdata/product',
	    'as'         => 'product.',
	    'namespace'  => 'Product',
	    'middleware' => 'can:view backend',
	], function () {

		Route::get('/', function () {
	        dd('Modul Produk!');
	    });

	    /*
	     * Category
	     */
	    Route::group(['namespace' => 'category'], function () {

	        Route::get('productcategory', [CategoryController::class, 'index'])->name('category.index');

	        Route::get('productcategory/create', [CategoryController::class, 'create'])->name('category.create');
	        Route::post('productcategory', [CategoryController::class, 'store'])->name('category.store');

	        Route::group(['prefix' => 'productcategory/{category}'], function () {
	            Route::get('edit', [CategoryController::class, 'edit'])->name('category.edit');
	            Route::patch('/', [CategoryController::class, 'update'])->name('category.update');
	            Route::delete('/', [CategoryController::class, 'destroy'])->name('category.destroy');
	        });
	    });

	    /*
	     * Produk
	     */

		Route::get('productdata', [ProductController::class, 'index'])->name('index');

		Route::get('productdata/getbycategory/{id}', [ProductController::class, 'getByCategory'])->name('getbycategory');

	    Route::get('productdata/import', [ProductController::class, 'import'])->name('import');
	    Route::post('productdata/import', [ProductController::class, 'storeimport'])->name('import.store');
	    Route::get('productdata/create', [ProductController::class, 'create'])->name('create');
	    Route::post('productdata', [ProductController::class, 'store'])->name('store');
		Route::post('productdata/storeOpname', [ProductController::class, 'saveStockOpname'])->name('storeOpname');

	    Route::group(['prefix' => 'productdata/{product}'], function () {
	    	Route::get('edit', [ProductController::class, 'edit'])->name('edit');
	    	Route::get('opname', [ProductController::class, 'createStockOpname'])->name('opname');
	    	Route::patch('/', [ProductController::class, 'update'])->name('update');
	    	Route::delete('/', [ProductController::class, 'destroy'])->name('destroy');
	    });

	    /*
	     * Paket Produk 
	     */

		Route::get('productpackage', [ProductPackageController::class, 'index'])->name('productpackage.index');

	    Route::get('productpackage/create', [ProductPackageController::class, 'create'])->name('productpackage.create');
	    Route::post('productpackage', [ProductPackageController::class, 'store'])->name('productpackage.store');

	    Route::group(['prefix' => 'productpackage/{product}'], function () {
	    	Route::get('list', [ProductPackageController::class, 'productdetail'])->name('productpackage.productdetail');
	    	Route::get('edit', [ProductPackageController::class, 'edit'])->name('productpackage.edit');
	    	Route::patch('/', [ProductPackageController::class, 'update'])->name('productpackage.update');
	    	Route::delete('/', [ProductPackageController::class, 'destroy'])->name('productpackage.destroy');

	    	Route::group(['prefix' => 'productpackagedetail/{detailid}'], function () {
		    	Route::delete('/', [ProductPackageController::class, 'destroydetail'])->name('productpackage.destroydetail');
		    });
	    });

	    /*
	     * Service Category
	     */
	    Route::group(['namespace' => 'servicecategory'], function () {

	        Route::get('servicecategory', [ServiceCategoryController::class, 'index'])->name('servicecategory.index');

	        Route::get('servicecategory/create', [ServiceCategoryController::class, 'create'])->name('servicecategory.create');
	        Route::post('servicecategory', [ServiceCategoryController::class, 'store'])->name('servicecategory.store');

	        Route::group(['prefix' => 'servicecategory/{servicecategory}'], function () {
	            Route::get('edit', [ServiceCategoryController::class, 'edit'])->name('servicecategory.edit');
	            Route::patch('/', [ServiceCategoryController::class, 'update'])->name('servicecategory.update');
	            Route::delete('/', [ServiceCategoryController::class, 'destroy'])->name('servicecategory.destroy');
	        });
	    });

	    /*
	     * Service
	     */

		Route::get('services', [ServiceController::class, 'index'])->name('service.index');

		Route::get('services/getbycategory/{id}', [ServiceController::class, 'getByCategory'])->name('service.getbycategory');

	    Route::get('services/import', [ServiceController::class, 'import'])->name('import-service');
	    Route::post('services/import', [ServiceController::class, 'storeimport'])->name('import-service.store');
	    Route::get('services/create', [ServiceController::class, 'create'])->name('service.create');
	    Route::post('services', [ServiceController::class, 'store'])->name('service.store');

	    Route::group(['prefix' => 'services/{service}'], function () {
	    	Route::get('edit', [ServiceController::class, 'edit'])->name('service.edit');
	    	Route::patch('/', [ServiceController::class, 'update'])->name('service.update');
	    	Route::delete('/', [ServiceController::class, 'destroy'])->name('service.destroy');
		});
		
		Route::group(['prefix' => 'supplier'], function () {
			Route::get('/',[SupplierController::class, 'index'])->name('supplier');
			Route::get('create',[SupplierController::class, 'create'])->name('supplier.create');
			Route::post('save',[SupplierController::class, 'store'])->name('supplier.save');
			Route::patch('update',[SupplierController::class, 'update'])->name('supplier.update');
			Route::delete('{supplier}/delete',[SupplierController::class, 'destroy'])->name('supplier.delete');
			Route::get('{supplier}/show',[SupplierController::class, 'show'])->name('supplier.show');
			Route::get('{supplier}/edit',[SupplierController::class, 'edit'])->name('supplier.edit');
		});

		Route::group(['prefix' => 'service-packages', 'as' => 'services-packages.'], function() {
			Route::get('/', [ServicesPackagesController::class, 'index'])->name('index');
			Route::get('/create', [ServicesPackagesController::class, 'create'])->name('create');
			Route::post('/store', [ServicesPackagesController::class, 'store'])->name('store');
			Route::get('/{ServicesPackage}/show', [ServicesPackagesController::class, 'show'])->name('show');
			Route::get('/{ServicesPackage}/edit', [ServicesPackagesController::class, 'edit'])->name('edit');
			Route::patch('/store', [ServicesPackagesController::class, 'update'])->name('store');
			Route::delete('/{ServicesPackage}/destroy', [ServicesPackagesController::class, 'destroy'])->name('destroy');
		});
	});

});