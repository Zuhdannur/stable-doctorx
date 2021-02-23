<?php

namespace App\Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Modules\Product\Models\Service;
use App\Modules\Product\Models\ServicesPackage;
use App\Modules\Product\Models\ServicesPackageDetail;
use App\Modules\Product\Http\Requests\StoreServicePackagesDetailRequest;

class ServicesPackagesController extends Controller
{
    public function index(Datatables $datatables)
    {
        if($datatables->getRequest()->ajax()){
            $model = ServicesPackage::get();

            return $datatables->of($model)
            ->addIndexColumn()
            ->addColumn('item', function($data){
                return $data->details->count();
            })
            ->addColumn('action', function($data){
                $html = '<a href="'.route('admin.product.services-packages.show', ["ServicesPackage" => $data ]).'" class="btn btn-primary btn-sm" title="Lihat Data"><i class="fa fa-list"></i></a>&nbsp;&nbsp;';
                $html .= '<a href="'.route('admin.product.services-packages.destroy', $data).'"
                        data-method="delete"
                        data-trans-button-cancel="'.__('buttons.general.cancel').'"
                        data-trans-button-confirm="'.__('buttons.general.crud.delete').'"
                        data-trans-title="'.__('strings.backend.general.are_you_sure').'"
                        class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.delete').'"><i class="fa fa-trash"></i></a> &nbsp;';
   ;
                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('product::service-package.index');
    }

    public function create()
    {
        $service = new Service;

        return view('product::service-package.create')
        ->withService($service->optionList());
    }

    public function store(StoreServicePackagesDetailRequest $request)
    {
        // Start transaction!
        \DB::beginTransaction();
        try {
            $ServicesPackage = new ServicesPackage;

            $ServicesPackage->name = $request->name;
            $ServicesPackage->description = $request->description;
            $ServicesPackage->is_active = $request->is_active ? 1 : 0;
            
            if ($ServicesPackage->save()) {
                $totaloop = $request->service;
                $service = $request->service;
                $qty = $request->qty;
                
                foreach ($totaloop as $key => $val) {
                    $serviceId = (int) $service[$key];
                    
                    $ServicesPackageDetail = ServicesPackageDetail::firstOrNew([
                        'service_package_id' => $ServicesPackage->id,
                        'service_id' => $serviceId,
                        ]);
                        
                    $ServicesPackageDetail->qty = (int) $qty[$key] + (int)$ServicesPackageDetail->qty;
                    $ServicesPackageDetail->save();
                }
                    
                }

                \DB::commit();
                return redirect()->route('admin.product.services-packages.index')->withFlashSuccess('Data Paket Service Berhasil Ditambahkan.');
        } catch (\Exception $e) {
            \DB::rollback();
            if(env("APP_DEBUG") == true){
                dd($e);
            }
            return redirect()->back()->withFlashDanger('Ada Kesalahan Pada Saat Menambahkad Data Paket Service. Silahkan Coba Kembali.');
        }

    }

    public function show(ServicesPackage $ServicesPackage)
    {
        return view('product::service-package.show')
        ->withServicesPackages($ServicesPackage);
    }

    public function edit(ServicesPackage $ServicesPackage)
    {
        $htmlDetail = '';
        $service = new Service;
        if(isset($ServicesPackage->details)){
            foreach($ServicesPackage->details as $key => $value ){

                $dataList = $service->get();
                $serviceOption = '<option></option>';
                if(!empty($dataList)){
                    foreach ($dataList as $keyList => $valList ) {
                        $serviceName = $valList->code.' - '.$valList->name.' - '.$valList->category->name;
                        if($value->service_id == $valList->id){
                            $serviceOption .= '<option value="'.$valList->id.'" selected>'.strtoupper($serviceName).'</option>';
                        }else{
                            $serviceOption .= '<option value="'.$valList->id.'">'.strtoupper($serviceName).'</option>';
                        }
                    }
                }

                $htmlDetail .= '<tr>
                                    <td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>
                                    <td class="nomor">
                                        '.($key + 1).'
                                    </td>
                                    <td>
                                        <select class="form-control product required" id="'.($key + 1).'select2" name="service['.($key + 1).']" data-placeholder="Pilih" style="width: 100%">';
                $htmlDetail .= $serviceOption.'</select>
                                    </td>
                                    <td>
                                        <input type="number" name="qty['.($key + 1).']" class="form-control qty required" value="'.$value->qty.'" />
                                    </td>
                                </tr>';
            }
        }

        return view('product::service-package.edit')
        ->withHtmlDetail($htmlDetail)
        ->withServicesPackage($ServicesPackage)
        ->withService($service->optionList());
    }

    public function update(Request $request)
    {
        $ServicesPackage = ServicesPackage::findOrFail($request->service_packages_id);
        if(strtolower($ServicesPackage->name) !== strtolower($request->name)){
            if (ServicesPackage::whereName($request->name)->first()) {
                throw new GeneralException('Data dengan nama : '.$request->name);
            }
        }
        // dd($request);
        \DB::beginTransaction();
        try{
            $ServicesPackage->name = $request->name;
            $ServicesPackage->description = $request->description;
            $ServicesPackage->is_active = $request->is_active ? 1 : 0;
            
            if ($ServicesPackage->save()) {
                $totaloop = $request->service;
                $service = $request->service;
                $qty = $request->qty;
                
                ServicesPackageDetail::where('service_package_id', $ServicesPackage->id)->delete();
                foreach ($totaloop as $key => $val) {   
                    $ServicesPackageDetail = ServicesPackageDetail::create([
                        'service_package_id' => $ServicesPackage->id,
                        'service_id' => $service[$key],
                        'qty' => (int) $qty[$key]
                    ]);
                }    
            }

            \DB::commit();
            return redirect()->route('admin.product.services-packages.index')->withFlashSuccess('Data Paket Service Berhasil Diperbaharui.');
        }catch(\Exception $e){
            \DB::rollback();

            if(env("APP_DEBUG") == true){
                dd($e);
            }
            throw new GeneralException("Ada Kesalahan pada saat memperbarui data, Silahkan Coba Kembali.");
        }
    }

    public function destroy(ServicesPackage $ServicesPackage)
    {
        \DB::beginTransaction();
        try {
            ServicesPackageDetail::where('service_package_id', $ServicesPackage->id)->delete();
            $ServicesPackage->delete();
            \DB::commit();
            return redirect()->route('admin.product.services-packages.index')->withFlashSuccess("Data paket services berhasil dihapus");
        } catch (\Exception $e) {
            \DB::rolback();
            if(env("APP_DEBUG") == true){
                dd($e);
            }
            throw new GeneralException("Ada Kesalahan pada saat menghapus data, Silahkan Coba Kembali.");
        }

    }
}
