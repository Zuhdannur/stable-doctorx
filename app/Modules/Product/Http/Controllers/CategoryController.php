<?php

namespace App\Modules\Product\Http\Controllers;

use App\Helpers\Auth\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Product\Repositories\ProductCategoryRepository;
use App\Modules\Product\Http\Requests\ProductCategory\ManageProductCategoryRequest;
use App\Modules\Product\Http\Requests\ProductCategory\StoreProductCategoryRequest;
use App\Modules\Product\Models\ProductCategory;
use Yajra\Datatables\Datatables;

class CategoryController extends Controller
{
	protected $productCategoryRepository;

    public function __construct(ProductCategoryRepository $productCategoryRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    public function index(Datatables $datatables)
    {
    	$ProductCategory = new ProductCategory;
    	$tree = $ProductCategory::where('id_klinik',Auth()->user()->klinik->id_klinik)->get()->toTree();
    	$flatData = $ProductCategory->flatten($tree);
        if ($datatables->getRequest()->ajax()) {
            return $datatables->of($flatData)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
	            $button = '<a href="'.route('admin.product.category.edit', $data['id']).'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.edit').'"><i class="fa fa-edit"></i></a> ';
	            $button .= '<a href="'.route('admin.product.category.destroy', $data['id']).'"
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

        return view('product::category.index');
    }

    public function create()
    {
    	$ProductCategory = new ProductCategory;
    	$categories = $ProductCategory::where('id_klinik',Auth()->user()->klinik->id_klinik)->get()->toTree();
    	$flatData = $ProductCategory->flatten($categories);

        return view('product::category.create')->withCategory($flatData);
    }

    public function store(StoreProductCategoryRequest $request)
    {
        $this->productCategoryRepository->create($request->only('name', 'is_active', 'parent_id'));

        return redirect()->route('admin.product.category.index')->withFlashSuccess(__('product::alerts.category.created'));
    }

    public function edit($id)
    {
    	$ProductCategory = new ProductCategory;
    	$categories = $ProductCategory::get()->toTree();
    	$flatData = $ProductCategory->flatten($categories);

    	$productCategory = $ProductCategory::findOrFail($id);

        return view('product::category.edit')->withCategory($productCategory)->withDropdownCategory($flatData);
    }

    public function update(ManageProductCategoryRequest $request, $id)
    {
    	$productCategory = ProductCategory::findOrFail($id);
        $this->productCategoryRepository->update($productCategory, $request->input());

        return redirect()->route('admin.product.category.index')->withFlashSuccess(__('product::alerts.category.updated'));
    }

    public function destroy($id)
    {
    	$productCategory = ProductCategory::findOrFail($id);
    	$productCategory->delete();
        // event(new StudentClassDeleted($classes));

        return redirect()->route('admin.product.category.index')->withFlashSuccess(__('product::alerts.category.deleted'));
    }
}
