<?php

namespace App\Modules\Crm\Http\Controllers;

use DataTables;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Modules\Crm\Models\CrmMarketing;
use App\Modules\Attribute\Models\LogActivity;

class CrmMarketingController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $model = CrmMarketing::query();

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $btn = '';
                $btn .= $data->edit_button;
                $btn .= $data->delete_button;
                return $btn;
            })
            ->addColumn('status', function($data){
                $string = '';
                if($data->is_active == 1){
                    $string = '<span class="badge badge-info">Aktif</span>';
                }else{
                    $string = '<span class="badge badge-danger">Tidak Aktif</span>';
                }
                return $string;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
        }
        return view('crm::marketing.index');
    }

    public function create()
    {
        return view('crm::marketing.create');
    }
    
    public function edit(CrmMarketing $marketing)
    {
        return view('crm::marketing.edit')
        ->withMarketing($marketing);
        
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            if($request->isMethod('POST')){
                $status = false;
                $message = trans('crm::exceptions.marketing.create_error');

                $startdate = \Carbon\Carbon::CreateFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
                $enddate = \Carbon\Carbon::CreateFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
                
                $save = new CrmMarketing;
                $save->name = $request->name;
                $save->start_date = $startdate;
                $save->end_date = $enddate;
                $save->discount = $request->discount;
                $save->point = $request->point;
                $save->is_active = ( $request->status == 'on' ? 1 : 0);
                $save->code = '';

                DB::beginTransaction();
                if($save->save()){
                    $code = CrmMarketing::generateCode();
                    $save->code = $code;

                    if($save->save()){

                        $log = new LogActivity();
                        $log->module_id = config('my-modules.crm');
                        $log->action = "Save Marketing Activity";
                        $log->desc = "Code : $save->code, Name : $save->name";

                        $log->save();

                        DB::commit();

                        $status = true;
                        $message = __('crm::alerts.marketing.created');
                    }else{
                        DB::rollback();
                    }
                }else{
                    DB::rollback();
                }

                return response()->json(array('status' => $status, 'message' => $message));

            }elseif($request->isMethod('PATCH')){
                $status = false;
                $message = trans('crm::exceptions.marketing.update_error');

                $startdate = \Carbon\Carbon::CreateFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
                $enddate = \Carbon\Carbon::CreateFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
                
                $update = CrmMarketing::find($request->id);
                
                if($update){
                    $update->name = $request->name;
                    $update->start_date = $startdate;
                    $update->end_date = $enddate;
                    $update->discount = $request->discount;
                    $update->point = $request->point;
                    $update->is_active = ( $request->status == 'on' ? 1 : 0);
                    
                    DB::beginTransaction();
                    try{
                        $update->save();

                        $log = new LogActivity();
                        $log->module_id = config('my-modules.crm');
                        $log->action = "Update Marketing Activity";
                        $log->desc = "Code : $update->code, Name : $update->name";

                        $log->save();

                        DB::commit();
                        $status = true;
                        $message = __('crm::alerts.marketing.updated');

                    }catch(\Exception $e){
                        DB::rollback();
                        if(env("APP_DEBUG") == true){
                            dd($e);
                        }
                    }
                }


                return response()->json(array('status' => $status, 'message' => $message));
            }
            abort(404);
        }else{
            abort(404);
        }
    }
    
    public function destroy(CrmMarketing $marketing)
    {
        DB::beginTransaction();
        try{

            $log = new LogActivity();
            $log->module_id = config('my-modules.crm');
            $log->action = "Delete Marketing Activity";
            $log->desc = "Code : $marketing->code, Name :". $marketing->name;

            $log->save();
            $marketing->delete();

            DB::commit();
            return redirect()->route('admin.crm.marketing.index')->withFlashSuccess(__('crm::alerts.marketing.deleted'));
        }catch(\Exception $e){
            DB::rollback();
            if(env("APP_DEBUG") == true){
                dd($e);
            }
            return redirect()->route('admin.crm.marketing.index')->withFlashDanger(__('crm::exceptions.marketing.delete_error'));
        }
    }
}
