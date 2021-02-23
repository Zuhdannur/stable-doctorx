<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['namespace' => 'Backend', 'prefix' => 'indonesia', 'as' => 'indonesia.', 'middleware' => 'admin'], function () {
    Route::get('/', function () {
        dd('This is the Indonesia module index page. Build something great!');
    });
});

Route::get('/cities', function (Request $request) {
    $city = new App\Modules\Indonesia\Models\City;
    $data = $city->getCitySel22($request->input('search'));
    return response()->json($data);
});

Route::get('/citiesProv/{city_id}', function (Request $request) {
    $city = new App\Modules\Indonesia\Models\City;
    $data = $city->getCityProv($this->input('city_id'));
    return response()->json($data);
});