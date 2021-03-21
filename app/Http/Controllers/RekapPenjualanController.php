<?php

namespace App\Http\Controllers;

use App\Modules\Billing\Models\Billing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RekapPenjualanController extends Controller
{
    public function index() {
        return view('pages.invoice.invoice_view');
    }

    public function getData(Request $request) {

        $input = $request->all();

        if(!empty($request->awal)) {
            $dateAwal =  \Carbon\Carbon::createFromFormat('d/m/Y', $request->awal)->format('Y-m-d');
            $dateAkhir =  \Carbon\Carbon::createFromFormat('d/m/Y', $request->akhir)->format('Y-m-d');
        }
        $model = Billing::whereHas('patient',function ($query) {
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->whereBetween('date',[@$dateAwal,@$dateAkhir])
            ->with('patient')
            ->orderBy('created_at', 'desc');
        $data = new Collection;

        $start = $input['start'];
        $total = 0;

        foreach ($model->get() as $item) {
            $status = '';
            switch ($item->status) {
                case 0 :
                    $status = '<span class="badge badge-danger">Belum Lunas</span>' ;
                    break;
                case 1 :
                    $status = '<span class="badge badge-success">Lunas</span>' ;
                    break;
                case 3 :
                    $status = '<span class="badge badge-warning">Sebagian</span>' ;
                    break;
            }
            $total += $item->totalPrice;
            $data->push([
                "DT_RowIndex" => ++$start,
                "invoice_no" => $item->invoice_no,
                "patient_name" => $item->patient->patient_name,
                "qty" => $item->qty,
                "status" => $status,
                "note" => $item->note,
                "total" => currency()->rupiah($item->totalPrice, setting()->get('currency_symbol')),
                "action" => $item->action_buttons
            ]);

        }

        $data->push([
            "DT_RowIndex" => "",
            "invoice_no" => "",
            "patient_name" => "",
            "qty" => "",
            "status" => "",
            "note" => "Total",
            "total" => currency()->rupiah($total, setting()->get('currency_symbol')),
            "action" => ""
        ]);

//        return DataTables::eloquent($model)
//            ->addIndexColumn()
//            ->addColumn('patient_name', function ($data) {
//                $patient = $data->patient;
//                return $patient->patient_name;
//            })
//            ->addColumn('qty',function ($data) {
//                return $data->qty;
//            })
//            ->addColumn('status', function ($data) {
//                $status = '';
//                switch ($data->status) {
//                    case 0 :
//                        $status = '<span class="badge badge-danger">Belum Lunas</span>' ;
//                        break;
//                    case 1 :
//                        $status = '<span class="badge badge-success">Lunas</span>' ;
//                        break;
//                    case 3 :
//                        $status = '<span class="badge badge-warning">Sebagian</span>' ;
//                        break;
//                }
//                return $status;
//            })
//            ->addColumn('sudah_dibayar',function ($data) {
//                $dikurang = (int)$data->in_paid - (int)$data->remaining_payment;
//                return currency()->rupiah($dikurang, setting()->get('currency_symbol'));
//            })
//            ->addColumn('total', function ($data) {
//                $total = $data->totalPrice;
//                return currency()->rupiah($total, setting()->get('currency_symbol'));
//            })
//            ->addColumn('action', function ($data) {
//                $button = $data->action_buttons;
//                return $button;
//            })
//            ->editColumn('id', '{{$id}}')
//            ->rawColumns(['action', 'status'])
//            ->make(true);

        return DataTables::of($data)
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}
