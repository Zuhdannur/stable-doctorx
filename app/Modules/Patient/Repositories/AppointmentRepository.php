<?php

namespace App\Modules\Patient\Repositories;

use App\Modules\Patient\Models\Appointment;
use App\Modules\Patient\Models\Patient;
use App\Modules\Patient\Models\PatientFlag;
use Illuminate\Support\Facades\DB;
use App\Modules\Patient\Models\PatientAdmission;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class AppointmentRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Appointment::class;
    }

    public function create(array $data) : Appointment
    {
        $patient = Patient::where('patient_unique_id', $data['pid'])->first();
        if (!$patient) {
            throw new GeneralException('Patient Not Exist:: '.$data['pid']);
        }
        $data['pid'] = $patient->id;
        // die(print_r($data));
        return DB::transaction(function () use ($data) {
            $date = $data['appointment_date'];
            $date = \Carbon\Carbon::CreateFromFormat(setting()->get('date_format'), $date)->format('Y-m-d');
            $combinedDT = date('Y-m-d H:i:00', strtotime($date.' '.$data['appointment_time']));

            $appointment = Appointment::create([
                'patient_id'  => $data['pid'],
                'date' => $combinedDT,
                'room_id' => $data['room_id'],
                'staff_id' => $data['staff_id'],
                'notes' => $data['notes'],
                'status_id' => 1,
                'id_klinik' => auth()->user()->klinik->id_klinik
            ]);


            if($appointment){
                $code = 'APN'.$appointment->id;

                $appointment->appointment_no = $code;
                $appointment->status_id = 1;
                $appointment->save();

                $patientFlag = PatientFlag::where('name', $data['patient_flag_id'])->first();
                // die(json_encode($patientFlag));
                if($patientFlag){
                    Patient::where('id', $data['pid'])->update(['patient_flag_id' => $patientFlag->id]);
                }

                if($data['qId']){
                    PatientAdmission::where('id', $data['qId'])->update(['status_id' => config('admission.admission_completed')]);

                    $admisson = PatientAdmission::find($data['qId']);
                    if(isset($admisson->is_online) && $admisson->is_online == "1"){
                        $appointment->is_online = "1";
                        $appointment->save();
                    }
                }

                return $appointment;
            }
        });
    }

    public function update(Appointment $appointment, array $data)
    {
        // dd($appointment);
        return DB::transaction(function () use ($appointment, $data) {
            $date = $data['appointment_date'];
            $date = \Carbon\Carbon::CreateFromFormat(setting()->get('date_format'), $date)->format('Y-m-d');
            $combinedDT = date('Y-m-d H:i:00', strtotime($date.' '.$data['appointment_time']));

            $appointment = $appointment->update([
                'date' => $combinedDT,
                'room_id' => $data['room_id'],
                'staff_id' => $data['staff_id'],
                'notes' => $data['notes'],
            ]);

            if($appointment){
                $patientFlag = PatientFlag::where('name', $data['patient_flag_id'])->first();
                // die(json_encode($patientFlag));
                if($patientFlag){
                    Patient::where('patient_unique_id', $data['pid'])->update(['patient_flag_id' => $patientFlag->id]);
                }

                return $appointment;
            }

            throw new GeneralException(trans('patient::exceptions.patient.update_error'));
        });
    }

    protected function checkExists($name, $category_id) : bool
    {
        return $this->model
                ->where('name', strtolower($name))
                ->where('category_id', $category_id)
                ->count() > 0;
    }
}
