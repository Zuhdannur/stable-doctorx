<?php

namespace App\Modules\Booking\Http\Controllers;

use App\Modules\Room\Models\Room;
use App\Modules\Patient\Models\Appointment;
use App\Modules\Patient\Models\Treatment;
use App\Modules\Product\Models\Service;
use App\Modules\Humanresource\Models\Staff;
use App\Modules\Patient\Models\PatientFlag;
use App\Modules\Patient\Models\Patient;
use App\Modules\Booking\Models\Booking;

use App\Modules\Patient\Repositories\AppointmentRepository;
use App\Modules\Patient\Repositories\TreatmentRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use function foo\func;

class BookingController extends Controller
{

    protected $backgroundColorEvent = array(
        '1' => '#a6a4a4',
        '2' => '#5cc4c3',
        '3' => '#c45147',
        '4' => '#81bd4a',
        '5' => '#3283a8',
    );

    public function store(Request $request)
    {
        if($request->type == 'Appointment'){
            try{
                $appointment = Appointment::where('appointment_no',$request->id)->firstOrFail();

                if($request->status == '7'){
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->appointment_date)->format('Y-m-d');
                    $time = \Carbon\Carbon::createFromFormat('H:i', $request->appointment_time)->format('H:i:s');
                    $combinedDT = $date.' '.$time;
                    $appointment->date = $combinedDT;
                }else{
                    $appointment->status_id = $request->status;
                }

                if($appointment->save()){
                    return redirect()->back()->withFlashSuccess(__('booking::alerts.updated'));
                }else{
                    return redirect()->back()->withFlashDanger(__('booking::exceptions.update_error'));
                }
            }catch(\Exception $e){
                return redirect()->back()->withFlashSuccess(__('booking::exceptions.update_error'));
            }

        }else if($request->type == 'Treatment'){
            try{
                DB::beginTransaction();
                $treatment = Treatment::where('treatment_no',$request->id)->firstOrFail();

                if($request->status == '7'){
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->appointment_date)->format('Y-m-d');
                    $time = \Carbon\Carbon::createFromFormat('H:i', $request->appointment_time)->format('H:i:s');
                    $combinedDT = $date.' '.$time;
                    $treatment->date = $combinedDT;
                }else{
                    $treatment->status_id = $request->status;

                    /** create billing if confirmed */
                    if($request->status == '1') {
                        $serviceData = array();
                        foreach ($treatment->getDetail() as $val) {
                            $serviceCode = explode(' - ', $val->name);
                            $service = Service::select('id')->where('code', $serviceCode)
                                    ->first();
                            $serviceData[] = $service->id;
                        }

                        $treatmentRepo = new TreatmentRepository;
                        $treatmentRepo->createBilling($serviceData, $treatment);
                    }
                }

                $treatment->save();
                DB::commit();
                return redirect()->back()->withFlashSuccess(__('booking::alerts.updated'));
            }catch(\Exception $e){
                DB::rollback();
                return redirect()->back()->withFlashDanger($e->getMessage());
            }
        }else{
            return redirect()->back()->withFlashDanger(__("Invalid Request"));
        }
    }

    public function showByTherapist()
    {
        $pid = 0;
        $flagpatient = 'green';
        $queueId = 0;
        $therapistData = Staff::whereHas('user',function ($query) {
            $query->where('id_klinik',auth()->user()->id_klinik);
        })->where('designation_id', 4)->get();

        /** For Konsultasi */
        $therapist = array();
        foreach($therapistData as $key => $val){
            $therapist[$key] = array(
                'id' => $val->id,
                'title' => $val->user->full_name,
            );
        };

        $therapist = json_encode($therapist);
        $staff = Staff::whereHas('user',function ($query) {
            $query->where('id_klinik',auth()->user()->id_klinik);
        })->where('designation_id', 2)->get();
        $roomList = Room::where('id_klinik',auth()->user()->id_klinik)->whereHas('group', function ($query) {
            $query->where('room_groups.type', '=', 'APPOINTMENT');
        })->get();

        /** End For Konsultasi */

        //** For Treatment */
        $staffTr = Staff::whereHas('user',function ($query) {
            $query->where('id_klinik',auth()->user()->id_klinik);
        })->whereIn('designation_id', [2,4])->get();
        $roomTr = Room::where('id_klinik',auth()->user()->id_klinik)->whereHas('group', function ($query) {
                    $query->where('room_groups.type', '=', 'TREATMENT');
                })->get();

        $service = new Service;
        $listService = $service->optionList();
        /** End For Treatment */

        $patient = Patient::get();
        $patientflag = PatientFlag::all();

        return view('booking::by-therapist')
        ->withTherapist($therapist)
        ->withStaff($staff)
        ->withPatient($patient)
        ->withPid($pid)
        ->withQid($queueId)
        ->withRoomList($roomList)
        ->withFlags($patientflag)
        ->withPatientflag($flagpatient)
        ->withStaffTr($staffTr)
        ->withRoomTr($roomTr)
        ->withService($listService);
    }

    public function showByDocter()
    {
        $pid = 0;
        $flagpatient = 'green';
        $queueId = 0;
        $docterData = Staff::whereHas('user',function ($query) {
            $query->where('id_klinik',auth()->user()->id_klinik);
        })->where('designation_id', 2)->get();

        /** For Konsultasi */
        $docter = array();
        foreach($docterData as $key => $val){
            $docter[$key] = array(
                'id' => $val->id,
                'title' => ($val->user->full_name != '' ? $val->user->full_name : $val->user->first_name.' '.$val->user->last_name),
            );
        };

        $docter = json_encode($docter);
        $staff = Staff::whereHas('user',function ($query) {
            $query->where('id_klinik',auth()->user()->id_klinik);
        })->where('designation_id', 2)->get();
        $roomList = Room::where('id_klinik',auth()->user()->id_klinik)->whereHas('group', function ($query) {
            $query->where('room_groups.type', '=', 'APPOINTMENT');
        })->get();

        /** End For Konsultasi */

        //** For Treatment */
        $staffTr = Staff::whereHas('user',function ($query) {
            $query->where('id_klinik',auth()->user()->id_klinik);
        })->whereIn('designation_id', [2,4])->get();
        $roomTr = Room::where('id_klinik',auth()->user()->id_klinik)->whereHas('group', function ($query) {
                    $query->where('room_groups.type', '=', 'TREATMENT');
                })->get();

        $service = new Service;
        $listService = $service->optionList();
        /** End For Treatment */

        $patient = Patient::get();
        $patientflag = PatientFlag::all();
        return view('booking::by-docter')
        ->withDocter($docter)
        ->withStaff($staff)
        ->withPatient($patient)
        ->withPid($pid)
        ->withQid($queueId)
        ->withRoomList($roomList)
        ->withFlags($patientflag)
        ->withPatientflag($flagpatient)
        ->withStaffTr($staffTr)
        ->withRoomTr($roomTr)
        ->withService($listService);
    }

    public function showByRoom()
    {
        $pid = 0;
        $flagpatient = 'green';
        $queueId = 0;
        $roomData = Room::all();

        /** For Konsultasi */
        $room = array();
        foreach($roomData as $key => $val){
            $room[$key] = array(
                'id' => $val->id,
                'title' => $val->name,
            );
        };

        $room = json_encode($room);
        $staff = Staff::where('designation_id', 2)->get();
        $roomList = Room::whereHas('group', function ($query) {
            $query->where('room_groups.type', '=', 'APPOINTMENT');
        })->get();

        /** End For Konsultasi */

        //** For Treatment */
        $staffTr = Staff::whereIn('designation_id', [2,4])->get();
        $roomTr = Room::whereHas('group', function ($query) {
                    $query->where('room_groups.type', '=', 'TREATMENT');
                })->get();

        $service = new Service;
        $listService = $service->optionList();
        /** End For Treatment */

        $patient = Patient::get();
        $patientflag = PatientFlag::all();

        return view('booking::by-room')
        ->withRoom($room)
        ->withStaff($staff)
        ->withPatient($patient)
        ->withPid($pid)
        ->withQid($queueId)
        ->withRoomList($roomList)
        ->withFlags($patientflag)
        ->withPatientflag($flagpatient)
        ->withStaffTr($staffTr)
        ->withRoomTr($roomTr)
        ->withService($listService);
    }

    public function storeAppointment (Request $request)
    {
        $save = new AppointmentRepository;

        DB::beginTransaction();
        $save = $save->create($request->input());
        if($save){
            $save->status_id = 5;
            $save->save();

            $booking = new Booking;
            $booking->code = $save->appointment_no;
            $booking->type = 'APN';
            $booking->date = $save->date;

            if($booking->save()){

                DB::commit();
                return redirect()->back()->withFlashSuccess(__('patient::alerts.appointment.created'));
            }

            DB::rollback();
        }else{
            DB::rollback();
            return redirect()->back()->withFlashDanger(__('patient::alerts.appointment.create_error'));
	    }


    }

    public function storeTreatment (Request $request)
    {
        $save = new TreatmentRepository;

        try {
            DB::beginTransaction();
            $treatment = $save->createBooking($request->input());

            $booking = new Booking;
            $booking->code = $treatment->treatment_no;
            $booking->type = 'TR';
            $booking->date = $treatment->date;

            $booking->save();
            DB::commit();
            return redirect()->back()->withFlashSuccess(__('patient::alerts.treatment.created'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withFlashDanger($e->getMessage());
        }
    }

    public function getEventByRoom(Request $request)
    {
       if($request->ajax()){
        $data_appointment = Appointment::whereBetween('date' ,[$request->start,$request->end])->get();
        $data_treatment = Treatment::whereBetween('date' ,[$request->start,$request->end])->get();
        $resp = array();
        $url = '';

        foreach($data_appointment as $val){
            if($val->status_id == 1){
                $url = route('admin.patient.prescription.create', $val->appointment_no);
            }

            $resp[] = array(
                'id' => $val->appointment_no,
                'type' => 'Appointment',
                'resourceId' => $val->room_id,
                'description' => $val->patient->patient_unique_id.' '.$val->patient->patient_name.'('.$val->status->name.')',
                'title' => $val->appointment_no,
                'start' => $val->date->format('Y-m-d H:i:s'),
                'end' => $val->date->format('Y-m-d H:i:s'),
                'backgroundColor' => isset($this->backgroundColorEvent[$val->status_id]) ? $this->backgroundColorEvent[$val->status_id] : '#5cc4c3',
                'textColor' => '#f5f5f5',
                'url_modal' => $url,
                'pid' => $val->patient->patient_unique_id,
                'patient_name' => $val->patient->patient_name,
                'status' => $val->status_id,
                'status_name' => $val->status->name,
                'room' => $val->room['name'],
                'staff_name' => $val->staff->user->full_name,
                'booking_date' => $val->date->format('d-m-Y'),
                'booking_time' => $val->date->format('H:i:s'),
                'booking_full_date' => $val->date->format('Y-m-d H:i:s'),
                'patient' => $val->patient->id,
                'reminder_wa' => $this->_mapReminder($val->patient->last_wa)
            );
        }

        foreach($data_treatment as $val){
            if($val->status_id == 1){
                $url = route('admin.patient.treatment.show', $val->id);
            }

            $resp[] = array(
                'id' => $val->treatment_no,
                'type' => 'Treatment',
                'resourceId' => $val->room_id,
                'description' => $val->patient->patient_unique_id.' '.$val->patient->patient_name.'('.$val->status->name.')',
                'title' => $val->treatment_no,
                'start' => $val->date->format('Y-m-d H:i:s'),
                'end' => ( is_null($val->end_time) ? $val->date->format('Y-m-d H:i:s') : $val->date->format('Y-m-d') .' '. $val->end_time),
                'end_time' => ( is_null($val->end_time) ? '-' : $val->end_time),
                'backgroundColor' => isset($this->backgroundColorEvent[$val->status_id]) ? $this->backgroundColorEvent[$val->status_id] : '#5cc4c3',
                'textColor' => '#f5f5f5',
                'url_modal' => $url,
                'pid' => $val->patient->patient_unique_id,
                'patient_name' => $val->patient->patient_name,
                'status' => $val->status_id,
                'status_name' => $val->status->name,
                'room_name' => $val->room['name'],
                'staff_name' => $val->staff->user->full_name,
                'booking_date' => $val->date->format('d-m-Y'),
                'booking_time' => $val->date->format('H:i:s'),
                'booking_full_date' => $val->date->format('Y-m-d H:i:s'),
                'patient' => $val->patient->id,
                'reminder_wa' => $this->_mapReminder($val->patient->last_wa)
            );
        }

        return response()->json($resp);
       }else{
           abort('404');
       }
    }

    public function getEventByDocter(Request $request)
    {
       if($request->ajax()){

            $data_appointment = Appointment::where('id_klinik',auth()->user()->id_klinik)->whereBetween('date' ,[$request->start,$request->end])->get();
            $data_treatment = Treatment::where('id_klinik',auth()->user()->id_klinik)->whereBetween('date' ,[$request->start,$request->end])->get();
            $resp = array();
            $url = '';

            foreach($data_appointment as $val){
                if(isset($val->staff->designation_id) && $val->staff->designation_id != 2){
                    continue;
                }

                if($val->status_id == 1){
                    $url = route('admin.patient.prescription.create', $val->appointment_no);
                }

                $resp[] = array(
                    'id' => $val->appointment_no,
                    'type' => 'Appointment',
                    'resourceId' => $val->staff->id,
                    'description' => $val->patient->patient_unique_id.' '.$val->patient->patient_name.'('.$val->status->name.')',
                    'title' => $val->appointment_no,
                    'start' => $val->date->format('Y-m-d H:i:s'),
                    'end' => $val->date->format('Y-m-d H:i:s'),
                    'backgroundColor' => isset($this->backgroundColorEvent[$val->status_id]) ? $this->backgroundColorEvent[$val->status_id] : '#5cc4c3',
                    'textColor' => '#f5f5f5',
                    'url_modal' => $url,
                    'pid' => $val->patient->patient_unique_id,
                    'patient_name' => $val->patient->patient_name,
                    'status' => $val->status_id,
                    'status_name' => $val->status->name,
                    'room' => $val->room['name'],
                    'staff_name' => $val->staff->user->full_name,
                    'booking_date' => $val->date->format('d-m-Y'),
                    'booking_time' => $val->date->format('H:i:s'),
                    'booking_full_date' => $val->date->format('Y-m-d H:i:s'),
                    'patient' => $val->patient->id,
                    'reminder_wa' => $this->_mapReminder($val->patient->last_wa)
                );
            }

            foreach($data_treatment as $val){
                if(isset($val->staff->designation_id) && $val->staff->designation_id != 2){
                    continue;
                }

                if($val->status_id == 1){
                    $url = route('admin.patient.treatment.show', $val->id);
                }

                $resp[] = array(
                    'id' => $val->treatment_no,
                    'type' => 'Treatment',
                    'resourceId' => $val->staff->id,
                    'description' => $val->patient->patient_unique_id.' '.$val->patient->patient_name.'('.$val->status->name.')',
                    'title' => $val->treatment_no,
                    'start' => $val->date->format('Y-m-d H:i:s'),
                    'end' => ( is_null($val->end_time) ? $val->date->format('Y-m-d H:i:s') : $val->date->format('Y-m-d') .' '. $val->end_time),
                    'end_time' => ( is_null($val->end_time) ? '-' : $val->end_time),
                    'backgroundColor' => isset($this->backgroundColorEvent[$val->status_id]) ? $this->backgroundColorEvent[$val->status_id] : '#5cc4c3',
                    'textColor' => '#f5f5f5',
                    'url_modal' => $url,
                    'pid' => $val->patient->patient_unique_id,
                    'patient_name' => $val->patient->patient_name,
                    'status' => $val->status_id,
                    'status_name' => $val->status->name,
                    'room_name' => $val->room['name'],
                    'staff_name' => $val->staff->user->full_name,
                    'booking_date' => $val->date->format('d-m-Y'),
                    'booking_time' => $val->date->format('H:i:s'),
                    'booking_full_date' => $val->date->format('Y-m-d H:i:s'),
                    'patient' => $val->patient->id,
                    'reminder_wa' => $this->_mapReminder($val->patient->last_wa)
                );
            }

            return response()->json($resp);
       }else{
           abort('404');
       }
    }

    public function getEventByTherapist(Request $request)
    {
       if($request->ajax()){
            $data = Treatment::where('id_klinik',auth()->user()->id_klinik)->whereBetween('date' ,[$request->start,$request->end])
                    ->get();
            $resp = array();
            $url = '';

            if($data){
                foreach ($data as $val) {
                    foreach($val->detail as $row) {
                        dd($row);
                    }
                    if(isset($val->staff->designation_id) && $val->staff->designation_id != 4){
                        continue;
                    }

                    if($val->status_id == 1){
                        $url = route('admin.patient.treatment.show', $val->id);
                    }

                    $resp[] = array(
                        'id' => $val->treatment_no,
                        'type' => 'Treatment',
                        'resourceId' => $val->staff->id,
                        'description' => $val->patient->patient_unique_id.' '.$val->patient->patient_name.'('.$val->status->name.')',
                        'title' => $val->treatment_no,
                        'start' => $val->date->format('Y-m-d H:i:s'),
                        'end' => ( is_null($val->end_time) ? $val->date->format('Y-m-d H:i:s') : $val->date->format('Y-m-d') .' '. $val->end_time),
                        'end_time' => ( is_null($val->end_time) ? '-' : $val->end_time),
                        'backgroundColor' => isset($this->backgroundColorEvent[$val->status_id]) ? $this->backgroundColorEvent[$val->status_id] : '#5cc4c3',
                        'textColor' => '#f5f5f5',
                        'url_modal' => $url,
                        'pid' => $val->patient->patient_unique_id,
                        'patient_name' => $val->patient->patient_name,
                        'status' => $val->status_id,
                        'status_name' => $val->status->name,
                        'room_name' => $val->room['name'],
                        'staff_name' => $val->staff->user->full_name,
                        'booking_date' => $val->date->format('d-m-Y'),
                        'booking_time' => $val->date->format('H:i:s'),
                        'booking_full_date' => $val->date->format('Y-m-d H:i:s'),
                        'patient' => $val->patient->id,
                        'reminder_wa' => $this->_mapReminder($val->patient->last_wa)
                    );
                }
            }

            return response()->json($resp);
       }else{
           abort('404');
       }
    }

/* old event

    public function getEventByRoom(Request $request)
    {
        if($request->ajax()){
            $data = Booking::whereBetween('date' ,[$request->start,$request->end])->get();
            $resp = array();

            if($data){
                foreach ($data as $key => $val) {
                    if($val->type == 'APN'){
                        $app = Appointment::where('appointment_no', $val->code)->first();
                        $url = route('admin.patient.prescription.create', $app->appointment_no);

                        if($app->prescription){
                            $presId = $appointment->prescription->id;
                            $url = route('admin.patient.prescription.show', $presId);
                        }
                        $resp[$key] = array(
                            'id' => $val->id,
                            'resourceId' => $app->room_id,
                            'description' => $app->patient->patient_unique_id.' '.$app->patient->patient_name,
                            'title' => $app->appointment_no,
                            'start' => $app->date->format('Y-m-d H:i:s'),
                            'end' => $app->date->format('Y-m-d H:i:s'),
                            'backgroundColor' => '#3283a8',
                            'textColor' => '#f5f5f5',
                            'url' => $url,
                        );

                    }else if($val->type == 'TR'){
                        $tr = Treatment::where('treatment_no', $val->code)->first();
                        $url = route('admin.patient.treatment.show', $tr->id);

                        $resp[$key] = array(
                            'id' => $val->id,
                            'resourceId' => $tr->room_id,
                            'description' => $tr->patient->patient_unique_id.' '.$tr->patient->patient_name,
                            'title' => $tr->treatment_no,
                            'start' => $tr->date->format('Y-m-d H:i:s'),
                            'end' => $tr->date->format('Y-m-d H:i:s'),
                            'backgroundColor' => '#43a88a',
                            'textColor' => '#f5f5f5',
                            'url' => $url,
                        );
                    }
                }
            }

            return response()->json($resp);
       }else{
           abort('404');
       }
    }

    public function getEventByDocter(Request $request)
    {
       if($request->ajax()){
            $data = Booking::whereBetween('date' ,[$request->start,$request->end])->get();
            $resp = array();

            if($data){
                foreach ($data as $key => $val) {
                    if($val->type == 'APN'){
                        $app = Appointment::where('appointment_no', $val->code)->first();

                        if(isset($app->staff->designation_id) && $app->staff->designation_id != 2){
                            continue;
                        }

                        $url = route('admin.patient.prescription.create', $app->appointment_no);

                        if($app->prescription){
                            $presId = $app->prescription->id;
                            $url = route('admin.patient.prescription.show', $presId);
                        }
                        $resp[] = array(
                            'id' => $val->id,
                            'resourceId' => $app->staff->id,
                            'description' => $app->patient->patient_unique_id.' '.$app->patient->patient_name,
                            'title' => $app->appointment_no,
                            'start' => $app->date->format('Y-m-d H:i:s'),
                            'end' => $app->date->format('Y-m-d H:i:s'),
                            'backgroundColor' => '#3283a8',
                            'textColor' => '#f5f5f5',
                            'url' => $url,
                        );

                    }else if($val->type == 'TR'){
                        $tr = Treatment::where('treatment_no', $val->code)->first();

                        if(isset($tr->staff->designation_id) && $tr->staff->designation_id != 2){
                            continue;
                        }

                        $url = route('admin.patient.treatment.show', $tr->id);

                        $resp[] = array(
                            'id' => $val->id,
                            'resourceId' => $tr->staff->id,
                            'description' => $tr->patient->patient_unique_id.' '.$tr->patient->patient_name,
                            'title' => $tr->treatment_no,
                            'start' => $tr->date->format('Y-m-d H:i:s'),
                            'end' => $tr->date->format('Y-m-d H:i:s'),
                            'backgroundColor' => '#43a88a',
                            'textColor' => '#f5f5f5',
                            'url' => $url,
                        );
                    }
                }
            }

            return response()->json($resp);
       }else{
           abort('404');
       }
    }


    public function getEventByTherapist(Request $request)
    {
       if($request->ajax()){
            $data = Booking::whereBetween('date' ,[$request->start,$request->end])
                    ->where('type', 'TR')->get();
            $resp = array();

            if($data){
                foreach ($data as $key => $val) {
                    $tr = Treatment::where('treatment_no', $val->code)->first();
                    $url = route('admin.patient.treatment.show', $tr->id);

                    $resp[] = array(
                        'id' => $key,
                        'resourceId' => $tr->staff->id,
                        'description' => $tr->patient->patient_unique_id.' '.$tr->patient->patient_name,
                        'title' => $tr->treatment_no,
                        'start' => $tr->date->format('Y-m-d H:i:s'),
                        'end' => $tr->date->format('Y-m-d H:i:s'),
                        'backgroundColor' => '#43a88a',
                        'textColor' => '#f5f5f5',
                        'url' => $url,
                    );
                }
            }

            return response()->json($resp);
       }else{
           abort('404');
       }
    }
*/

    public function getBooking(Request $request)
    {
        if($request->ajax()){
            $status = false;
            $msg = "Data Tidak Diemukan";

            switch ($request->type) {
                case '1':
                    $data = Appointment::find($request->id);
                    if($data){
                        $data = [
                            'id' => $data->appointment_no,
                            'pid' => $data->patient->patient_unique_id,
                            'type' => 'Appointment',
                            'patient_name' => $data->patient->patient_name,
                            'booking_date' => $data->date->format('d-m-Y'),
                            'booking_time' => $data->date->format('H:i:s'),
                            'room' => $data->room->name,
                            'staff_name' => $data->staff->user->full_name,
                            'status_name' => $data->status->name
                        ];
                        $status = true;
                        $msg = $data;
                    }
                    break;

                case '2';
                    $data = Treatment::find($request->id);
                    if($data){
                        // dd($data);
                        $data = [
                            'id' => $data->treatment_no,
                            'pid' => $data->patient->patient_unique_id,
                            'type' => 'Treatment',
                            'patient_name' => $data->patient->patient_name,
                            'booking_date' => $data->date->format('d-m-Y'),
                            'booking_time' => $data->date->format('H:i:s'),
                            'room' => $data->room->name,
                            'staff_name' => $data->staff->user->full_name,
                            'status_name' => $data->status->name
                        ];

                        $status = true;
                        $msg = $data;
                    }
                    break;
            }

            return response()->json(array('status' => $status, 'msg' => $msg), 200);
        }

        abort(404);
    }

    private function _mapReminder($lastWa)
    {
        if($lastWa != null){
            $lastWa = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $lastWa)->format('Y');
        };
        $now = \Carbon\Carbon::now()->format('Y');

        if($now == $lastWa){
            $badge = '<span class="badge badge-success">Sudah dikirim</span>';
        }else{
            $badge = '<span class="badge badge-danger">Belum dikirim</span>';
        }

        return $badge;
    }
}
