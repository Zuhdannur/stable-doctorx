<?php

namespace App\Modules\Incentive\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Humanresource\Models\Staff;
use App\Modules\Incentive\Models\Incentive;
use App\Modules\Incentive\Models\IncentiveStaff;
use App\Modules\Incentive\Models\IncentiveDetail;

use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

class StaffIncentiveController extends Controller
{
    public function index(Datatables $datatables)
    {
        if ($datatables->getRequest()->ajax()) {
            return $datatables->of(IncentiveStaff::get())
            ->addIndexColumn()
            ->addColumn('staff_name', function($data) {
                return $data->staff->user->full_name;
            })
            ->addColumn('incentive_name', function($data) {
                return $data->incentive->name;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('incentive::staff.index');
    }

    public function staffList(Datatables $datatables)
    {
        if ($datatables->getRequest()->ajax()) {
            return $datatables->of(Staff::get())
            ->addIndexColumn()
            ->addColumn('checkbox', function ($item) {
                return '<input type="checkbox" id="staffId'.$item->id.'" name="staffId" class="staffId" value="'.$item->id.'" />';
            })
            ->addColumn('full_name', function ($data) {
                $user = $data->user;
                return $user->full_name;
            })
            ->addColumn('role', function ($data) {
                $user = $data->user;
                $role = $user->roles->first();
                return $role->title;
            })
            ->addColumn('department', function ($data) {
                $department = $data->department;
                return $department->department_name;
            })
            ->addColumn('designation', function ($data) {
                $designation = $data->designation;
                return $designation->name;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['checkbox'])
            ->make(true);
        }

        throw new GeneralException('Request not allowed!');
    }

    public function create()
    {
        if (auth()->user()->cannot('create incentivestaff')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        $incentive = new Incentive;
        $listIncentive = $incentive->get();

        $optionListIncentive = '<option></option>';
        foreach ($listIncentive as $key => $val) {
            $name = $val->name;
            $optionListIncentive .= '<option value="'.$val->id.'">'.($name).'</option>';
        }

        return view('incentive::staff.create')->withOptionincentive($optionListIncentive)->withIncentive($listIncentive);
    }

    public function store(Request $request)
    {
        $incentiveId = $request->incentive_id;

        $staffIds = null;

        if($request->staffId){
            $staffIds = explode(',', $request->staffId);
        }

        if(empty($staffIds)){
            return response()->json(array('status' => false, 'message' => 'Silahkan pilih staff'));
        }

        if(!$incentiveId){
            return response()->json(array('status' => false, 'message' => 'Silahkan pilih komisi'));
        }

        \DB::beginTransaction();
        
        foreach ($staffIds as $staff) {
            $Staff = Staff::findOrFail($staff);
            
            // $Staff->staffIncentive()->sync($incentiveId);

            $IncentiveStaff = IncentiveStaff::firstOrNew(['staff_id' => $Staff->id, 'incentive_id' => $incentiveId]);

            $IncentiveStaff->save();
        }

        \DB::commit();

        return response()->json(array('status' => true, 'message' => 'Oke!'));
    }

    public function destroy($id)
    {
        if (auth()->user()->cannot('delete incentivestaff')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $IncentiveStaff = IncentiveStaff::findOrFail($id);

        $IncentiveStaff->destroy($IncentiveStaff->id);

        return redirect()->route('admin.incentive.staff.index')->withFlashSuccess('Data berhasil di hapus.');
    }
}
