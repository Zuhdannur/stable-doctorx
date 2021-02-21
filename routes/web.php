<?php

use App\Http\Controllers\LanguageController;

Route::get('lang/{lang}', [LanguageController::class, 'swap']);

/*
 * Frontend Routes
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    include_route_files(__DIR__.'/frontend/');
});

/*
 * Backend Routes
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    /*
     * view-backend permission
     */
    include_route_files(__DIR__.'/backend/');
});

Route::prefix('region')->group(function() {
	Route::get('/district/{id}', function($cityId)
	{
	    $city = \Indonesia::findCity($cityId, ['districts']);
		$data = $city->districts;
		return response()->json(array('data' => $data));

	})->name('district.get');
	Route::get('/village/{id}', function($districtId)
	{
	    $city = \Indonesia::findDistrict($districtId, ['villages']);
		$data = $city->villages;
		return response()->json(array('data' => $data));

	})->name('village.get');
});

Route::resource('admin/masterdata/product/stok','StokObatController');
Route::group(['prefix' => 'admin/masterdata/product/stok/data'], function() {
   Route::get('/getData','StokObatController@getData');
});

Route::resource('modul','ModulAccessController');
Route::resource('mapping','MappingController');

Route::resource('list-splitbill','SplitBillController');
Route::group(['prefix' => 'list-splitbill/data'], function() {
    Route::get('/getData','SplitBillController@getData');
});

Route::group(['prefix' => 'api-master/data'] , function () {
    Route::post('/getFlagService','ApiController@getFlagService');
});

Route::group(['middleware' => 'admin'], function () {
    Route::group(['prefix' => 'admin/accounting'] , function () {
        Route::resource('/rekap-penjualan','RekapPenjualanController');
        Route::group(['prefix' => 'rekap-penjualan/data'],function () {
            Route::get('/getData','RekapPenjualanController@getData');
        });

        Route::resource('/rekap-produk','RekapPenjualanProdukController');
        Route::group(['prefix' => 'rekap-produk/data'],function () {
            Route::get('/getData','RekapPenjualanProdukController@getData');
        });

        Route::resource('/rekap-service','RekapPenjualanServiceController');
        Route::group(['prefix' => 'rekap-service/data'],function () {
            Route::get('/getData','RekapPenjualanServiceController@getData');
        });

    });

    Route::group(['prefix' => 'admin/masterdata'] , function () {
       Route::resource('/klinik','KlinikController');
        Route::group(['prefix' => 'klinik/data'],function () {
            Route::get('/getData','KlinikController@getData');
            Route::post('/simpan','KlinikController@simpan');
        });

        Route::resource('/setting-modul','SettingModulController');
        Route::group(['prefix' => 'setting-modul/data'],function () {
            Route::get('/getData','SettingModulController@getData');
//            Route::post('/simpan','KlinikController@simpan');
        });

        Route::resource('/setting-modul-urutan','UrutanModulController');
        Route::group(['prefix' => 'setting-modul/data'],function () {
            Route::get('/getData','UrutanModulController@getData');
        });

    });



});


