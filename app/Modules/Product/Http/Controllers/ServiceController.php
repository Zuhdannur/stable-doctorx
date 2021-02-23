<?php

namespace App\Modules\Product\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Product\Repositories\ServiceRepository;
use App\Modules\Product\Http\Requests\Service\ManageServiceRequest;
use App\Modules\Product\Http\Requests\Service\StoreServiceRequest;
use App\Modules\Product\Models\Service;
use App\Modules\Product\Models\ServiceCategory;

use Yajra\Datatables\Datatables;
use function foo\func;

class ServiceController extends Controller
{
	protected $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function index(Datatables $datatables)
    {
    	if ($datatables->getRequest()->ajax()) {
            return $datatables->of($this->serviceRepository->where('id_klinik',Auth()->user()->klinik->id_klinik)->get())
            ->addIndexColumn()
            ->addColumn('category', function ($data) {
                $category = $data->category;
                return $category->name;
            })
            ->addColumn('price', function ($data) {
                $price = $data->price;
                return currency()->rupiah($price, 'Rp ');
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
                ->addColumn('flag_service', function ($data) {
                    return $data->flag == 1 ? "Dokter" : "Terapis";
                })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('product::service.index');
    }

    public function create()
    {
        $ServiceCategory = ServiceCategory::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();
        return view('product::service.create')->withCategory($ServiceCategory);
    }

    public function store(StoreServiceRequest $request)
    {
        $this->serviceRepository->create($request->only('code', 'name', 'category_id', 'price', 'is_active','flag'));

        return redirect()->route('admin.product.service.index')->withFlashSuccess(__('product::alerts.service.created'));
    }

    public function edit(Service $service)
    {
        $ServiceCategory = ServiceCategory::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();

        return view('product::service.edit')->withService($service)->withDropdownCategory($ServiceCategory);
    }

    public function update(ManageServiceRequest $request, Service $service)
    {
        $this->serviceRepository->update($service, $request->input());

        return redirect()->route('admin.product.service.index')->withFlashSuccess(__('product::alerts.service.updated'));
    }

    public function destroy(Service $service)
    {
    	$service->delete();

        return redirect()->route('admin.product.service.index')->withFlashSuccess(__('product::alerts.service.deleted'));
    }

    public function getByCategory($id)
    {
        $data = $this->serviceRepository->getByCategory($id);

        $status = false;
        $message = 'Not Found!';

        $newdata = array();
        if(count($data)){
            $status = true;
            $message = 'OK';
        }

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $data));
    }
}
