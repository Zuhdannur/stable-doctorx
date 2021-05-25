<?php

namespace App\Modules\Product\Http\Controllers;

use App\Helpers\Auth\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Http\Requests\Product\ManageProductRequest;
use App\Modules\Product\Http\Requests\Product\StoreProductRequest;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductCategory;
use App\Modules\Accounting\Models\FinanceAccount;

use App\Modules\Product\Imports\ProductImport;
use App\Modules\Product\Imports\ProductExport;
use Yajra\Datatables\Datatables;

class ProductController extends Controller
{
	protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Datatables $datatables)
    {
    	if ($datatables->getRequest()->ajax()) {

    	    $input = $datatables->getRequest();

    	    $model = $this->productRepository->where('id_klinik',Auth()->user()->klinik->id_klinik);

    	    if($input->has('search') && !empty($input->search)) {
    	        $model = $model->where(function ($query) use ($input) {
    	          $query->where('name','like','%'.$input->search['value'].'%');
    	          $query->orWhere('quantity','like','%'.$input->search['value'].'%');
    	          $query->orWhere('code','like','%'.$input->search['value'].'%');
                });
            }

    	    if($input->category != "semua") {
    	        $model = $model->where('category_id',$input->category);
            }

            return $datatables->of($model->get())
            ->addIndexColumn()
            ->addColumn('category', function ($data) {
                $category = $data->category;
                return $category->name;
            })
            ->addColumn('price', function ($data) {
                $price = $data->price;
                return currency()->rupiah($price, 'Rp ');
            })
            ->editColumn('purchase_price', function($data){
                $price = $data->purchase_price;
                return currency()->rupiah($price, 'Rp ');
            })
            ->editColumn('percentage_price_sales', function($data){
                return $data->percentage_price_sales.' %';
            })
            ->editColumn('quantity', function($data){
                if($data->quantity < $data->min_stock){
                    return '<span class="text-danger">'.$data->quantity.'</span>';
                }else{
                    return $data->quantity;
                }
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action', 'quantity'])
            ->make(true);
        }

        return view('product::product.index');
    }

    public function create()
    {
        $ProductCategory = new ProductCategory;
        $categories = $ProductCategory::where('id_klinik',Auth()->user()->klinik->id_klinik)->get()->toTree();
        $flatData = $ProductCategory->flatten($categories);

        return view('product::product.create')->withCategory($flatData);
    }

    public function import()
    {
        $ProductCategory = new ProductCategory;
        $categories = $ProductCategory::get()->toTree();
        $flatData = $ProductCategory->flatten($categories);

        return view('product::product.import')->withCategory($flatData);
    }

    public function storeimport(Request $request)
    {
        //VALIDASI
        $this->validate($request, [
            'file_excel' => 'required|mimes:xls,xlsx'
        ]);

        $Product = new Product;
        $input = $request->all();

        if ($request->hasFile('file_excel')) {
            $file = $request->file('file_excel'); //GET FILE
            \Excel::import(new ProductImport($input), $file); //IMPORT FILE
            return redirect()->route('admin.product.import')->withFlashSuccess(__('product::alerts.import.success'));
        }
        return redirect()->route('admin.product.import')->withFlashError(__('product::alerts.import.failed'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->productRepository->create($request->input());

        return redirect()->route('admin.product.index')->withFlashSuccess(__('product::alerts.product.created'));
    }

    public function edit(Product $product)
    {
    	$ProductCategory = new ProductCategory;
        $categories = $ProductCategory::get()->toTree();
        $flatData = $ProductCategory->flatten($categories);

        return view('product::product.edit')->withProduct($product)->withDropdownCategory($flatData);
    }

    public function update(ManageProductRequest $request, Product $product)
    {
        $this->productRepository->update($product, $request->input());

        return redirect()->route('admin.product.index')->withFlashSuccess(__('product::alerts.product.updated'));
    }

    public function destroy(Product $product)
    {
    	$product->delete();

        return redirect()->route('admin.product.index')->withFlashSuccess(__('product::alerts.product.deleted'));
    }

    public function getByCategory($id)
    {
        $data = $this->productRepository->getByCategory($id);

        $status = false;
        $message = 'Not Found!';

        $newdata = array();
        if(count($data)){
            $status = true;
            $message = 'OK';

            //filter
            /*foreach ($data as $key => $value) {
                $newdata[$key]['staff_id'] = $value->id;
                $newdata[$key]['employee_id'] = $value->employee_id;
                $newdata[$key]['full_name'] = $value->user->full_name;
            }*/
        }

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $data));
    }

    public function export()
    {
        return \Excel::download(new ProductExport, 'product.xlsx');
    }

    public function createStockOpname(Product $product)
    {
        $account = FinanceAccount::with('accountCategory')
                ->whereHas('accountCategory', function($q){
                    $q->where('type', config('finance_account.type.beban'))
                    ->where('parent_id', '>', 1);
                })
                ->orderBy('account_code', 'asc')
                ->get();

        $accountList = '<option></option>';
        foreach($account as $val){
            $name = $val->account_code.' - '.$val->account_name.' - '.$val->accountCategory['category_name'];
            $accountList .= '<option value="'.$val->id.'">'.$name.'</option>';
        }
        return view('product::product.opname')
        ->withProduct($product)
        ->withAccount($accountList);
    }

    public function saveStockOpname(Request $request)
    {
        $opname = $this->productRepository->storeOpname($request->input());

        if($opname){
            $status = TRUE;
            $message = trans('product::alerts.product.opname_created');
        }else{
            $status = FALSE;
            $message = trans('product::exceptions.product.opname_error');
        }

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $status));
    }
}
