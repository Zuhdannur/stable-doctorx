<?php

namespace App\Modules\Accounting\Repositories;

use App\Repositories\BaseRepository;
use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Accounting\Models\FinanceTransaction;

class FinanceRepository extends BaseRepository
{
    public function model()
    {
        return FinanceAccount::class;
    }

    public static function storePendapatan($transaction_id, $value)
    {
        //save to pendapatan
        $pendapatan = FinanceAccount::sumKredit(config('finance_account.default.acc_pendapatan'), $value);
        $pendapatanJournal = FinanceJournal::create([
            'transaction_id' => $transaction_id,
            'account_id' => $pendapatan->id,
            'balance' => $pendapatan->balance,
            'type' => config('finance_journal.types.kredit'),
            'value' => $value,
            'tax' => '',
            'tags' => 'is_pendapatan'
        ]);
    }

    public static function storePiutang($transaction_id, $value)
    {
        $piutang = FinanceAccount::sumDebit(config('finance_account.default.acc_piutang'), $value);
        $piutangJournal = FinanceJournal::create([
            'transaction_id' => $transaction_id,
            'account_id' => $piutang->id,
            'balance' => $piutang->balance,
            'type' => config('finance_journal.types.debit'),
            'value' => $value,
            'tax' => '',
            'tags' => 'is_piutang'
        ]);
    }

    public static function storeKreditPiutang($transaction_id, $value)
    {
        $piutang = FinanceAccount::sumKredit(config('finance_account.default.acc_piutang'), $value);
        $piutangJournal = FinanceJournal::create([
            'transaction_id' => $transaction_id,
            'account_id' => $piutang->id,
            'balance' => $piutang->balance,
            'type' => config('finance_journal.types.kredit'),
            'value' => $value,
            'tax' => '',
            'tags' => 'is_piutang'
        ]);
    }

    public static function storeDebitDiscount($transaction_id, $value)
    {
        $discount = FinanceAccount::sumDebit(config('finance_account.default.acc_discount'), $value);
        $discountJournanl = FinanceJournal::create([
            'transaction_id' => $transaction_id,
            'account_id' => $discount->id,
            'balance' => $discount->balance,
            'type' => config('finance_journal.types.debit'),
            'value' => $value,
            'tax' => '',
            'tags' => 'is_discount'
        ]);
    }

    public static function storeDebitRadeem($transaction_id, $value)
    {
        $acc_radeem = FinanceAccount::sumDebit( config('finance_account.default.acc_radeem'), $value);
        $jornals = FinanceJournal::create([
            'transaction_id' => $transaction_id,
            'account_id' => $acc_radeem->id,
            'balance'  => $acc_radeem->balance,
            'type' => config('finance_journal.types.debit'),
            'value' => $value,
            'description' => 'Radeem Point Membership',
            'tags' => 'is_radeem'
        ]);
    }

    /**
     * example person param
     * $person = array(
     *  'id' => $patient->id || user()->id
     *  'name' => $patient->name || user()->name || null
     *  'type' =>  config('finance_trx.person_type.patient')
     * )
     *
     * $transactionType = config('finance_trx.trx_types.invoice_payment')
     */
    public static function createTransaction($transactionType, array $person)
    {
        /** save data to transaction table*/
        $transaction = FinanceTransaction::firstOrNew([
            'trx_type_id' => $transactionType,
            'transaction_code' => '', //update di bawah
            'memo' => '',
            'person' => $person['name'],
            'person_id' => $person['id'],
            'person_type' => $person['type'],
            'trx_date' => \Carbon\Carbon::now()->format('Y-m-d'),
            'potongan' => '',
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ]);

        $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);
        $transaction->transaction_code = $transaction_code;
        $transaction->save();

        return $transaction;
    }

    public static function storeDebitCash($transaction_id, $cash_id, $value)
    {
        $cash = FinanceAccount::sumDebit($cash_id, $value);
        $cashJournal = FinanceJournal::create([
            'transaction_id' => $transaction_id,
            'account_id' => $cash->id,
            'balance' => $cash->balance,
            'type' => config('finance_journal.types.debit'),
            'value' => $value,
            'tax' => '',
            'tags' => 'is_cash'
        ]);
    }
}
