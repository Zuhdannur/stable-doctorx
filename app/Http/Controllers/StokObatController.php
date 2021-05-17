<?php

namespace App\Http\Controllers;

use App\Modules\Accounting\Models\FinanceTransaction;
use App\Modules\Billing\Models\Billing;
use App\Modules\Patient\Models\AppointmentInvoice;
use App\Modules\Patient\Models\Patient;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StokObatController extends Controller
{
    public function index() {

        $query = Product::all();

        $data['ready'] = 0;
        $data['min'] = 0;
        $data['habis'] = 0;

        foreach ($query as $item) {
            if($item->quantity > 10) {
                $data['ready'] += 1;
            }

            if($item->quantity > 0 && $item->quantity <= 10) {
                $data['min'] += 1;
            }

            if($item->quantity == 0 ) {
                $data['habis'] += 1;
            }
        }

        return view('pages.stok.daftar_stok')->with($data);
    }

    public function getData(Request $request) {
        $data = new Collection;

        $model = Product::orderBy('code');
        $no = 0;
        foreach ($model->get() as $index => $row) {

            $query = Billing::where('status',1)->whereHas('invDetail',function ($query) use ($row) {
                $query->where('product_id',$row->id);
            })->orderBy('date','desc')->get();
            $total = 0;
            $qty = 0;

            foreach ($query as $rowDetail) {
                foreach (@$rowDetail->invDetail as $item) {
                    $total += ($item->price * $item->qty);
                    $qty += $item->qty;
                }
            }
            if($total > 0 ) {
                $total = $total / count($query);
            }

            $last_buy = 0;

            if(!empty($query[0])) {
                foreach ($query[0]->invDetail as $value) {
                    $last_buy += ($value->price * $value->qty);
                }
            }


            if($total != 0) {
                $data->push([
                    "DT_RowIndex" => ++$no,
                    "name" => $row->name,
                    "category" => $row->category->name,
                    "rata-rata" => currency()->rupiah($total, setting()->get('currency_symbol')),
                    "qty" => $qty,
                    "unit" => "pcs",
                    "last_buy" => currency()->rupiah($last_buy, setting()->get('currency_symbol')),
                    "harga_beli" => currency()->rupiah($row->price, setting()->get('currency_symbol')),
                ]);
            }

        }
        return DataTables::of($data)
//            ->rawColumns(['action'])
            ->make(true);

//        return DataTables::eloquent($model)
//            ->addIndexColumn()
//            ->addColumn('category', function ($data) {
//                return $data->category->name;
//            })
//            ->addColumn('rata-rata', function ($data) {
//                $query = Billing::where('status',1)->whereHas('invDetail',function ($query) use ($data) {
//                    $query->where('product_id',$data->id);
//                })->get();
//                $total = 0;
////
//                foreach ($query as $row) {
//                    foreach (@$row->invDetail as $item) {
//                        $total += ($item->price * $item->qty);
//                    }
//                }
//                if($total > 0 ) {
//                    $total = $total / count($query);
//                }
//                return currency()->rupiah($total, setting()->get('currency_symbol'));
//            })
//            ->make(true);
    }
}
