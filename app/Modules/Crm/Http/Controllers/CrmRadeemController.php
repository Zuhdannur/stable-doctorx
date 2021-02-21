<?php

namespace App\Modules\Crm\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modules\Patient\Models\Patient;
use App\Modules\Crm\Models\CrmMembership;
use App\Modules\Crm\Models\CrmRadeemPoint;
use App\Modules\Crm\Models\CrmMsRadeemPoint;

use App\Modules\Attribute\Models\LogActivity;
use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Accounting\Models\FinanceTransaction;

class CrmRadeemController extends Controller
{
    public function index(Request $request)
    {
        if($request->isMethod('POST')){
            $patient = Patient::where('patient_unique_id', $request->pid)->first();
            $errors = array(
                'msg' => __('crm::exceptions.radeem.patient_not_found'),
                'data' => $request->pid,
            );

            if($patient){
                if($patient->membership){
                    $radeem = CrmMsRadeemPoint::optionList();

                    return view('crm::radeem_point.index')
                    ->withRadeem($radeem)
                    ->withPatient($patient);
                }else{
                    $errors['msg'] = __('crm::exceptions.radeem.not_registered');
                    return view('crm::radeem_point.index')
                    ->withError($errors);
                }
            }else{
                return view('crm::radeem_point.index')
                ->withError($errors);
            }
        }else{
            return view('crm::radeem_point.index');
        }
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            $status = false;
            $message = trans('crm::exceptions.radeem.create_error');

            $radeem_item = CrmMsRadeemPoint::find($request->item_radeem);
            if(!$radeem_item){
                return response()->json(array('status' => $status, 'message' => $message));
            }
            
            $membership = CrmMembership::find($request->id);
            if(!$membership){
                return response()->json(array('status' => $status, 'message' => $message));
            }
            
            $is_point = $request->item_ammount * $radeem_item->point;
            if($is_point > $membership->total_point){
                $message = trans('crm::exceptions.radeem.point_limited');
                return response()->json(array('status' => $status, 'message' => $message));
            }

            DB::beginTransaction();
            try {

                //save
                $save = new CrmRadeemPoint;
                $save->membership_id = $membership->id;
                $save->item_code = $radeem_item->code;
                $save->point = $radeem_item->point;
                $save->ammount = $request->item_ammount;
                $save->nominal = $radeem_item->nominal_gift;
    
                // update membership point
                $membership->total_point =  $membership->total_point - $is_point;
                
                $transaction = FinanceTransaction::create([
                    'trx_type_id' => config('finance_trx.trx_types.general'), 
                    'transaaction_code' => '',
                    'memo' => '',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ]);
    
                if($transaction){
                    $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);
                    $transaction->transaction_code = $transaction_code;
    
                    $transaction->save();
    
                    //update balance radeem account
                    $acc_radeem = config('finance_account.default.acc_radeem');
                    $acc_radeem = FinanceAccount::sumDebit($acc_radeem, $radeem_item->nominal_gift);
    
                    // save journal radeem point
                    $jornals = FinanceJournal::create([
                         'transaction_id' => $transaction->id,
                         'type' => config('finance_journal.types.debit'), 
                         'account_id' => $acc_radeem->id,
                         'value' => $radeem_item->nominal_gift,
                         'balance'  => $acc_radeem->balance,                      
                         'description' => 'Radeem Point Membership',                        
                         'tags' => 'is_radeem'                       
                    ]);
    
                    //update balance radeem account
                    $acc_cash = config('finance_account.default.acc_cash');
                    $acc_cash = FinanceAccount::sumKredit($acc_cash, $radeem_item->nominal_gift);
    
                    // save journal radeem point
                    $jornals = FinanceJournal::create([
                         'transaction_id' => $transaction->id,
                         'type' => config('finance_journal.types.kredit'), 
                         'account_id' => $acc_cash->id,
                         'value' => $radeem_item->nominal_gift,
                         'balance'  => $acc_cash->balance,                      
                         'description' => 'Radeem Point Membership',                        
                         'tags' => 'is_cash'                       
                    ]);
                }
    
                if($save->save() &&  $membership->save() && $transaction){
    
                    $log = new LogActivity();
                    $log->module_id = config('my-modules.crm');
                    $log->action = "Radeem Point Membership";
                    $log->desc = "Nama : ".$membership->patient->patient_name.'('.$membership->patient->patient_unique_id.')'
                                .', Point yang ditukar : '.($save->point * $save->ammount)
                                .', Total Hadiah : '.($save->point * $save->ammount * $save->nominal);
    
                    $log->save();
    
                    $status = true;
                    $message = trans('crm::alerts.radeem.created');
                    DB::commit();
                }else{
                    DB::rollback();
                }
            }catch(\Exception $e){

                DB::rollback();
                if(env("APP_DEBUG") == true){
                    dd($e);
                };
                
            }

            return response()->json(array('status' => $status, 'message' => $message));
            
        }else{
            abort(404);
        }
    }
}
