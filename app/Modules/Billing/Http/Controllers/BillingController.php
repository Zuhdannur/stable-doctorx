<?php

namespace App\Modules\Billing\Http\Controllers;

use App\Helpers\Auth\Auth;
use PDF;

use DataTables;
use Mike42\Escpos\Printer;

use Illuminate\Http\Request;
use Mike42\Escpos\EscposImage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modules\Billing\Models\Billing;

// finance model
use App\Modules\Patient\Models\Patient;
use App\Modules\Product\Models\Product;

//crm model
use App\Modules\Product\Models\Service;
use App\Modules\Crm\Models\CrmMarketing;

use App\Modules\Crm\Models\CrmMsRadeemPoint;
use App\Modules\Accounting\Models\FinanceTax;
use App\Modules\Product\Models\ProductCategory;

use App\Modules\Patient\Models\PatientAdmission;

use App\Modules\Accounting\Models\FinanceAccount;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use App\Modules\Billing\Repositories\BillingRepository;
use App\Modules\Billing\Http\Requests\Billing\StoreBillingRequest;
use App\Modules\Billing\Http\Requests\Billing\ManageBillingRequest;

class BillingController extends Controller
{
	protected $billingRepository;

    public function __construct(BillingRepository $billingRepository)
    {
        $this->billingRepository = $billingRepository;
    }

    public function index(Request $request)
    {
    	if($request->ajax()){
            $model = Billing::where('id_klinik',auth()->user()->id_klinik)->with('patient')->orderBy('created_at', 'desc');

            return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('patient_name', function ($data) {
                $patient = $data->patient;
                return $patient->patient_name;
            })
            ->addColumn('status', function ($data) {
                $status = '';
                switch ($data->status) {
                    case 0 :
                        $status = '<span class="badge badge-danger">'.config('billing.unpaid').'</span>' ;
                        break;
                    case 1 :
                        $status = '<span class="badge badge-success">'.config('billing.paid').'</span>' ;
                        break;
                    case 3 :
                        $status = '<span class="badge badge-warning">'.config('billing.partial_paid').'</span>' ;
                        break;
                }
                return $status;
            })
            ->addColumn('total', function ($data) {
                $total = $data->totalPrice;
                return currency()->rupiah($total, setting()->get('currency_symbol'));
            })
            ->addColumn('klinik',function ($data) {
                $patient = @$data->patient->klinik->nama_klinik;
                return $patient;
            })
            ->addColumn('action', function ($data) {
                $button = $data->action_buttons;
                return $button;
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['action', 'status'])
            ->make(true);
        }

        return view('billing::billing.index');
    }

    public function create($patientId, $queueId)
    {
        //Init
        if($queueId){
            $admisson = PatientAdmission::find($queueId);
            if($admisson->reference_id){
                $appointment = Appointment::where('appointment_no', $admisson->reference_id)->first();

                $prescription = $appointment->prescription;
                $patient = $appointment->patient;
            }
        }
        $pid = 0;
        if($patientId){
            $patientId = Patient::findOrFail($patientId);
            $pid = $patientId->patient_unique_id;
        }

    	$patient = Patient::get();
        $products = Product::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();
        $services = Service::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();
    	/*$ProductCategory = new ProductCategory;
        $categories = $ProductCategory::select('id', 'name', 'parent_id')->get()->toTree();
        $flatData = $ProductCategory->flatten($categories);
        $category = collect($flatData);*/

        // Create a new collection instance.
        $collection = new Collection($products);

        // Merge an array with the existing collection.
        $newCollection = $collection->merge($services);
        $option = '<option></option>';
        foreach ($newCollection as $key => $val) {
            $productName = $val->code.' - '.$val->name.' - '.$val->category->name;
            $option .= '<option value="'.$val->id.'#'.strtolower($val->type).'" data-type="'.strtolower($val->type).'" data-price="'.$val->price.'" data-point="'.$val->point.'" data-tax="'.$val->tax_id.'" data-min-qty="'.$val->min_qty.'">';
            $option .=strtoupper($productName).'</option>';
        }

        // get cash account
        $cashAccount = FinanceAccount::where('account_category_id', config('finance_account.default.cash'))->get();

        $cashAccountList = '';
        foreach($cashAccount as $val){
            $cashAccountList .= '<option value="'.$val->id.'">'.$val->account_name.'</option>';
        }

        // get marketing activity
        $marketing = CrmMarketing::where('is_active', 1)
        ->where('end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'))
        ->get();

        $marketingList = '<option></option>';
        foreach ($marketing as  $val) {
            $marketingList .= '<option value="'.$val->id.'" data-point="'.$val->point.'" data-discount="'.$val->discount.'">'.$val->name.' ('.$val->code.')</option>';
        }

        //get tax list
        $taxList = FinanceTax::optionList();

        return view('billing::billing.create')
        ->withDataproducts($option)
        ->withPatients($patient)
        ->withPid($pid)
        ->withQid($queueId)
        ->withCashAccountList($cashAccountList)
        ->withMarketingList($marketingList)
        ->withTaxList($taxList);

        // return view('billing::billing.create')->withCategory(\Form::select('category[]', $category->pluck('name','id'), null, ['placeholder' => 'Please select', 'id' => '1select2', 'data-placeholder' => 'Please select', 'class' => 'form-control categoryProduct required']));
    }

    public function store(StoreBillingRequest $request)
    {
        // die(json_encode($request));
        $save = $this->billingRepository->create($request->input());

        if($save){
            $status = true;
            $message = __('billing::alerts.billing.created');
        }else{
            $status = false;
            $message = trans('billing::exceptions.billing.create_error');
        }

        return response()->json(array('status' => $status, 'message' => $message, 'data' => $save));
    }

    public function storepaid($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $Billing = Billing::findOrFail($id);
            $this->billingRepository->storepaid($Billing, $request);
            DB::commit();
            return response()->json(array('status' => true, 'message' => 'Data Berhasil disimpan', 'data' => $Billing));
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(array('status' => false, 'message' => $e->getMessage()), 500);
        }
    }

    public function show(Billing $billing)
    {
        // radeem list
        $radeem = CrmMsRadeemPoint::optionList();

        // get cash account
        $cashAccount = FinanceAccount::where('account_category_id', config('finance_account.default.cash'))->get();

        $cashAccountList = '';
        foreach($cashAccount as $val){
            $cashAccountList .= '<option value="'.$val->id.'">'.$val->account_name.'</option>';
        }


        if($billing->status == 0){
            // get marketing activity
            $marketing = CrmMarketing::where('is_active', 1)
            ->where('end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'))
            ->get();

            $marketingList = '<option></option>';
            foreach ($marketing as  $val) {
                $marketingList .= '<option value="'.$val->id.'" data-point="'.$val->point.'" data-discount="'.$val->discount.'">'.$val->name.' ('.$val->code.')</option>';
            }

            return view('billing::billing.show')->withBilling($billing)
            ->withMarketingList($marketingList)
            ->withRadeem($radeem)
            ->withCashAccountList($cashAccountList);
        }else if($billing->status == 3){
            return view('billing::billing.show-partial')->withBilling($billing)
            ->withRadeem($radeem)
            ->withCashAccountList($cashAccountList);

        }else{
            return view('billing::billing.show-finish')->withBilling($billing);
        }
    }

    public function edit(Billing $billing)
    {
        if (auth()->user()->cannot('update billing')) {
            throw new GeneralException(trans('exceptions.cannot_edit'));
        }

        $invDetail = $billing->invDetail;

        // die(json_encode($invDetail));

        $patient = Patient::findOrFail($billing->patient->id);
        $products = Product::where('id_klinik',auth()->user()->klinik->id_klinik)->get();
        $services = Service::where('id_klinik',auth()->user()->klinik->id_klinik)->get();

        $cashAccount = FinanceAccount::where('account_category_id', config('finance_account.default.cash'))->get();

        $cashAccountList = '';
        foreach($cashAccount as $val){
            $cashAccountList .= '<option value="'.$val->id.'">'.$val->account_name.'</option>';
        }

        // get marketing activity
        $marketing = CrmMarketing::where('is_active', 1)
        ->where('end_date', '>=', \Carbon\Carbon::now()->format('Y-m-d'))
        ->get();

        //get tax list
        $taxList = FinanceTax::optionList();
        $marketingList = '<option></option>';
        foreach ($marketing as  $val) {
            $marketingList .= '<option value="'.$val->id.'" data-point="'.$val->point.'" data-discount="'.$val->discount.'">'.$val->name.' ('.$val->code.')</option>';
        }

        // Create a new collection instance.
        $collection = new Collection($products);

        // Merge an array with the existing collection.
        $newCollection = $collection->merge($services);
        $option = '<option></option>';
        foreach ($newCollection as $key => $val) {
            $productName = $val->code.' - '.$val->name.' - '.$val->category->name;
            $option .= '<option value="'.$val->id.'#'.strtolower($val->type).'" data-type="'.strtolower($val->type).'" data-price="'.$val->price.'">'.strtoupper($productName).'</option>';
        }

        $htmlProductDetail = null;
        $numProduct = 1;

        $keyProduct = 1;

        if($invDetail){
            $numProduct = count($invDetail);
            foreach ($invDetail as $data) {
                $htmlProductDetail .= '<tr>
                                        <td>
                                            <button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button>
                                        </td>
                                        <td class="nomor">
                                            '.$keyProduct.'
                                        </td>
                                        <td>
                                            <select name="product['.$keyProduct.']" id="'.$keyProduct.'"productselect2" class="form-control product required" data-placeholder="Pilih" style="width: 100%">';
                                            foreach ($newCollection as $key => $val) {
                                                $name = $val->code.' - '.$val->name.' - '.$val->category->name;
                                                $isSelected = null;
                                                if($data->type == $val->type){
                                                    $isSelected = ($val->id == $data->product_id ? "selected" : "");
                                                }

                                                $htmlProductDetail .= '<option value="'.$val->id.'#'.strtolower($val->type).'" data-point="'.$val->point.'" data-tax="'.$val->tax_id.'" data-price="'.$val->price.'" data-min-qty="'.$val->min_qty.'" '.$isSelected.'>';
                                                $htmlProductDetail .= $name.'</option>';


                                            }
                $htmlProductDetail .= '</select>
                                        </td>
                                        <td>
                                            <input type="number" name="qty['.$keyProduct.']" id="'.$keyProduct.'qty" placeholder="0" class="form-control qty required" min="1" value='."$data->qty".' />
                                        </td>
                                        <td>
                                            <input type="number" name="before_discount['.$keyProduct.']" placeholder="0" class="form-control before-discount" min="0" value='.(intval($data->price) + intval($data->discount) ).' />
                                        </td>
                                         <td>
                                            <input type="number" name="discount_item['.$keyProduct.']" placeholder="0" class="form-control discount-item" min="0" value='.intval($data->discount).' />
                                        </td>
                                        <td>
                                            <input type="number" name="price['.$keyProduct.']" id="'.$keyProduct.'price" placeholder="0" class="form-control text-right price required" min="1" readonly/>
                                        </td>
                                        <td>
                                            <input type="text" name="notes_item['.$keyProduct.']" id="'.$keyProduct.'notes" placeholder="Keterangan" class="form-control"/>
                                        </td>';
                $htmlProductDetail .= '<td>
                                            <select name="tax['.$keyProduct.']" id="'.$keyProduct.'taxselect2" class="form-control tax" data-placeholder="Pilih" style="width=: 100%">'.$taxList.'</select>
                                        </td>
                                        <td>
                                            <input type="text" name="total['.$keyProduct.']" id="'.$keyProduct.'total" placeholder="0" class="form-control total tanpa-rupiah text-right" readonly/>
                                        </td>
                                    </tr>';
                $keyProduct++;

            }
        }

        // radeem list
        $radeem = CrmMsRadeemPoint::optionList();

        return view('billing::billing.edit')
        ->withBilling($billing)
        ->withRadeem($radeem)
        ->withHtmlProductDetail($htmlProductDetail)
        ->withDataproducts($option)->withPatients($patient)
        ->withNumproduct($numProduct)
        ->withMarketingList($marketingList)
        ->withCashAccountList($cashAccountList)
        ->withTaxList($taxList);
    }

    public function update(Request $request, Billing $billing)
    {
        try {
            DB::beginTransaction();
            $this->billingRepository->update($billing, $request->input());
            DB::commit();
            return response()->json(array('status' => true, 'message' => 'Data Berhasil diperbaharui', 'data' => $billing));
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(array('status' => false, 'message' => $e->getMessage()), 500);
        }
    }

    public function destroy(Billing $billing)
    {
        if (auth()->user()->cannot('delete billing')) {
            throw new GeneralException(trans('exceptions.cannot_delete'));
        }

        $billing->delete();

        return redirect()->back()->withFlashSuccess('Data berhasil di hapus.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf(Billing $billing)
    {
        $data = ['billing' => $billing];
        $pdf = PDF::loadView('billing::billing.print.billing-pdf', $data);

        return $pdf->stream();
    }

    public function html(Billing $billing , Request $request){
        // return view('billing::billing.print.billing-html', compact('billing'));
        $data['billing'] = $billing;
        $data['message'] = $request->message;
        return view('billing::billing.print.struk-html')->with($data);
    }

    public function print_receipt($id)
    {
         $struk = $this->menu_model->getStruks(array('where' => array('pembayaran.id_order' => $id)));
         $conf = $this->office_model->get_config_resto();
         require_once dirname(__FILE__) . "/Escpos.php";
         $fp = fopen("/dev/usb/lp1", "w");
         $printer = new Escpos($fp);
         $printer->lineSpacing(19);
         $printer->setJustification(1);
         $printer->text($conf->row('nama_resto') . "\n");
         $printer->text($conf->row('alamat_resto') . "\n");
         $printer->text($conf->row('telephone') . $hp . "\n");
         $printer->setJustification();
         $printer->text("\n");
         $printer->text("No. Meja : " . $struk->row('no_meja') . " || Nama : " . $struk->row('nama_konsumen') . "\n");
         $printer->text("----------------------------------------");
         $printer->text("Nama Menu          Jml  Harga   SubTot\n");
         $printer->text("----------------------------------------");
         foreach ($struk->result() as $key => $v) {
             $sub_tot = $v->harga * $v->jumlah_selesai;
             $printer->text($v->nama_menu . " \n");
             $printer->setJustification(2);
             $printer->text($v->jumlah_selesai . " | " . outNominal($v->harga) . " | " . outNominal($sub_tot) . " \n");
             $printer->setJustification();
         }
         $printer->text("         -------------------------------");
         $printer->text("                 TOTAL  : Rp.   " . outNominal($struk->row('total')) . " \n");
         $printer->text("                 DISC   : %     " . $struk->row('discount') . " \n");
         $printer->text("           GRAND TOTAL  : Rp.   " . outNominal($struk->row('grand_total')) . " \n");
         $printer->text("                 TUNAI  : Rp.   " . outNominal($struk->row('fisik_uang')) . " \n");
         $printer->text("                 KEMBALI: Rp.   " . outNominal($struk->row('kembali')) . " \n");
         $printer->text("----------------------------------------");
         $printer->setJustification(1);
         $printer->text("Terimakasih atas kunjunganya.\n");
         $printer->setJustification();
         $printer->text("----------------------------------------");
         $printer->text("Kasir : " . $this->session->userdata('display_name') . "       \n");
         $printer->text(date("d/m/Y H:i:s") . "\n");
         $printer->text("\n");
         $printer->text("\n");
         $printer->cut();
    }

    public function test(){
        require __DIR__ . '/vendor/autoload.php';
        /* Open the printer; this will change depending on how it is connected */
        $connector = new FilePrintConnector("/dev/usb/lp0");
        $printer = new Printer($connector);

        /* Information for the receipt */
        $items = array(
            new item("Example item #1", "4.00"),
            new item("Another thing", "3.50"),
            new item("Something else", "1.00"),
            new item("A final item", "4.45"),
        );
        $subtotal = new item('Subtotal', '12.95');
        $tax = new item('A local tax', '1.30');
        $total = new item('Total', '14.25', true);
        /* Date is kept the same for testing */
        // $date = date('l jS \of F Y h:i:s A');
        $date = "Monday 6th of April 2015 02:56:25 PM";

        /* Start the printer */
        $logo = EscposImage::load("resources/escpos-php.png", false);
        $printer = new Printer($connector);

        /* Print top logo */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> graphics($logo);

        /* Name of shop */
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text("ExampleMart Ltd.\n");
        $printer -> selectPrintMode();
        $printer -> text("Shop No. 42.\n");
        $printer -> feed();

        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> text("SALES INVOICE\n");
        $printer -> setEmphasis(false);

        /* Items */
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> setEmphasis(true);
        $printer -> text(new item('', '$'));
        $printer -> setEmphasis(false);
        foreach ($items as $item) {
            $printer -> text($item);
        }
        $printer -> setEmphasis(true);
        $printer -> text($subtotal);
        $printer -> setEmphasis(false);
        $printer -> feed();

        /* Tax and total */
        $printer -> text($tax);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text($total);
        $printer -> selectPrintMode();

        /* Footer */
        $printer -> feed(2);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text("Thank you for shopping at ExampleMart\n");
        $printer -> text("For trading hours, please visit example.com\n");
        $printer -> feed(2);
        $printer -> text($date . "\n");

        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> pulse();

        $printer -> close();
    }

    public function textprint(){
        $connector = new FilePrintConnector("php://stdout");
        $printer = new Printer($connector);
        $printer -> text("Hello World!\n");
        $printer -> cut();
        $printer -> close();
    }
}
