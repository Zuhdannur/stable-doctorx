<?php

namespace App\Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Accounting\Models\FinanceTransaction;

use App\Http\Controllers\Controller;
use DB;

class FinanceReportsController extends Controller
{
    public function showNeraca(Request $request)
    {
        /// variabel default
        $date = array(
            'date_1' => date('d/m/Y'),
            'date_2' => date('d/m/Y'),
        );
        $date_1 = date('Y-m-d');
        $date_2 = date('Y-m-d');

        if($request->isMethod('post')){
            if($request->input('date_1')){
                $date_1 = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1)->format('Y-m-d');
                $date['date_1'] = $request->input('date_1');
            }
            
            if($request->input('date_2')){
                $date_2 = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2)->format('Y-m-d');
                $date['date_2'] = $request->input('date_2');
            }
        }
        
        // get account aktiva, kewajiban, dan modal
        $account = FinanceAccount::with('accountCategory')
        ->whereHas('accountCategory', function($q){
            return $q->whereIn('type', [1,2,3]);
        })
        ->orderBy('account_code')
        ->get();

        foreach($account as $key => $val){
            $get_last_balance = FinanceJournal::where('account_id', $val->id)
            ->select(['balance', 'transaction_id'])
            ->with('transaction')
            ->whereHas('transaction', function($q) use($date_2) {
                return $q->whereDate('trx_date', [$date_2]);
            })
            ->orderBy('finance_journals.id', "desc")
            ->first();

            if($date_1 === $date_2) {
                $get_first_balance = FinanceJournal::where('account_id', $val->id)
                ->select(['balance', 'transaction_id'])
                ->with('transaction')
                ->whereHas('transaction', function($q) use($date_1) {
                    return $q->whereDate('trx_date', '<', [$date_1]);
                })
                ->orderBy('finance_journals.id', "desc")
                ->first();
            }else {
                $get_first_balance = FinanceJournal::where('account_id', $val->id)
                ->select(['balance', 'transaction_id'])
                ->with('transaction')
                ->whereHas('transaction', function($q) use($date_1) {
                    return $q->whereDate('trx_date', [$date_1]);
                })
                ->orderBy('finance_journals.id', "asc")
                ->first();
            }
            
            $last_balance = isset($get_first_balance['balance']) ? $get_last_balance['balance'] : 0;
            $first_balance = isset($get_first_balance['balance']) && $get_first_balance['balance'] >= 0 ? $get_first_balance['balance'] : 0;

            $get_balance = $last_balance - $first_balance;

            if($get_balance < 0) $get_balance = 0;
           
            $neraca[$key] = array(
                'account_name' => $val->account_name,
                'account_code' => $val->account_code,
                'category' => $val->accountCategory->category_name,
                'category_code' => $val->accountCategory->category_code,
                'category_id' => $val->accountCategory->id,
                'category_parent' => $val->accountCategory->parent->category_name,
                'category_parent_code' => $val->accountCategory->parent->category_code,
                'category_parent_id' => $val->accountCategory->parent->id,
                'type' => $val->accountCategory->type,
                'value' => $get_balance
            );
        }
        

        // get pendapatan dan beban (4,5)
        $pendapatanBeban = FinanceAccount::with('accountCategory')
        ->whereHas('accountCategory', function($q){
            return $q->whereIn('type',[ 4, 5]);
        })
        ->orderBy('account_code')
        ->get();

        $sum_pendapatan = 0;
        foreach($pendapatanBeban as $key => $val){
            $get_last_balance = FinanceJournal::where('account_id', $val->id)
            ->with('transaction')
            ->whereHas('transaction', function($q) use($date_2) {
                return $q->whereDate('trx_date', [$date_2]);
            })
            ->orderBy('finance_journals.id', "desc")
            ->first();

            if($date_1 === $date_2) {
                $get_first_balance = FinanceJournal::where('account_id', $val->id)
                ->select(['balance', 'transaction_id'])
                ->where('balance', '!=', $get_last_balance['balance'])
                ->with('transaction')
                ->whereHas('transaction', function($q) use($date_1) {
                    return $q->whereDate('trx_date', '<', [$date_1]);
                })
                ->orderBy('finance_journals.id', "desc")
                ->first();
            }else {
                $get_first_balance = FinanceJournal::where('account_id', $val->id)
                ->select(['balance', 'transaction_id'])
                ->with('transaction')
                ->whereHas('transaction', function($q) use($date_1) {
                    return $q->whereDate('trx_date', [$date_1]);
                })
                ->orderBy('finance_journals.id', "asc")
                ->first();
            }
            
            $last_balance = isset($get_first_balance['balance']) ? $get_last_balance['balance'] : 0;
            $first_balance = isset($get_first_balance['balance']) && $get_first_balance['balance'] >= 0 ? $get_first_balance['balance'] : 0;

            $get_balance = $last_balance - $first_balance;

            if($get_balance < 0) $get_balance = 0;

            if($val->accountCategory->type == 4){
                $sum_pendapatan = $sum_pendapatan +  $get_balance;
            }
            else if($val->accountCategory->type == 5){
                $sum_pendapatan = $sum_pendapatan -  $get_balance;
                $neraca[] = array(
                    'account_name' => $val->account_name,
                    'account_code' => $val->account_code,
                    'category' => $val->accountCategory->category_name,
                    'category_code' => $val->accountCategory->category_code,
                    'category_id' => $val->accountCategory->id,
                    'category_parent' => $val->accountCategory->parent->category_name,
                    'category_parent_code' => $val->accountCategory->parent->category_code,
                    'category_parent_id' => $val->accountCategory->parent->id,
                    'type' => $val->accountCategory->type,
                    'value' => $get_balance
                );
            }

        }

        return view('accounting::reports.neraca')
        ->withDate($date)
        ->withSumPendapatan($sum_pendapatan)
        ->withNeraca($neraca);
    }

    public function showLostProfit(Request $request)
    {
        // variabel default
        $date = array(
            'date_1' => date('d/m/Y'),
            'date_2' => date('d/m/Y'),
        );
        $date_1 = date('Y-m-d');
        $date_2 = date('Y-m-d');

        if($request->isMethod('post')){
            if($request->input('date_1')){
                $date_1 = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1)->format('Y-m-d');
                $date['date_1'] = $request->input('date_1');
            }
            
            if($request->input('date_2')){
                $date_2 = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2)->format('Y-m-d');
                $date['date_2'] = $request->input('date_2');
            }
        }

        // DB::connection()->enableQueryLog();

        // get account pendapatan dan beban
        $account = FinanceAccount::with('accountCategory')
        ->whereHas('accountCategory', function($q){
            return $q->whereIn('type', [4,5]);
        })
        ->orderBy('account_code')
        ->get();
        
        $report = array();
        foreach($account as $key => $val){
            $get_last_balance = FinanceJournal::where('account_id', $val->id)
            ->with('transaction')
            ->whereHas('transaction', function($q) use($date_2) {
                return $q->whereDate('trx_date', [$date_2]);
            })
            ->orderBy('finance_journals.id', "desc")
            ->first();

            if($date_1 === $date_2) {
                $get_first_balance = FinanceJournal::where('account_id', $val->id)
                ->select(['balance', 'transaction_id'])
                ->where('balance', '!=', $get_last_balance['balance'])
                ->with('transaction')
                ->whereHas('transaction', function($q) use($date_1) {
                    return $q->whereDate('trx_date', '<', [$date_1]);
                })
                ->orderBy('finance_journals.id', "desc")
                ->first();
            }else {
                $get_first_balance = FinanceJournal::where('account_id', $val->id)
                ->select(['balance', 'transaction_id'])
                ->with('transaction')
                ->whereHas('transaction', function($q) use($date_1) {
                    return $q->whereDate('trx_date', [$date_1]);
                })
                ->orderBy('finance_journals.id', "asc")
                ->first();
            }
            
            $last_balance = isset($get_first_balance['balance']) ? $get_last_balance['balance'] : 0;
            $first_balance = isset($get_first_balance['balance']) && $get_first_balance['balance'] >= 0 ? $get_first_balance['balance'] : 0;

            $get_balance = $last_balance - $first_balance;

            if($get_balance < 0) $get_balance = 0;
            
            $report[$key] = array(
                'account_name' => $val->account_name,
                'account_code' => $val->account_code,
                'category' => $val->accountCategory->category_name,
                'category_code' => $val->accountCategory->category_code,
                'category_id' => $val->accountCategory->id,
                'category_parent' => $val->accountCategory->parent->category_name,
                'category_parent_code' => $val->accountCategory->parent->category_code,
                'category_parent_id' => $val->accountCategory->parent->id,
                'type' => $val->accountCategory->type,
                'value' => $get_balance
            );
        }
        // dd(DB::getQueryLog());
        return view('accounting::reports.lost-profit')
        ->withDate($date)
        ->withReport($report);

    }
}


/**
 * 
 * neraca
 * 
 * aktiva == kewajiban + modal
 * 
 * modal = total ekuitas - beban
 * 
 */
