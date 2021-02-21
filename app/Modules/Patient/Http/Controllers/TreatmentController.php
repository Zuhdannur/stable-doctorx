<?php

namespace App\Modules\Patient\Http\Controllers;

use App\Helpers\Auth\Auth;
use DataTables;

use Illuminate\Http\Request;
use App\Modules\Room\Models\Room;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modules\Patient\Models\Patient;
use App\Modules\Product\Models\Service;
use App\Modules\Patient\Models\Treatment;
use App\Modules\Humanresource\Models\Staff;
use App\Modules\Patient\Models\Appointment;

use App\Modules\Patient\Models\PatientFlag;
use App\Modules\Product\Models\ServiceCategory;

use App\Modules\Patient\Models\PatientAdmission;
use App\Modules\Patient\Repositories\TreatmentRepository;
use function foo\func;

class TreatmentController extends Controller
{
	protected $treatmentRepository;

    public function __construct(TreatmentRepository $treatmentRepository)
    {
        $this->treatmentRepository = $treatmentRepository;
    }

    public function index(Request $request)
    {
    	if($request->ajax()){
            // return $datatables->of($this->treatmentRepository->orderBy('created_at', 'desc')->get())
            $startDate = $request->startDate;
            $endDate = $request->endDate;


            $model = Treatment::whereHas('patient',function ($query){
                return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
            })->with('patient');

            if($startDate && $endDate){
                $start_date = \DateTime::createFromFormat(setting()->get('date_format'), $startDate)->format('Y-m-d');
                $end_date = \DateTime::createFromFormat(setting()->get('date_format'), $endDate)->format('Y-m-d');

                $model = $model->whereRaw("(DATE(date) between '".$start_date."' and '".$end_date."')");
            }

            $model = $model->orderBy('id', 'desc');
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
            ->addColumn('staff_name', function ($data) {
                $entity = isset($data->staff->user->full_name) ? $data->staff->user->full_name : null;
                return $entity;
            })
            ->addColumn('status', function ($data) {
                $entity = '<span class="badge badge-'.$data->status->class_style.'">'.$data->status->name.'</span>';
                return $entity;
            })
            ->addColumn('date', function ($data) {
                $entity = timezone()->convertToLocal($data->date);
                return $entity;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['status', 'action'])
            ->make(true);
        }

        return view('patient::treatment.index');
    }

    public function create($patientId, $queueId)
    {
        $patient = array();
        $pid = 0;
        $flagpatient = 'green';
        if($patientId){
            $patientId = Patient::findOrFail($patientId);
            $pid = $patientId->patient_unique_id;

            $flagpatient = 0;
            if(isset($patientId->flag->name)){
                $flagpatient = strtolower($patientId->flag->name);
            }
        }

        $prescription = null;
        if($queueId){
            $admisson = PatientAdmission::find($queueId);
            if($admisson->reference_id){
                $appointment = Appointment::where('appointment_no', $admisson->reference_id)->first();

                $prescription = $appointment->prescription;
                $patient = $appointment->patient;
            }
        }

//        $staff = Staff::whereIn('designation_id', [2,4])->get();
        $dokter = Staff::whereHas('user',function ($query) {
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->where('designation_id', 2)->get();
        $terapis = Staff::whereHas('user',function ($query) {
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->where('designation_id', 4)->get();
        $room = Room::whereHas('group', function ($query) {
                    $query->where('room_groups.type', '=', 'TREATMENT');
                })->get();
        $patientList = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();
        $patientflag = PatientFlag::all();

        //Add Service Item
        $service = new Service;
        $listService = $service->optionList();

        return view('patient::treatment.create')
            ->withPatient($patient)->withDokter($dokter)
            ->withTerapis($terapis)
            ->withPatientlist($patientList)
            ->withRoom($room)->withPid($pid)->withQid($queueId)->withFlags($patientflag)->withPrescription($prescription)->withPatientflag($flagpatient)->withService($listService);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->treatmentRepository->create($request->input());
            DB::commit();
            return redirect()->route('admin.patient.treatment.index')->withFlashSuccess(__('patient::alerts.treatment.created'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withFlashDanger($e->getMessage());
        }
    }

    public function show(Treatment $treatment)
    {
        $services = Service::get();
        $patient = array();
        $prescription = null;
        if($treatment->appointment_id){
            $appointment = Appointment::find($treatment->appointment_id);
            $patient = $appointment->patient;
            $prescription = $appointment->prescription;
        }

        return view('patient::treatment.show')->withPatient($patient)->withTreatment($treatment)->withDataservice($services)->withPrescription($prescription);
    }

    public function update(Request $request)
    {
        $treatment = Treatment::findOrFail($request->get('tId'));

        return $this->treatmentRepository->update($treatment, $request->input());
    }
}
