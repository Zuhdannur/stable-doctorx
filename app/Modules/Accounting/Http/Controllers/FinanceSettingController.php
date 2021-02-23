<?php

namespace App\Modules\Accounting\Http\Controllers;

use DB;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Modules\Accounting\Models\FinanceTax;
use App\Modules\Attribute\Models\LogActivity;
use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceAccountCategory;

class FinanceSettingController extends Controller
{
    public function showMsCategories(Request $request)
    {
        if($request->ajax()){
            $model = FinanceAccountCategory::where('parent_id' ,'<', 1)
                    ->orderBy('category_code', 'ASC');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $btn = $data->edit_ms_button;
                return $btn;
            })
            ->editColumn('type', function($data){
                return config('finance_account.type_list.'.$data->type);
            })
            ->rawColumns(['action'])
            ->make(true);
        }
       
        return view('accounting::settings.ms_categories');
    }

    public function showCategories(Request $request)
    {
        if($request->ajax()){
            $model = FinanceAccountCategory::where('parent_id' ,'>', 0)
                    ->with('parent')
                    ->orderBy('category_code', 'ASC');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $btn = $data->edit_button;
                return $btn;
            })
            ->editColumn('type', function($data){
                return config('finance_account.type_list.'.$data->type);
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('accounting::settings.categories');
    }

    public function createMsCategories()
    {
        $typeList = config('finance_account.type_list');
        return view('accounting::settings.form.create_ms_categories')
        ->withType($typeList);
    }

    public function editMsCategories(FinanceAccountCategory $categories)
    {
        return view('accounting::settings.form.edit_ms_categories')
        ->withCategories($categories);
    }

    public function storeMsCategories(Request $request)
    {
        $status = false;
        $message = 'Akses tidak sah !!!';

        if($request->isMethod('patch')){

            if (auth()->user()->cannot('update master categories')) {
                return response()->json(['messages' => trans('exceptions.cannot_update')], 401);
            }

            $category = FinanceAccountCategory::find($request->input('id'));
            if($category){
                $category->category_name = $request->category_name;

                DB::beginTransaction();
                try {
                    DB::commit();
                    $category->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.accounting');
                    $log->action = "Update Master Category";
                    $log->desc = "Kode Master Kategori : $category->category_code, Nama : $category->category_name";
                    
                    $log->save();
                    $status = true;
                    $message = __('accounting::alerts.settings.ms_categories.updated');
                } catch (\Exception $e) {

                    DB::rollback();
                    if(env("APP_DEBUG")){
                        dd($e);
                    }

                    $status = false;
                    $message = trans('accounting::exceptions.settings.ms_categories.update_error');
                }
            }else{
                $status = false;
                $message = trans('accounting::exceptions.settings.ms_categories.update_error');
            }
        }else if($request->isMethod('post')){
            $save = DB::transaction(function () use ($request){
                $categories = new FinanceAccountCategory;
                $categories->category_name = $request->category_name;
                $categories->type = $request->type;

                $categories->save();
                
                if($categories){
                    $count = FinanceAccountCategory::where('parent_id', '<', 1)->where('type',$request->type)->count();
                    if($count < 1){
                        $count = 1;
                    }
    
                    $categories->category_code = $request->type."-".$count;
    
                    $categories->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.accounting');
                    $log->action = "Create Master Category";
                    $log->desc = "Kode Master Kategori : $categories->category_code, Nama : $categories->category_name";
                    
                    $log->save();

                    return true;
                }
                // throw new GeneralException(trans('accounting::exceptions.settings.ms_categories.create_error'));
            });

            if($save){
                $status = true;
                $message = __('accounting::alerts.settings.ms_categories.created');
            }else{
                $status = false;
                $message = trans('accounting::exceptions.settings.ms_categories.create_error');
            }
        }

        return response()->json(array('status' => $status, 'message' => $message));
    }

    public function createCategories()
    {
        $master = FinanceAccountCategory::where('parent_id','<',1)->get();

        $masterList = '<option></option>';
        foreach($master as $val){
            $masterName = $val->category_code.' '.$val->category_name.' ('.config('finance_account.type_list.'.$val->type).')';
            $masterList .= '<option value="'.$val->id.'">'.$masterName.'</option>';
        }
        return view('accounting::settings.form.create_categories')
        ->withMasterList($masterList);
    }

    public function editCategories(FinanceAccountCategory $categories)
    {
        return view('accounting::settings.form.edit_categories')
        ->withCategories($categories);
    }

    public function storeCategories(Request $request)
    {
        $status = false;
        $message = 'Akses tidak sah !!!';

        if($request->isMethod('patch')){

            if (auth()->user()->cannot('update categories')) {
                return response()->json(['messages' => trans('exceptions.cannot_update')], 401);
            }

            $category = FinanceAccountCategory::find($request->input('id'));
            if($category){
                $category->category_name = $request->category_name;

                DB::beginTransaction();
                try {

                    $category->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.accounting');
                    $log->action = "Update Category";
                    $log->desc = "Kode Kategori : $category->category_code, Nama : $category->category_name";
                    
                    $log->save();
                    DB::commit();
                    $status = true;
                    $message = __('accounting::alerts.settings.categories.updated');
                } catch (\Exception $e) {

                    DB::rollback();
                    if(env("APP_DEBUG") == true){
                        dd($e);
                    }
                    $status = false;
                    $message = trans('accounting::exceptions.settings.categories.update_error');
                }
            }else{
                $status = false;
                $message = trans('accounting::exceptions.settings.categories.update_error');
            }
        }else if($request->isMethod('post')){

            $save = DB::transaction(function () use ($request){
                $master = FinanceAccountCategory::findOrFail($request->master);

                $categories = new FinanceAccountCategory;
                $categories->category_name = $request->category_name;
                $categories->parent_id = $request->master;
                $categories->type = $master->type;

                $categories->save();
                
                if($categories){
                    $count = FinanceAccountCategory::where('parent_id', $categories->parent_id)->where('type',$categories->type)->count();
                    if($count < 1){
                        $count = 1;
                    }
    
                    $categories->category_code = $master->category_code."-".$count;
    
                    $categories->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.accounting');
                    $log->action = "Create Category";
                    $log->desc = "Kode Kategori : $categories->category_code, Nama : $categories->category_name";
                    
                    $log->save();

                    return true;
                }
                // throw new GeneralException(trans('accounting::exceptions.settings.categories.create_error'));
            });

            if($save){
                $status = true;
                $message = __('accounting::alerts.settings.categories.created');
            }else{
                $status = false;
                $message = trans('accounting::exceptions.settings.categories.create_error');
            }
        }

        return response()->json(array('status' => $status, 'message' => $message));
    }

    public function showTax(Request $request)
    {
        if($request->ajax()){
            $model = Financetax::with('accSales')
                    ->with('accPurchase')
                    ->orderBy('id', 'ASC');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $btn = $data->edit_button;
                // $btn = $data->edit_ms_button;
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('accounting::settings.tax');
    }

    public function createTax()
    {
        $account = FinanceAccount::where('account_category_id', '!=' ,config('finance_account.default.cash'))
            ->whereHas('accountCategory', function($q){
                $q->where('type','!=', config('finance_account.type.ekuitas'));
            })
            ->get();
        $accountList = '<option></option>';
        foreach($account as $val){
            $name = $val->account_code.' - '.$val->account_name.' - '.$val->accountCategory['category_name'];
            $accountList .= '<option value ="'.$val->id.'">'.$name.'</option>';
        }
        return view('accounting::settings.form.create_tax')
        ->withAccountList($accountList);
    }

    public function editTax(FinanceTax $tax)
    {
        return view('accounting::settings.form.edit_tax')
        ->withTax($tax);
    }

    public function storeTax(Request $request)
    {
        $status = false;
        $message = 'Akses tidak sah !!!';

        if($request->isMethod('patch')){

            if (auth()->user()->cannot('update tax')) {
                return response()->json(['messages' => trans('exceptions.cannot_update')], 401);
            }

            $tax = FinanceTax::find($request->input('id'));
            if($tax){

                DB::beginTransaction();
                try {

                    $tax->tax_name = $request->tax_name;
                    $tax->percentage = $request->percentage;
    
                    $tax->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.accounting');
                    $log->action = "Update Tax";
                    $log->desc = "ID : $tax->id, Nama : $tax->tax_name";

                    $log->save();

                    DB::commit();
                    $status = true;
                    $message = __('accounting::alerts.settings.tax.updated');
                } catch (\Exception $e) {

                    DB::rollback();
                    if(env("APP_DEBUG") == true){
                        dd($e);
                    }
                    $status = false;
                    $message = trans('accounting::exceptions.settings.tax.update_error');
                }
            }else{
                $status = false;
                $message = trans('accounting::exceptions.settings.tax.update_error');
            }
        }else if($request->isMethod('post')){

            if (auth()->user()->cannot('create tax')) {
                return response()->json(['messages' => trans('exceptions.cannot_create')], 401);
            }

            if($request->tax_sales == $request->tax_purchase){
                $message = 'Akun Pajak Keluaran dan Masukan Tidak Boleh Sama !!';
                return response()->json(array('status' => false, 'message' => $message));
            }
            $save = DB::transaction(function () use ($request){
                $taxSales = FinanceAccount::findOrFail($request->tax_sales);
                $taxPurchase = FinanceAccount::findOrFail($request->tax_purchase);

                $tax = new FinanceTax;
                $tax->tax_name = $request->tax_name;
                $tax->account_tax_sales = $taxSales->id;
                $tax->account_tax_purchase = $taxPurchase->id;
                $tax->percentage = $request->percentage;
                $tax->created_by = auth()->user()->id;
                $tax->updated_by = auth()->user()->id;

                if($tax->save()){
                    $log = new LogActivity();
                    $log->module_id = config('my-modules.accounting');
                    $log->action = "Create Tax";
                    $log->desc = "ID : $tax->id, Nama : $tax->tax_name";

                    $log->save();
                    return true;
                };

                return false;
            });

            if($save){
                $status = true;
                $message = __('accounting::alerts.settings.tax.created');
            }else{
                $status = false;
                $message = trans('accounting::exceptions.settings.tax.create_error');
            }
        }

        return response()->json(array('status' => $status, 'message' => $message));

    }
}
