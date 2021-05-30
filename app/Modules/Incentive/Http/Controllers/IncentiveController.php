<?php

namespace App\Modules\Incentive\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Incentive\Models\Incentive;
use App\Modules\Incentive\Models\IncentiveDetail;
use App\Modules\Incentive\Models\IncentiveStaffDetail;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Service;
use App\Modules\Humanresource\Models\Staff;

use App\Http\Controllers\Controller;
use App\Exceptions\GeneralException;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

use App\Modules\Incentive\Http\Requests\Incentive\ManageDiagnoseItemRequest;
use App\Modules\Incentive\Http\Requests\Incentive\StoreIncentiveRequest;
use App\Modules\Incentive\Http\Requests\Incentive\UpdateIncentiveItemRequest;

class IncentiveController extends Controller
{
    public function index(Datatables $datatables)
    {
        if ($datatables->getRequest()->ajax()) {
            return $datatables->of(Incentive::where('id_klinik',auth()->user()->id_klinik)->get())
            ->addIndexColumn()
            ->addColumn('details_url', function($data) {
                return route('admin.incentive.incentivedetail', $data->id);
            })
            ->addColumn('product_incentive_percent', function ($data) {
                $data = $data->product_incentive_percent;
                return $data;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('incentive::index');
    }

    public function incentiveDetail(Datatables $datatables, $id)
    {
        if ($datatables->getRequest()->ajax()) {

            $incentiveProductDetail = IncentiveDetail::leftJoin('products', 'products.id', '=', 'incentive_details.entity_id')
                    ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')
                    ->leftJoin('incentives', 'incentives.id', '=', 'incentive_details.incentive_id')
                    ->where('incentive_details.incentive_id', $id)
                    ->where('incentive_details.type', 'product')
                    ->select('incentive_details.*', 'incentives.name', 'products.code', 'products.price', 'products.name AS product_name', 'products.price', 'product_categories.name as category_name')
                    ->orderBy('products.name', 'asc')->get();

            $incentiveServiceDetail = IncentiveDetail::leftJoin('services', 'services.id', '=', 'incentive_details.entity_id')
                    ->leftJoin('service_categories', 'service_categories.id', '=', 'services.category_id')
                    ->leftJoin('incentives', 'incentives.id', '=', 'incentive_details.incentive_id')
                    ->where('incentive_details.incentive_id', $id)
                    ->where('incentive_details.type', 'services')
                    ->select('incentive_details.*', 'incentives.name', 'services.code', 'services.price', 'services.name AS product_name', 'services.price', 'service_categories.name as category_name')
                    ->orderBy('services.name', 'asc')->get();

            // Create a new collection instance.
            $collection = new Collection($incentiveProductDetail);

            // Merge an array with the existing collection.
            // die(json_encode($collection));
            $incentiveDetail = $collection->merge($incentiveServiceDetail);

            return $datatables->of($incentiveDetail)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->addColumn('value_type', function ($data) {
                $value_type = null;
                if($data->value_type == 'amount'){
                    $value_type = 'Komisi';
                }

                if($data->value_type == 'percent'){
                    $value_type = 'Persentase';
                }

                return $value_type;
            })
            ->addColumn('type', function ($data) {
                $type = null;
                if($data->type == 'product'){
                    $type = 'Obat';
                }

                if($data->type == 'services'){
                    $type = 'Tindakan';
                }

                return $type;
            })
            ->addColumn('value', function ($data) {
                $value = null;
                if($data->value_type == 'amount'){
                    $value = currency()->rupiah($data->value, 'Rp ');
                }

                if($data->value_type == 'percent'){
                    $value = $data->value.'%';
                }

                return $value;
            })
            ->addColumn('product', function ($data) {
                return $data->code.' - '.$data->product_name.' - '.$data->category_name;
            })
            ->addColumn('price', function ($data) {
                return currency()->rupiah($data->price, 'Rp ');
            })
            ->addColumn('value_incentive', function ($data) {
                $value = null;
                if($data->value_type == 'amount'){
                    $value = currency()->rupiah($data->value, 'Rp ');
                }

                if($data->value_type == 'percent'){
                    $incentive = ($data->price * ($data->value / 100));
                    $value = currency()->rupiah($incentive, 'Rp ');
                }

                return $value;
            })
            ->editColumn('id', '{{$id}}')
            ->make(true);
        }
    }

    public function create()
    {
    	if (auth()->user()->cannot('create incentive')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        $product = new Product;
        $listProduct = $product->where('id_klinik',auth()->user()->klinik->id_klinik)->get();

        $optionListProduct = '<option></option>';
        foreach ($listProduct as $key => $val) {
            $name = $val->code.' - '.$val->name.' - '.$val->category->name.' - '.currency()->rupiah($val->price, setting()->get('currency_symbol'));
            $optionListProduct .= '<option value="'.$val->id.'">'.($name).'</option>';
        }

        $service = new Service;
        $listService = $service->where('id_klinik',auth()->user()->klinik->id_klinik)->get();

        $optionListService = '<option></option>';
        foreach ($listService as $k => $v) {
            $nameService = $v->code.' - '.$v->name.' - '.$v->category->name.' - '.currency()->rupiah($v->price, setting()->get('currency_symbol'));
            $optionListService .= '<option value="'.$v->id.'">'.($nameService).'</option>';
        }
        return view('incentive::create')->withProduct($optionListProduct)->withService($optionListService);
    }

    public function store(StoreIncentiveRequest $request)
    {
        \DB::beginTransaction();

            $Incentive = new Incentive;
            // die(json_encode($request->input()));
            $Incentive->name = $request->name;
            $Incentive->product_incentive = $request->product_incentive;
            $Incentive->description = $request->description;
            $Incentive->id_klinik = auth()->user()->klinik->id_klinik;

            if ($Incentive->save()) {

                //Save Detail
                $detailProduct = array();
                $detailProductPrice = array();
                $detailService = array();
                $detailServicePrice = array();

                $totaloop = $request->product;
                $totaloopService = $request->service;
                $producttype = $request->producttype;
                $productvalue = $request->productvalue;
                $servicetype = $request->servicetype;
                $serviceqty = $request->serviceqty;

                $saveObat = false;
                $saveTreatment = false;

                foreach ($totaloop as $key => $val) {
                    if($request->product[$key]){
                        $getProduct = Product::find($request->product[$key]);

                        if($getProduct){
                            $productDetail = IncentiveDetail::firstOrNew([
                                'incentive_id' => $Incentive->id,
                                'type' => 'product',
                                'entity_id' => $getProduct->id,
                                'value_type' => $producttype[$key],
                                'value' => $productvalue[$key],
                            ]);

                            $productDetail->save();
                        }
                    }
                }

                foreach ($totaloopService as $key3 => $val3) {
                    if($request->service[$key3]){
                        $getService = Service::find($request->service[$key3]);

                        if($getService){
                            $serviceDetail = IncentiveDetail::firstOrNew([
                                'incentive_id' => $Incentive->id,
                                'type' => 'services',
                                'entity_id' => $getService->id,
                                'value_type' => $servicetype[$key3],
                                'value' => $serviceqty[$key3],
                            ]);

                            $serviceDetail->save();
                        }
                    }
                }
            }

        \DB::commit();

        return redirect()->route('admin.incentive.index')->withFlashSuccess('Berhasil menambahkan data.');
    }

    public function edit($id)
    {
        if (auth()->user()->cannot('update incentive')) {
            throw new GeneralException(trans('exceptions.cannot_edit'));
        }

        $incentive = Incentive::findOrFail($id);

        $incentiveDetail = IncentiveDetail::leftJoin('products', 'products.id', '=', 'incentive_details.entity_id')
                    ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')
                    ->leftJoin('incentives', 'incentives.id', '=', 'incentive_details.incentive_id')
                    ->where('incentive_details.incentive_id', $id)
                    // ->where('incentive_details.type', 'product')
                    ->select('incentive_details.*', 'incentives.name', 'products.code', 'products.price', 'products.name AS product_name', 'products.price', 'product_categories.name as category_name')
                    ->orderBy('products.name', 'asc')->get();

        $product = new Product;
        $listProduct = $product->get();

        $service = new Service;
        $listService = $service->get();

        $htmlProductDetail = null;
        $htmlServiceDetail = null;
        $numProduct = 1;
        $numService = 1;

        $keyProduct = 1;
        $keyService = 1;
        if($incentiveDetail){
            foreach ($incentiveDetail->groupBy('type') as $type => $data) {
                if($type == 'product'){
                    foreach ($data as $key => $product) {
                        $numProduct = count($data);

                        $htmlProductDetail .= '<tr>
                                            <td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>
                                            <td class="nomor">
                                                '.$keyProduct.'
                                            </td>
                                            <td>
                                                <select class="form-control product required" id="'.$keyProduct.'select2" name="category_id['.$keyProduct.']" data-placeholder="Pilih" style="width: 100%">';
                                                    foreach ($listProduct as $key => $val) {
                                                        $name = $val->code.' - '.$val->name.' - '.$val->category->name.' - '.currency()->rupiah($val->price, setting()->get('currency_symbol'));
                                                        $htmlProductDetail .= '<option value="'.$val->id.'" '.($val->id == $product->entity_id ? "selected" : "").'>'.($name).'</option>';
                                                    }
                        $htmlProductDetail .= '</select>
                                            </td>
                                            <td width="10%">
                                                <select class="form-control producttype" name="producttype['.$keyProduct.']" data-placeholder="Pilih" style="width: 100%">
                                                    <option></option>
                                                    <option value="amount" '.($product->value_type == 'amount' ? 'selected' : null).'>Komisi</option>
                                                    <option value="percent" '.($product->value_type == 'percent' ? 'selected' : null).'>Persentase</option>
                                                    <option value="point" '.($product->value_type == 'point' ? 'selected' : null).'>Point</option>
                                                </select>
                                            </td>
                                            <td width="10%">
                                                <input type="number" name="productvalue['.$keyProduct.']" class="form-control productvalue required" value="'.$product->value.'" />
                                            </td>
                                        </tr>';
                        $keyProduct++;
                    }
                }


                if($type == 'services'){
                    foreach ($data as $key => $service) {
                        $numService = count($data);

                        $htmlServiceDetail .= '<tr>
                                            <td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>
                                            <td class="nomor3">
                                                '.$keyService.'
                                            </td>
                                            <td>
                                                <select class="form-control services required" id="service'.$keyService.'" name="service['.$keyService.']" data-placeholder="Pilih" style="width: 100%">';
                                                    foreach ($listService as $key => $val) {
                                                        $name = $val->code.' - '.$val->name.' - '.$val->category->name.' - '.currency()->rupiah($val->price, setting()->get('currency_symbol'));
                                                        $htmlServiceDetail .= '<option value="'.$val->id.'" '.($val->id == $service->entity_id ? "selected" : "").'>'.($name).'</option>';
                                                    }
                        $htmlServiceDetail .= '</select>
                                            </td>
                                            <td width="10%">
                                                <select class="form-control servicetype" name="servicetype['.$keyService.']" data-placeholder="Pilih" style="width: 100%">
                                                    <option></option>
                                                    <option value="amount" '.($service->value_type == 'amount' ? 'selected' : null).'>Komisi</option>
                                                    <option value="percent" '.($service->value_type == 'percent' ? 'selected' : null).'>Persentase</option>
                                                    <option value="point" '.($service->value_type == 'point' ? 'selected' : null).'>Point</option>
                                                </select>
                                            </td>
                                            <td width="10%">
                                                <input type="number" name="serviceqty['.$keyService.']" class="form-control serviceqty required" value="'.$service->value.'" />
                                            </td>
                                        </tr>';
                        $keyService++;
                    }
                }
            }
        }

        $optionListProduct = '<option></option>';
        foreach ($listProduct as $key => $val) {
            $name = $val->code.' - '.$val->name.' - '.$val->category->name.' - '.currency()->rupiah($val->price, setting()->get('currency_symbol'));
            $optionListProduct .= '<option value="'.$val->id.'">'.($name).'</option>';
        }

        $service = new Service;
        $listService = $service->get();

        $optionListService = '<option></option>';
        foreach ($listService as $k => $v) {
            $nameService = $v->code.' - '.$v->name.' - '.$v->category->name.' - '.currency()->rupiah($v->price, setting()->get('currency_symbol'));
            $optionListService .= '<option value="'.$v->id.'">'.($nameService).'</option>';
        }

        return view('incentive::edit')->withIncentive($incentive)->withHtmlProductDetail($htmlProductDetail)->withHtmlServiceDetail($htmlServiceDetail)->withProduct($optionListProduct)->withService($optionListService)->withNumproduct($numProduct)->withNumservice($numService);
    }

    public function update(Request $request, $id)
    {
        $Incentive = Incentive::findOrFail($id);

        if($Incentive->name <> $request->name){
            $check = Incentive::where('name', '=', $request->name)->first();
            if ($check) {
                throw new GeneralException('Nama '.$request->name.' sudah ada!');
            }
        }

        // die(json_encode($request->input()));
        \DB::beginTransaction();

            $Incentive->name = $request->name;
            $Incentive->product_incentive = $request->product_incentive;
            $Incentive->point_value = $request->point_value;
            $Incentive->description = $request->description;

            if ($Incentive->save()) {
                //Save Detail
                $detailProduct = array();
                $detailService = array();

                $totaloop = $request->product;
                $totaloopService = $request->service;
                $producttype = $request->producttype;
                $productvalue = $request->productvalue;
                $servicetype = $request->servicetype;
                $serviceqty = $request->serviceqty;

                $saveObat = false;
                $saveTreatment = false;

                if($totaloop){
                    foreach ($totaloop as $key => $val) {
                        if($request->product[$key]){
                            $getProduct = Product::find($request->product[$key]);

                            if($getProduct){
                                $productDetail = IncentiveDetail::firstOrNew([
                                    'incentive_id' => $Incentive->id,
                                    'type' => 'product',
                                    'entity_id' => $getProduct->id
                                ]);

                                $productDetail->value_type = $producttype[$key];
                                $productDetail->value = $productvalue[$key];

                                $productDetail->save();
                            }
                        }
                    }

                    IncentiveDetail::whereNotIn('entity_id', array_values($request->product))->where('type', 'product')->delete();
                }else{
                    IncentiveDetail::where('type', 'product')->delete();
                }

                if($totaloopService){

                    foreach ($totaloopService as $key3 => $val3) {
                        if($request->service[$key3]){
                            $getService = Service::find($request->service[$key3]);

                            if($getService){
                                $serviceDetail = IncentiveDetail::firstOrNew([
                                    'incentive_id' => $Incentive->id,
                                    'type' => 'services',
                                    'entity_id' => $getService->id
                                ]);

                                $serviceDetail->value_type = $servicetype[$key3];
                                $serviceDetail->value = $serviceqty[$key3];

                                $serviceDetail->save();
                            }
                        }
                    }

                    IncentiveDetail::whereNotIn('entity_id', array_values($request->service))->where('type', 'services')->delete();
                }else{
                    IncentiveDetail::where('type', 'services')->delete();
                }
            }

        \DB::commit();

        return redirect()->route('admin.incentive.index')->withFlashSuccess('Berhasil mengubah data.');
    }

    public function destroy($id)
    {
        if (auth()->user()->cannot('delete incentive')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $Incentive = Incentive::findOrFail($id);

        $Incentive->destroy($Incentive->id);

        return redirect()->route('admin.incentive.index')->withFlashSuccess('Data berhasil di hapus.');
    }

    public function reporting()
    {
        $Staff = Staff::get();

        return view('incentive::reporting.index')->withStaffs($Staff);
    }

    public function reportingstaff(Request $request)
    {
        $input = $request->all();
        $data = array();

        if($input){
            $staffId = $request->staff_id;
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $queryService = IncentiveStaffDetail::with(['service' => function ($query) {
                $query->select('id', 'name as service_name');
            }])->where('staff_id', $staffId)->where('type', 'services');

            if($startDate && $endDate){
                $start_date = \DateTime::createFromFormat(setting()->get('date_format'), $startDate)->format('Y-m-d');
                $end_date = \DateTime::createFromFormat(setting()->get('date_format'), $endDate)->format('Y-m-d');

                // $queryService = $queryService->whereBetween('date', [$start_date, $end_date]);
                // $queryService = $queryService->whereDate('date', '<=', $start_date)->whereDate('date', '>=', $end_date);
                $queryService = $queryService->whereRaw("(DATE(date) between '".$start_date."' and '".$end_date."')");
            }

            $queryService = $queryService->get();

            $queryProduct = IncentiveStaffDetail::with(['product' => function ($query) {
                $query->select('id', 'name as product_name');
            }])->where('staff_id', $staffId)->where('type', 'product');

            if($startDate && $endDate){
                $start_date = \DateTime::createFromFormat(setting()->get('date_format'), $startDate)->format('Y-m-d');
                $end_date = \DateTime::createFromFormat(setting()->get('date_format'), $endDate)->format('Y-m-d');

                // $queryProduct = $queryProduct->whereBetween('date', [$start_date, $end_date]);
                // $queryProduct = $queryProduct->whereDate('date', '<=', $start_date)->whereDate('date', '>=', $end_date);
                $queryProduct = $queryProduct->whereRaw("(DATE(date) between '".$start_date."' and '".$end_date."')");
            }

            $queryProduct = $queryProduct->get();
            // dd(($queryProduct));

            // Create a new collection instance.
            $collection = new Collection($queryService);
            $newCollection = $collection->merge($queryProduct);

            $data = $newCollection;
        }
        // die(json_encode($newCollection));
        $Staff = Staff::get();

        return view('incentive::reporting.index', compact('data'))->withStaffs($Staff)->withInput($request->input());
    }
}
