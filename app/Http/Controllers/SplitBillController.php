<?php

namespace App\Http\Controllers;

use App\Modules\Billing\Models\Billing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use function foo\func;

class SplitBillController extends Controller
{
    public function index() {
        $model = Billing::whereHas('patient',function ($query){
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->where('status','3')->with('patient')->orderBy('created_at', 'desc');
        $now = Carbon::now();
        $data['all'] = count($model->get());
        $data['today'] = count($model->whereDate('date',$now->format('Y-m-d'))->get());

        return view('pages.split.index')->with($data);
    }

    public function getData(Request $request) {
        $model = Billing::whereHas('patient',function ($query){
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->where('status','3')->with('patient')->orderBy('created_at', 'desc');

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('patient_name', function ($data) {
                $patient = $data->patient;
                return $patient->patient_name;
            })
            ->addColumn('status', function ($data) {
                $status = '';
                switch ($data->status) {
                    case 0 :
                        $status = '<span class="badge badge-danger">'.config('billing.unpaid').'</span>' ;
                        break;
                    case 1 :
                        $status = '<span class="badge badge-success">'.config('billing.paid').'</span>' ;
                        break;
                    case 3 :
                        $status = '<span class="badge badge-warning">'.config('billing.partial_paid').'</span>' ;
                        break;
                }
                return $status;
            })
            ->addColumn('sudah_dibayar',function ($data) {
                $dikurang = (int)$data->in_paid - (int)$data->remaining_payment;
                return currency()->rupiah($dikurang, setting()->get('currency_symbol'));
            })
            ->addColumn('total', function ($data) {
                $total = $data->totalPrice;
                return currency()->rupiah($total, setting()->get('currency_symbol'));
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

}
