<?php

namespace App\Modules\Billing\Repositories;

use Exception;
use App\Models\Auth\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Modules\Billing\Models\Billing;

use App\Modules\Patient\Models\Patient;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Service;
use App\Modules\Crm\Models\CrmMarketing;
use App\Modules\Patient\Models\Treatment;
use App\Modules\Crm\Models\CrmRadeemPoint;
use App\Modules\Crm\Models\CrmMsRadeemPoint;

use App\Modules\Accounting\Models\FinanceTax;
use App\Modules\Billing\Models\BillingDetail;
use App\Modules\Billing\Models\PaymentHistory;
use App\Modules\Product\Models\ServicesPackage;

use App\Modules\Incentive\Models\IncentiveStaff;
use App\Modules\Patient\Models\PatientAdmission;
use App\Modules\Patient\Models\TreatmentInvoice;

use App\Modules\Accounting\Models\FinanceAccount;
use App\Modules\Accounting\Models\FinanceJournal;
use App\Modules\Incentive\Models\IncentiveDetail;
use App\Modules\Accounting\Models\FinanceTransaction;
use App\Modules\Incentive\Models\IncentiveStaffDetail;
use App\Modules\Accounting\Repositories\FinanceRepository;

class BillingRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Billing::class;
    }

    public function create(array $data): Billing
    {
        return DB::transaction(function () use ($data) {

            $patient = Patient::findOrFail($data['patient_id']);

            //save data to transaction table
            $transaction = FinanceTransaction::create([
                'trx_type_id' => config('finance_trx.trx_types.invoice'),
                'memo' => $data['notes'],
                'person' => $patient->name,
                'person_id' => $patient->id,
                'person_type' => config('finance_trx.person_type.patient'),
                'trx_date' => (\Carbon\Carbon::createFromFormat('d/m/Y', $data['date']))->format('Y-m-d'),
                'potongan' => '',
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            // save data billing
            $dataBilling = [
                'invoice_no' => Billing::generateInvoiceNumer($data['date']),
                'patient_id' => $data['patient_id'],
                'status' => config('billing.invoice_paid'),
                'remaining_payment' => 0,
                'total_ammount' => 0,
                'date' => $data['date'],
                'tax_total' => '', //update dibawah
                'discount' => $data['discount'],
                'created_by' => auth()->user()->id,
                'in_paid' => $data['total_receive']
            ];
            $createBilling = parent::create($dataBilling);
            $transaction->transaction_code = $createBilling->invoice_no;
            $transaction->save();

            if ($createBilling) {
                $totaloop = $data['product'];

                // default variabel
                $tax = '';
                $sum_tax = 0;
                $tax_value = 0;

                $beban_pendapatan_value = 0;
                $pendapatan_value = 0;

                $saveDetail = false;

                // save detail billing
                foreach ($totaloop as $key => $val) {

                    if (isset($data['tax'][$key])) {
                        $tax = FinanceTax::findOrFail($data['tax'][$key]);
                    }

                    $dataVal = explode('#', $data['product'][$key]);
                    $product = $dataVal[0];
                    $type = $dataVal[1];
                    $billingDetail = BillingDetail::create([
                        'invoice_id' => $createBilling->id,
                        'product_id' => $product,
                        'price' => currency()->digit($data['price'][$key]),
                        'tax_label' => !empty($tax) ? $tax->tax_name . ' ' . $tax->percentage . ' %' : '',
                        'tax_value' => !empty($tax) ? $tax->percentage : '',
                        'type' => $type,
                        'discount' => $data['discount_item'][$key],
                        'notes' => $data['notes'][$key] ?? ''
                    ]);

                    $billingDetail->qty = $data['qty'][$key] + (int)$billingDetail->qty;

                    $save = $billingDetail->save();

                    //update stock obat
                    if ($type == 'product') {
                        $_product = Product::findOrFail($billingDetail->product_id);
                        $_product->quantity = $_product->quantity - $billingDetail->qty;

                        if ($_product->quantity < $_product->min_stock) {
                            $_product->is_min_stock = 1;
                        }

                        $_product->save();

                        if ($_product->purchase_price_avg != 0 && $_product->sales_type == 0) {
                            $_product_value = $billingDetail->qty * $_product->purchase_price_avg;
                        } else {
                            $_product_value = $billingDetail->qty * $_product->purchase_price;
                        }

                        // update persediaan
                        $_persediaan = FinanceAccount::sumKredit(config('finance_account.default.acc_persediaan_obat'), $_product_value);

                        $_persediaanJournal = FinanceJournal::create([
                            'transaction_id' => $transaction->id,
                            'account_id' => $_persediaan->id,
                            'balance' => $_persediaan->balance,
                            'type' => config('finance_journal.types.kredit'),
                            'value' => $_product_value,
                            'tax' => '',
                            'tags' => 'is_persediaan'
                        ]);

                        $beban_pendapatan_value = $beban_pendapatan_value + $_product_value;

                    }

                    // save tax and sum tax total
                    if (!empty($tax)) {
                        //summary total tax
                        $tax_value = intval(($billingDetail->price * $billingDetail->qty) * ($billingDetail->tax_value / 100));

                        $_tax = FinanceAccount::sumKredit($tax->account_tax_sales, $tax_value);

                        $_taxJournal = FinanceJournal::create([
                            'transaction_id' => $transaction->id,
                            'account_id' => $_tax->id,
                            'balance' => $_tax->balance,
                            'type' => config('finance_journal.types.kredit'),
                            'value' => $tax_value,
                            'tax' => '',
                            'tags' => 'is_tax'
                        ]);

                        $sum_tax = $sum_tax + $tax_value;
                    }

                    if ($save) {
                        $saveDetail = true;
                    }

                    if ($data['qId']) {
                        PatientAdmission::where('id', $data['qId'])->update(['status_id' => config('admission.admission_completed')]);
                    }
                }

                if ($saveDetail) {
                    // update tax on create billing
                    $createBilling->tax_total = $sum_tax;
                    $createBilling->save();

                    // save discount journal
                    $discount_val = currency()->digit($data['discount_amount']);
                    if ($discount_val > 0) {
                        $_discount = FinanceAccount::sumDebit(config('finance_account.default.acc_discount'), $discount_val);

                        $_discountJournanl = FinanceJournal::create([
                            'transaction_id' => $transaction->id,
                            'account_id' => $_discount->id,
                            'balance' => $_discount->balance,
                            'type' => config('finance_journal.types.debit'),
                            'value' => $discount_val,
                            'tax' => '',
                            'tags' => 'is_discount'
                        ]);
                    }

                    //save beban pendapatan
                    if ($beban_pendapatan_value > 0) {
                        $beban_pendapatan = FinanceAccount::sumDebit(config('finance_account.default.acc_beban_pokok'), $beban_pendapatan_value);

                        $beban_pendapatanJournal = FinanceJournal::create([
                            'transaction_id' => $transaction->id,
                            'account_id' => $beban_pendapatan->id,
                            'balance' => $beban_pendapatan->balance,
                            'type' => config('finance_journal.types.debit'),
                            'value' => $beban_pendapatan_value,
                            'tax' => '',
                            'tags' => 'is_beban_pendapatan'
                        ]);
                    }

                    // save pendapatan journal
                    $pendapatan_value = 0;
                    foreach ($data['total'] as $value) {
                        $pendapatan_value = $pendapatan_value + currency()->digit($value);
                    }
                    $pendapatan_value = $pendapatan_value - $sum_tax;
                    $pendapatan = FinanceAccount::sumKredit(config('finance_account.default.acc_pendapatan'), $pendapatan_value);

                    $pendapatanJournal = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'account_id' => $pendapatan->id,
                        'balance' => $pendapatan->balance,
                        'type' => config('finance_journal.types.kredit'),
                        'value' => $pendapatan_value,
                        'tax' => '',
                        'tags' => 'is_pendapatan'
                    ]);

                    //save cash
                    $cash_value = $pendapatan_value + $sum_tax - $discount_val;

                    $cash = FinanceAccount::sumDebit($data['acc_cash'], $cash_value);

                    $cashJournal = FinanceJournal::create([
                        'transaction_id' => $transaction->id,
                        'account_id' => $cash->id,
                        'balance' => $cash->balance,
                        'type' => config('finance_journal.types.debit'),
                        'value' => $cash_value,
                        'tax' => '',
                        'tags' => 'is_cash'
                    ]);

                    // update membership point
                    if ($patient->membership) {
                        $patient->membership->total_point = $patient->membership->total_point + $data['point_ammount'];
                        $patient->membership->save();
                    }

                    $createBilling->total_ammount = $createBilling->total_price;
                    $createBilling->save();

                    /** store payment history */
                    PaymentHistory::create([
                        'invoice_id' => $createBilling->id,
                        'total_pay' => $createBilling->total_ammount - $discount_val ,
                        'in_paid' => $data['total_receive']
                    ]);

                    return $createBilling;
                }

            }

            throw new GeneralException(__('exceptions.staff.create_error'));
        });
    }

    public function update(Billing $billing, array $data)
    {
        try {
            $discount = isset($data['discount']) ? $data['discount'] : 0;
            $discount = $discount + (isset($data['discountMarketing']) ? $data['discountMarketing'] : 0);
            $billing->update([
                'discount' => $discount,
                'note' => $data['notes'],
                'tax_total' => '',
                'in_paid' => 0
            ]);


            $_transaction = FinanceTransaction::find($billing->transaction_id);
            $patient = Patient::find($billing->patient_id);
            if ($_transaction) {
                /** reverse and delete last finance process */
                $_inv_detail = $billing->invDetail;
                $_rev_point = 0;
                // looping reverse product stock
                foreach ($_inv_detail as $key => $val) {
                    //update stock obat
                    if ($val->type == 'product') {
                        $_product = Product::find($val->product_id);
                        $_product->quantity = $_product->quantity - $val->qty;

                        $_rev_point = $_rev_point + $_product->point;
                        if ($_product->quantity < $_product->min_stock) {
                            $_product->is_min_stock = 1;
                        }

                        $_product->save();

                    } else {
                        $_service = Service::find($val->product_id);
                        $_rev_point = $_rev_point + $_service->point;
                    }
                }

                //reverse finance journal and account balance
                foreach ($_transaction->journal as $key => $val) {
                    if ($val->type == config('finance_journal.types.debit')) {
                        FinanceAccount::sumDebit($val->account_id, $val->value);
                    } else if (config('finance_journal.types.kredit')) {
                        FinanceAccount::sumKredit($val->account_id, $val->value);
                    }
                }

                // deleting the journal and inv detail
                $_transaction->journal()->delete();
                $billing->invDetail()->delete();
                $billing->save();

                // update point membership
                if ($_rev_point > 0) {
                    if ($patient->membership) {
                        $patient->membership->total_point = $patient->membership->total_point - $_rev_point;
                        $patient->membership->save();
                    }
                }
                /** End Of reverse and delete last finance process */
            } else {
                /** reverse the product */
                $billing->invDetail()->delete();
                /** End Of reverse the product */

                $_transaction = FinanceRepository::createTransaction(config('finance_trx.trx_types.invoice'), [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'type' => config('finance_trx.person_type.patient')
                ]);

                // $billing->transaction_id = $_transaction->id;
                $billing->save();
            }

            /** Save new Invoice detail and new finance journal */
            // default variabel
            $tax = '';
            $sum_tax = 0;
            $tax_value = 0;
            $beban_pendapatan_value = 0;
            $pendapatan_value = 0;
            $sum_point = 0;

            foreach ($data['product'] as $key => $val) {
                $dataVal = explode('#', $val);
                $productBil = $dataVal[0];
                $type = $dataVal[1];

                // check tax and sum point
                $product = '';
                if ($type == 'product') {
                    $product = Product::find($productBil);
                    $sum_point = $sum_point + $product->point;
                } else {
                    $product = Service::find($productBil);
                    $sum_point = $sum_point + $product->point;
                }

                $price = $data['price'][$key];
                if ($data['tax'][$key] != null) {
                    $tax = FinanceTax::find($data['tax'][$key]);
                    $price = $price + ($price * $tax->percentage / 100);
                }

                //save billing detail
                $billingDetail = BillingDetail::create([
                    'invoice_id' => $billing->id,
                    'product_id' => $product->id,
                    'price' => $price,
                    'tax_label' => !empty($tax) ? $tax->tax_name . ' ' . $tax->percentage . ' %' : '',
                    'tax_value' => !empty($tax) ? $tax->percentage : '',
                    'type' => $type,
                    'qty' => $data['qty'][$key],
                    'discount' => $data['discount_item'][$key] ?? 0,
                    'notes' => $data['notes'][$key] ?? ''
                ]);

                //update stock obat
                if ($type == 'product') {
                    $product->quantity = $product->quantity - $billingDetail->qty;

                    if ($product->quantity < $product->min_stock) {
                        $product->is_min_stock = 1;
                    }

                    $product->save();

                    if ($product->purchase_price_avg != 0 && $product->sales_type == 0) {
                        $product_value = $billingDetail->qty * $product->purchase_price_avg;
                    } else {
                        $product_value = $billingDetail->qty * $product->purchase_price;
                    }

                    // update persediaan
                    $_persediaan = FinanceAccount::sumKredit(config('finance_account.default.acc_persediaan_obat'), $product_value);

                    $_persediaanJournal = FinanceJournal::create([
                        'transaction_id' => $_transaction->id,
                        'account_id' => $_persediaan->id,
                        'balance' => $_persediaan->balance,
                        'type' => config('finance_journal.types.kredit'),
                        'value' => $product_value,
                        'tax' => '',
                        'tags' => 'is_persediaan'
                    ]);

                    $beban_pendapatan_value = $beban_pendapatan_value + $product_value;
                    $pendapatan_value = intval($pendapatan_value + $product->price);

                } // sum pendapatan from treatments
                else {
                    $pendapatan_value = intval($pendapatan_value + $product->price);
                }

                // save tax and sum tax total
                if (!empty($tax)) {
                    //summary total tax
                    $tax_value = intval(($billingDetail->price * $billingDetail->qty) * ($billingDetail->tax_value / 100));
                    $tax_value = intval(($billingDetail->price * $billingDetail->qty) * ($billingDetail->tax_value / 100));
                    $tax_value = intval(($billingDetail->price * $billingDetail->qty) * ($billingDetail->tax_value / 100));
                    $tax_value = intval(($billingDetail->price * $billingDetail->qty) * ($billingDetail->tax_value / 100));
                    $tax_value = intval(($billingDetail->price * $billingDetail->qty) * ($billingDetail->tax_value / 100));
                    $tax_value = intval(($billingDetail->price * $billingDetail->qty) * ($billingDetail->tax_value / 100));
                    $tax_value = intval(($billingDetail->price * $billingDetail->qty) * ($billingDetail->tax_value / 100));

                    $_tax = FinanceAccount::sumKredit($tax->account_tax_sales, $tax_value);

                    $_taxJournal = FinanceJournal::create([
                        'transaction_id' => $_transaction->id,
                        'account_id' => $_tax->id,
                        'balance' => $_tax->balance,
                        'type' => config('finance_journal.types.kredit'),
                        'value' => $tax_value,
                        'tax' => '',
                        'tags' => 'is_tax'
                    ]);

                    $sum_tax = $sum_tax + $tax_value;
                }
            }

            // update tax on billing
            $billing->tax_total = $sum_tax;
            $billing->save();

            //save beban pendapatan
            if ($beban_pendapatan_value > 0) {
                $beban_pendapatan = FinanceAccount::sumKredit(config('finance_account.default.acc_beban_pokok'), $beban_pendapatan_value);

                $beban_pendapatanJournal = FinanceJournal::create([
                    'transaction_id' => $_transaction->id,
                    'account_id' => $beban_pendapatan->id,
                    'balance' => $beban_pendapatan->balance,
                    'type' => config('finance_journal.types.kredit'),
                    'value' => $beban_pendapatan_value,
                    'tax' => '',
                    'tags' => 'is_beban_pendapatan'
                ]);
            }

            //save to piutang
            $piutang_value = $pendapatan_value + $sum_tax;
            FinanceRepository::storePiutang($_transaction->id, $piutang_value);

            FinanceRepository::storePendapatan($_transaction->id, $pendapatan_value);
            /** End Of Save new Invoice detail and new finance journal */

            /** Store a Invoice Payment */
            $this->storepaid($billing, $request = (object)$data);
            /** End Of Store a Invoice Payment */
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function storepaid(Billing $Billing, $request)
    {
        $sum_discount = 0;
        $total_gift = 0;
        $total_pay = 0;
        $discount_point = 0;
        $nominal_point = 0;

        if ($Billing->transaction_id == null || $Billing->transaction_id < 1)
            throw new Exception("Error, Ini Adalah Billing dengan Data Lama, Silahkan Pergunakan Menu Edit untuk memprosesnya.");

        try {
            if ($Billing->status != config('billing.invoice_unpaid') && $Billing->status != config('billing.invoice_partial_paid'))
                throw new Exception("Error, Pembayaran tidak dapat di proses di menu ini.");

            /** cek nullable total
             * jika null, di isi dengan total price(sub total + tax)
             */
            if ($Billing->total_ammount == null) {
                $Billing->total_ammount = $Billing->sub_total;
                $Billing->remaining_payment = $Billing->total_ammount;
            }

            /** marketing and discount, hanya berlaku untuk invoices yang pertama
             * mengurangi total ammount dengan diskon
             * dan menyimpan diskon
             */
            if ($Billing->status == config('billing.invoice_unpaid')) {
                if (isset($request->marketing) && $request->marketing != null) {
                    $Billing->marketing_id = $request->marketing;
                    $discount = CrmMarketing::find($request->marketing);
                    if (isset($request->discountMarketing)) {
                        $discountMarketing = (isset($discount->discount) ? $discount->discount : 0);
                        $discount = (isset($request->discount) ? $request->discount : 0);
                        $discount = $discount + $discountMarketing;
                        $sum_discount = $Billing->total_ammount * $discount / 100;
                        $Billing->discount = $discount;
                    } else {
                        $sum_discount = (isset($discount->discount) ? $Billing->total_ammount * ($discount->discount / 100) : 0);
                        $Billing->discount = $discount->discount;
                        if ($Billing->patient->membership) {
                            $discount_point = $discount->point;
                        }
                    }

                    /** update total ammount dan sisa pembayaran */
                    $Billing->total_ammount = intVal($Billing->total_ammount - $sum_discount);
                    $Billing->remaining_payment = $Billing->total_ammount;
                }

                if ($Billing->patient->membership) {
                    $nominal_point = intval(($Billing->total_price / $Billing->patient->membership->ms_membership->min_trx) * $Billing->patient->membership->ms_membership->point);
                }
            }


            if (config('billing.invoice_partial_paid')) {
                $total_pay = $Billing->total_ammount - $Billing->total_pay;
            } else {
                $total_pay = $Billing->total_ammount;
            }

            /** throw error, jika jumlah yang akan dibayarkan lebih besar daripada total */
            if ($Billing->paymentHistory) {
                $totalPay = $Billing->total_pay + $total_pay;
                if ($totalPay > $Billing->total_ammount)
                    throw new Exception("Jumlah yang akan dibayarkan, tidak boleh lebih besar dari total atau sisa pembayaran");
            }

            $patient = Patient::findOrFail($Billing->patient_id);

            /** create finance transaction data */
            $transaction = FinanceRepository::createTransaction(config('finance_trx.trx_types.invoice_payment'), [
                'id' => $patient->id,
                'name' => $patient->name,
                'type' => config('finance_trx.person_type.patient')
            ]);

            /** cek radeem point */
            if (isset($request->item_radeem) && is_array($request->item_radeem)) {
                if (!$Billing->patient->membership)
                    throw new Exception('Tidak Dapat Melakukan Radeem Point, Pasien Bukanlah Membership');

                $point_radeem = 0;
                $patient_point = $Billing->patient->membership->total_point;
                foreach ($request->item_radeem as $key => $val) {
                    $msRadeem = CrmMsRadeemPoint::find($val);
                    if (!$msRadeem) {
                        continue;
                    } else {
                        $sum_radeem_point = $msRadeem->point * $request->qty_point[$key];
                        $point_radeem = $sum_radeem_point + $point_radeem;

                        if ($point_radeem > ($patient_point + $discount_point + $nominal_point))
                            throw new Exception ('Point Pasien Tidak Cukup Untuk Melakukan Radeem Point');

                        /** save radeem point */
                        $radeem = new CrmRadeemPoint;
                        $radeem->membership_id = $Billing->patient->membership->id;
                        $radeem->item_code = $msRadeem->code;
                        $radeem->point = $msRadeem->point;
                        $radeem->ammount = $request->qty_point[$key];
                        $radeem->nominal = $msRadeem->nominal_gift;
                        $radeem->invoice_id = $Billing->id;
                        $radeem->save();

                        $sum_radeem_gift = $msRadeem->nominal_gift * $request->qty_point[$key];
                        $total_gift = intVal($total_gift + $sum_radeem_gift);
                    }
                };

                /** save radeem to accounting module */
                FinanceRepository::storeDebitRadeem($transaction->id, $total_gift);

                /** Update Invoice Radeem Point */
                $Billing->radeem_point = $Billing->radeem_point + $total_gift;
                $Billing->remaining_payment = intval($Billing->remaining_payment) - $total_gift;
                $Billing->total_ammount = intval($Billing->total_ammount) - $total_gift;

                $total_pay = $total_pay - $total_gift;

                $Billing->patient->membership->total_point = $Billing->patient->membership->total_point + $discount_point + $nominal_point - $point_radeem;
            }

            /** save dicount to accounting module */
            if ($sum_discount > 0) {
                FinanceRepository::storeDebitDiscount($transaction->id, $sum_discount);
            }

            if ($request->isPayAll === '2') {
                $total_pay = $request->totalPay;
            }

            // update balance cash
            FinanceRepository::storeDebitCash($transaction->id, $request->acc_cash, $total_pay);

            // store kredit piutang
            FinanceRepository::storeKreditPiutang($transaction->id, $total_pay);

            /** store payment history */
            PaymentHistory::create([
                'invoice_id' => $Billing->id,
                'total_pay' => $total_pay,
                'in_paid' => $request->total_receive
            ]);

            /** store final billing */
            $Billing->remaining_payment = intval($Billing->remaining_payment - $total_pay);
            if ($Billing->remaining_payment < 0) {
                $Billing->remaining_payment = 0;
            }

            if ($Billing->remaining_payment <= 0) {
                $Billing->status = config('billing.invoice_paid');
            } else {
                $Billing->status = config('billing.invoice_partial_paid');
            }

            $Billing->save();
            if ($Billing->patient->membership) {
                $Billing->patient->membership->save();
            }

            $status = $this->saveKomisi($Billing->id);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function saveKomisi($invNo)
    {
        $Billing = Billing::findOrFail($invNo);
        //Save Komisi
        $staffId = array();
        $terapisStaffId = array();

        $appInvoice = isset($Billing->appointmentInvoice->appointment->staff->id) ? $Billing->appointmentInvoice->appointment->staff->id : null;
        $treatInvoice = isset($Billing->treatmentInvoice->treatment->staff->id) ? $Billing->treatmentInvoice->treatment->staff->id : null;

        if ($appInvoice) {
            $staffId[] = $appInvoice;
        }

        if ($treatInvoice) {
            $staffId[] = $treatInvoice;
        } else {
            $appId = isset($Billing->appointmentInvoice->appointment_id) ? $Billing->appointmentInvoice->appointment_id : null;
            if ($appId) {
                $treatInvoice = Treatment::where('appointment_id', $appId)->first();
                if ($treatInvoice) {
                    $staffId[] = $treatInvoice->staff->id;
                }
            }
        }

        $treatInvoiceTerapis = isset($Billing->treatmentInvoice->treatment->staffTerapis->id) ? $Billing->treatmentInvoice->treatment->staffTerapis->id : null;

        if ($treatInvoiceTerapis) {
            $staffId[] = $treatInvoiceTerapis;
        }

        $invoiceService = $Billing->invDetail->where('type', 'service');
        $invoiceProduct = $Billing->invDetail->where('type', 'product');
        $data = array($invoiceService, $invoiceProduct);

        foreach ($staffId as $staff) {
            $IncentiveStaff = IncentiveStaff::where('staff_id', $staff)->get();
            if ($IncentiveStaff) {
                foreach ($IncentiveStaff as $key => $incentive) {
                    $prodcutIncentive = $incentive->incentive->product_incentive ?? 0;
                    $prodcutPoint = $incentive->incentive->point_value ?? 0;
                    $productIdHasIncentive = array();

                    if ($incentive->details) {

                        foreach ($incentive->details as $incDetail) {
                            $productserviceAmount = 0;

                            $isService = false;
                            $isProduct = false;

                            if ($incDetail->type == 'services' && !empty($invoiceService)) {
                                foreach ($invoiceService as $item) {
                                    if ($item->product_id == $incDetail->entity_id) {
                                        $isService = true;
                                        $productserviceAmount = $item->price;
                                    }
                                }
                            } elseif ($incDetail->type == 'product' && !empty($invoiceProduct)) {
                                foreach ($invoiceProduct as $item) {
                                    if ($item->product_id == $incDetail->entity_id) {
                                        $productserviceAmount = $item->price;
                                        $isProduct = true;
                                        $productIdHasIncentive[] = $incDetail->entity_id;
                                    }
                                }
                            }

                            $incentiveValue = 0;
                            if ($incDetail->value_type == 'amount') {
                                $incentiveValue = $incDetail->value;
                            } elseif ($incDetail->value_type == 'percent') {
                                $incentiveCalc = ($productserviceAmount * ($incDetail->value / 100));
                                $incentiveValue = $incentiveCalc;
                            } elseif ($incDetail->value_type == 'point') {
                                $incentiveValue = $incentiveCalc;
                            }

                            if ($incentiveValue != 0 && ($isService == true || $isProduct == true)) {
                                $IncentiveStaffDetail = IncentiveStaffDetail::firstOrNew([
                                    'invoice_id' => $Billing->id,
                                    'staff_id' => $incentive->staff_id,
                                    'incentive_id' => $incDetail->incentive_id,
                                    'type' => $incDetail->type,
                                    'entity_id' => $incDetail->entity_id,
                                    'value_type' => $incDetail->value_type,
                                    'value' => $incDetail->value
                                ]);

                                $IncentiveStaffDetail->price = $productserviceAmount;
                                $IncentiveStaffDetail->incenvite_value = $incentiveValue;
                                $IncentiveStaffDetail->date = \Carbon\Carbon::now();
                                $IncentiveStaffDetail->save();
                            }
                        }
                    }

                    //Hitung Komisi Seluruh Produk
                    if ($prodcutIncentive > 0) {
                        foreach ($invoiceProduct as $item) {
                            if (!in_array($item->product_id, $productIdHasIncentive)) {
                                $incentiveValue = ($item->price * ($prodcutIncentive / 100));

                                if ($incentiveValue != 0 && ($isService == true || $isProduct == true)) {
                                    $IncentiveStaffDetail = IncentiveStaffDetail::firstOrNew([
                                        'invoice_id' => $Billing->id,
                                        'staff_id' => $incentive->staff_id,
                                        'incentive_id' => $incentive->incentive_id,
                                        'type' => "product",
                                        'entity_id' => $item->product_id,
                                        'value_type' => "percent",
                                        'value' => $prodcutIncentive
                                    ]);

                                    $IncentiveStaffDetail->price = $item->price;
                                    $IncentiveStaffDetail->incenvite_value = $incentiveValue;
                                    $IncentiveStaffDetail->date = \Carbon\Carbon::now();
                                    $IncentiveStaffDetail->save();
                                }
                            }
                        }
                    }

                    //Hitung Point
                    if ($prodcutPoint > 0) {
                        foreach ($invoiceProduct as $item) {
                            if (!in_array($item->product_id, $productIdHasIncentive)) {
                                $incentiveValue = $prodcutPoint;

                                if ($incentiveValue != 0 && ($isService == true || $isProduct == true)) {
                                    $IncentiveStaffDetail = IncentiveStaffDetail::firstOrNew([
                                        'invoice_id' => $Billing->id,
                                        'staff_id' => $incentive->staff_id,
                                        'incentive_id' => $incentive->incentive_id,
                                        'type' => "product",
                                        'entity_id' => $item->product_id,
                                        'value_type' => "point",
                                        'value' => $prodcutPoint
                                    ]);

                                    $IncentiveStaffDetail->price = $item->price;
                                    $IncentiveStaffDetail->incenvite_value = $incentiveValue;
                                    $IncentiveStaffDetail->date = \Carbon\Carbon::now();
                                    $IncentiveStaffDetail->save();
                                }
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    public function storeInvoicePrescription($data = array('product' => array()), Billing $billing, FinanceTransaction $_transaction)
    {

        /** Save new Invoice detail and new finance journal */
        $totaloop = $data['product'];
        $saveDetail = false;
        // default variabel
        $tax = '';
        $sum_tax = 0;
        $tax_value = 0;
        $beban_pendapatan_value = 0;
        $pendapatan_value = 0;
        $sum_point = 0;

        DB::beginTransaction();
        try {
            foreach ($totaloop as $key => $val) {
                $dataVal = explode('#', $data['product'][$key]);

                if (sizeof($dataVal) == "2") {
                    $productBil = $dataVal[0];
                    $type = $dataVal[1];
                } else if (sizeof($dataVal) == "3") {
                    $productBil = $dataVal[1];
                    $type = $dataVal[0];
                }

                $product = '';
                if ($type == 'product') {
                    // check tax and sum point
                    $product = Product::findOrFail($productBil);
                    $sum_point = $sum_point + $product->point;

                    if ($product->tax != 'null') {
                        $tax = FinanceTax::find($product->tax_id);
                    }

                    //save billing detail
                    $billingDetail = BillingDetail::create([
                        'invoice_id' => $billing->id,
                        'product_id' => $productBil,
                        'price' => currency()->digit($data['price'][$key]),
                        'tax_label' => !empty($tax) ? $tax->tax_name . ' ' . $tax->percentage . ' %' : '',
                        'tax_value' => !empty($tax) ? $tax->percentage : '',
                        'qty' => $data['qty'][$key],
                        'type' => $type
                    ]);

                    //update stock product
                    $product->quantity = $product->quantity - $billingDetail->qty;

                    if ($product->quantity < $product->min_stock) {
                        $product->is_min_stock = 1;
                    }

                    $product->save();
                    if ($product->purchase_price_avg != 0 && $product->sales_type == 0) {
                        $product_value = $billingDetail->qty * $product->purchase_price_avg;
                    } else {
                        $product_value = $billingDetail->qty * $product->purchase_price;
                    }

                    // update persediaan
                    $_persediaan = FinanceAccount::sumKredit(config('finance_account.default.acc_persediaan_obat'), $product_value);

                    $_persediaanJournal = FinanceJournal::create([
                        'transaction_id' => $_transaction->id,
                        'account_id' => $_persediaan->id,
                        'balance' => $_persediaan->balance,
                        'type' => config('finance_journal.types.kredit'),
                        'value' => $product_value,
                        'tax' => '',
                        'tags' => 'is_persediaan'
                    ]);

                    $beban_pendapatan_value = $beban_pendapatan_value + $product_value;
                    $pendapatan_value = intval($pendapatan_value + $product->price);

                } else if ($type == 'service') {
                    $service = Service::findOrFail($productBil);
                    $sum_point = $sum_point + $service->point;

                    if ($service->tax != 'null') {
                        $tax = FinanceTax::find($service->tax_id);
                    }

                    //save billing detail
                    $billingDetail = BillingDetail::create([
                        'invoice_id' => $billing->id,
                        'product_id' => $service->id,
                        'price' => currency()->digit($data['price'][$key]),
                        'tax_label' => !empty($tax) ? $tax->tax_name . ' ' . $tax->percentage . ' %' : '',
                        'tax_value' => !empty($tax) ? $tax->percentage : '',
                        'qty' => $data['qty'][$key],
                        'type' => $type
                    ]);

                    $pendapatan_value = intval($pendapatan_value + $service->price);
                } else if ($type == "servicePackages") {
                    $getService = ServicesPackage::find("$explode_service[1]");
                    if (isset($getService->details)) {
                        foreach ($getService->details as $value) {

                            $service = Service::findOrFail($productBil);
                            $sum_point = $sum_point + $service->point;

                            if ($service->tax != 'null') {
                                $tax = FinanceTax::find($service->tax_id);
                            }

                            //save billing detail
                            $billingDetail = BillingDetail::create([
                                'invoice_id' => $billing->id,
                                'product_id' => $service->id,
                                'price' => currency()->digit($data['price'][$key]),
                                'tax_label' => !empty($tax) ? $tax->tax_name . ' ' . $tax->percentage . ' %' : '',
                                'tax_value' => !empty($tax) ? $tax->percentage : '',
                                'qty' => $data['qty'][$key],
                                'type' => $type
                            ]);

                            $pendapatan_value = intval($pendapatan_value + $service->price);
                        }
                    }
                } else {
                    return false;
                }

                // save tax and sum tax total
                if (!empty($tax)) {
                    //summary total tax
                    $tax_value = intval(($billingDetail->price * $billingDetail->qty) * ($billingDetail->tax_value / 100));

                    $_tax = FinanceAccount::sumKredit($tax->account_tax_sales, $tax_value);

                    $_taxJournal = FinanceJournal::create([
                        'transaction_id' => $_transaction->id,
                        'account_id' => $_tax->id,
                        'balance' => $_tax->balance,
                        'type' => config('finance_journal.types.kredit'),
                        'value' => $tax_value,
                        'tax' => '',
                        'tags' => 'is_tax'
                    ]);

                    $sum_tax = $sum_tax + $tax_value;
                }

                $saveDetail = true;
            }

            // update tax on billing
            $billing->tax_total = $sum_tax;
            $billing->save();

            //save beban pendapatan
            if ($beban_pendapatan_value > 0) {
                $beban_pendapatan = FinanceAccount::sumDebit(config('finance_account.default.acc_beban_pokok'), $beban_pendapatan_value);

                $beban_pendapatanJournal = FinanceJournal::create([
                    'transaction_id' => $_transaction->id,
                    'account_id' => $beban_pendapatan->id,
                    'balance' => $beban_pendapatan->balance,
                    'type' => config('finance_journal.types.debit'),
                    'value' => $beban_pendapatan_value,
                    'tax' => '',
                    'tags' => 'is_beban_pendapatan'
                ]);
            }

            //save to piutang
            $piutang_value = $pendapatan_value + $sum_tax;
            $piutang = FinanceAccount::sumDebit(config('finance_account.default.acc_piutang'), $piutang_value);
            $piutangJournal = FinanceJournal::create([
                'transaction_id' => $_transaction->id,
                'account_id' => $piutang->id,
                'balance' => $piutang->balance,
                'type' => config('finance_journal.types.debit'),
                'value' => $piutang_value,
                'tax' => '',
                'tags' => 'is_piutang'
            ]);

            //save to pendapatan
            $pendapatan = FinanceAccount::sumKredit(config('finance_account.default.acc_pendapatan'), $pendapatan_value);
            $pendapatanJournal = FinanceJournal::create([
                'transaction_id' => $_transaction->id,
                'account_id' => $pendapatan->id,
                'balance' => $pendapatan->balance,
                'type' => config('finance_journal.types.kredit'),
                'value' => $pendapatan_value,
                'tax' => '',
                'tags' => 'is_piutang'
            ]);

            // update membership point
            if ($billing->patient->membership) {
                $billing->patient->membership->total_point = $billing->patient->membership->total_point + $sum_point;
                $billing->patient->membership->save();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            if (env("APP_DEBUG") == true) {
                dd($e);
            }
            return false;
        }
        /** End Of Save new Invoice detail and new finance journal */

    }

    /**
     * example parame $serviceData = [ '1', '2'] value of array is service id
     */
    public function storeInvoiceTreatments(array $serviceData, Billing $billing, FinanceTransaction $_transaction, Treatment $treatment)
    {
        $saveDetail = false;

        // default variabel
        $tax = '';
        $sum_tax = 0;
        $tax_value = 0;
        $pendapatan_value = 0;
        $sum_point = 0;

        foreach ($serviceData as $val) {
            // check tax and sum point
            $service = Service::find($val);
            $sum_point = $sum_point + $service->point;

            if ($service->tax != 'null') {
                $tax = FinanceTax::find($service->tax_id);
            }

            $pendapatan_value = intval($pendapatan_value + $service->price);

            // save billing detail
            $billingDetail = BillingDetail::firstOrNew([
                'invoice_id' => $billing->id,
                'product_id' => $service->id,
                'price' => currency()->digit($service->price),
                'tax_label' => !empty($tax) ? $tax->tax_name . ' ' . $tax->percentage . ' %' : '',
                'tax_value' => !empty($tax) ? $tax->percentage : '',
                'type' => 'service'
            ]);

            $billingDetail->qty = 1 + (int)$billingDetail->qty;
            $save = $billingDetail->save();

            // save tax and sum tax total
            if (!empty($tax)) {
                //summary total tax
                $tax_value = intval(($billingDetail->price * $billingDetail->qty) * ($billingDetail->tax_value / 100));

                $_tax = FinanceAccount::sumKredit($tax->account_tax_sales, $tax_value);

                $_taxJournal = FinanceJournal::create([
                    'transaction_id' => $_transaction->id,
                    'account_id' => $_tax->id,
                    'balance' => $_tax->balance,
                    'type' => config('finance_journal.types.kredit'),
                    'value' => $tax_value,
                    'tax' => '',
                    'tags' => 'is_tax'
                ]);

                $sum_tax = $sum_tax + $tax_value;
            }

            //Save Maping Billing
            $TreatmentInvoice = TreatmentInvoice::firstOrNew(['treatment_id' => $treatment->id, 'invoice_id' => $billing->id]);
            $TreatmentInvoice->save();
            $saveDetail = true;
        }

        // update tax on billing
        $billing->tax_total = $sum_tax;
        $billing->save();


        //save to piutang
        $piutang_value = $pendapatan_value + $sum_tax;
        FinanceRepository::storePiutang($_transaction->id, $piutang_value);

        // save to pendapatan
        FinanceRepository::storePendapatan($_transaction->id, $pendapatan_value);
    }
}
