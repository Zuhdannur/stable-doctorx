<?php

namespace App\Modules\Patient\Http\Controllers;

use App\Helpers\Auth\Auth;
use App\Modules\Billing\Models\Billing;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

use App\Modules\Attribute\Models\AttributeReligion;
use App\Modules\Attribute\Models\AttributeBloodBank;
use App\Modules\Attribute\Models\AttributeWork;
use App\Modules\Attribute\Models\AttributeInfoSource;
use App\Modules\Attribute\Models\AttributeInfoMedia;

use App\Modules\Indonesia\Models\City;
use App\Modules\Patient\Models\Patient;
use App\Modules\Patient\Models\PatientBeforeAfter;
use App\Modules\Patient\Models\PatientFlag;
use App\Modules\Patient\Models\Appointment;
use App\Modules\Patient\Models\AdmissionType;
use App\Modules\Patient\Models\AppointmentStatus;
use App\Modules\Patient\Repositories\PatientRepository;
use App\Modules\Patient\Http\Requests\Patient\ManagePatientRequest;
use App\Modules\Patient\Http\Requests\Patient\StorePatientRequest;

use DataTables;
use function foo\func;

class PatientController extends Controller
{
	protected $patientRepository;

    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function index(Request $request)
    {
    	if ($request->ajax()) {
            $model = Patient::orderBy('created_at', 'desc');
            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('dob', function ($data) {
                $dob = $data->dobformatted;
                return $dob;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->addColumn('klinik',function ($data) {
                $asal = $data->klinik->nama_klinik ?? '-';
                return $asal;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('patient::patient.index');
    }

    public function create()
    {
        if (auth()->user()->cannot('create patient')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        $religion = AttributeReligion::all();
        $bloodBank = AttributeBloodBank::all();

        $appointmentType = AdmissionType::all();
        $appointmentStatus = AppointmentStatus::all();

        $religions = AttributeReligion::all();
        $works = AttributeWork::all();
        $infosource = AttributeInfoSource::all();
        $patientflag = PatientFlag::all();
        $infomedia = AttributeInfoMedia::all();

        return view('patient::patient.create')
            ->withReligion($religion)
            ->withBloodbank($bloodBank)
            ->withWorks($works)
            ->withReligions($religions)
            ->withInfo($infosource)
            ->withMedia($infomedia)
            ->withFlags($patientflag);
    }

    public function store(StorePatientRequest $request)
    {
        if (auth()->user()->cannot('create patient')) {
            // return response()->json(['errors' => ['message' => [trans('exceptions.cannot_create')]]], 401);
            return response()->json(['message' => trans('exceptions.cannot_create')], 401);
        }

        $save = $this->patientRepository->create($request->input());

        if($save){
        	$status = true;
	        $message = __('patient::alerts.patient.created');
        }else{
        	$status = false;
	        $message = trans('patient::exceptions.patient.create_error');
	    }

        return response()->json(array('status' => $status, 'message' => $message));

    }

    public function edit(Patient $patient)
    {
        if (auth()->user()->cannot('create patient')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        $religion = AttributeReligion::all();
        $bloodBank = AttributeBloodBank::all();

        $appointmentType = AdmissionType::all();
        $appointmentStatus = AppointmentStatus::all();

        $religions = AttributeReligion::all();
        $works = AttributeWork::all();
        $infosource = AttributeInfoSource::all();
        $patientflag = PatientFlag::all();
        $infomedia = AttributeInfoMedia::all();

        $patient->load(
            [
                'media' => function ($q2) {
                    $q2->leftJoin('attribute_info_media', 'attribute_info_media.id', '=', 'patient_media_info.media_id');
                    $q2->select('patient_media_info.*', 'attribute_info_media.name AS media_name');
                }
            ]
        );

        return view('patient::patient.edit')
            ->withPatient($patient)
            ->withReligion($religion)
            ->withBloodbank($bloodBank)
            ->withWorks($works)
            ->withReligions($religions)
            ->withInfo($infosource)
            ->withMedia($infomedia)
            ->withFlags($patientflag);
    }

    public function update(ManagePatientRequest $request, Patient $patient)
    {
        if (auth()->user()->cannot('update patient')) {
            return response()->json(['message' => trans('exceptions.cannot_update')], 401);
        }

        $update = $this->patientRepository->update($patient, $request->input());


        if($update){
            $status = true;
	        $message = __('patient::alerts.patient.updated');
        }else{
            $status = false;
	        $message = trans('patient::exceptions.patient.updated_error');
	    }

        return response()->json(array('status' => $status, 'message' => $message));
    }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->load(
            [
                'timeline' => function ($q) {
                    $q->orderBy('created_at', 'desc');
                },
                'media' => function ($q2) {
                    $q2->leftJoin('attribute_info_media', 'attribute_info_media.id', '=', 'patient_media_info.media_id');
                    $q2->select('patient_media_info.*', 'attribute_info_media.name AS media_name');
                },
                'appointment' => function($q3){
                    // $q3->leftJoin('rooms','rooms.id','room_id');
                    $q3->orderBy('date', 'desc');
                },
                'treatment' => function($q){
                    $q->leftJoin('rooms','rooms.id','room_id');
                    $q->orderBy('date', 'desc');
                }
            ]
        );
        $invoice = Billing::where('patient_id',$patient->id)->orderBy('created_at','desc')->get();
        // dd($patient->appointment);
        return view('patient::patient.show')->withPatient($patient)->withInvoice($invoice);
    }

    public function getpatient($pid)
    {
        $patient = Patient::with('media')->with('city')->where('patient_unique_id', $pid)->first();

        if(!empty($patient)){
            $status = true;
            $message = 'OK';
        }else{
            $status = false;
            $message = 'Data Pasien Tidak Ditemukan!';
        }

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $patient));
    }

    public function getflag($pid)
    {
        $patient = Patient::where('patient_unique_id', $pid)->first();

        if(!empty($patient)){
            $status = true;
            $message = 'OK';
        }else{
            $status = false;
            $message = 'Data Pasien Tidak Ditemukan!';
        }

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $patient->flag));
    }

    public function checkPatient(Request $request)
    {
        $pid = $request->input('pid');
        $patient = Patient::where('patient_unique_id', $pid)->first();

        if(!empty($patient)){
            $message = 'true';
            $patient_name = $patient->patient_name;
            $dob = $patient->dob;
        }else{
            $message = 'false';
            $patient_name = null;
            $dob = null;
        }

        return response()->json(array('status' => $message, 'patient_name' => $patient_name, 'dob' => $dob));
    }

    public function register()
    {
        $religion = AttributeReligion::all();
        $bloodBank = AttributeBloodBank::all();

        $appointmentType = AdmissionType::all();
        $appointmentStatus = AppointmentStatus::all();

        $province = \Indonesia::findProvince(32, ['cities']);
        $cities = $province->cities;

        $religions = AttributeReligion::all();
        $works = AttributeWork::all();
        $infosource = AttributeInfoSource::all();
        $infomedia = AttributeInfoMedia::all();
        $patientflag = PatientFlag::all();

        return view('patient::frontend.patient.register')
            ->withReligion($religion)
            ->withBloodbank($bloodBank)
            ->withAppointmenttype($appointmentType)
            ->withAppointmentstatus($appointmentStatus)
            ->withCities($cities)
            ->withWorks($works)
            ->withReligions($religions)
            ->withInfo($infosource)
            ->withMedia($infomedia)
            ->withFlags($patientflag);
    }

    public function storeAdmission(Request $request)
    {
        // die(print_r($request->input()));
        $patient = $this->patientRepository->createAdmission($request->input()); //
        // die(json_encode($patient));
        if($patient){
            $status = true;
            $message = 'Pendaftaran Berhasil';

            // return View::make('patient::frontend.patient.register-success')->withData($patient)->with('successMsg', $message);
            return redirect()->route('patient.registersuccess')->with( ['data' => $patient] );
        }else{
            $status = false;
            $message = 'Pendaftaran Gagal';

            return redirect()->route('patient.register')->withFlashError($message);
        }
    }

    public function storesuccess()
    {
        $data = \Session::get('data');

        if(!$data){
            return redirect()->route('patient.register');
        }
        return view('patient::frontend.patient.register-success')->withData($data)->with('successMsg', 'Pendaftaran Berhasil');
    }

    public function timeline(Request $request, $patientId)
    {
        if($request->ajax()){
            $data = Patient::where('patient_unique_id', $patientId)->firstOrFail();

            return view('patient::patient.form.timeline')->withPatient($data);
        }

        abort(404);
    }

    public function storetimeline(Request $request, $patientId)
    {
        if($request->ajax()){
            return $this->patientRepository->createTimeline($request);
        }

        abort(404);
    }

    public function queues()
    {
        return view('patient::queues.index');
    }

    public function hasqueues()
    {
        return view('patient::queues.has-queues');
    }

    public function beforeafter()
    {
        $patient = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();

        return view('patient::patient.beforeafter')->withPatients($patient);
    }

    public function getbeforeafter($patientId)
    {
        $patient = Patient::findOrFail($patientId);

        return view('patient::patient.getbeforeafter')->withPatient($patient);
    }

    public function storebeforeafter(Request $request)
    {
        if ($request->has('inputFoto')) {
            $patient = Patient::find($request->patient_id);
            if($patient){
                $status = false;
                foreach ($request->inputFoto as $image) {
                    $patientBeforeAfter = new PatientBeforeAfter;

                    $patientBeforeAfter->patient_id = $patient->id;
                    $patientBeforeAfter->date = \Carbon\Carbon::now();
                    $patientBeforeAfter->type = $request->type_foto;
                    $patientBeforeAfter->setFileImage64($image);
                    if($patientBeforeAfter->save()){
                        $status = true;
                    }
                }
            }else{
                return response()->json(array('status' => false, 'message' => 'Tidak data pasien!'));
            }
        }else{
            return response()->json(array('status' => false, 'message' => 'Tidak ada foto yang di pilih!'));
        }

        if($status){
            return response()->json(array('status' => true, 'message' => 'Oke!', 'id' => $patient->id));
        }else{
            return response()->json(array('status' => false, 'message' => 'Gagal upload!'));
        }

    }

    /*public function nextappointment(Datatables $datatables)
    {
        if ($datatables->getRequest()->ajax()) {
            return $datatables->of($this->patientRepository->orderBy('created_at', 'desc')->get())
            ->addIndexColumn()
            ->addColumn('dob', function ($data) {
                $dob = $data->dob;
                return $dob;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('patient::reporting.nextappointment');
    }*/

    public function birthday()
    {
        return view('patient::patient.birthday');
    }

    public function birthdaylist(Request $request)
    {
        if ($request->ajax()) {
            $data = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->whereRaw('DAYOFYEAR("'.\Carbon\Carbon::createFromFormat(setting()->get('date_format'), $request->startDate)->format('Y-m-d').'") <= DAYOFYEAR(dob) AND DAYOFYEAR("'.\Carbon\Carbon::createFromFormat(setting()->get('date_format'), $request->endDate)->format('Y-m-d').'") >=  dayofyear(dob)')->orderByRaw('DAYOFYEAR(dob)');

            return DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('last_wa', function($data){
                $lastWa = '';
                if($data->last_wa != null){
                    $lastWa = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->last_wa)->format('Y');
                };
                $now = \Carbon\Carbon::now()->format('Y');

                if($now == $lastWa){
                    $badge = '<span class="badge badge-success">Sudah dikirim</span>';
                }else{
                    $badge = '<span class="badge badge-danger">Belum dikirim</span>';
                }

                return $badge;
            })
            ->addColumn('dob', function ($data) {
                $dob = $data->dob_string;
                return $dob;
            })
            ->addColumn('action', function ($data) {
                $button = $data->send_wa_buttons;
                $button .= $data->edit_wa_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action', 'last_wa'])
            ->make(true);
        }

        return false;
    }

    public function reportingpatient(Request $request)
    {
        if($request->ajax()){
            $model = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->with(['city', 'district', 'village']);

            if($request->oldPatient){
                $model = $model->where('old_patient', $request->oldPatient);
            }

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('city', function ($data) {
                return isset($data->city->name) ? ucwords(strtolower($data->city->name)) : '-';
            })
            ->addColumn('district', function ($data) {
                return isset($data->district->name) ? ucwords(strtolower($data->district->name)) : '-';
            })
            ->addColumn('village', function ($data) {
                return isset($data->village->name) ? ucwords(strtolower($data->village->name)) : '-';
            })
            ->addColumn('old_patient_string', function ($data) {
                return $data->old_patient_string;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['status', 'action'])
            ->toJson();
        }

        return view('patient::patient.reporting.patient');
    }

    public function calendar()
    {
        return view('patient::dashboard.calendar');
    }

    public function calendarload(Request $request)
    {
        if($request->ajax()){
            $startDate = $request->start;
            $endDate = $request->end;


            $model = Appointment::whereHas('patient',function ($query) {
                return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
            })->with('patient');

            if($startDate && $endDate){
                $model = $model->whereRaw("(DATE(date) between '".$startDate."' and '".$endDate."')");
            }

            $model = $model->where('status_id', 1)->orderBy('id', 'desc')->get();

            $result = array();

            if($model){
                foreach ($model as $key => $value) {
                    $result[$key]['id'] = $value->id;
                    $result[$key]['title'] = $value->appointment_no.' - '.$value->patient->patient_unique_id.' '.$value->patient->patient_name.' - '.$value->notes;
                    $result[$key]['description'] = $value->patient->patient_unique_id.' '.$value->patient->patient_name;
                    $result[$key]['notes'] = $value->notes ?? '-';
                    $result[$key]['staff'] = isset($value->staff->user->full_name) ? $value->staff->user->full_name : null;
                    $result[$key]['room'] = $value->room->name ?? null;
                    $result[$key]['floor'] = $value->room->group->floor->name ?? null;
                    $result[$key]['start'] = $value->date->format('Y-m-d H:i:s');
                    $result[$key]['end'] = $value->date->format('Y-m-d H:i:s');
                    $result[$key]['url'] = route('admin.patient.prescription.create', $value->appointment_no);
                }
            }

            return $result;
        }

        die('sia');
    }
}
