<?php

namespace App\Modules\Crm\Http\Controllers;

use DataTables;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modules\Patient\Models\Patient;

use App\Modules\Crm\Models\CrmMembership;
use App\Modules\Crm\Models\CrmRadeemPoint;
use App\Modules\Crm\Models\CrmMsMembership;
use App\Modules\Attribute\Models\LogActivity;
use function foo\func;

class CrmMembershipController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $model = CrmMembership::with(['patient', 'ms_membership','invoice'])
            ->orderBy('created_at', 'ASC');

//            dd($model->get());

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('total_amount',function ($data){
                $totals = $data->invoice->sum('total_ammount');
                $totals += $data->invoice->sum('is_paid');

                return currency()->rupiah($totals, setting()->get('currency_symbol'));
            })
            ->addColumn('action', function($data){
                $btn = '';
                $btn .= $data->show_membership_button;
                $btn .= $data->edit_membership_button;
                $btn .= $data->delete_membership_button;
                return $btn;
            })
            ->addColumn('grade',function ($data) {
                if(!empty($data->id_grade)) {
                    return '<span class="badge badge-primary">Grade '.$data->grade->nama_grade.'</span>';
                } else {
                    return '<span class="badge badge-primary">Tidak Ada Grade</span>';
                }
            })
            ->rawColumns(['total_amount','action','grade'])
            ->make(true);
        }
        return view('crm::membership.index');
    }

    public function create()
    {
        $patient = Patient::orderBy('patient_unique_id', 'asc')
        ->get();
        $patientList = '<option></option>';
        foreach ($patient as $val ) {
            $patientList .= '<option value="'.$val->id.'">'.$val->patient_unique_id.' - '.$val->patient_name.'</option>';
        }

        $grade = \App\Grade::all();
        $gradeList = '<option></option>';
        foreach ($grade as $item) {
            $gradeList .= '<option value="'.$item->id_grade.'">'.$item->nama_grade.' - '.$item->keterangan.'</option>';
        }


        return view('crm::membership.create')
        ->withGrade($gradeList)
        ->withPatient($patientList)
        ->withMembership(CrmMsMembership::optionList());
    }

    public function edit(CrmMembership $membership)
    {
        $patientData = $membership->patient;
        $patient = '';
        if($patientData){
            $patient = $patientData->patient_unique_id.' - '.$patientData->patient_name;
        }

        $grade = \App\Grade::all();
        $gradeList = '<option></option>';
        foreach ($grade as $item) {
            if(!empty($membership->id_grade) && $membership->id_grade == $item->id_grade) {
                $gradeList .= '<option value="'.$item->id_grade.'" selected>'.$item->nama_grade.' - '.$item->keterangan.'</option>';
            } else {
                $gradeList .= '<option value="'.$item->id_grade.'">'.$item->nama_grade.' - '.$item->keterangan.'</option>';
            }
        }

        return view('crm::membership.edit')
        ->withGrade($gradeList)
        ->withPatient($patient)
        ->withMembership($membership)
        ->withMembershipList(CrmMsMembership::optionList());
    }

    public function store(Request $request)
    {
        if($request->ajax()){

            if($request->isMethod('POST')){

                // search availiable patent on membership table
                $patient = CrmMembership::where('patient_id',$request->patient)->first();
                if($patient){
                    return response()->json(array('status' => false, 'message' => trans('crm::exceptions.membership.already_exists')));
                }

                $membership = new CrmMembership;
                $membership->patient_id = $request->patient;
                $membership->ms_membership_id = $request->membership;
                $membership->total_point = 0;
                $membership->id_grade = $request->grade;
                $membership->id_klinik = auth()->user()->id_klinik;

                DB::beginTransaction();

                try {
                    $membership->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.crm');
                    $log->action = "Create Membership";
                    $log->desc = "PID : $membership->patient_id, Membership :". $membership->ms_membership->name;

                    $log->save();

                    DB::commit();
                    $status = true;
                    $message = __('crm::alerts.membership.created');
                } catch (\Exception $e) {
                    DB::rollback();

                    if(env("APP_DEBUG") == true){
                        dd($e);
                    }

                    $status = false;
                    $message = trans('crm::exceptions.membership.create_error');
                }

                return response()->json(array('status' => $status, 'message' => $message));

            }
            else if($request->isMethod('PATCH')){
                $membership = CrmMembership::find($request->id);

                if(!$membership){
                    $status = false;
                    $message = trans('crm::exceptions.membership.update_error');
                }else{

                    if (auth()->user()->cannot('edit membership')) {
                        return response()->json(['messages' => trans('exceptions.cannot_edit')], 401);
                    }

                    $membership->ms_membership_id = $request->membership;
                    $membership->   total_point = $request->total_point;
                    $membership->id_grade = $request->grade;

                    DB::beginTransaction();
                    try {
                        $membership->save();

                        $log = new LogActivity();
                        $log->module_id = config('my-modules.crm');
                        $log->action = "Update Membership";
                        $log->desc = "PID : $membership->patient_id, Membership :". $membership->ms_membership->name;

                        $log->save();
                        DB::commit();

                        $status = true;
                        $message = __('crm::alerts.membership.updated');
                    } catch (\Exception $e) {

                        DB::rollback();
                        if(env("APP_DEBUG") == true){
                            dd($e);
                        }
                        $status = false;
                        $message = trans('crm::exceptions.membership.update_error');
                    }
                }

                return response()->json(array('status' => $status, 'message' => $message));

            }
        }else{
            abort(404);
        }
    }

    public function destroy(CrmMembership $membership)
    {
        DB::beginTransaction();
        try{
            $log = new LogActivity();
            $log->module_id = config('my-modules.crm');
            $log->action = "Delete Membership";
            $log->desc = "PID : $membership->patient_id, Membership :". $membership->ms_membership->name;

            $log->save();

            $membership->delete();
            DB::commit();
            return redirect()->route('admin.crm.membership.index')->withFlashSuccess(__('crm::alerts.membership.deleted'));
        }catch (\Exception $e){
            DB::rollback();

            if(env("APP_DEBUG") == true){
                dd($e);
            }

            return redirect()->route('admin.crm.membership.index')->withFlashSuccess(__('crm::exception.membership.delete_error'));
        }
    }

    public function show(CrmMembership $membership, Request $request)
    {
        if($request->ajax()){
            if($request->ajax()){
                $model = CrmRadeemPoint::where('membership_id' , $membership->id)
                ->with(['user'])
                ->orderBy('created_at', 'desc');

                return DataTables::eloquent($model)
                ->addColumn('point_total', function($data){
                    $total = intval($data->point * $data->ammount);
                    return currency()->rupiah($total, setting()->get('currency_symbol'));
                })
                ->addColumn('nominal_total', function($data){
                    $total = intval($data->point * $data->ammount * $data->nominal);
                    return currency()->rupiah($total, setting()->get('currency_symbol'));
                })
                ->addColumn('user_app', function($data){
                    return $data->user->first_name.' '.$data->user->last_name;
                })
                ->editColumn('created_at', function($data){
                    return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d/m/Y');
                })
                ->make(true);
            }
        }
        return view('crm::membership.show')
        ->withMembership($membership);
    }
}
