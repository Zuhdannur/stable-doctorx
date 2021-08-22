<?php

namespace App\Modules\Patient\Repositories;

use App\Models\Auth\User;
use App\Modules\Booking\Models\Booking;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Modules\Billing\Models\Billing;
use App\Modules\Patient\Models\Patient;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Service;
use App\Modules\Patient\Models\Diagnoses;
use App\Modules\Patient\Models\Appointment;
use App\Modules\Patient\Models\DiagnoseItem;

use App\Modules\Patient\Models\Prescription;

use App\Modules\Accounting\Models\FinanceTax;
use App\Modules\Billing\Models\BillingDetail;
use App\Modules\Patient\Models\PatientTimeline;
use App\Modules\Product\Models\ServicesPackage;

use App\Modules\Patient\Models\PatientAdmission;

use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Patient\Models\AppointmentInvoice;
use App\Modules\Patient\Models\PatientBeforeAfter;

use App\Modules\Patient\Models\PrescriptionDetail;
use App\Modules\Accounting\Models\FinanceTransaction;
use App\Modules\Patient\Models\PatientTimelineDetail;
use App\Modules\Patient\Models\PrescriptionTreatment;
use App\Modules\Billing\Repositories\BillingRepository;

class PrescriptionRepository extends BaseRepository
{
    protected $billingRepository;
    protected $appointmentRepository;

    public function __construct(BillingRepository $billingRepository , AppointmentRepository $appointmentRepository)
    {
        $this->billingRepository = $billingRepository;
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * @return string
     */
    public function model()
    {
        return Prescription::class;
    }

    public function createPrescription($request)
    {
        $appointment = Appointment::findOrFail($request->appid);

        if (Prescription::where('appointment_id', $request->appid)->first()) {
            return response()->json(array('status' => false, 'message' => trans('patient::exceptions.prescription.already_exists')));
        }

        DB::beginTransaction();
        try {
            $informConcern = null;
            if ($request->withConcern == 'true') {
                $Prescription = new Prescription;
                // $informConcern = $Prescription->createInformConcent($appointment->patient);
                $informConcern = $Prescription->generatePDFInformConcern($appointment->patient, $request->input());
            }

            if (!empty($request->is_record) && $request->is_record == "on") {
                $recordMapping = new \App\RecordMapping;
                $recordMapping->appointment_id = $request->appid;
                $recordMapping->jenis_tindakan = $request->jenis_tindakan;
                $recordMapping->jenis_bahan = $request->jenis_bahan;
                $recordMapping->jumlah = $request->jumlah;
                $recordMapping->lokasi = $request->lokasi;
                $recordMapping->catatan = $request->catatan;
                $recordMapping->canvas = $request->canvas;
                $recordMapping->image = $request->base64;
                $recordMapping->save();
            }

            $createPrescription = Prescription::create([
                'appointment_id' => $request->appid,
                // 'service_id' => $request->service_id,
                // 'notes' => $request->notes,
                'complaint' => $request->complaint,
                'treatment_history' => $request->treatment_history,
                'patient_id' => $appointment->patient_id,
                'inform_concern' => $informConcern,
                'created_by' => auth()->user()->id
            ]);

            //SetArrayForBill
            $billProduct = array();
            $billProductPrice = array();
            $billService = array();
            $billServicePrice = array();
            $billProductQty = array();
            $billServiceQty = array();

            $totaloop = $request->product;
            $totaloopDiagnosis = $request->diagnosis;
            $totaloopService = $request->service;

            $saveObat = false;
            $saveTreatment = false;

            foreach ($totaloop as $key => $val) { //$value->code.' - '.$value->name.' - '.$value->category->name
                if ($request->product[$key]) {
                    $getProduct = Product::find($request->product[$key]);
                    $billProduct[] = $request->product[$key] . '#product';
                    $billProductPrice[] = $getProduct->price;
                    $billProductQty[] = $request->qty[$key];

                    $prescriptionDetail = PrescriptionDetail::firstOrNew([
                        'prescription_id' => $createPrescription->id,
                        'product_id' => $getProduct->id,
                        'name' => $getProduct->code . ' - ' . $getProduct->name . ' - ' . $getProduct->category->name,
                        'instruction' => $request->instruction[$key]
                    ]);

                    $saveObat = $prescriptionDetail->save();
                }
            }

            foreach ($totaloopService as $key3 => $val3) {
                if ($request->service[$key3]) {
                    $explode_service = explode('#', $request->service[$key3]);

                    if ($explode_service[0] == "servicePackages") {
                        $getService = ServicesPackage::find("$explode_service[1]");
                        if (isset($getService->details)) {
                            foreach ($getService->details as $value) {

                                $billService[] = $value->service['id'] . '#service';
                                $billServicePrice[] = $value->service['price'];
                                $billServiceQty[] = $request->qty[$key] * $value->qty;
                                $prescriptionService = PrescriptionTreatment::firstOrNew([
                                    'prescription_id' => $createPrescription->id,
                                    'services_id' => $value->service['id'],
                                    'name' => $value->service['code'] . ' - ' . $value->service['name'] . ' - ' . $value->service['category']['name'],
                                    'description' => $request->servicenotes[$key3]
                                ]);
                            }
                        }
                    } else {
                        $getService = Service::find($explode_service[1]);
                        // $getService = Service::find($request->service[$key3]);
                        $billService[] = $request->service[$key3] . '#service';
                        $billServicePrice[] = $getService->price;
                        $billServiceQty[] = $request->qty[$key];

                        $prescriptionService = PrescriptionTreatment::firstOrNew([
                            'prescription_id' => $createPrescription->id,
                            'services_id' => $getService->id,
                            'name' => $getService->code . ' - ' . $getService->name . ' - ' . $getService->category->name,
                            'description' => $request->servicenotes[$key3]
                        ]);
                    }

                    $saveTreatment = $prescriptionService->save();
                }
            }

            $totaloopDiagnosis = $request->diagnosis;
            $saveDiagnosis = false;

            foreach ($totaloopDiagnosis as $key2 => $val2) {
                if ($request->diagnosis[$key2]) {
                    $diagnoseItem = DiagnoseItem::find($request->diagnosis[$key2]);
                    if ($diagnoseItem) {
                        $diagnosis = Diagnoses::firstOrNew([
                            'appointment_id' => $request->appid,
                            'diagnose_item_id' => $diagnoseItem->id,
                            'name' => $diagnoseItem->name,
                            'instruction' => $request->diaginstruction[$key2]
                        ]);

                        $saveDiagnosis = $diagnosis->save();
                    }
                }
            }

            if ($request->is_treatment != "on" && $saveTreatment == true) {
                $saveTreatment = false;
            }

            $date = \Carbon\Carbon::now();
            if ($saveObat || $saveTreatment) {
                if ($saveTreatment == true) {  //daftar treatment
                    $admissionData = [
                        'patient_id' => $appointment->patient_id,
                        'admission_type_id' => config('admission.admission_treatment'),
                        'admission_no' => PatientAdmission::generateQueue(config('admission.admission_treatment')),
                        'status_id' => config('admission.admission_waiting'),
                        'reference_id' => $appointment->appointment_no,
                        'created_by' => auth()->user()->id,
                        'notes' => 'Referensi #' . $appointment->appointment_no
                    ];

                    $admission = PatientAdmission::create($admissionData);
                }

                if($saveObat == true) {
                    $admissionData = [
                        'patient_id' => $appointment->patient_id,
                        'admission_type_id' => config('admission.admission_medicine'),
                        'admission_no' => PatientAdmission::generateQueue(config('admission.admission_medicine')),
                        'status_id' => config('admission.admission_waiting'),
                        'reference_id' => $appointment->appointment_no,
                        'created_by' => auth()->user()->id,
                        'notes' => 'Referensi #' . $appointment->appointment_no
                    ];

                    $admission = PatientAdmission::create($admissionData);
                }

                /* Start Create Billing */
                $dataProduct = array(
                    'product' => array_merge($billProduct, $billService),
                    'price' => array_merge($billProductPrice, $billServicePrice),
                    'qty' => array_merge($billProductQty, $billServiceQty)
                );

                //save data to transaction table
                $patient = Patient::findOrFail($appointment->patient_id);
                $transaction = FinanceTransaction::create([
                    'trx_type_id' => config('finance_trx.trx_types.invoice'),
                    'memo' => '',
                    'person' => $patient->name,
                    'person_id' => $patient->id,
                    'person_type' => config('finance_trx.person_type.patient'),
                    'trx_date' => $date->format('Y-m-d'),
                    'potongan' => '',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ]);

                $dataBilling = [
                    'invoice_no' => Billing::generateInvoiceNumer(),
                    'appointment_id' => $request->appid,
                    'patient_id' => $appointment->patient_id,
                    'tax_total' => '', //update dibawah
                    'status' => config('billing.invoice_unpaid'),
                    'date' => $date->format(setting()->get('date_format')),
                    'created_by' => auth()->user()->id,
                    'id_klinik' => auth()->user()->klinik->id_klinik
                ];

                $createBilling = Billing::create($dataBilling);

                // Find a Same invoice
                $duplicate = FinanceTransaction::where('transaction_code', $createBilling->invoice_no)->first();
                if ($duplicate) {
                    $duplicate->delete();
                }

                $transaction->transaction_code = $createBilling->invoice_no;
                $transaction->save();

                $createBilling->transaction_id = $transaction->id;
                $createBilling->save();

                $storeInvoicePrescription = $this->billingRepository->storeInvoicePrescription($dataProduct, $createBilling, $transaction);
                if ($storeInvoicePrescription == false) {
                    DB::rollback();
                    return response()->json(array('status' => false, 'message' => 'Ada Kesalahan Saat Menyimpan Data Ini'));
                }

                //Save Maping Billing
                $AppointmentInvoice = AppointmentInvoice::firstOrNew(['appointment_id' => $request->appid, 'invoice_id' => $createBilling->id]);
                $AppointmentInvoice->save();

            }
            /**End Of Create Billing */

            //Create Timeline
            $timeline = new PatientTimeline;

            $timeline->patient_id = $appointment->patient_id;
            $timeline->title = 'Referensi #' . $appointment->appointment_no;
            $timeline->timeline_date = $date;
            $timeline->description = 'Konsultasi';

            if ($request->has('inputFoto')) {

                if ($timeline->save()) {
                    foreach ($request->inputFoto as $image) {
                        if (substr($request->inputFoto[0], 0, 4) != 'http') {
                            $timelineDetail = new PatientTimelineDetail;

                            $timelineDetail->timeline_id = $timeline->id;
                            $timelineDetail->setFileImage64($image);
                            $timelineDetail->save();
                        }
                    }

                    Prescription::where('id', $createPrescription->id)->update(['timeline_id' => $timeline->id]);
                }

                $input = $request->all();

                foreach ($request->inputFoto as $index => $image) {
                    if (substr($request->inputFoto[0], 0, 4) != 'http') {
                        $patientBeforeAfter = new PatientBeforeAfter;

                        $patientBeforeAfter->patient_id = $appointment->patient_id;
                        $patientBeforeAfter->date = \Carbon\Carbon::now();
                        $patientBeforeAfter->type = @$input['tipe'][$index];
                        $patientBeforeAfter->setFileImage64($image);
                        $patientBeforeAfter->save();
                    }
                }
            }

            //Update Status
            Appointment::where('id', $request->appid)->update(
                [
                    'status_id' => 4
                ]
            );

            if ($request->next_appointment_date) {
                $date = $request->next_appointment_date;
                $date = \Carbon\Carbon::createFromFormat(setting()->get('date_format'), $date)->format('Y-m-d');

                Appointment::where('id', $request->appid)->update(
                    [
                        'next_appointment_date' => $date,
                        'next_appointment_notes' => $request->next_appointment_notes
                    ]
                );

                //#TODO NEW APOINTMENT

                $newAppointmentParams = [];
                $newAppointmentParams['qId'] = 0;
                $newAppointmentParams['pid'] = $appointment->patient->patient_unique_id;
                $newAppointmentParams['appointment_date'] = $request->next_appointment_date;
                $newAppointmentParams['appointment_time'] = "08:00";
                $newAppointmentParams['staff_id'] = $appointment->staff_id;
                $newAppointmentParams['room_id'] = $appointment->room_id;
                $newAppointmentParams['notes'] = $appointment->notes;
                $newAppointmentParams['patient_flag_id'] = $appointment->patient->flag->name;

                $newAppointment = $this->appointmentRepository->create($newAppointmentParams);

                if($newAppointment) {
                    $newAppointment->status_id = 5;
                    $newAppointment->save();

                    $booking = new Booking;
                    $booking->code = $newAppointment->appointment_no;
                    $booking->type = 'APN';
                    $booking->date = $newAppointment->date;
                    $booking->save();
                }
            }

            DB::commit();
            return response()->json(array('status' => true, 'message' => trans('patient::alerts.prescription.created')));

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage()
                ], 500);
        }
    }

    public function updatePrescription(Prescription $prescription, $request)
    {
        return DB::transaction(function () use ($request, $prescription) {
            if (!empty($request->is_record) && $request->is_record == "on") {

                $recordMapping = \App\RecordMapping::updateOrCreate(
                    ['appointment_id' => $prescription->appointment_id],
                    [
                        'jenis_tindakan' => $request->jenis_tindakan,
                        'jenis_bahan' => $request->jenis_bahan,
                        'jumlah' => $request->jumlah,
                        'lokasi' => $request->lokasi,
                        'catatan' => $request->catatan,
                        'canvas' => $request->canvas,
                        'image' => $request->base64
                    ]);

            } else {
                $findRecord = \App\RecordMapping::where('appointment_id', $prescription->appointment_id)->first();
                if (!empty($findRecord)) {
                    $findRecord->delete();
                }
            }

            $update_prescription = $prescription->update([
                'complaint' => $request->complaint,
                'treatment_history' => $request->treatment_history
            ]);

            if ($update_prescription) {

                //SetArrayForBill
                $billProduct = array();
                $billProductPrice = array();
                $billService = array();
                $billServicePrice = array();
                $billProductQty = array();
                $billServiceQty = array();

                $totaloop = $request->product;
                $totaloopDiagnosis = $request->diagnosis;
                $totaloopService = $request->service;

                $saveObat = false;
                $saveTreatment = false;
                $saveDiagnosis = false;

                //renew data obat
                PrescriptionDetail::where('prescription_id', $prescription->id)->delete();
                foreach ($totaloop as $key => $val) { //$value->code.' - '.$value->name.' - '.$value->category->name
                    if ($request->product[$key]) {
                        $getProduct = Product::find($request->product[$key]);
                        $billProduct[] = $request->product[$key] . '#product';
                        $billProductPrice[] = $getProduct->price;
                        $billProductQty[] = $request->qty[$key];

                        $prescriptionDetail = PrescriptionDetail::firstOrNew([
                            'prescription_id' => $prescription->id,
                            'product_id' => $getProduct->id,
                            'name' => $getProduct->code . ' - ' . $getProduct->name . ' - ' . $getProduct->category->name,
                            'instruction' => $request->instruction[$key]
                        ]);

                        $saveObat = $prescriptionDetail->save();
                    }
                }

                //renew data treatment
                if ($totaloopService) {
                    PrescriptionTreatment::where('prescription_id', $prescription->id)->delete();
                    foreach ($totaloopService as $key3 => $val3) {
                        if ($request->service[$key3]) {
                            $getService = Service::find($request->service[$key3]);
                            $billService[] = $request->service[$key3] . '#service';
                            $billServicePrice[] = $getService->price;
                            $billServiceQty[] = $request->qty[$key];

                            $prescriptionService = PrescriptionTreatment::firstOrNew([
                                'prescription_id' => $prescription->id,
                                'services_id' => $getService->id,
                                'name' => $getService->code . ' - ' . $getService->name . ' - ' . $getService->category->name,
                                'description' => $request->servicenotes[$key3]
                            ]);

                            $saveTreatment = $prescriptionService->save();
                        }
                    }
                }

                //renew data diagnosis
                Diagnoses::where('appointment_id', $prescription->appointment_id)->delete();
                foreach ($totaloopDiagnosis as $key2 => $val2) {
                    if ($request->diagnosis[$key2]) {
                        $diagnoseItem = DiagnoseItem::find($request->diagnosis[$key2]);
                        if ($diagnoseItem) {
                            $diagnosis = Diagnoses::firstOrNew([
                                'appointment_id' => $prescription->appointment_id,
                                'diagnose_item_id' => $diagnoseItem->id,
                                'name' => $diagnoseItem->name,
                                'instruction' => $request->diaginstruction[$key2]
                            ]);

                            $saveDiagnosis = $diagnosis->save();
                        }
                    }
                }

                $date = \Carbon\Carbon::now();
                if ($saveObat || $saveTreatment) {

                    // renew data invoices detail
                    if ($prescription->billing->id) {
                        if ($prescription->billing->transaction_id == null || $prescription->billing->transaction_id < 0) {
                            return response()->json(array('status' => false, 'message' => 'Oopps, Data ini sudah tidak bisa di update'));
                        } else {

                            $_transaction = FinanceTransaction::findOrFail($prescription->billing->transaction_id);
                            $billing = $prescription->billing;
                            /** reverse and delete last finance process */
                            $_inv_detail = $billing->invDetail;
                            $_rev_point = 0;

                            // looping reverse product stock
                            foreach ($_inv_detail as $key => $val) {

                                //update stock obat
                                if ($val->type == 'product') {
                                    $_product = Product::findOrFail($val->product_id);
                                    $_product->quantity = $_product->quantity - $val->qty;

                                    $_rev_point = $_rev_point + $_product->point;
                                    if ($_product->quantity < $_product->min_stock) {
                                        $_product->is_min_stock = 1;
                                    }

                                    $_product->save();

                                } else {
                                    $_service = Service::findOrFail($val->product_id);
                                    $_rev_point = $_rev_point + $_service->point;
                                }
                            }

                            //reverse finance journal and account balance
                            foreach ($_transaction->journal as $key => $val) {
                                if ($val->type == config('finance_journal.types.debit')) {
                                    FinanceAccount::sumDebit($val->account_id, $val->value);
                                } else if (config('finance_journal.types.kredit')) {
                                    FinanceAccount::sumKredit($val->account_id, $val->value);
                                }
                            }

                            // deleting the journal and inv detail
                            $_transaction->journal()->delete();
                            $billing->invDetail()->delete();

                            // update point membership
                            if ($_rev_point > 0) {
                                $patient = Patient::findOrFail($billing->patient_id);
                                if ($patient->membership) {
                                    $patient->membership->total_point = $patient->membership->total_point - $_rev_point;
                                    $patient->membership->save();
                                }
                            }

                            /** End Of reverse and delete last finance process */

                            //CreateBillingData
                            $dataProduct = array(
                                'product' => array_merge($billProduct, $billService),
                                'price' => array_merge($billProductPrice, $billServicePrice),
                                'qty' => array_merge($billProductQty, $billServiceQty)
                            );

                            $storeInvoicePrescription = $this->billingRepository->storeInvoicePrescription($dataProduct, $billing, $_transaction);

                            if ($storeInvoicePrescription == false) {
                                return response()->json(array('status' => false, 'message' => 'Error Update Process'));
                            }

                        }

                    }
                }

                if ($request->next_appointment_date) {
                    $date = $request->next_appointment_date;
                    $date = \Carbon\Carbon::CreateFromFormat(setting()->get('date_format'), $date)->format('Y-m-d');

                    Appointment::where('id', $prescription->appointment_id)->update(
                        [
                            'next_appointment_date' => $date,
                            'next_appointment_notes' => $request->next_appointment_notes
                        ]
                    );
                }

                $timeline = PatientTimeline::where('title', 'Referensi #' . $prescription->appointment->appointment_no)->first();

                if (empty($timeline)) {
                    $timeline = new PatientTimeline;

                    $timeline->patient_id = $prescription->patient_id;
                    $timeline->title = 'Referensi #' . $prescription->appointment->appointment_no;
                    $timeline->timeline_date = $date;
                    $timeline->description = 'Konsultasi';
                    $timeline->save();
                }

                if ($request->has('inputFoto')) {

                    if (!empty($timeline)) {
                        foreach ($request->inputFoto as $image) {
                            if (substr($request->inputFoto[0], 0, 4) != 'http') {
                                $delete = PatientTimelineDetail::where('timeline_id', $timeline->id)->delete();
                                $timelineDetail = new PatientTimelineDetail;

                                $timelineDetail->timeline_id = $timeline->id;
                                $timelineDetail->setFileImage64($image);
                                $timelineDetail->save();
                            }
                        }

                        Prescription::where('id', $prescription->id)->update(['timeline_id' => $timeline->id]);
                    }

                    $input = $request->all();

                    foreach ($request->inputFoto as $index => $image) {
                        if (substr($request->inputFoto[0], 0, 4) != 'http') {
                            $delete = PatientBeforeAfter::whereDate('date', $timeline->date)->delete();
                            $patientBeforeAfter = new PatientBeforeAfter;

                            $patientBeforeAfter->patient_id = $prescription->patient_id;
                            $patientBeforeAfter->date = \Carbon\Carbon::now();
                            $patientBeforeAfter->type = @$input['tipe'][$index];
                            $patientBeforeAfter->setFileImage64($image);
                            $patientBeforeAfter->save();
                        }
                    }
                }

                return response()->json(array('status' => true, 'message' => trans('patient::alerts.prescription.updated')));
            }

            return response()->json(array('status' => false, 'message' => 'Error Update Process'));
        });
    }
}
