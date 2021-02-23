<?php

use App\Modules\Crm\Http\Controllers\CrmBirthday;
use App\Modules\Crm\Http\Controllers\CrmLogController;
use App\Modules\Crm\Http\Controllers\CrmPointController;
use App\Modules\Crm\Http\Controllers\CrmRadeemController;
use App\Modules\Crm\Http\Controllers\CrmSettingsController;
use App\Modules\Crm\Http\Controllers\CrmMarketingController;
use App\Modules\Crm\Http\Controllers\CrmMembershipController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {

    Route::group([
        'prefix' => 'crm',
        'as' => 'crm.',
        'namespace' => 'Crm',
        'middleware'    => 'can:view backend',
    ], function () {

        // Route::get('/', function () {
        //     dd('This is the Crm module index page. Build something great!');
        // });

        /**
         * Settings
         */
        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::get('/membership', [CrmSettingsController::class, 'indexMembership'])->name('membership');
            Route::get('/membership/create', [CrmSettingsController::class, 'createMembership'])->name('membership.create');
            Route::get('{membership}/membership/show', [CrmSettingsController::class, 'showMembership'])->name('membership.show');
            Route::post('/membership', [CrmSettingsController::class, 'storeMembership'])->name('membership.store');
            Route::patch('/membership', [CrmSettingsController::class, 'storeMembership'])->name('membership.store');
            Route::delete('{membership}/membership', [CrmSettingsController::class, 'destroyMembership'])->name('membership.destroy');
            Route::get('/wa', [CrmSettingsController::class, 'settingsWhatsapp'])->name('wa');
            Route::post('/wa/store/woo', [CrmSettingsController::class, 'storeWaVendorTwillio'])->name('wa.twillio');
            Route::post('/wa/store/twillio', [CrmSettingsController::class, 'storeWaVendorWooWa'])->name('wa.woo');
            Route::post('/wa/store/message', [CrmSettingsController::class, 'storeWaMessage'])->name('wa.msg');
        });

        /**
         * Membership
         */
        Route::group(['prefix' => 'membership', 'as' => 'membership.'], function () {
            Route::get('/', [CrmMembershipController::class, 'index'])->name('index');
            Route::post('/', [CrmMembershipController::class, 'store'])->name('store');
            Route::patch('/', [CrmMembershipController::class, 'store'])->name('store');
            Route::delete('/{membership}', [CrmMembershipController::class, 'destroy'])->name('destroy');
            Route::get('/create', [CrmMembershipController::class, 'create'])->name('create');
            Route::get('{membership}/show', [CrmMembershipController::class, 'show'])->name('show');
            Route::get('{membership}/edit', [CrmMembershipController::class, 'edit'])->name('edit');
        });

        /**
         *  Point
         */
        Route::group(['prefix' => 'point', 'as' => 'point.'], function () {
            Route::group(['prefix' => 'radeem'], function () {
                Route::get('/', [CrmPointController::class, 'index'])->name('index');
                Route::get('/create', [CrmPointController::class, 'create'])->name('create');
                Route::post('/', [CrmPointController::class, 'store'])->name('save');
                Route::get('/{data}/edit', [CrmPointController::class, 'edit'])->name('edit');
                Route::patch('/', [CrmPointController::class, 'store'])->name('update');
                Route::delete('/{data}', [CrmPointController::class, 'destroy'])->name('destroy');
            });

            Route::group(['prefix' => 'obat', 'as' => 'obat.'], function () {
                Route::get('/', [CrmPointController::class, 'indexObat'])->name('index');
                Route::get('/{product}', [CrmPointController::class, 'editObat'])->name('edit');
                Route::patch('/', [CrmPointController::class, 'storeObat'])->name('store');
            });

            Route::group(['prefix' => 'service', 'as' => 'service.'], function () {
                Route::get('/',[CrmPointController::class, 'indexService'])->name('index');
                Route::get('/{service}',[CrmPointController::class, 'editService'])->name('edit');
                Route::patch('/', [CrmPointController::class, 'storeService'])->name('store');
            });
        });

        /**
         * Radeem Point
         */

        Route::group(['prefix' => 'radeem', 'as' => 'radeem.'], function () {
            Route::get('/',[CrmRadeemController::class, 'index'])->name('index');
            Route::post('/',[CrmRadeemController::class, 'index'])->name('index');
            Route::post('/save',[CrmRadeemController::class, 'store'])->name('save');
        });

        /**
         * Maketing Activity
         */
        Route::group(['prefix' => 'marketing', 'as' => 'marketing.'], function () {
            Route::get('/', [CrmMarketingController::class, 'index'])->name('index');
            Route::get('/create', [CrmMarketingController::class, 'create'])->name('create');
            Route::get('{marketing}', [CrmMarketingController::class, 'edit'])->name('edit');
            Route::post('/save', [CrmMarketingController::class, 'store'])->name('save');
            Route::patch('/update', [CrmMarketingController::class, 'store'])->name('update');
            Route::delete('/{marketing}', [CrmMarketingController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'birthday', 'as' => 'birthday.'], function () {
           Route::get('/', [CrmBirthday::class, 'index'])->name('index'); 
           Route::post('/sendWa', [CrmBirthday::class, 'sendWa'])->name('sendWa'); 
           Route::get('{patient}/edit', [CrmBirthday::class, 'editNoWA'])->name('editNoWA'); 
           Route::post('/store', [CrmBirthday::class, 'storeWa'])->name('storeWa'); 
        });

        /**
         * Log Activity
         */
        Route::group(['prefix' => 'log', 'as' => 'log.'], function() {
            Route::get('/', [CrmLogController::class, 'index'])->name('show');
            Route::post('/', [CrmLogController::class, 'index'])->name('show');
        });
    });

});
