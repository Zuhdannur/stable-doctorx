<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;
use Str;
use URL;
use Illuminate\Http\UploadedFile;

class PatientTimelineDetail extends Model
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
    	'timeline_id', 
        'document',
    ];

    public function upload($image, $prefix){
        $imagePath = config('patient.document_path');
            $image_path = $this->$prefix;  // Value is not URL but directory file path
            if(\File::exists($image_path)) {
                \File::delete($image_path);
            }

            $filename = $this->id.$prefix.date('mdYHis') . uniqid().'.'.strtolower($image->getClientOriginalExtension());

            // Image Directory
            $imageDirectory = public_path() . '/' . $imagePath;
            
            // Paths
            $relativePath = $imagePath.'/'.$filename;
            $absolutePath = public_path($relativePath);
            // die($absolutePath);
            $image->move($imageDirectory, $filename);

            $img = \Image::make($absolutePath);

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($absolutePath);

        if (file_exists($absolutePath)) {
            return $relativePath;
        }
    }

    public function uploadBase64($image, $prefix){
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        
        $imagePath = config('patient.document_path');
        
        $filename = $this->id.$prefix.date('mdYHis') . uniqid().'.png';

        // Image Directory
        $imageDirectory = public_path() . '/' . $imagePath;
            
        // Paths
        $relativePath = $imagePath.'/'.$filename;
        $absolutePath = public_path($relativePath);
        // die($relativePath);
        if($image != ""){
            // storing image in storage/app/public Folder
            \File::put($absolutePath, base64_decode($image));

            if (file_exists($absolutePath)) {
                return $relativePath;
            }
        }
    }

    public function setFile(UploadedFile $image, $prefix = 'document')
    {
        $this->document = $this->upload($image, $prefix);
    }

    public function setFileImage64($image, $prefix = 'document')
    {
        $this->document = $this->uploadBase64($image, $prefix);
    }
}
