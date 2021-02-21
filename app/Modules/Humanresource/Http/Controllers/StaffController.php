<?php

namespace App\Modules\Humanresource\Http\Controllers;

use App\Helpers\Auth\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use App\Modules\Attribute\Models\AttributeReligion;
use App\Modules\Attribute\Models\AttributeBloodBank;
use App\Modules\Attribute\Models\AttributeDepartment;
use App\Modules\Attribute\Models\AttributeMaritalStatus;

use App\Modules\Humanresource\Models\Staff;
use App\Modules\Humanresource\Models\StaffDesignation;
use App\Modules\Humanresource\Repositories\StaffRepository;
use App\Modules\Humanresource\Http\Requests\Staff\ManageStaffRequest;
use App\Modules\Humanresource\Http\Requests\Staff\StoreStaffRequest;

use Yajra\Datatables\Datatables;
use function foo\func;

class StaffController extends Controller
{
    protected $staffRepository;

    public function __construct(StaffRepository $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    public function index(Datatables $datatables)
    {
    	if ($datatables->getRequest()->ajax()) {
            return $datatables->of($this->staffRepository->whereHas('user',function ($query) {
                return $query->where('id_klinik',Auth()->user()->id_klinik);
            })->get())
            ->addIndexColumn()
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
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('humanresource::staff.index');
    }

    public function create()
    {
    	$religion = AttributeReligion::get();
    	$bloodBank = AttributeBloodBank::get();
    	$departments = AttributeDepartment::get();
    	$designations = StaffDesignation::get();
    	$maritalstatus = AttributeMaritalStatus::get();
    	$roles = \Bouncer::role()->get();

        return view('humanresource::staff.create')
	        ->withReligion($religion)
	        ->withBloodbank($bloodBank)
	        ->withDepartments($departments)
	        ->withDesignations($designations)
	        ->withMaritalstatus($maritalstatus)
	        ->withRoles($roles);
    }

    public function store(StoreStaffRequest $request)
    {
        $this->staffRepository->create($request->input());

        return redirect()->route('admin.humanresource.staff.index')->withFlashSuccess(__('humanresource::alerts.staff.created'));
    }

    public function getByRole($id)
    {
    	$data = $this->staffRepository->getByRole($id);

    	$status = false;
		$message = 'Not Found!';

		$newdata = array();
    	if(count($data)){
    		$status = true;
    		$message = 'OK';

    		//filter
    		foreach ($data as $key => $value) {
    			$newdata[$key]['staff_id'] = $value->id;
    			$newdata[$key]['employee_id'] = $value->employee_id;
    			$newdata[$key]['full_name'] = $value->user->full_name;
    		}
    	}

    	return response()->json(array('status' => $status, 'message' => $message, 'data' => $newdata));
    }

    public function edit(Staff $staff)
    {
        $religion = AttributeReligion::get();
        $bloodBank = AttributeBloodBank::get();
        $departments = AttributeDepartment::get();
        $designations = StaffDesignation::get();
        $maritalstatus = AttributeMaritalStatus::get();
        $roles = \Bouncer::role()->get();

        return view('humanresource::staff.edit')
            ->withReligion($religion)
            ->withBloodbank($bloodBank)
            ->withDepartments($departments)
            ->withDesignations($designations)
            ->withMaritalstatus($maritalstatus)
            ->withRoles($roles)
            ->withStaff($staff);
    }

    public function update(Request $request, Staff $staff)
    {
        // return redirect()->route('admin.humanresource.staff.index')->withFlashDanger('Ooopss sorry, fitur ini belum tersedia.');
        $this->staffRepository->update($staff, $request->input());

        return redirect()->route('admin.humanresource.staff.index')->withFlashSuccess(__('humanresource::alerts.staff.updated'));
    }

    public function destroy($id)
    {
        if (auth()->user()->cannot('delete staff')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $Staff = Staff::findOrFail($id);

        $Staff->destroy($Staff->id);

        return redirect()->route('admin.humanresource.staff.index')->withFlashSuccess('Data berhasil di hapus.');
    }
}
