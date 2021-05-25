<?php

namespace App\Modules\Patient\Repositories;

use App\Modules\Patient\Models\Appointment;
use App\Modules\Patient\Models\Treatment;
use App\Modules\Patient\Models\Patient;
use App\Modules\Patient\Models\PatientFlag;
use App\Modules\Patient\Models\PatientAdmission;
use App\Modules\Patient\Models\TreatmentInvoice;
use App\Modules\Patient\Models\TreatmentDetail;
use App\Modules\Product\Models\Service;
use App\Modules\Billing\Models\Billing;
use App\Modules\Billing\Models\BillingDetail;
use App\Modules\Patient\Models\PatientTimeline;
use App\Modules\Patient\Models\PatientTimelineDetail;
use App\Modules\Patient\Models\Prescription;
use App\Modules\Patient\Models\PatientBeforeAfter;

use App\Modules\Billing\Repositories\BillingRepository;
use App\Modules\Accounting\Models\FinanceTransaction;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class TreatmentRepository extends BaseRepository
{
    protected $billingRepository;

    public function __construct()
    {
        $this->billingRepository = new BillingRepository;
    }

    /**
     * @return string
     */
    public function model()
    {
        return Treatment::class;
    }

    public function create(array $data) //: Treatment
    {
        // die(json_encode($data));
        $patient = Patient::where('patient_unique_id', $data['pid'])->first();
        if (!$patient) {
            throw new GeneralException('Patient Not Exist:: '.$data['pid']);
        }

        $data['pid'] = $patient->id;

        $date = $data['appointment_date'];
        $date = \Carbon\Carbon::CreateFromFormat(setting()->get('date_format'), $date)->format('Y-m-d');
        $combinedDT = date('Y-m-d H:i:00', strtotime($date.' '.$data['appointment_time']));

        $treatment = Treatment::create([
            'patient_id'  => $data['pid'],
            'date' => $combinedDT,
            'end_time' => $data['end_time'],
            'room_id' => $data['room_id'],
            'staff_id' => $data['dokter_staff_id'] ?? 0,
            'notes' => $data['notes'],
            'status_id' => config('admission.admission_waiting'),
            'id_klinik' => auth()->user()->klinik->id_klinik,
            'staff_terapis_id' => $data['staff_terapis_id'] ?? 0
        ]);

        $code = 'TR'.$treatment->id;

        $treatment->treatment_no = $code;
        $treatment->save();

        $patientFlag = PatientFlag::where('name', $data['patient_flag_id'])->first();

        if($patientFlag){
            Patient::where('id', $data['pid'])->update(['patient_flag_id' => $patientFlag->id]);
        }

        if($data['qId']){
            PatientAdmission::where('id', $data['qId'])->update(['status_id' => config('admission.admission_completed')]);

            // Tambahkan Referensi Appointment ke Treatment
            $admisson = PatientAdmission::find($data['qId']);
            if(isset($admisson->is_online) && $admisson->is_online == "1"){
                $treatment->is_online = "1";
                $treatment->save();
            }

            if($admisson->reference_id){
                $appointment = Appointment::where('appointment_no', $admisson->reference_id)->first();
                if($appointment){
                    Treatment::where('id', $treatment->id)->update(['appointment_id' => $appointment->id]);
                }
            }
        }

        $serviceData = [];
        if(isset($data['service'])){
            foreach ($data['service'] as $key => $val) {
                $service = Service::find($val);
                $serviceData[] = $service->id;

                $treatmentDetail = TreatmentDetail::firstOrNew([
                    'treatment_id' => $treatment->id,
                    'name' => $service->code.' - '.$service->name.' - '.$service->category->name,
                    'description' => $data['servicenotes'][$key]
                ]);

                $saveTreatmentDetail = $treatmentDetail->save();
            }

            /** create billing */
            $this->createBilling($serviceData, $treatment);
        }

        return $treatment;
    }

    public function update(Treatment $treatment, array $data)
    {
        return DB::transaction(function () use ($treatment, $data) {

            $updateTreatment = $treatment->update([
                'service_id'      => $data['service_id'],
                'service_notes' => $data['notes'],
                'status_id' => config('admission.admission_completed')
            ]);

            if ($updateTreatment) {
                $treatment = Treatment::find($data['tId']);
                if($treatment->appointment_id){
                    $appointment = Appointment::find($treatment->appointment_id);
                    $patient = $appointment->patient;
                    $prescription = $appointment->prescription;

                    $date = \Carbon\Carbon::now();
                    //Create Timeline
                    $timeline = new PatientTimeline;

                    $timeline->patient_id = $treatment->patient_id;
                    $timeline->title = 'Referensi #'.$treatment->treatment_no;
                    $timeline->timeline_date = $date;
                    $timeline->description = 'Treatment';
                    if (isset($data['inputFoto']) && !empty($data['inputFoto'])) {
                        if($timeline->save()){
                            foreach ($data['inputFoto'] as $image) {
                                $timelineDetail = new PatientTimelineDetail;

                                $timelineDetail->timeline_id = $timeline->id;
                                $timelineDetail->setFileImage64($image);
                                $timelineDetail->save();
                            }
                            Prescription::where('id', $prescription->id)->update(['timeline_id' => $timeline->id]);
                        }

                        foreach ($data['inputFoto'] as $image) {
                            $patientBeforeAfter = new PatientBeforeAfter;

                            $patientBeforeAfter->patient_id = $appointment->patient_id;
                            $patientBeforeAfter->date = \Carbon\Carbon::now();
                            $patientBeforeAfter->type = 'after';
                            $patientBeforeAfter->setFileImage64($image);
                            $patientBeforeAfter->save();
                        }
                    }
                }

                return response()->json(array('status' => true, 'message' => 'Treatement Berhasil diupdate.'));
            }

            return response()->json(array('status' => false, 'message' => 'Error Update Treatment'));
        });
    }

    /** data from request, example form on booking treatement */
    public function createBooking(array $data)
    {
        $patient = Patient::where('patient_unique_id', $data['pid'])->first();
        if (!$patient) {
           throw new \Exception('Patient Not Exist:: '.$data['pid']);
        }

        $data['pid'] = $patient->id;
        $date = $data['appointment_date'];
        $date = \Carbon\Carbon::CreateFromFormat(setting()->get('date_format'), $date)->format('Y-m-d');
        $combinedDT = date('Y-m-d H:i:00', strtotime($date.' '.$data['appointment_time']));

        $treatment = Treatment::create([
            'patient_id'  => $data['pid'],
            'date' => $combinedDT,
            'end_time' => $data['end_time'],
            'room_id' => $data['room_id'],
            'staff_id' => $data['staff_id'],
            'notes' => $data['notes'],
            'status_id' => 5 ,//is booking,
            'id_klinik' => auth()->user()->id_klinik
        ]);

        $code = 'TR'.$treatment->id;
        $treatment->treatment_no = $code;
        $treatment->save();

        /** update patient flag */
        $patientFlag = PatientFlag::where('name', $data['patient_flag_id'])->first();

        if($patientFlag){
            Patient::where('id', $data['pid'])->update(['patient_flag_id' => $patientFlag->id]);
        }

        foreach ($data['service'] as $key => $val) {
            $service = Service::find($val);

            if(!$service) throw new \Exception('Treatment yang dipilih tidak valid');

            $treatmentDetail = TreatmentDetail::firstOrNew([
                'treatment_id' => $treatment->id,
                'name' => $service->code.' - '.$service->name.' - '.$service->category->name,
                'description' => $data['servicenotes'][$key]
            ]);

            $saveTreatmentDetail = $treatmentDetail->save();
        }
        return $treatment;
    }

    /**
     * example parame $serviceData = [ '1', '2'] value of array is service id
     */
    public function createBilling(array $serviceData, Treatment $treatment)
    {
        //Add Bill Treatment
        $date = \Carbon\Carbon::now();

        $billing = Billing::create([
            'invoice_no' => Billing::generateInvoiceNumer(),
            'patient_id' => $treatment->patient_id,
            'status' => config('billing.invoice_unpaid'),
            'date' => $date->format( setting()->get('date_format')),
            'created_by' => auth()->user()->id,
            'id_klinik' => auth()->user()->klinik->id_klinik
        ]);

        /** Integrate To Finance */
        $transaction = $this->_storeToTransactionTable($billing);
        $billing->transaction_id = $transaction->id;
        $billing->save();

        $this->billingRepository->storeInvoiceTreatments($serviceData,  $billing, $transaction, $treatment);
    }

    private function _storeToTransactionTable(Billing $billing)
    {
        $transaction = FinanceTransaction::firstOrNew([
            'trx_type_id' => config('finance_trx.trx_types.invoice'),
            'transaction_code' => $billing->invoice_no,
            'memo' => '',
            'person' => $billing->patient->name,
            'person_id' => $billing->patient->id,
            'person_type' => config('finance_trx.person_type.patient'),
            'trx_date' => \Carbon\Carbon::now(),
            'potongan' => '',
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'id_klinik' => auth()->user()->klinik->id_klinik
        ]);

//        $transaction->transaction_code =
        $transaction->save();

        return $transaction;
    }
}
