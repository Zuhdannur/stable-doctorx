<?php

namespace App\Modules\Core\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class GeneralSettingController extends Controller
{
    public function index(){
    	return view('core::setting.general');
    }

    public function store(Request $request){
    	$input = $request->all();

    	setting($input)->save();
    	
    	$array = array('status' => true, 'message' => 'OK');
        return json_encode($array);
    }

    public function storeImage(Request $request){
    	$key = $request->input('key');
    	$image = $request->file('file');
    	
        if($image){
            $image_path = setting()->get($key);  // Value is not URL but directory file path
            if(\File::exists($image_path)) {
                \File::delete($image_path);
            }

            $filename = date('mdYHis') . uniqid().'.'.strtolower($image->getClientOriginalExtension());

            // Image Directory
            $imageDirectory = public_path() . '/' . config('core.global_media');
            
            // Paths
            $relativePath = config('core.global_media').'/'.$filename;

            $absolutePath = public_path($relativePath);
            // die($absolutePath);
            $image->move($imageDirectory, $filename);

            $img = \Image::make($absolutePath);

            $img->resize(250, 250, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($absolutePath);

            if (file_exists($absolutePath)) {
                setting([$key => $relativePath])->save();
            }
        }

        return response()->json([
        	'status' => true,
        	'message' => 'OK',
        	'filename' => $relativePath
        ]);
    }
}
