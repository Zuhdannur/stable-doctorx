<?php

namespace App\Modules\Crm\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Service;
use App\Modules\Crm\Models\CrmMsRadeemPoint;
use App\Modules\Attribute\Models\LogActivity;

use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Repositories\ServiceRepository;

class CrmPointController extends Controller
{
    public function index(Datatables $datatables)
    {
        if($datatables->getRequest()->ajax()){
            $model = CrmMsRadeemPoint::get();

            return $datatables->of($model)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $btn = '';
                $btn .= $data->edit_button;
                $btn .= $data->delete_button;
                return $btn;
            })
            ->editColumn('nominal_gift', function($data){
                return currency()->rupiah($data->nominal_gift, setting()->get('currency_symbol'));
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('crm::point.index');
    }

    public function create()
    {
        return view('crm::point.create');
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            if($request->isMethod('POST')){
                $status = false;
                $message = trans('crm::exceptions.point.radeem.create_error');
                
                $checking = CrmMsRadeemPoint::where('point', $request->point)->first();
                
                if($checking){
                    $message = trans('crm::exceptions.point.radeem.already_exists');
                    return response()->json(array('status' => $status, 'message' => $message));
                }

                DB::beginTransaction();
                $radeem = new CrmMsRadeemPoint;
                $radeem->point = $request->point;
                $radeem->nominal_gift = currency()->digit($request->nominal_gift);

                try{
                    $radeem->save();
                    $radeem = CrmMsRadeemPoint::find($radeem->id);
                    $code = CrmMsRadeemPoint::generateCode();
                    $radeem->code = $code;

                    $radeem->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.crm');
                    $log->action = "Create Master Radeem Point";
                    $log->desc = "Kode : $radeem->code";

                    $log->save();

                    DB::commit();
                    $status = true;
                    $message = __('crm::alerts.point.radeem.created');
                }catch(\Exception $e){

                    DB::rollback();
                    if(env("APP_DEBUG") == true){
                        dd($e);
                    }
                }
                    
                return response()->json(array('status' => $status, 'message' => $message));
                
            }else if($request->isMethod('PATCH')){
                $status = false;
                $message = trans('crm::exceptions.point.radeem.update_error');
                
                $radeem = CrmMsRadeemPoint::find($request->id);
                $checking = CrmMsRadeemPoint::where('point', $request->point)->first();
                
                if($checking->id != $radeem->id){
                    $message = trans('crm::exceptions.point.radeem.already_exists');
                    return response()->json(array('status' => $status, 'message' => $message));
                }

                $radeem->point = $request->point;
                $radeem->nominal_gift = currency()->digit($request->nominal_gift);

                DB::beginTransaction();
                try {

                    $radeem->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.crm');
                    $log->action = "Update Master Radeem Point";
                    $log->desc = "Kode : $radeem->code";

                    $log->save();
                    DB::commit();
                    $status = true;
                    $message = __('crm::alerts.point.radeem.updated');
                } catch (\Throwable $th) {
                    
                    DB::rollback();
                    if(env("APP_DEBUG") == true){
                        dd($e);
                    }
                }

                return response()->json(array('status' => $status, 'message' => $message));
                
            }
        }else{
            abort(404);
        }
    }

    public function edit(CrmMsRadeemPoint $data)
    {
        return view('crm::point.edit')
        ->withData($data);
    }

    public function destroy(CrmMsRadeemPoint $data)
    {
        DB::beginTransaction();
        try {
            
            $log = new LogActivity();
            $log->module_id = config('my-modules.crm');
            $log->action = "Delete Master Radeem Point";
            $log->desc = "Kode : $data->code";
            
            $log->save();
            $data->delete();
            DB::commit();
            return redirect()->route('admin.crm.point.index')->withFlashSuccess(__('crm::alerts.point.radeem.deleted'));  
        } catch (\Exception $e) {
            
            DB::rollback();
            if(env("APP_DEBUG") == true){
                dd($e);
            }
            return redirect()->route('admin.crm.point.index')->withFlashDanger(__('crm::exceptions.point.radeem.delete_error'));  
        }

    }

    public function indexObat(Datatables $datatables)
    {
        if($datatables->getRequest()->ajax()){
            $productRepository = new ProductRepository;

            return $datatables->of($productRepository->select('id','code','name','point','min_qty','category_id')->get())
            ->addIndexColumn()
            ->addColumn('category', function ($data) {
                $category = $data->category;
                return $category->name;
            })
            ->addColumn('action', function($data){
                $btn = '';
                $btn .= $data->edit_point_button;
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('crm::point.obat.index');
    }

    public function editObat(Product $product)
    {
        return view('crm::point.obat.form')
        ->withProduct($product);
    }

    public function storeObat(Request $request)
    {
        if($request->ajax()){

            $status = false;
            $message = trans('crm::exceptions.point.obat.update_error');
            
            $product = Product::find($request->id);
            if($product){

                $product->point = $request->point;
                $product->min_qty = $request->min_qty;
                
                DB::beginTransaction();
                try {
                    $product->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.crm');
                    $log->action = "Update Product Point";
                    $log->desc = "Kode Produk : $product->code, Point : $product->point";
                    
                    $log->save();
                    DB::commit();
                    $status = true;
                    $message = __('crm::alerts.point.obat.updated');
                } catch (\Exception $e) {

                    DB::rollback();
                    if(env("APP_DEBUG") == true){
                        dd($e);
                    }
                }

            }

            return response()->json(array('status' => $status, 'message' => $message));
        }else{
            abort(404);
        }
    }

    public function indexService(Datatables $datatables)
    {
        if ($datatables->getRequest()->ajax()) {
            $serviceRepository = new ServiceRepository;

            return $datatables->of($serviceRepository->select('id','category_id','code','name','point')->get())
            ->addIndexColumn()
            ->addColumn('category', function ($data) {
                $category = $data->category;
                return $category->name;
            })
            ->addColumn('action', function ($data) {
                $button = $data->edit_point_button;
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('crm::point.service.index');
    }

    public function editService(Service $service)
    {
        return view('crm::point.service.form')
        ->withService($service);
    }

    public function storeService(Request $request)
    {
        if($request->ajax()){

            $status = false;
            $message = trans('crm::exceptions.point.service.update_error');
            
            $service = Service::find($request->id);
            if($service){

                $service->point = $request->point;
                
                DB::beginTransaction();
                try {

                    $service->save();

                    $log = new LogActivity();
                    $log->module_id = config('my-modules.crm');
                    $log->action = "Update Service Point";
                    $log->desc = "Kode Service : $service->code, Point : $service->point";
                    
                    $log->save();
                    DB::commit();
                    $status = true;
                    $message = __('crm::alerts.point.service.updated');
                } catch (\Exception $e) {

                    DB::rollback();
                    if(env("APP_DEBUG" == true)){
                        dd($e);
                    }
                }
            }

            return response()->json(array('status' => $status, 'message' => $message));
        }else{
            abort(404);
        }
    }
}
