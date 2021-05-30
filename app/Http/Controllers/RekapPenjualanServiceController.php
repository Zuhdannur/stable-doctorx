<?php

namespace App\Http\Controllers;

use App\Modules\Billing\Models\BillingDetail;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Yajra\DataTables\Facades\DataTables;

class RekapPenjualanServiceController extends Controller
{
    public function index() {
        return view('pages.invoice.service.rekap_service_view');
    }

    public function getData(Request $request) {
        $input = $request->all();
        if(!empty($request->awal)) {
            $dateAwal =  \Carbon\Carbon::createFromFormat('d/m/Y', $request->awal)->format('Y-m-d').' 00:00:00';
            $dateAkhir =  \Carbon\Carbon::createFromFormat('d/m/Y', $request->akhir)->format('Y-m-d').' 23:59:59';
        } else {
            $dateAwal = "";
            $dateAkhir = "";
        }
        $model = Service::where('id_klinik',Auth()->user()->klinik->id_klinik)->orderBy('id');

        if(!empty($request->category) && $request->category != "semua") {
            $model = $model->where('category_id',$request->category);
        }

        $model = $model->orderBy('id');

        $data = new Collection;

        $start = $input['start'];
        $totalRecap = 0;
        foreach ($model->get() as $row) {
            $sold = BillingDetail::whereBetween('created_at',[@$dateAwal,@$dateAkhir])
                ->where('product_id',$row->id)
                ->where('type','service')
                ->get();

            $total = 0;
            $qty = 0;
            foreach ($sold as $item) {
                $total += (intval($item->price) * intval($item->qty));
                $qty += intval($item->qty);
            }

            $rata_rata = "0";
            if($total != 0 && $qty  != 0) {
                $rata_rata = currency()->rupiah($total / $qty, setting()->get('currency_symbol'));
            }

//            if(!empty($request->filter) && $request->filter != "semua") {
//
//            } else {
//                $data->push([
//                    "DT_RowIndex" => ++$start,
//                    "name" => $row->name,
//                    "qty_sold" => count($sold),
//                    "retur" => 0,
//                    "satuan" => "pcs",
//                    "total_sold" => currency()->rupiah($total, setting()->get('currency_symbol')),
//                    "total_retur" => 0,
//                    "stok" => $row->quantity,
//                    "rata_rata" => $rata_rata,
//                    "action" => $row->action_buttons
//                ]);
//            }

            if($rata_rata != "0")  {
                $data->push([
                    "DT_RowIndex" => ++$start,
                    "name" => $row->name,
                    "qty_sold" => count($sold),
                    "retur" => 0,
                    "satuan" => "pcs",
                    "total_sold" => currency()->rupiah($total, setting()->get('currency_symbol')),
                    "total_retur" => 0,
                    "stok" => $row->quantity,
                    "rata_rata" => $rata_rata,
                    "action" => $row->action_buttons
                ]);
            }

            $totalRecap += $total;
        }

        $data->push([
            "DT_RowIndex" => "",
            "name" => "Total",
            "qty_sold" => "",
            "retur" => "",
            "satuan" => "",
            "total_sold" => currency()->rupiah($totalRecap, setting()->get('currency_symbol')),
            "total_retur" => "",
            "stok" => "",
            "rata_rata" => "",
            "action" => "",
        ]);

        return DataTables::of($data)
            ->rawColumns(['action'])
            ->make(true);

        //  return DataTables::eloquent($model)
        //     ->addIndexColumn()
        //     ->addColumn('qty_sold', function ($data) use ($dateAwal , $dateAkhir) {
        //         $query = BillingDetail::whereBetween('created_at',[@$dateAwal,@$dateAkhir])
        //             ->where('product_id',$data->id)
        //             ->get();
        //         return count($query);
        //     })
        //     ->addColumn('retur',function ($data) {
        //         return 0;
        //     })
        //     ->addColumn('satuan',function ($data) {
        //         return "pcs";
        //     })
        //     ->addColumn('total_sold',function ($data) use ($dateAwal , $dateAkhir) {
        //         $query = BillingDetail::whereBetween('created_at',[@$dateAwal,@$dateAkhir])
        //             ->where('product_id',$data->id)
        //             ->get();

        //         $total = 0;
        //         foreach ($query as $item) {
        //             $total += (intval($item->price) * intval($item->qty));
        //         }

        //         return currency()->rupiah($total, setting()->get('currency_symbol'));
        //     })
        //     ->addColumn('total_retur',function ($data) {
        //         return 0;
        //     })
        //     ->addColumn('rata_rata',function ($data) use ($dateAwal , $dateAkhir)  {
        //         $query = BillingDetail::whereBetween('created_at',[@$dateAwal,@$dateAkhir])
        //             ->where('product_id',$data->id)
        //             ->get();

        //         $total = 0;
        //         $qty = 0;
        //         foreach ($query as $item) {
        //             $total += (intval($item->price) * intval($item->qty));
        //             $qty += intval($item->qty);
        //         }

        //         if($total == 0 || $qty  == 0) {
        //             return 0;
        //         }
        //         return currency()->rupiah($total / $qty, setting()->get('currency_symbol'));
        //     })
        //     ->editColumn('id', '{{$id}}')
        //     ->rawColumns(['action', 'status'])
        //     ->make(true);
    }
}
