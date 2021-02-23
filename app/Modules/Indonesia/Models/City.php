<?php

namespace App\Modules\Indonesia\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $table = 'indonesia_cities';

    public function getCitySel2($city = null){
        $data = City::select('indonesia_cities.id', 'indonesia_cities.name as text', 'indonesia_provinces.name as value')
                    ->leftJoin('indonesia_provinces', 'indonesia_provinces.id', '=', 'indonesia_cities.province_id');
                    if($city){
                    	$data = $data->where('indonesia_cities.name', 'like', '%' . $city . '%');
                    }
                    $data = $data->orderBy('indonesia_cities.name', 'asc')->get();
        return $data;
    }

    public function getCityProv($city = null){
        $data = City::select('indonesia_cities.id', 'indonesia_cities.name as text', 'indonesia_provinces.name as value')
                    ->leftJoin('indonesia_provinces', 'indonesia_provinces.id', '=', 'indonesia_cities.province_id');
                    if($city){
                    	$data = $data->where('indonesia_cities.id', $city);
                    }
                    $data = $data->orderBy('indonesia_cities.name', 'asc')->first();
        return $data;
    }

    public function getCitySel22($city = null, $page = 1){
	    $resultCount = 25;

	    $offset = ($page - 1) * $resultCount;

	    $data = City::select('indonesia_cities.id', 'indonesia_cities.name as city', 'indonesia_provinces.name as province', \DB::raw('CONCAT(indonesia_cities.name, ", ", indonesia_provinces.name) AS full_name'))
                    ->leftJoin('indonesia_provinces', 'indonesia_provinces.id', '=', 'indonesia_cities.province_id');
        if($city){
        	$data = $data->where('indonesia_cities.name', 'like', '%' . $city . '%');
        }
        $count = $data->count();
        
        $data = $data->orderBy('indonesia_cities.name', 'asc')->skip($offset)->take($resultCount)->get();

	    $endCount = $offset + $resultCount;
	    $morePages = $endCount > $count;

	    $results = array(
	    	"items" 				=> $data,
	    	"incomplete_results"	=> $morePages,
	    	"total_count" 			=> $count
	    );
        return $results;
    }
}
