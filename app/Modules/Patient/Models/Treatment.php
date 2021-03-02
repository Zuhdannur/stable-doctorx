<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Patient\Models\TreatmentDetail;
use App\Modules\Patient\Models\Traits\Attribute\TreatmentAttribute;
use DB;

class Treatment extends Model
{
    use TreatmentAttribute;

	public $timestamps = false;

	protected $dates = ['date', 'created_at', 'updated_at', 'deleted_at'];

	public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->seq = $model::generateSeriesNumberWithPrefix('treatments','seq','1');
            $model->created_at = $model->freshTimestamp();
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
        });
    }

    public static function generateSeriesNumberWithPrefix($tableName = '', $autogenField = '', $autogenStart = '', $autogenPrefix = '')
    {
        $listFiledValues = DB::table($tableName)->select($autogenField)->whereDate('date', \Carbon\Carbon::today())->get();
        if ($listFiledValues->isEmpty())
        {
            $generatedAutogen = $autogenPrefix.$autogenStart;
            return $generatedAutogen;
        }
        elseif ($listFiledValues->isNotEmpty())
        {
            foreach($listFiledValues as $listFiledValue)
            {
                $eachListarray = $listFiledValue->$autogenField;
                $totalListArrays[] = $eachListarray;
            }
            foreach($totalListArrays as $totalListArray)
            {
                $stringRemovedEachListArray = substr($totalListArray,strlen($autogenPrefix));
                $stringRemovedTotalListArray[] = $stringRemovedEachListArray;
            }
            $maximumValue = max($stringRemovedTotalListArray);
            $generatedAutogen = $autogenPrefix.++$maximumValue;
            return $generatedAutogen;
        }
    }

	public function getDateFormatedAttribute()
	{
	    return \DateTime::createFromFormat('Y-m-d H:i:s', $this->attributes['date'])->format(setting()->get('date_format').'  H:i:s');
	}

    protected $fillable = [
    	'patient_id',
    	'treatment_no',
        'seq',
        'date',
        'end_time',
    	'room_id',
        'appointment_id',
        'staff_id',
        'status_id',
        'is_online',
        'service_id',
        'service_notes',
    	'notes',
        'id_klinik'
    ];

    public function patient()
    {
        return $this->hasOne('App\Modules\Patient\Models\Patient', 'id', 'patient_id');
    }

    public function staff()
    {
        return $this->hasOne('App\Modules\Humanresource\Models\Staff', 'id', 'staff_id');
    }

    public function room()
    {
        return $this->hasOne('App\Modules\Room\Models\Room', 'id', 'room_id');
    }

    public function status()
    {
        return $this->hasOne('App\Modules\Patient\Models\AppointmentStatus', 'id', 'status_id');
    }

    public function service()
    {
        return $this->hasOne('App\Modules\Product\Models\Service', 'id', 'service_id');
    }

    public function detail()
    {
        return $this->hasMany('App\Modules\Patient\Models\TreatmentDetail', 'treatment_id', 'id');
    }

    public function getDetail()
    {
        return TreatmentDetail::where('treatment_id',substr($this->treatment_no,2))->get();
    }
}
