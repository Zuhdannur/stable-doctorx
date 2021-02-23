<?php

namespace App\Http\Controllers;

use App\Modules\Accounting\Models\FinanceTransaction;
use App\Modules\Billing\Models\Billing;
use App\Modules\Patient\Models\AppointmentInvoice;
use App\Modules\Patient\Models\Patient;
use App\Modules\Product\Models\Product;
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
        $model = Product::orderBy('code');
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('category', function ($data) {
                return $data->category->name;
            })
            ->addColumn('rata-rata', function ($data) {
                $query = Billing::where('status',1)->whereHas('invDetail',function ($query) use ($data) {
                    $query->where('product_id',$data->id);
                })->get();
                $total = 0;
//
                foreach ($query as $row) {
                    foreach (@$row->invDetail as $item) {
                        $total += ($item->price * $item->qty);
                    }
                }
                if($total > 0 ) {
                    $total = $total / count($query);
                }
                return currency()->rupiah($total, setting()->get('currency_symbol'));
            })
            ->make(true);
    }
}
