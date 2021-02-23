<?php

namespace App\Modules\Product\Repositories;

use App\Helpers\Auth\Auth;
use App\Modules\Product\Models\Product;
use App\Modules\Accounting\Models\FinanceTransaction;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Accounting\Models\FinanceAccount;

use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    public function create(array $data) : Product
    {
        if ($this->checkExists($data['name'], $data['category_id'])) {
            throw new GeneralException(trans('product::exceptions.product.already_exists') . ' - '.$data['name']);
        }

        return DB::transaction(function () use ($data) {
            $status = isset($data['is_active']) ? 1 : 0;
            $is_min_stock = 0;
            if( $data['quantity'] < $data['min_stock']){
                $is_min_stock = 1;
            }

        /*
            $purchase_price = currency()->digit($data['purchase_price']) * $data['percentage_price_sales'] / 100;
            $price = currency()->digit($data['purchase_price']) + $purchase_price;

            $create = parent::create([
                'code'      => $data['code'],
                'name'      => $data['name'],
                'category_id' => $data['category_id'],
                'price' => $price,
                'quantity' => $data['quantity'],
                'min_stock' => $data['min_stock'],
                'is_min_stock' => $is_min_stock,
                'purchase_price_avg' => currency()->digit($data['purchase_price']),
                'purchase_price' => currency()->digit($data['purchase_price']),
                'sales_type' => $data['type_sales'],
                'percentage_price_sales' => $data['percentage_price_sales'],
                'is_active' => $status
                ]);
        */
            $create = parent::create([
                'code'      => $data['code'],
                'name'      => $data['name'],
                'category_id' => $data['category_id'],
                'quantity' => $data['quantity'],
                'min_stock' => $data['min_stock'],
                'is_min_stock' => $is_min_stock,
                'purchase_price_avg' => currency()->digit($data['purchase_price']),
                'purchase_price' => currency()->digit($data['purchase_price']),
                'price' =>  currency()->digit($data['price']),
                'is_active' => $status,
                'id_klinik' => Auth()->user()->klinik->id_klinik
            ]);


            if ($create) {

                //save data to transaction table
                $transaction = FinanceTransaction::create([
                    'trx_type_id' => config('finance_trx.trx_types.general'),
                    'memo' => '',
                    'person' => auth()->user()->first_name.' '.auth()->user()->last_name,
                    'person_id' => '',
                    'person_type' => config('finance_trx.person_type.general'),
                    'trx_date' => date('Y/m/d'),
                    'potongan' => '',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ]);

                if($transaction){
                    $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);

                    $transaction = FinanceTransaction::find($transaction->id);
                    $transaction->transaction_code = $transaction_code;


                    $transaction->save();

                    $sum_value = $data['quantity'] * currency()->digit($data['purchase_price']);

                    // update account ekuitas
                    $ekuitas = FinanceAccount::sumKredit(config('finance_account.default.acc_ekuitas'), $sum_value);
                    $ekuitasJournal = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.kredit'),
                        'account_id' => $ekuitas->id,
                        'value' => $sum_value,
                        'tax'  => '',
                        'tags' => 'is_ekuitas',
                        'balance'  => $ekuitas->balance,
                        'description' => '',
                        ]);

                    // update account opname and create journal
                    $persediaan = FinanceAccount::sumDebit(config('finance_account.default.acc_persediaan_obat'), $sum_value);

                    $persediaanJournal = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'type' => config('finance_journal.types.debit'),
                        'account_id' => $persediaan->id,
                        'value' => $sum_value,
                        'tax'  => '',
                        'tags' => 'is_persediaan',
                        'balance'  => $persediaan->balance,
                        'description' => '',
                    ]);

                    return $create;
                }
            }

            throw new GeneralException(trans('product::exceptions.product.create_error'));
        });
    }

    public function update(Product $product, array $data)
    {
        if(strtolower($product->name) !== strtolower($data['name']) && $product->category_id !== $data['category_id']){
            if ($this->checkExists($data['name'], $data['category_id'])) {
                throw new GeneralException(trans('product::exceptions.product.already_exists') . ' - '.$data['name']);
            }
        }

        return DB::transaction(function () use ($product, $data) {
            $status = isset($data['is_active']) ? 1 : 0;
            $is_min_stock = 0;
            if($product->quantity < $data['min_stock']){
                $is_min_stock = 1;
            }

        /*  $up_price = $product->percentage_price_sales / 100;
            $price = 0;
            if( $data['type_sales'] == 0){ //0 for price average
                $price = $product->purchase_price_avg + ($product->purchase_price_avg * $up_price);
            }else{ //set to last purchase price

                $price = currency()->digit($data['purchase_price']) + ($product->purchase_price * $up_price);
            }

            // if purchase_price_avg == 0 set like purchase price
            if($product->purchase_price_avg == 0){

                $product = $product->update([
                    'code'      => $data['code'],
                    'name'      => $data['name'],
                    'category_id' => $data['category_id'],
                    'purchase_price' => currency()->digit($data['purchase_price']),
                    'purchase_price_avg' => currency()->digit($data['purchase_price']),
                    'percentage_price_sales' => $data['percentage_price_sales'],
                    'price' => $price,
                    'sales_type' => $data['type_sales'],
                    'min_stock' => $data['min_stock'],
                    'is_min_stock' => $is_min_stock,
                    'is_active' => $status
                ]);

            }else{
                $product = $product->update([
                    'code'      => $data['code'],
                    'name'      => $data['name'],
                    'category_id' => $data['category_id'],
                    'purchase_price' => currency()->digit($data['purchase_price']),
                    'percentage_price_sales' => $data['percentage_price_sales'],
                    'price' => $price,
                    'sales_type' => $data['type_sales'],
                    'min_stock' => $data['min_stock'],
                    'is_min_stock' => $is_min_stock,
                    'is_active' => $status
                ]);
            }
        */

            $product = $product->update([
                'code'      => $data['code'],
                'name'      => $data['name'],
                'category_id' => $data['category_id'],
                'purchase_price' => currency()->digit($data['purchase_price']),
                'purchase_price_avg' => currency()->digit($data['purchase_price']),
                'percentage_price_sales' => '',
                'price' => currency()->digit($data['price']),
                'sales_type' => null,
                'min_stock' => $data['min_stock'],
                'is_min_stock' => $is_min_stock,
                'is_active' => $status
            ]);

            if ($product) {
                return $product;
            }

            throw new GeneralException(trans('product::exceptions.product.update_error'));
        });
    }

    protected function checkExists($name, $category_id) : bool
    {
        return $this->model
                ->where('name', strtolower($name))
                ->where('category_id', $category_id)
                ->where('id_klinik',Auth()->user()->klinik)
                ->count() > 0;
    }

    public function getByCategory($catId){
        return $this->model->whereHas('category', function ($q) use ($catId) {
            $q->where('category_id', $catId);
        })->get();
    }

    public function storeOpname(array $data)
    {
        return DB::transaction(function () use($data){

             //save data to transaction table
             $transaction = FinanceTransaction::create([
                'trx_type_id' => config('finance_trx.trx_types.opname'),
                'memo' => '',
                'person' => auth()->user()->first_name.' '.auth()->user()->last_name,
                'person_id' => '',
                'person_type' => config('finance_trx.person_type.general'),
                'trx_date' => date('Y/m/d'),
                'potongan' => '',
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            if($transaction){
                $transaction_code = FinanceTransaction::generateTrxCode($transaction->trx_type_id, $transaction->trxType->label);

                $transaction = FinanceTransaction::find($transaction->id);
                $transaction->transaction_code = $transaction_code;


                $transaction->save();

                // Update Product Stock
                $product = Product::findOrFail($data['id']);
                $qty_penyusutan = $product->quantity - $data['qty_now'];

                $product->quantity = $data['qty_now'];

                if($product->quantity < $product->min_stock){
                    $product->is_min_stock = 1;
                }else{
                    $product->is_min_stock = 0;
                }

                $product->save();

                $price_avg = ( $product->purchase_price_avg != null ||$product->purchase_price_avg != '' ? $product->purchase_price_avg : $product->purchase_price );
                $_sum_penyusutan_value = $qty_penyusutan * $price_avg;

                // update account persediaan and create journal
                $persediaan = FinanceAccount::sumKredit(config('finance_account.default.acc_persediaan_obat'), $_sum_penyusutan_value);
                $persediaanJournal = FinanceJournal::create([
                    'transaction_id' => $transaction->id,
                    'type' => config('finance_journal.types.kredit'),
                    'account_id' => $persediaan->id,
                    'value' => $_sum_penyusutan_value,
                    'tax'  => '',
                    'tags' => 'is_persediaan',
                    'balance'  => $persediaan->balance,
                    'description' => '',
                    ]);

                // update account opname and create journal
                $opname = FinanceAccount::sumDebit($data['acc_opname'], $_sum_penyusutan_value);

                $opnameJournal = FinanceJournal::create([
                    'transaction_id' => $transaction->id,
                    'type' => config('finance_journal.types.debit'),
                    'account_id' => $opname->id,
                    'value' => $_sum_penyusutan_value,
                    'tax'  => '',
                    'tags' => 'is_opname',
                    'balance'  => $opname->balance,
                    'description' => '',
                ]);

                return true;
            }

            throw new GeneralException(trans('product::exceptions.product.opname_error'));
        });
    }
}
