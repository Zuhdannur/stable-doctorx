<?php

/**
 * account
 */
Breadcrumbs::for('admin.accounting.account', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('accounting::labels.account.account'), route('admin.accounting.account'));
});

Breadcrumbs::for('admin.accounting.account.create', function ($trail) {
    $trail->parent('admin.accounting.account');
    $trail->push(trans('accounting::labels.account.create'), route('admin.accounting.account.create'));
});

Breadcrumbs::for('admin.accounting.account.edit', function ($trail, $financeAccount) {
    $trail->parent('admin.accounting.account');
    $trail->push(trans('accounting::labels.account.edit'), route('admin.accounting.account.edit', $financeAccount));
});

//journal
Breadcrumbs::for('admin.accounting.account.journal', function ($trail) {
    $trail->parent('admin.accounting.account');
    $trail->push(trans('accounting::labels.journal.create'), route('admin.accounting.account.journal'));
});

Breadcrumbs::for('admin.accounting.account.journal.show', function ($trail, $id) {
    $trail->parent('admin.accounting.account');
    $trail->push(trans('accounting::labels.journal.show'), route('admin.accounting.account.journal.show', $id));
});

Breadcrumbs::for('admin.accounting.account.journal.general', function ($trail, $trx) {
    $trail->parent('admin.accounting.account');
    $trail->push(trans('accounting::labels.journal.general'), route('admin.accounting.account.journal.general', $trx));
});

/**
 * cash and bank
 */
Breadcrumbs::for('admin.accounting.cash', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('accounting::labels.cash.cash'), route('admin.accounting.cash'));
});

Breadcrumbs::for('admin.accounting.cash.create', function ($trail) {
    $trail->parent('admin.accounting.cash');
    $trail->push(trans('accounting::labels.cash.create'), route('admin.accounting.cash.create'));
});

Breadcrumbs::for('admin.accounting.cash.receipt', function ($trail, $id) {
    $trail->parent('admin.accounting.cash');
    $trail->push(trans('accounting::labels.cash.receipt'), route('admin.accounting.cash.receipt', $id));
});

Breadcrumbs::for('admin.accounting.cash.transfer', function ($trail, $id) {
    $trail->parent('admin.accounting.cash');
    $trail->push(trans('accounting::labels.cash.transfer'), route('admin.accounting.cash.transfer', $id));
});

Breadcrumbs::for('admin.accounting.cash.send', function ($trail, $id) {
    $trail->parent('admin.accounting.cash');
    $trail->push(trans('accounting::labels.cash.send'), route('admin.accounting.cash.send', $id));
});

Breadcrumbs::for('admin.accounting.cash.journal', function ($trail, $id) {
    $trail->parent('admin.accounting.cash');
    $trail->push(trans('accounting::labels.cash.journal'), route('admin.accounting.cash.journal', $id));
});

Breadcrumbs::for('admin.accounting.cash.journal.transfer.show', function ($trail, $trx) {
    $trail->parent('admin.accounting.cash');
    $trail->push(trans('accounting::labels.cash.show_transfer'), route('admin.accounting.cash.journal.transfer.show', $trx));
});

Breadcrumbs::for('admin.accounting.cash.journal.receipt.show', function ($trail, $trx) {
    $trail->parent('admin.accounting.cash');
    $trail->push(trans('accounting::labels.cash.show_receipt'), route('admin.accounting.cash.journal.receipt.show', $trx));
});

Breadcrumbs::for('admin.accounting.cash.journal.send.show', function ($trail, $trx) {
    $trail->parent('admin.accounting.cash');
    $trail->push(trans('accounting::labels.cash.show_send'), route('admin.accounting.cash.journal.send.show', $trx));
});
/* end cash */

/**
 * setting
 */
Breadcrumbs::for('admin.accounting.settings.ms_categories', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('accounting::labels.settings.ms_categories.main'), route('admin.accounting.settings.ms_categories'));
});

Breadcrumbs::for('admin.accounting.settings.categories', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('accounting::labels.settings.categories.main'), route('admin.accounting.settings.categories'));
});

Breadcrumbs::for('admin.accounting.settings.tax', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('accounting::labels.settings.tax.main'), route('admin.accounting.settings.tax'));
});

Breadcrumbs::for('admin.accounting.settings.ms_categories.edit', function ($trail, $categories) {
    $trail->parent('admin.accounting.settings.ms_categories');
    $trail->push(trans('accounting::labels.settings.ms_categories.edit'), route('admin.accounting.settings.ms_categories.edit', $categories));
});

Breadcrumbs::for('admin.accounting.settings.ms_categories.create', function ($trail) {
    $trail->parent('admin.accounting.settings.ms_categories');
    $trail->push(trans('accounting::labels.settings.ms_categories.create'), route('admin.accounting.settings.ms_categories.create'));
});

Breadcrumbs::for('admin.accounting.settings.categories.create', function ($trail) {
    $trail->parent('admin.accounting.settings.categories');
    $trail->push(trans('accounting::labels.settings.categories.create'), route('admin.accounting.settings.categories.create'));
});

Breadcrumbs::for('admin.accounting.settings.categories.edit', function ($trail, $categories) {
    $trail->parent('admin.accounting.settings.categories');
    $trail->push(trans('accounting::labels.settings.categories.edit'), route('admin.accounting.settings.categories.edit', $categories));
});

Breadcrumbs::for('admin.accounting.settings.tax.create', function ($trail) {
    $trail->parent('admin.accounting.settings.tax');
    $trail->push(trans('accounting::labels.settings.tax.create'), route('admin.accounting.settings.tax.create'));
});

Breadcrumbs::for('admin.accounting.settings.tax.edit', function ($trail, $tax) {
    $trail->parent('admin.accounting.settings.tax');
    $trail->push(trans('accounting::labels.settings.tax.edit'), route('admin.accounting.settings.tax.edit', $tax));
});
/* end of setting */

/**
 * Biaya
 */

Breadcrumbs::for('admin.accounting.biaya', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('accounting::labels.biaya.main'), route('admin.accounting.biaya'));
});
Breadcrumbs::for('admin.accounting.biaya.create', function ($trail) {
    $trail->parent('admin.accounting.biaya');
    $trail->push(trans('accounting::labels.biaya.create'), route('admin.accounting.biaya.create'));
});
Breadcrumbs::for('admin.accounting.biaya.show', function ($trail ,$id) {
    $trail->parent('admin.accounting.biaya');
    $trail->push(trans('accounting::labels.biaya.show'), route('admin.accounting.biaya.show', $id));
});
Breadcrumbs::for('admin.accounting.biaya.pay', function ($trail ,$id) {
    $trail->parent('admin.accounting.biaya');
    $trail->push(trans('accounting::labels.biaya.pay'), route('admin.accounting.biaya.pay', $id));
});
Breadcrumbs::for('admin.accounting.biaya.paymentDetail', function ($trail ,$trx) {
    $trail->parent('admin.accounting.biaya');
    $trail->push(trans('accounting::labels.biaya.paymentDetail'), route('admin.accounting.biaya.paymentDetail', $trx));
});

/**
 * Pembelian
 */
Breadcrumbs::for('admin.accounting.purchase', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('accounting::labels.purchase.main'), route('admin.accounting.purchase'));
});
Breadcrumbs::for('admin.accounting.purchase.create', function ($trail) {
    $trail->parent('admin.accounting.purchase');
    $trail->push(trans('accounting::labels.purchase.create'), route('admin.accounting.purchase.create'));
});

Breadcrumbs::for('admin.accounting.purchase.show', function ($trail, $purchase) {
    $trail->parent('admin.accounting.purchase');
    $trail->push(trans('accounting::labels.purchase.show'), route('admin.accounting.purchase.show', $purchase));
});

Breadcrumbs::for('admin.accounting.purchase.pay', function ($trail, $purchase) {
    $trail->parent('admin.accounting.purchase');
    $trail->push(trans('accounting::labels.purchase.pay'), route('admin.accounting.purchase.pay', $purchase));
});

Breadcrumbs::for('admin.accounting.purchase.paymentDetail', function ($trail, $trx) {
    $trail->parent('admin.accounting.purchase');
    $trail->push(trans('accounting::labels.purchase.paymentDetail'), route('admin.accounting.purchase.paymentDetail', $trx));
});

/**
 * Reports
 */
Breadcrumbs::for('admin.accounting.reports.neraca', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('accounting::labels.reports.neraca'), route('admin.accounting.reports.neraca'));
});
Breadcrumbs::for('admin.accounting.reports.lost_profit', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('accounting::labels.reports.lost_profit'), route('admin.accounting.reports.lost_profit'));
});

/**
 * Log
 */
Breadcrumbs::for('admin.accounting.log.show', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('log.show'), route('admin.accounting.log.show'));
});
