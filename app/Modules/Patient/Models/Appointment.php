<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Patient\Models\Traits\Attribute\AppointmentAttribute;
use DB;

class Appointment extends Model
{
    use AppointmentAttribute;

	public $timestamps = false;

	protected $dates = ['date', 'created_at', 'updated_at', 'deleted_at', 'next_appointment_date'];

	public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->seq = $model::generateSeriesNumberWithPrefix('appointments','seq','1');
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
        'seq',
    	'appointment_no',
    	'appointment_type_id',
    	'date',
    	'room_id',
        'staff_id',
        'is_online',
    	'status_id'
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

    public function diagnoses()
    {
        return $this->hasMany('App\Modules\Patient\Models\Diagnoses', 'appointment_id', 'id');
    }

    public function prescription()
    {
        return $this->hasOne('App\Modules\Patient\Models\Prescription', 'appointment_id', 'id');
    }

    public function billing()
    {
        return $this->hasOne('App\Modules\Billing\Models\Billing', 'appointment_id', 'id');
    }

    public static function generateUnique($typeId)
    {
        $code = 'APN';

        $count = self::whereDate('created_at', \Carbon\Carbon::today())->where('admission_type_id', $typeId)->count();

        $newQueueId = $code . str_pad($count + 1, 3, 0, STR_PAD_LEFT);

        return $newQueueId;
    }
}
