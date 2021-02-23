<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('backend.dashboard');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function statistik(Request $request)
    {

        if($request->isMethod('GET')){

            $date_widget = array(
                'start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::now()->format('Y-m-d'),
            );

            $date = array(
                'date_1' => \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y'),
                'date_2' => \Carbon\Carbon::now()->format('d/m/Y'),
            );

        }else if($request->isMethod('Post')){
            $date_widget = array(
                'start_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2)->format('Y-m-d'),
            );

            $date = array(
                'date_1' => $request->date_1,
                'date_2' => $request->date_2,
            );
        }

        return view('backend.statistik')
        ->withDateWidget($date_widget)
        ->withDate($date);
    }
}
