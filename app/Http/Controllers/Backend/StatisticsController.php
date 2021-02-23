<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function showTraffict(Request $request)
    {
        if($request->isMethod('GET')){

            $date_widget = array(
                'start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::now()->format('Y-m-d'),
            );
            $filter = 8;

            $date = array(
                'date_1' => \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y'),
                'date_2' => \Carbon\Carbon::now()->format('d/m/Y'),
            );

        }else if($request->isMethod('Post')){
            $date_widget = array(
                'start_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2)->format('Y-m-d'),
            );

            $filter = $request->filters;
            $date = array(
                'date_1' => $request->date_1,
                'date_2' => $request->date_2,
            );
        }

        return view('backend.statistics.traffict')
        ->withDateWidget($date_widget)
        ->withFilter($filter)
        ->withDate($date);
    }

    public function showRevenue(Request $request)
    {
        if($request->isMethod('GET')){

            $filter = 8;
            $date_widget = array(
                'start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::now()->format('Y-m-d'),
            );
    
            $date = array(
                'date_1' => \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y'),
                'date_2' => \Carbon\Carbon::now()->format('d/m/Y'),
            );

        }else if($request->isMethod('Post')){
            $filter = $request->filters;
            $date_widget = array(
                'start_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2)->format('Y-m-d'),
            );

            $date = array(
                'date_1' => $request->date_1,
                'date_2' => $request->date_2,
            );
        }

        return view('backend.statistics.revenue')
            ->withDateWidget($date_widget)
            ->withFilter($filter)
            ->withDate($date);
    }

    public function showDemografi(Request $request)
    {
        if($request->isMethod('GET')){

            $filter = 8;
            $date_widget = array(
                'start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::now()->format('Y-m-d'),
            );
    
            $date = array(
                'date_1' => \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y'),
                'date_2' => \Carbon\Carbon::now()->format('d/m/Y'),
            );

        }else if($request->isMethod('Post')){
            $filter = $request->filters;
            $date_widget = array(
                'start_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2)->format('Y-m-d'),
            );

            $date = array(
                'date_1' => $request->date_1,
                'date_2' => $request->date_2,
            );
        }

        return view('backend.statistics.demografi')
        ->withDateWidget($date_widget)
        ->withFilter($filter)
        ->withDate($date);
    }

    public function showMarketing(Request $request)
    {
        if($request->isMethod('GET')){

            $date_widget = array(
                'start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::now()->format('Y-m-d'),
            );
            $filter = 8;

            $date = array(
                'date_1' => \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y'),
                'date_2' => \Carbon\Carbon::now()->format('d/m/Y'),
            );

        }else if($request->isMethod('Post')){
            $date_widget = array(
                'start_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2)->format('Y-m-d'),
            );

            $filter = $request->filters;
            $date = array(
                'date_1' => $request->date_1,
                'date_2' => $request->date_2,
            );
        }

        return view('backend.statistics.marketing')
        ->withDateWidget($date_widget)
        ->withFilter($filter)
        ->withDate($date);
    }

    public function showMembership(Request $request)
    {
        if($request->isMethod('GET')){

            $filter = 8;
            $date_widget = array(
                'start_date' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::now()->format('Y-m-d'),
            );
    
            $date = array(
                'date_1' => \Carbon\Carbon::now()->startOfMonth()->format('d/m/Y'),
                'date_2' => \Carbon\Carbon::now()->format('d/m/Y'),
            );

        }else if($request->isMethod('Post')){
            $filter = $request->filters;
            $date_widget = array(
                'start_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_1)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_2)->format('Y-m-d'),
            );

            $date = array(
                'date_1' => $request->date_1,
                'date_2' => $request->date_2,
            );
        }
        
        return view('backend.statistics.membership')
            ->withDateWidget($date_widget)
            ->withFilter($filter)
            ->withDate($date);
    }
}
