<?php

namespace App\Modules\Product\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Product\Repositories\ServiceCategoryRepository;
use App\Modules\Product\Http\Requests\ServiceCategory\ManageServiceCategoryRequest;
use App\Modules\Product\Http\Requests\ServiceCategory\StoreServiceCategoryRequest;
use App\Modules\Product\Models\ServiceCategory;
use Yajra\Datatables\Datatables;

class ServiceCategoryController extends Controller
{
	protected $serviceCategoryRepository;

    public function __construct(ServiceCategoryRepository $serviceCategoryRepository)
    {
        $this->serviceCategoryRepository = $serviceCategoryRepository;
    }

    public function index(Datatables $datatables)
    {
        $ServiceCategory = ServiceCategory::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();
        if ($datatables->getRequest()->ajax()) {
            return $datatables->of($ServiceCategory)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
	            $button = '<a href="'.route('admin.product.servicecategory.edit', $data['id']).'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.edit').'"><i class="fa fa-edit"></i></a> ';
	            $button .= '<a href="'.route('admin.product.servicecategory.destroy', $data['id']).'"
					data-method="delete"
					data-trans-button-cancel="'.__('buttons.general.cancel').'"
					data-trans-button-confirm="'.__('buttons.general.crud.delete').'"
					data-trans-title="'.__('strings.backend.general.are_you_sure').'"
					class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.delete').'"><i class="fa fa-trash"></i></a>';
	            return $button;
	        })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('product::service-category.index');
    }

    public function create()
    {
        return view('product::service-category.create');
    }

    public function store(StoreServiceCategoryRequest $request)
    {
        $this->serviceCategoryRepository->create($request->only('name', 'is_active'));

        return redirect()->route('admin.product.servicecategory.index')->withFlashSuccess(__('product::alerts.servicecategory.created'));
    }

    public function edit( $id)
    {
        $data = ServiceCategory::findOrFail($id);
        return view('product::service-category.edit')->withCategory($data);
    }

    public function update(ManageServiceCategoryRequest $request, $id)
    {
    	$productCategory = ServiceCategory::findOrFail($id);
        $this->serviceCategoryRepository->update($productCategory, $request->input());

        return redirect()->route('admin.product.servicecategory.index')->withFlashSuccess(__('product::alerts.servicecategory.updated'));
    }

    public function destroy($id)
    {
    	$productCategory = ServiceCategory::findOrFail($id);
    	$productCategory->delete();

        return redirect()->route('admin.product.servicecategory.index')->withFlashSuccess(__('product::alerts.servicecategory.deleted'));
    }
}
