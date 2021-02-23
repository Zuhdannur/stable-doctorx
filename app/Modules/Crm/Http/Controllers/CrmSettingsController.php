<?php

namespace App\Modules\Crm\Http\Controllers;

use DataTables;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modules\Crm\Models\CrmSettingWa;

use App\Modules\Crm\Models\CrmMsMembership;
use App\Modules\Attribute\Models\LogActivity;

class CrmSettingsController extends Controller
{
    public function indexMembership(Request $request)
    {
        if($request->ajax()){
            $model = CrmMsMembership::orderBy('Name', 'ASC');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $btn = '';
                $btn .= $data->edit_button;
                $btn .= $data->delete_button;
                return $btn;
            })
            ->editColumn('min_trx', function($data){
                return currency()->rupiah($data->min_trx, setting()->get('currency_symbol'));
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('crm::settings.membership.index');
    }

    public function createMembership()
    {
        return view('crm::settings.membership.create');
    }

    public function storeMembership(Request $request)
    {
        if($request->ajax()){

            if($request->isMethod('post')){
                $name = CrmMsMembership::where('name', $request->name)->first();
                if($name){
                    $status = false;
                    $message = trans('crm::exceptions.settings.membership.name_already_exists');
                    return response()->json(array('status' => $status, 'message' => $message));
                }
    
                $membership = new CrmMsMembership;
                $membership->name = $request->name;
                $membership->point = $request->point;
                $membership->min_trx =  currency()->digit($request->min_trx);

                DB::beginTransaction();
                try {
                    $membership->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.crm');
                    $log->action = "Create Master Memsbership";
                    $log->desc = "ID : $membership->id, Name :". $membership->name;

                    $log->save();

                    DB::commit();
                    $status = true;
                    $message = __('crm::alerts.settings.membership.created');
                } catch (\Exception $e) {
                    DB::rollback();

                    if(env("APP_DEBUG") == true){
                        dd($e);
                    }

                    $status = false;
                    $message = trans('crm::exceptions.settings.membership.create_error');
                }

                return response()->json(array('status' => $status, 'message' => $message));
            }

            //update
            if($request->isMethod('patch')){
                $name = CrmMsMembership::where('name', $request->name)->first();
                $membership = CrmMsMembership::find($request->id);

                if(!$membership){
                    $status = false;
                    $message = trans('crm::exceptions.settings.membership.update_error');
                    return response()->json(array('status' => $status, 'message' => $message));
                }

                if($name->id != $membership->id){
                    $status = false;
                    $message = trans('crm::exceptions.settings.membership.name_already_exists');
                    return response()->json(array('status' => $status, 'message' => $message));
                }

                $membership->name = $request->name;
                $membership->point = $request->point;
                $membership->min_trx =  currency()->digit($request->min_trx);

                DB::beginTransaction();
                try {
                    $membership->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.crm');
                    $log->action = "Update Master Memsbership";
                    $log->desc = "ID : $membership->id, Name :". $membership->name;

                    $log->save();
                    DB::commit();
                    $status = true;
                    $message = __('crm::alerts.settings.membership.updated');
                } catch (\Exception $e) {
                    DB::rollback();

                    if(env("APP_DEBUG") == true){
                        dd($e);
                    }

                    $status = false;
                    $message = trans('crm::exceptions.settings.membership.update_error');
                }

                return response()->json(array('status' => $status, 'message' => $message));
            }
        }
    }

    public function showMembership(CrmMsMembership $membership)
    {
        return view('crm::settings.membership.edit')
        ->withMembership($membership);
    }

    public function destroyMembership(CrmMsMembership $membership)
    {
        DB::beginTransaction();
        try{

            $log = new LogActivity();
            $log->module_id = config('my-modules.crm');
            $log->action = "Delete Master Memsbership";
            $log->desc = "ID : $membership->id, Name :". $membership->name;

            $log->save();
            
            $membership->delete();
            DB::commit();
            return redirect()->route('admin.crm.settings.membership')->withFlashSuccess(__('crm::alerts.settings.membership.deleted'));
        }catch(\Exception $e){
            DB::rollback();

            if(env("APP_DEBUG") == true){
                dd($e);
            }
            return redirect()->route('admin.crm.settings.membership')->withFlashDanger(__('crm::exceptions.settings.membership.delete_error'));
        }
    }

    public function settingsWhatsapp(Request $request)
    {
        $wooConfig = CrmSettingWa::getSettingsWooWa();
        $twillioConfig = CrmSettingWa::getSettingsTwiilio();
        $activeConfig = CrmSettingWa::where('is_active', 1)->first();
        $allConfig = CrmSettingWa::get();

        $list = '';
        $msg = '';

        foreach ($allConfig as $val) {
            $list .= '<option value="'.$val->id.'" '.($val->is_active == 1 ? 'selected' : '').">".$val->name.'</option>';
            if($val->is_active == 1){
                $msg = $val->wa_message;
                $msg_reminder = $val->wa_message_reminder;
            }
        }
        // $twilioConfig
        return view('crm::settings.wa')
        ->withWooConfig(json_decode($wooConfig->settings))
        ->withMsg($msg)
        ->withMsgReminder($msg_reminder)
        ->withList($list)
        ->withTwillioConfig(json_decode($twillioConfig->settings));
    }

    public function storeWaVendorTwillio(Request $request)
    {
        $status = false;
        $message = trans('crm::exceptions.settings.wa.twillio_error');

        $wa = CrmSettingWa::find(2);
        $data = array(
            'sid'   => $request->sid,
            'token' => $request->token,
            'senders' => $request->senders,
        );

        $wa->settings = json_encode($data);
        if($wa->save()){
            $status = true;
            $message = __('crm::alerts.settings.wa.twillio_stored');
        }

        return response()->json(array('status' => $status, 'message' => $message));
    }

    public function storeWaVendorWooWa(Request $request)
    {
        $status = false;
        $message = trans('crm::exceptions.settings.wa.store_woo_error');
        
        $wa = CrmSettingWa::find(1);
        $data = array('cs_id'=> $request->cs_id);
        
        $wa->settings = json_encode($data);
        if($wa->save()){
            $status = true;
            $message = __('crm::alerts.settings.wa.woo_stored');
        }

        return response()->json(array('status' => $status, 'message' => $message));
    }

    public function storeWaMessage(Request $request)
    {
        $msg = $request->msg;
        
        $save = DB::transaction(function () use($msg, $request){
            $i = 1;

            while($i <3) {
                $wa = CrmSettingWa::find($i);

                if($request->is_vendor == $wa->id){
                    $wa->is_active = 1;
                }else{
                    $wa->is_active = 0;
                }

                $wa->wa_message = $msg;
                $wa->wa_message_reminder = $request->msg_reminder;
                $wa->save();
                $i++;
            }

            return true;
        });

        if($save){
            $status = true;
            $message = __('crm::alerts.settings.wa.msg_stored');
        }else{
            $status = false;
            $message = trans('crm::exceptions.settings.wa.msg_error');
        }

        return response()->json(array('status' => $status, 'message' => $message));

    }
}
