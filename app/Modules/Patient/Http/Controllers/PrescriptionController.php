<?php

namespace App\Modules\Patient\Http\Controllers;

use App\Modules\Patient\Models\PatientTimeline;
use App\RecordMapping;
use Illuminate\Http\Request;

use App\Modules\Patient\Models\Appointment;
use App\Modules\Patient\Models\Prescription;
use App\Modules\Product\Models\ProductCategory;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Service;
use App\Modules\Patient\Models\DiagnoseItem;

use App\Http\Controllers\Controller;
use App\Modules\Patient\Repositories\PrescriptionRepository;
use Yajra\Datatables\Datatables;

class PrescriptionController extends Controller
{
	protected $prescriptionRepository;

    public function __construct(PrescriptionRepository $prescriptionRepository)
    {
        $this->prescriptionRepository = $prescriptionRepository;
    }

    public function index(Datatables $datatables)
    {
    	if ($datatables->getRequest()->ajax()) {
    	    $model = \App\Modules\Patient\Models\Prescription::where('id_klinik',auth()->user()->klinik->id_klinik)
                ->orderBy('created_at', 'desc')
                ->get();
//            return $datatables->of(\App\Modules\Patient\Models\Prescription::whereHas('patient',function ($query) {
//                return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
//            })
//                ->orderBy('created_at', 'desc')
//                ->get())
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

        return view('patient::appointment.index');
    }

    public function create($appId)
    {
        $appointment = array();
        $patient = array();
        if($appId){
            $appointment = Appointment::where('appointment_no', '=', $appId)->firstOrFail();
            if($appointment) {
                $patient = PatientTimeline::where('patient_id',$appointment->patient_id)->orderBy('timeline_date','desc')->skip(1)->take(1)->get();
            }
        }
        if(!empty($appointment->prescription)){
            return redirect()->route('admin.patient.appointment.index')->withFlashSuccess(__('patient::exceptions.appointment.already_exists'));
        }
        $product = new Product;
        $listProduct = $product->optionList();

        $service = new Service;
        $listService = $service->optionListWithPackages();

        $diagnose = DiagnoseItem::get();
        $optionDiagnose = '<option></option>';
        foreach ($diagnose as $key => $val) {
            $name = $val->name;
            $optionDiagnose .= '<option value="'.$val->id.'">'.($name).'</option>';
        }


        // die(json_encode($appointment->patient));
        return view('patient::prescription.create')
            ->withAppointment($appointment)
            ->withProduct($listProduct)
            ->withService($listService)
            ->withDatadiagnoseitem($optionDiagnose)
            ->withPatient($appointment->patient)
            ->withTimeline($patient);
    }

    public function store(Request $request)
    {
        return $this->prescriptionRepository->createPrescription($request);
    }

    public function show(Prescription $prescription)
    {
        // die(json_encode($prescription->timeline->files));

        return view('patient::prescription.show')->withPrescription($prescription)->withPatient($prescription->appointment->patient);
    }

    public function edit(Prescription $prescription)
    {
        $appointment = array();
        $patient = array();
        $record = null;
        if($prescription){
            $appointment = Appointment::find($prescription->appointment_id);
            if($appointment) {
                $patient = PatientTimeline::where('patient_id',$appointment->patient_id)->orderBy('timeline_date','desc')->skip(1)->take(1)->get();
            }
            $record = RecordMapping::where('appointment_id',$prescription->appointment_id)->first();
        }

        $product = new Product;
        $listProduct = $product->optionList();

        $service = new Service;
        $listService = $service->optionListWithKlinik();

        $diagnose = DiagnoseItem::get();
        $optionDiagnose = '<option></option>';
        foreach ($diagnose as $key => $val) {
            $name = $val->name;
            $optionDiagnose .= '<option value="'.$val->id.'">'.($name).'</option>';
        }

        // die(json_encode($appointment->patient));
        return view('patient::prescription.edit')
                ->withRecord($record)
                ->withPatient($appointment->patient)
                ->withPrescription($prescription)
                ->withProduct($listProduct)->withService($listService)
                ->withDatadiagnoseitem($optionDiagnose)
                ->withPatient($prescription->appointment->patient);
    }

    public function update(Prescription $prescription, Request $request)
    {
        return $this->prescriptionRepository->updatePrescription($prescription, $request);
    }
}
