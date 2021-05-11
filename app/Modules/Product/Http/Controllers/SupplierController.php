<?php

namespace App\Modules\Product\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Product\Http\Requests\Supplier\StoreSupplierRequest;
use App\Modules\Product\Repositories\SupplierRepository;

use App\Modules\Accounting\Models\FinanceTransaction;
use App\Modules\Product\Models\Supplier;
use Yajra\Datatables\Datatables;

class SupplierController extends Controller
{
    protected $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository) {
        $this->supplierRepository = $supplierRepository;
    }

    public function index(Datatables $datatables)
    {
        if ($datatables->getRequest()->ajax()) {

            return $datatables->of($this->supplierRepository->where('id_klinik',auth()->user()->id_klinik)->get())
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = $data->detail_button;
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('product::supplier.index');
    }

    public function create()
    {
        return view('product::supplier.create');
    }

    public function edit(Supplier $supplier)
    {
        return view('product::supplier.edit')
        ->withSupplier($supplier);
    }

    public function store(StoreSupplierRequest $request)
    {
        if (auth()->user()->cannot('create supplier')) {
            return response()->json(['message' => trans('exceptions.cannot_create')], 401);
        }

        $save = $this->supplierRepository->create($request->input());

        if($save){
        	$status = true;
	        $message = __('product::alerts.supplier.created');
        }else{
        	$status = false;
	        $message = trans('product::exceptions.supplier.create_error');
	    }

        return response()->json(array('status' => $status, 'message' => $message));
    }

    public function update(Request $request)
    {
        if (auth()->user()->cannot('update supplier')) {
            return response()->json(['message' => trans('exceptions.cannot_update')], 401);
        }

        $update = $this->supplierRepository->update($request->input());


        if($update){
            $status = true;
	        $message = __('product::alerts.supplier.updated');
        }else{
            $status = false;
	        $message = trans('product::exceptions.supplier.updated_error');
	    }

        return response()->json(array('status' => $status, 'message' => $message));
    }

    public function destroy(Supplier $supplier)
    {
        if($supplier->delete()){

            return redirect()->route('admin.product.supplier')->withFlashSuccess(__('product::alerts.supplier.deleted'));
        }

        return redirect()->route('admin.product.supplier')->withFlashSuccess(__('product::exceptions.supplier.delete_error'));

    }
    public function show(Supplier $supplier, Datatables $datatables)
    {
        if($datatables->getRequest()->ajax()){
                $model = FinanceTransaction::where('person_type' , config('finance_trx.person_type.supplier'))
                ->where('person_id', $supplier->id)
                ->with('purchase')
                ->orderBy('trx_date', 'desc')
                ->orderBy('id', 'desc');

                return $datatables->eloquent($model)
                ->editColumn('trx_date', function($data){
                    return \Carbon\Carbon::createFromFormat('Y-m-d', $data->trx_date)->format('d/m/Y');
                })
                ->editColumn('purchase.remain_payment', function($data){
                    return currency()->rupiah($data->purchase->remain_payment, setting()->get('currency_symbol'));
                })
                ->editColumn('purchase.total', function($data){
                    return currency()->rupiah($data->purchase->total, setting()->get('currency_symbol'));
                })
                ->make(true);
        }

        // sum total tagihan
        $tagihan =  $model = FinanceTransaction::where('person_type' , config('finance_trx.person_type.supplier'))
        ->where('person_id', $supplier->id)
        ->with('purchase')
        ->whereHas('purchase', function($q){
            return $q->where('status', '!=', 1); //belum lunas
        })
        ->get()
        ->sum('purchase.remain_payment');

        // sum purchase < 30 days from now
        $last_30_day = \Carbon\Carbon::now()->subDay(15)->format('Y-m-d');
        $purchase_30days = FinanceTransaction::where('person_type' , config('finance_trx.person_type.supplier'))
        ->where('person_id', $supplier->id)
        ->whereHas('purchase', function($q){
            return $q->where('status', 1); // lunas
        })
        ->where('trx_date', '>', $last_30_day)
        ->with('purchase')
        ->get()
        ->sum('purchase.total');

        return view('product::supplier.show')
        ->withTagihan(currency()->rupiah($tagihan, setting()->get('currency_symbol') ))
        ->withPurchase30Days(currency()->rupiah($purchase_30days, setting()->get('currency_symbol') ))
        ->withSupplier($supplier);
    }
}
