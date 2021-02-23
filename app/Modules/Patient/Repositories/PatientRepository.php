<?php

namespace App\Modules\Patient\Repositories;

use App\Modules\Patient\Models\Patient;
use App\Modules\Patient\Models\Appointment;
use App\Modules\Patient\Models\PatientAdmission;
use App\Modules\Patient\Models\PatientTimeline;
use App\Modules\Patient\Models\PatientMediaInfo;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class PatientRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Patient::class;
    }

    public function create(array $data) : Patient
    {
        return DB::transaction(function () use ($data) {
            // die(print_r($data));
            $patient = parent::create([
                // 'admission_date'  => $combinedDT,
                'patient_name'  => $data['patient_name'],
                'phone_number' => $data['phone_number'],
                'wa_number' => $data['wa_number'],
                'birth_place' => $data['birth_place'],
                'blood_id' => $data['blood_id'],
                'dob' => $data['dob'],
                'gender' => $data['gender'],
                'religion_id' => $data['religion_id'],
                'city_id' => $data['city_id'],
                'district_id' => $data['district_id'],
                'village_id' => $data['village_id'],
                'zip_code' => $data['zip_code'],
                'info_id' => $data['info_id'],
                'work_id' => $data['work_id'],
                'patient_flag_id' => $data['patient_flag_id'],
                'hobby' => $data['hobby'],
                'address' => $data['address'],
                'old_patient' => $data['old_patient'],
                'id_klinik' => Auth()->user()->klinik->id_klinik
            ]);

            if($patient){
                $patient->mediaInfo()->sync($data['media_info_id']);

                return $patient;
            }
        });
    }

    public function createAdmission(array $data)
    {
        return DB::transaction(function () use ($data) {

            $patient = Patient::firstOrNew([
                'patient_unique_id' => $data['pid'],
            ]);

            $patient->patient_name = $data['patient_name'];
            $patient->phone_number = $data['phone_number'];
            $patient->birth_place = $data['birth_place'];
            $patient->blood_id = $data['blood_id'];
            $patient->dob = $data['dob'];
            $patient->gender = $data['gender'];
            $patient->religion_id = $data['religion_id'];
            $patient->city_id = $data['city_id'];
            $patient->district_id = $data['district_id'];
            $patient->village_id = $data['village_id'];
            $patient->zip_code = $data['zip_code'];
            $patient->info_id = $data['info_id'];
            $patient->work_id = $data['work_id'];
            $patient->hobby = $data['hobby'];
            $patient->address = $data['address'];
            $patient->old_patient = $data['old_patient'];
            $patient->save();

            if($patient->id){
                //Insert media Info
                if(isset($data['media_info_id'])){
                    $patient->mediaInfo()->sync($data['media_info_id']);
                }

                $admissionData = [
                    'patient_id' => $patient->id,
                    'admission_type_id' => $data['appointment_type'],
                    'admission_no' => PatientAdmission::generateQueue($data['appointment_type']),
                    'is_online' => "1",
                    'status_id' => 1
                ];

                $admission = PatientAdmission::create($admissionData);

                if($admission){
                    return array('patient' => $patient, 'admission' => $admission);
                }
            }
        });
    }

    public function update(Patient $patient, array $data)
    {
        // dd($data);
        return DB::transaction(function () use ($patient, $data) {
            $patient = $patient->update([
                    // 'admission_date'  => $combinedDT,
                    'patient_name'  => $data['patient_name'],
                    'phone_number' => $data['phone_number'],
                    'wa_number' => $data['wa_number'],
                    'birth_place' => $data['birth_place'],
                    'blood_id' => $data['blood_id'],
                    'dob' => $data['dob'],
                    'gender' => $data['gender'],
                    'religion_id' => $data['religion_id'],
                    'city_id' => $data['city_id'],
                    'district_id' => $data['district_id'],
                    'village_id' => $data['village_id'],
                    'zip_code' => $data['zip_code'],
                    'info_id' => $data['info_id'],
                    'work_id' => $data['work_id'],
                    'patient_flag_id' => $data['patient_flag_id'],
                    'hobby' => $data['hobby'],
                    'address' => $data['address'],
                    'old_patient' => $data['old_patient']
            ]);

            if ($patient) {

                return $patient;
            }

            throw new GeneralException(trans('patient::exceptions.patient.update_error'));
        });
    }

    public function createTimeline($request)
    {
        $patient = Patient::where('patient_unique_id', $request->get('patient_id'))->firstOrFail();

        if($patient){

            return DB::transaction(function () use ($request, $patient) {
                $date = $request->get('timeline_date');
                $date = \Carbon\Carbon::CreateFromFormat(setting()->get('date_format'), $date)->format('Y-m-d');

                $timeline = new PatientTimeline;

                $timeline->patient_id = $patient->id;
                $timeline->title = $request->get('title');
                $timeline->timeline_date = $date;
                $timeline->description = $request->get('description');

                if ($request->hasFile('document')) {
                    $timeline->setFile($request->file('document'));
                }

                if ($timeline->save()) {
                    return response()->json(array('status' => true, 'message' => 'Berhasil Tambah Timeline'));

                }

                DB::rollBack();
                return response()->json(array('status' => false, 'message' => 'Error menyimpan konfirmasi pembayaran!'));
            });
        }else{
            return response()->json(array('status' => false, 'message' => 'Tidak ada user: '.$request->get('patient_id')));
        }

    }

    protected function checkExists($name, $category_id) : bool
    {
        return $this->model
                ->where('name', strtolower($name))
                ->where('category_id', $category_id)
                ->count() > 0;
    }
}
