<?php

use App\Modules\Accounting\Http\Controllers\BiayaController;
use App\Modules\Accounting\Http\Controllers\FinanceLogController;
use App\Modules\Accounting\Http\Controllers\FinanceKasControlller;
use App\Modules\Accounting\Http\Controllers\FinanceAccountController;
use App\Modules\Accounting\Http\Controllers\FinanceReportsController;
use App\Modules\Accounting\Http\Controllers\FinanceSettingController;
use App\Modules\Accounting\Http\Controllers\FinancePurchaseController;

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
        'prefix' => 'accounting',
        'as'    => 'accounting.',
        'namespace'    => 'Accounting',
        'middleware'    => 'can:view backend',
    ], function () {

        // Route::get('/', function () {
        //     dd('This is the Accounting module index page. Build something great!');
        // });

        Route::group(['prefix' => 'account'], function() {
            Route::get('/', [FinanceAccountController::class, 'index'])->name('account');
            Route::get('create', [FinanceAccountController::class, 'getFormCreate'])->name('account.create');
            Route::get('{financeAccount}/edit', [FinanceAccountController::class, 'getFormEdit'])->name('account.edit');
            Route::post('save', [FinanceAccountController::class, 'store'])->name('account.save');
            Route::patch('save', [FinanceAccountController::class, 'store'])->name('account.save');
            Route::group(['prefix' => 'journal'], function () {
                Route::get('/', [FinanceAccountController::class, 'createJournal'])->name('account.journal');
                Route::post('save', [FinanceAccountController::class, 'storeJournal'])->name('account.journal.save');
                Route::get('{id}/show', [FinanceAccountController::class, 'showJournal'])->name('account.journal.show');
                Route::get('{trx}/detail', [FinanceAccountController::class, 'showJournalDetail'])->name('account.journal.detail');
                Route::get('{trx}/general', [FinanceAccountController::class, 'generalJournal'])->name('account.journal.general');
                Route::get('{trx}/download', [FinanceAccountController::class, 'download'])->name('account.journal.download');
            });
        });


        /**
         * Cash
         */
        Route::group(['prefix' => 'cash'], function() {
            Route::get('/', [FinanceKasControlller::class, 'index'])->name('cash');
            Route::get('create', [FinanceKasControlller::class, 'createCashAccount'])->name('cash.create');
            Route::post('store', [FinanceKasControlller::class, 'saveCashAccount'])->name('cash.store');
            Route::get('{account}/transfer', [FinanceKasControlller::class, 'transfer'])->name('cash.transfer');
            Route::post('transfer', [FinanceKasControlller::class, 'saveTransfer'])->name('cash.store_transfer');
            Route::get('{account}/receipt', [FinanceKasControlller::class, 'receipt'])->name('cash.receipt');
            Route::post('receipt', [FinanceKasControlller::class, 'saveReceipt'])->name('cash.store_receipt');
            Route::get('{account}/send', [FinanceKasControlller::class, 'send'])->name('cash.send');
            Route::post('send', [FinanceKasControlller::class, 'saveSend'])->name('cash.store_send');
            Route::group(['prefix' => 'journal'], function () {
                Route::get('/{account}', [FinanceKasControlller::class, 'journal'])->name('cash.journal');
                Route::get('{trx}/receipt/show', [FinanceKasControlller::class, 'showReceipt'])->name('cash.journal.receipt.show');
                Route::get('{trx}/receipt/pdf', [FinanceKasControlller::class, 'pdfReceipt'])->name('cash.journal.receipt.pdf');
                Route::get('{trx}/receipt/print', [FinanceKasControlller::class, 'printReceipt'])->name('cash.journal.receipt.print');
                Route::get('{trx}/transfer/show', [FinanceKasControlller::class, 'showTransfer'])->name('cash.journal.transfer.show');
                Route::get('{trx}/send/show', [FinanceKasControlller::class, 'showSend'])->name('cash.journal.send.show');
                Route::get('{trx}/send/pdf', [FinanceKasControlller::class, 'pdfSend'])->name('cash.journal.send.pdf');
                Route::get('{trx}/send/print', [FinanceKasControlller::class, 'printSend'])->name('cash.journal.send.print');
            });
        });

        /**
         * Setting
         */
        Route::group(['prefix' => 'settings'], function () {
            // ms categories
            Route::get('ms_categories', [FinanceSettingController::class, 'showMsCategories'])->name('settings.ms_categories');
            Route::group(['prefix' => 'ms_categories'], function () {
                Route::get('{categories}/edit', [FinanceSettingController::class, 'editMsCategories'])->name('settings.ms_categories.edit');
                Route::get('create', [FinanceSettingController::class, 'createMsCategories'])->name('settings.ms_categories.create');
                Route::patch('save', [FinanceSettingController::class, 'storeMsCategories'])->name('settings.ms_categories.save');
                Route::post('save', [FinanceSettingController::class, 'storeMsCategories'])->name('settings.ms_categories.save');
            });

            // categories
            Route::get('categories', [FinanceSettingController::class, 'showCategories'])->name('settings.categories');
            Route::group(['prefix' => 'categories'], function () {
                Route::get('{categories}/edit',[FinanceSettingController::class, 'editCategories'])->name('settings.categories.edit');
                Route::get('create',[FinanceSettingController::class, 'createCategories'])->name('settings.categories.create');
                Route::patch('save', [FinanceSettingController::class, 'storeCategories'])->name('settings.categories.save');
                Route::post('save', [FinanceSettingController::class, 'storeCategories'])->name('settings.categories.save');
            });

            Route::group(['prefix' => 'tax'], function () {
                Route::get('/', [FinanceSettingController::class, 'showTax'])->name('settings.tax');
                Route::get('create', [FinanceSettingController::class, 'createTax'])->name('settings.tax.create');
                Route::get('{tax}/edit', [FinanceSettingController::class, 'editTax'])->name('settings.tax.edit');
                Route::patch('save', [FinanceSettingController::class, 'storeTax'])->name('settings.tax.save');
                Route::post('save', [FinanceSettingController::class, 'storeTax'])->name('settings.tax.save');
            });
        });

        /**
         * Biaya
         */
        Route::group(['prefix' => 'biaya'], function () {
            Route::get('/', [BiayaController::class, 'index'])->name('biaya');
            Route::get('create', [BiayaController::class, 'create'])->name('biaya.create');
            Route::post('save', [BiayaController::class, 'store'])->name('biaya.save');
            Route::post('savePay', [BiayaController::class, 'storePay'])->name('biaya.savePay');
            Route::get('{biaya}/show', [BiayaController::class, 'showDetail'])->name('biaya.show');
            Route::get('{biaya}/pay', [BiayaController::class, 'bayar'])->name('biaya.pay');
            Route::get('{biaya}/pdf', [BiayaController::class, 'printPdf'])->name('biaya.pdf');
            Route::get('{biaya}/print', [BiayaController::class, 'printHtml'])->name('biaya.print');
            Route::get('{biaya}/download', [BiayaController::class, 'download'])->name('biaya.download');
            Route::get('{trx}/payment/detail', [BiayaController::class, 'paymentDetail'])->name('biaya.paymentDetail');
        });
        
        /**
         * Pembelian
         */
        Route::group(['prefix' => 'purchase'], function () {
            Route::get('/', [FinancePurchaseController::class, 'index'])->name('purchase');
            Route::post('/', [FinancePurchaseController::class, 'index'])->name('purchase');
            Route::get('create', [FinancePurchaseController::class, 'create'])->name('purchase.create');
            Route::post('store', [FinancePurchaseController::class, 'storePurchase'])->name('purchase.store');
            Route::get('{purchase}/show', [FinancePurchaseController::class, 'showDetail'])->name('purchase.show');
            Route::get('{purchase}/pay', [FinancePurchaseController::class, 'bayar'])->name('purchase.pay');
            Route::post('/pay/save', [FinancePurchaseController::class, 'storePayment'])->name('purchase.savePay');
            Route::get('{purchase}/pdf', [FinancePurchaseController::class, 'printPdf'])->name('purchase.pdf');
            Route::get('{purchase}/print', [FinancePurchaseController::class, 'printHtml'])->name('purchase.print');
            Route::get('{purchase}/download', [FinancePurchaseController::class, 'download'])->name('purchase.download');
            Route::get('{trx}/payment/detail', [FinancePurchaseController::class, 'paymentDetail'])->name('purchase.paymentDetail');
        });

        /**
         * Reporting
         */

        Route::group(['prefix' => 'reports'], function () {
            Route::get('neraca', [FinanceReportsController::class, 'showNeraca'])->name('reports.neraca');
            Route::post('neraca', [FinanceReportsController::class, 'showNeraca'])->name('reports.neraca');
            Route::get('lost_profit', [FinanceReportsController::class, 'showLostProfit'])->name('reports.lost_profit');
            Route::post('lost_profit', [FinanceReportsController::class, 'showLostProfit'])->name('reports.lost_profit');
        });

        Route::group(['prefix' => 'log', 'as' => 'log.'], function() {
            Route::get('/', [FinanceLogController::class, 'index'])->name('show');
            Route::post('/', [FinanceLogController::class, 'index'])->name('show');
        });
    });

});
