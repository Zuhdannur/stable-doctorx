<?php

namespace App\Modules\Product\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Product\Http\Requests\ProductPackage\ManageProductPackageRequest;
use App\Modules\Product\Http\Requests\ProductPackage\StoreProductPackageRequest;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductCategory;
use App\Modules\Product\Models\ProductPackage;
use App\Modules\Product\Models\ProductPackageDetail;

use App\Modules\Product\Imports\ProductImport;
use Yajra\Datatables\Datatables;

class ProductPackageController extends Controller
{
    public function index(Datatables $datatables)
    {
    	if ($datatables->getRequest()->ajax()) {
            return $datatables->of(ProductPackage::get())
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->addColumn('details_url', function($data) {
                return route('admin.product.productpackage.productdetail', $data->id);
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('product::product-package.index');
    }

    public function productDetail(Datatables $datatables, $id)
    {
        if ($datatables->getRequest()->ajax()) {
            $productDetail = ProductPackageDetail::leftJoin('products', 'products.id', '=', 'product_package_details.product_id')
                    ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')
                    ->leftJoin('product_packages', 'product_packages.id', '=', 'product_package_details.product_package_id')
                    ->where('product_package_id', $id)
                    ->select('product_package_details.*', 'product_packages.name', 'products.code', 'products.price', 'products.name AS product_name', 'products.price', 'product_categories.name as category_name')
                    ->orderBy('products.name', 'asc')->get();

            return $datatables->of($productDetail)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->addColumn('product', function ($data) {
                return $data->code.' - '.$data->product_name.' - '.$data->category_name;
            })
            ->addColumn('price', function ($data) {
                return currency()->rupiah($data->price, 'Rp ');
            })
            ->editColumn('id', '{{$id}}')
            ->make(true);
        }
    }

    public function create()
    {

        $product = new Product;
        $listProduct = $product->get();

        $optionListProduct = '<option></option>';
        foreach ($listProduct as $key => $val) {
            $name = $val->code.' - '.$val->name.' - '.$val->category->name;
            $optionListProduct .= '<option value="'.$val->id.'">'.($name).'</option>';
        }

        return view('product::product-package.create')->withProduct($optionListProduct);
    }


    public function store(StoreProductPackageRequest $request)
    {
        // Start transaction!
        \DB::beginTransaction();

        $ProductPackage = new ProductPackage;
        
        $ProductPackage->name = $request->name;
        $ProductPackage->description = $request->description;
        $ProductPackage->discount = $request->discount;
        $ProductPackage->is_active = $request->is_active ? 1 : 0;

        if ($ProductPackage->save()) {
            $totaloop = $request->product;
            $product = $request->product;
            $qty = $request->qty;
            
            foreach ($totaloop as $key => $val) {
                $productId = (int)$product[$key];

                $ProductPackageDetail = ProductPackageDetail::firstOrNew([
                    'product_package_id' => $ProductPackage->id,
                    'product_id' => $productId,
                ]);

                $ProductPackageDetail->qty = (int)$qty[$key] + (int)$ProductPackageDetail->qty;
                $ProductPackageDetail->save();
            }
                
        }

        \DB::commit();

        return redirect()->route('admin.product.productpackage.index')->withFlashSuccess('Berhasil menambahkan data.');
    }

    public function edit($id)
    {
        $productpackage = ProductPackage::findOrFail($id);

        $productDetail = ProductPackageDetail::leftJoin('products', 'products.id', '=', 'product_package_details.product_id')
                    ->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')
                    ->leftJoin('product_packages', 'product_packages.id', '=', 'product_package_details.product_package_id')
                    ->where('product_package_id', $id)
                    ->select('product_package_details.*', 'product_packages.name', 'products.code', 'products.price', 'products.name AS product_name', 'products.price', 'product_categories.name as category_name')
                    ->orderBy('products.name', 'asc')->get();

        

        $product = new Product;
        $listProduct = $product->get();

        $htmlDetail = null;
        $num = 1;
        if($productDetail){
            $num = count($productDetail);
            foreach ($productDetail as $key => $value) {
                // die(json_encode($value));
                $numkey = ($key+1);
                $htmlDetail .= '<tr>
                                    <td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>
                                    <td class="nomor">
                                        '.$numkey.'
                                    </td>
                                    <td>
                                        <select class="form-control product required" id="'.$numkey.'select2" name="category_id['.$numkey.']" data-placeholder="Pilih" style="width: 100%">';
                                            $optionListProduct = '<option></option>';
                                            foreach ($listProduct as $key => $val) {
                                                $name = $val->code.' - '.$val->name.' - '.$val->category->name;
                                                $htmlDetail .= '<option value="'.$val->id.'#'.$value->id.'" '.($val->id == $value->product_id ? "selected" : "").'>'.($name).'</option>';
                                            }
                $htmlDetail .= '</select>
                                    </td>
                                    <td>
                                        <input type="number" name="qty['.$numkey.']" class="form-control qty required" value="'.$value->qty.'" />
                                    </td>
                                </tr>';
            }
        }

        $optionListProduct = '<option></option>';
        foreach ($listProduct as $key => $val) {
            $name = $val->code.' - '.$val->name.' - '.$val->category->name;
            $optionListProduct .= '<option value="'.$val->id.'#0">'.($name).'</option>';
        }
        
        return view('product::product-package.edit')->withProductpackage($productpackage)->withProductdetail($productDetail)->withHtmlDetail($htmlDetail)->withProduct($optionListProduct)->withNum($num);
    }

    public function update(Request $request, $id)
    {
        $ProductPackage = ProductPackage::findOrFail($id);
        // die(json_encode($request->input()));
        if(strtolower($ProductPackage->name) !== strtolower($request->name)){
            if (ProductPackage::whereName($request->name)->first()) {
                throw new GeneralException('Data sudah ada:: '.$request->name);
            }
        }

        // Start transaction!
        \DB::beginTransaction();

        $ProductPackage->name = $request->name;
        $ProductPackage->description = $request->description;
        $ProductPackage->discount = $request->discount;
        $ProductPackage->is_active = $request->is_active ? 1 : 0;

        if ($ProductPackage->save()) {

            $totaloop = $request->product;
            $product = $request->product;
            $qty = $request->qty;
            
            $detailId = array();
            foreach ($totaloop as $key => $val) {
                $arrProduct = explode('#', $product[$key]);

                $productId = $arrProduct[0];
                $prodPackageDetailId = $arrProduct[1];
                if($prodPackageDetailId){
                    $detailId[] = $prodPackageDetailId;

                    $ProductPackageDetail = ProductPackageDetail::updateOrCreate([
                        'id' => $prodPackageDetailId,
                        'product_package_id' => $ProductPackage->id,
                        'product_id' => $productId,
                        'qty' => (int)$qty[$key],
                    ]);
                }else{
                    $ProductPackageDetail = ProductPackageDetail::firstOrNew([
                        'product_package_id' => $ProductPackage->id,
                        'product_id' => $productId,
                    ]);

                    $ProductPackageDetail->qty = (int)$qty[$key] + (int)$ProductPackageDetail->qty;

                    $ProductPackageDetail->save();
                    $detailId[] = $ProductPackageDetail->id;
                }

                
            }

            //Delete Deleted
            ProductPackageDetail::whereNotIn('id', $detailId)->where('product_package_id', $ProductPackage->id)->delete();
                
        }

        \DB::commit();
        
        return redirect()->route('admin.product.productpackage.index')->withFlashSuccess('Berhasil mengubah data.');
    }

    public function destroy(ProductPackage $product)
    {
        $product->delete();

        return redirect()->route('admin.product.productpackage.index')->withFlashSuccess(__('product::alerts.product.deleted'));
    }

    public function destroydetail($packageDetail, $detailId)
    {
        $ProductPackageDetail = ProductPackageDetail::find($detailId);
        $ProductPackageDetail->delete();

        return redirect()->route('admin.product.productpackage.index')->withFlashSuccess(__('product::alerts.product.deleted'));
    }

}
