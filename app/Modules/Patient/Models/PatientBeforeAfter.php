<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;
use Str;
use URL;

class PatientBeforeAfter extends Model
{
    public $timestamps = false;

    protected $dates = ['created_at', 'updated_at'];

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
        'type',
        'date',
        'image'
    ];

    public function uploadBase64($image, $prefix){
        $filename = $this->id.$prefix.date('mdYHis') . uniqid() .'.jpg';
        
        $imagePath = config('patient.beforeafter_path');

        // Image Directory
        $imageDirectory = public_path() . '/' . $imagePath;
            
        // Paths
        $relativePath = $imagePath.'/'.$filename;
        $absolutePath = public_path($relativePath);
        // die($relativePath);
        if($image != ""){
            // storing image in storage/app/public Folder
            \Image::make(file_get_contents($image))->save($relativePath);   

            if (file_exists($absolutePath)) {
                return $relativePath;
            }
        }
    }

    public function setFileImage64($image, $prefix = 'before')
    {
        $this->image = $this->uploadBase64($image, $prefix);
    }
}
