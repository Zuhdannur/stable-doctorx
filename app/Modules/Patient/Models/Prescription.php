<?php

namespace App\Modules\Patient\Models;

use App\RecordMapping;
use Illuminate\Database\Eloquent\Model;
use PDF;

class Prescription extends Model
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
        });
    }

    protected $fillable = [
    	'patient_id',
    	'appointment_id',
    	'service_id',
        'notes',
        'complaint',
        'treatment_history',
        'inform_concern',
    	'created_by',
    ];

    public function appointment()
    {
        return $this->hasOne('App\Modules\Patient\Models\Appointment', 'id', 'appointment_id');
    }

    public function service()
    {
        return $this->hasOne('App\Modules\Product\Models\Service', 'id', 'service_id');
    }

    public function detail()
    {
        return $this->hasMany('App\Modules\Patient\Models\PrescriptionDetail', 'prescription_id', 'id');
    }

    public function treatment()
    {
        return $this->hasMany('App\Modules\Patient\Models\PrescriptionTreatment', 'prescription_id', 'id');
    }

    public function treatmentProcess()
    {
        return $this->hasOne('App\Modules\Patient\Models\Treatment', 'appointment_id', 'appointment_id');
    }

    public function billing()
    {
        return $this->hasOne('App\Modules\Billing\Models\Billing', 'appointment_id', 'appointment_id');
    }

    public function timeline()
    {
        return $this->hasOne('App\Modules\Patient\Models\PatientTimeline', 'id', 'timeline_id');
    }

    public function createInformConcent($patient){
        $templatePath = config('patient.template_inform_concern');
        $imagePath = config('patient.inform_concern');
        $filename = 'INFORM_CONCENT.docx';
        $imageDirectory = public_path() . '/' . $templatePath.'/'.$filename;
        $relativePath = $templatePath.'/'.$filename;
        $newRelativePath = $imagePath.'/'.$this->id.date('mdYHis') . uniqid() .'.docx';
        $absolutePath = public_path($relativePath);
        $newAbsolutePath = public_path($newRelativePath);

        if (!file_exists($absolutePath)) {
            return false;
        }

        $convertDate = \Carbon\Carbon::CreateFromFormat(setting()->get('date_format'), $patient->dob)->translatedFormat('jS F Y');

        $template = new \PhpOffice\PhpWord\TemplateProcessor($imageDirectory);
        $template->setValue('nama', $patient->patient_name);
        $template->setValue('tanggallahir', $patient->age.' / '.$convertDate);
        $template->setValue('jeniskelamin', ($patient->gender == 'F') ? 'Perempuan' : 'Laki - laki');
        $template->setValue('alamat', $patient->address);
        $template->setValue('telepon', $patient->phone_number);
        $template->setValue('clinic', setting()->get('app_name'));
        $template->setValue('kota', trim(setting()->get('city_name')));
        $template->setValue('tanggal', trim(timezone()->convertToLocal(\Carbon\Carbon::now(), 'date')));

        $template->setValue('tandatangan', $patient->patient_name);
        /*$template->setImg('tandatangan', array(
            'src'  => asset(setting()->get('logo_square')),
            'size' => array( 102, 40 ) //px
        ));*/

        try {
            $template->saveAs($newRelativePath);
        } catch (Exception $e) {

        }

        if (file_exists($newAbsolutePath)) {
            return $newRelativePath;
        }

        return false;
    }

    public function generatePDFInformConcern($patient, $postdata){
        $imagePath = config('patient.inform_concern');
        $newRelativePath = $imagePath.$patient->appointment_id.'/'.$this->id.date('mdYHis') . uniqid() .'.pdf';
        $newAbsolutePath = public_path($newRelativePath);

        $convertDate = \Carbon\Carbon::parse($patient->dob)->translatedFormat('jS F Y');

        $data = array(
            'patient' => $patient,
            'data' => $postdata,
            'date' => $convertDate
        );
        // die(json_encode($data['patient']->appointment[0]->appointment_no));
        $pdf = PDF::loadView('patient::appointment.inform-concern', compact('data'));

        try {
            $pdf->save($newAbsolutePath);
        } catch (Exception $e) {

        }

        if (file_exists($newAbsolutePath)) {
            return $newRelativePath;
        }

        return false;
    }

    public function patient() {
        return $this->hasOne('App\Modules\Patient\Models\Patient','id','patient_id');
    }

    public function mapping() {
        return $this->hasOne(RecordMapping::class,'appointment_id','appointment_id');
    }
}
