<?php

namespace App\Http\Controllers;

use App\Modules\Billing\Models\Billing;
use App\Modules\Billing\Models\BillingDetail;
use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RekapPenjualanProdukController extends Controller
{
    public function index() {
        return view('pages.invoice.produk.rekap_produk_view');
    }

    public function getData(Request $request) {

        if(!empty($request->awal)) {
            $dateAwal =  \Carbon\Carbon::createFromFormat('d/m/Y', $request->awal)->format('Y-m-d').' 00:00:00';
            $dateAkhir =  \Carbon\Carbon::createFromFormat('d/m/Y', $request->akhir)->format('Y-m-d').' 23:59:59';
        } else {
            $dateAwal = "";
            $dateAkhir = "";
        }
        $model = Product::orderBy('id');

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('qty_sold', function ($data) use ($dateAwal , $dateAkhir) {
                $query = BillingDetail::whereBetween('created_at',[@$dateAwal,@$dateAkhir])
                    ->where('product_id',$data->id)
                    ->get();
                return count($query);
            })
            ->addColumn('retur',function ($data) {
                return 0;
            })
            ->addColumn('satuan',function ($data) {
                return "pcs";
            })
            ->addColumn('total_sold',function ($data) use ($dateAwal , $dateAkhir) {
                $query = BillingDetail::whereBetween('created_at',[@$dateAwal,@$dateAkhir])
                    ->where('product_id',$data->id)
                    ->get();

                $total = 0;
                foreach ($query as $item) {
                    $total += (intval($item->price) * intval($item->qty));
                }

                return currency()->rupiah($total, setting()->get('currency_symbol'));
            })
            ->addColumn('total_retur',function ($data) {
                return 0;
            })
            ->addColumn('rata_rata',function ($data) use ($dateAwal , $dateAkhir)  {
                $query = BillingDetail::whereBetween('created_at',[@$dateAwal,@$dateAkhir])
                    ->where('product_id',$data->id)
                    ->get();

                $total = 0;
                $qty = 0;
                foreach ($query as $item) {
                    $total += (intval($item->price) * intval($item->qty));
                    $qty += intval($item->qty);
                }

                if($total == 0 || $qty  == 0) {
                    return 0;
                }
                return currency()->rupiah($total / $qty, setting()->get('currency_symbol'));
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}
