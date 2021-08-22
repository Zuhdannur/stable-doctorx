<?php

namespace App\Modules\Patient\Http\Controllers;

use App\Helpers\Auth\Auth;
use Illuminate\Http\Request;

use App\Modules\Patient\Models\Patient;
use App\Modules\Patient\Models\Appointment;
use App\Modules\Humanresource\Models\Staff;
use App\Modules\Room\Models\Room;
use App\Modules\Patient\Models\PatientFlag;

use App\Http\Controllers\Controller;
use App\Modules\Patient\Repositories\AppointmentRepository;
use App\Modules\Patient\Http\Requests\Patient\ManagePatientRequest;
use App\Modules\Patient\Http\Requests\Patient\StorePatientRequest;
use DataTables;
use function foo\func;

class AppointmentController extends Controller
{
	protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function index(Request $request)
    {
    	if($request->ajax()){
            $startDate = $request->startDate;
            $endDate = $request->endDate;


            $model = Appointment::where('id_klinik',auth()->user()->klinik->id_klinik)->with('patient');

            if($startDate && $endDate){
                $start_date = \DateTime::createFromFormat(setting()->get('date_format'), $startDate)->format('Y-m-d');
                $end_date = \DateTime::createFromFormat(setting()->get('date_format'), $endDate)->format('Y-m-d');

                $model = $model->whereRaw("(DATE(date) between '".$start_date."' and '".$end_date."')");
            }

            $model = $model->orderBy('id', 'desc');

            return DataTables::eloquent($model)
            // return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('patient_unique_id', function ($data) {
                $button = $data->patient->patient_unique_id;
                return $button;
            })
            ->addColumn('patient_name', function ($data) {
                $entity = $data->patient->patient_name;
                return $entity;
            })
            ->addColumn('room_name', function ($data) {
                $entity = $data->room->name ?? null;
                return $entity;
            })
            ->addColumn('floor_name', function ($data) {
                $entity = $data->room->group->floor->name ?? null;
                return $entity;
            })
            ->addColumn('staff_name', function ($data) {
                $entity = isset($data->staff->user->full_name) ? $data->staff->user->full_name : null;
                return $entity;
            })
            ->addColumn('flag_color', function ($data) {
                $flag = isset($data->patient->flag->color_code) ? '<span class="badge badge-pill badge-'.$data->patient->flag->color_code.'">&nbsp;</span>' : null;
                return $flag;
            })
            ->addColumn('status', function ($data) {
                if($data->billing){
                    switch ($data->billing->status) {
                        case '1':
                            $entity = "<span class='badge badge-info'>Finished</span>";
                            break;
                        default:
                            $entity = '<span class="badge badge-'.$data->status->class_style.'">'.$data->status->name.'</span>';
                            break;
                    }
                }else{
                    if($data->status->id == 4 ){
                        $entity = "<span class='badge badge-info'>Finished</span>";
                    }else{
                        $entity = '<span class="badge badge-'.$data->status->class_style.'">'.$data->status->name.'</span>';
                    }
                }
                    return $entity;
            })
            ->addColumn('date', function ($data) {
                $entity = timezone()->convertToLocal($data->date, 'date');
                return $entity;
            })
            ->addColumn('hour', function ($data) {
                $entity = timezone()->convertToLocal($data->date, 'time');
                return $entity;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['status', 'flag_color', 'action'])
            ->toJson();
        }

        return view('patient::appointment.index');
    }

    public function create($patientId, $queueId)
    {

        $pid = 0;
        $flagpatient = 'green';
        if($patientId){
            $patientId = Patient::with('flag')->findOrFail($patientId);
            $pid = $patientId->patient_unique_id;

            $flagpatient = 0;
            if(isset($patientId->flag->name)){
                $flagpatient = strtolower($patientId->flag->name);
            }
        }

    	$staff = Staff::whereHas('user',function ($query) {
    	    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->where('designation_id', 2)->get();
    	$room = Room::whereHas('group', function ($query) {
                    $query->where('room_groups.type', '=', 'APPOINTMENT');
                })->get();

        $patient = Patient::get();
        $patientflag = PatientFlag::all();

        return view('patient::appointment.create')->withStaff($staff)->withPatient($patient)->withRoom($room)->withPid($pid)->withQid($queueId)->withFlags($patientflag)->withPatientflag($flagpatient);
    }

    public function store(Request $request)
    {
        $save = $this->appointmentRepository->create($request->input());

        if($save){
        	$status = true;
	        $message = __('patient::alerts.appointment.created');
        }else{
        	$status = false;
	        $message = trans('patient::exceptions.appointment.create_error');
	    }

        return redirect()->route('admin.patient.appointment.index')->withFlashSuccess(__('patient::alerts.appointment.created'));

    }

    public function saveUpdate(Request $request, Appointment $appointment)
    {
        // dd($appointment);
        $update = $this->appointmentRepository->update($appointment, $request->input());

        if($update){
        	$status = true;
            $message = __('patient::alerts.appointment.updated');
        }else{
        	$status = false;
	        $message = trans('patient::exceptions.appointment.update_error');
        }

        return redirect()->route('admin.patient.appointment.index')->withFlashSuccess($message);
    }

    public function getFormEdit(Appointment $appointment)
    {
        // dd($appointment);
        $patient = Patient::find($appointment->patient_id);
        // dd($patient);

        $staff = Staff::where('designation_id', 2)->get();
    	$room = Room::whereHas('group', function ($query) {
                    $query->where('room_groups.type', '=', 'APPOINTMENT');
                })->get();

        $patientflag = PatientFlag::all();
        return view('patient::appointment.edit')->withAppointment($appointment)
                ->withStaff($staff)
                ->withRoom($room)
                ->withPatient($patient)
                ->withFlags($patientflag);
    }

    public function show(Appointment $appointment)
    {
        return view('patient::appointment.show')->withAppointment($appointment);
    }

    public function nextappointment(Request $request)
    {
        if($request->ajax()){
            $model = Appointment::with('patient')->whereDate('next_appointment_date', '>=', \Carbon\Carbon::today())->orderBy('id', 'desc');
            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('patient_unique_id', function ($data) {
                $button = $data->patient->patient_unique_id;
                return $button;
            })
            ->addColumn('patient_name', function ($data) {
                $entity = $data->patient->patient_name;
                return $entity;
            })
            ->addColumn('room_name', function ($data) {
                $entity = $data->room->name ?? null;
                return $entity;
            })
            ->addColumn('floor_name', function ($data) {
                $entity = $data->room->group->floor->name ?? null;
                return $entity;
            })
            ->addColumn('staff_name', function ($data) {
                $entity = isset($data->staff->user->full_name) ? $data->staff->user->full_name : null;
                return $entity;
            })
            ->addColumn('status', function ($data) {
                $entity = '<span class="badge badge-'.$data->status->class_style.'">'.$data->status->name.'</span>';
                return $entity;
            })
            ->addColumn('date', function ($data) {
                $entity = timezone()->convertToLocal($data->next_appointment_date, 'date');
                return $entity;
            })
            ->addColumn('date_remainings', function ($data) {
                $future_date = new \Carbon\Carbon($data->next_appointment_date);
                $now = \Carbon\Carbon::now();
                $interval = $future_date->diff($now);

                $difference = ($future_date->diffInDays($now) < 1) ? 'hari ini': $interval->format("%a hari lagi");

                return $difference;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['status', 'action'])
            ->toJson();
        }

        return view('patient::reporting.nextappointment');
    }

    public function loadform(Request $request)
    {
        $pid = 0;
        $flagpatient = 'green';

        $staff = Staff::where('designation_id', 2)->get();
        $room = Room::whereHas('group', function ($query) {
                    $query->where('room_groups.type', '=', 'APPOINTMENT');
                })->get();

        $patient = Patient::get();
        $patientflag = PatientFlag::all();

        $startdate = $request->start;
        $startdate = \Carbon\Carbon::CreateFromFormat('Y-m-d H:i:s', $startdate)->format('d/m/Y');

        return view('patient::appointment.partial.formcreate')->withStaff($staff)->withPatient($patient)->withRoom($room)->withPid($pid)->withFlags($patientflag)->withPatientflag($flagpatient)->withStart($startdate)->withEnd($request->end);
    }
}
