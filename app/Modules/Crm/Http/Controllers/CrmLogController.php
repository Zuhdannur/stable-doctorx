<?php

namespace App\Modules\Crm\Http\Controllers;

use DataTables;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Attribute\Models\LogActivity;

class CrmLogController extends Controller
{
    public function index(Request $request)
    {
        // date for view
        $date_1 = \Carbon\Carbon::now()->subdays(30)->format('d/m/Y');     
        $date_2 = \Carbon\Carbon::now()->format('d/m/Y');

        if($request->isMethod('POST')){
            $date_1 = $request->date_1;
            $date_2 = $request->date_2;
        }

        if($request->ajax()){
            $start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1); 
            $end_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2);   

            $model = LogActivity::where('module_id', config('my-modules.crm'))
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->with('user')
                    ->orderBy('created_at','DESC');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->editColumn('user', function($data){
                return '';
            })
            ->addColumn('username', function($data){
                return $data->user->first_name.' '.$data->user->last_name;
            })
            ->make(true);
        }

        return view('crm::log.index')
        ->withDate(array('date_1' => $date_1, 'date_2' => $date_2));
    }
}
