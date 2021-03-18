<?php

namespace App\Modules\Billing\Models;

use App\Modules\Accounting\Models\FinanceTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Billing\Models\PaymentHistory;
use App\Modules\Billing\Models\Traits\Attribute\BillingAttribute;

class Billing extends Model
{
	use SoftDeletes, BillingAttribute;

    protected $table = 'invoices';
    protected $appends = ['discount_percent'];
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
        });
    }

    protected $fillable = [
    	'invoice_no',
        'patient_id',
        'appointment_id',
        'status',
        'is_payment',
    	'note',
    	'date',
        'tax_total',
        'discount',
        'total',
        'in_paid',
        'created_by',
        'marketing_id',
        'total_ammount',
        'remaining_payment',
        'radeem_point',
        'id_klinik',
        'notes'
    ];

    public function getDiscountPercentAttribute() {
        return $this->discount ? intval($this->discount) . '%' : '0%';
    }

    public function setDateAttribute($value)
	{
	    $this->attributes['date'] = \DateTime::createFromFormat(setting()->get('date_format'), $value)->format('Y-m-d');
	}

    public function getDateAttribute()
    {
        return \DateTime::createFromFormat('Y-m-d', $this->attributes['date'])->format(setting()->get('date_format'));
    }

    public function getTotalAttribute()
    {
        return \DateTime::createFromFormat('Y-m-d', $this->attributes['date'])->format(setting()->get('date_format'));
    }

    public static function generateInvoiceNumer($date = '')
    {

        if($date == ''){
            $dt = \Carbon\Carbon::now()->format('Ymd');
            $date = \Carbon\Carbon::today();
        }else{
            $dt = \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Ymd');
            $date = \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        }

    	$separator = setting()->get('invoice_separator');
    	$numberdigit = setting()->get('invoice_digit');
    	$code = strtoupper(setting()->get('invoice_code')).$separator.$dt.$separator;

    	$count = self::whereDate('date', $date)->withTrashed()->count();

		$newId = $code . str_pad($count + 1, $numberdigit, 0, STR_PAD_LEFT);

		return $newId;
    }

    public function patient()
    {
    	return $this->hasOne('App\Modules\Patient\Models\Patient', 'id', 'patient_id');
    }

    public function invDetail()
    {
        return $this->hasMany('App\Modules\Billing\Models\BillingDetail', 'invoice_id', 'id');
    }

    public function getTotalPriceAttribute() {
        // return $this->invDetail()->sum(\DB::raw('qty * price')) + ($this->invDetail()->sum(\DB::raw('qty * price')) * ($this->tax/100)) - ($this->invDetail()->sum(\DB::raw('qty * price')) * ($this->discount/100));
        return intval( ($this->invDetail()->sum(\DB::raw('qty * price')) + ($this->invDetail()->sum(\DB::raw('qty * price * tax_value / 100')) )) - ( ($this->invDetail()->sum(\DB::raw('qty * price')) + ($this->invDetail()->sum(\DB::raw('qty * price * tax_value / 100')) )) * ($this->discount /100)) );
    }

    public function getTotalPriceStrukAttribute() {
        $invDetail = BillingDetail::where('invoice_id', $this->id)
            ->where('payment_step', $this->is_payment);
        $sum1 = $invDetail->sum(\DB::raw('qty * price'));
        $sum2 = $invDetail->sum(\DB::raw('qty * price * tax_value / 100'));
        $sum = intval( $sum1 + $sum2 - ( ($sum1 + $sum2) * ($this->discount /100) ));

        return $sum;
    }

    public function getSubtotalAttribute() {
        // return $this->invDetail()->sum(\DB::raw('qty * price'));
        return intval($this->invDetail()->sum(\DB::raw('qty * price')) + ($this->invDetail()->sum(\DB::raw('qty * price * tax_value / 100')) ));
    }

    public function getDiscountPriceAttribute() {
        return $this->sub_total * $this->attributes['discount'] / 100;
    }

    public function getTotalPayAttribute()
    {
        return intval($this->paymentHistory()->sum('total_pay'));
    }

    public function treatmentInvoice()
    {
        return $this->hasOne('App\Modules\Patient\Models\TreatmentInvoice', 'invoice_id', 'id');
    }

    public function appointmentInvoice()
    {
        return $this->hasOne('App\Modules\Patient\Models\AppointmentInvoice', 'invoice_id', 'id');
    }

    public function radeem()
    {
        return $this->hasMany('App\Modules\Crm\Models\CrmRadeemPoint', 'invoice_id', 'id');
    }

    public function marketing()
    {
        return $this->hasOne('App\Modules\Crm\Models\CrmMarketing', 'id', 'marketing_id');
    }

    public function paymentHistory()
    {
        return $this->hasMany('App\Modules\Billing\Models\PaymentHistory', 'invoice_id', 'id');
    }

    public function getLastPaymentAttribute()
    {
        return PaymentHistory::select('total_pay', 'in_paid')->where('invoice_id', $this->attributes['id'])
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getQtyAttribute() {
        return intval($this->invDetail()->sum('qty'));
    }

    public function finance_transaction() {
        return $this->hasOne('App\Modules\Accounting\Models\FinanceTransaction','transaction_code','invoice_no');
    }

    public function getLastPaymentHistoriAttribute() {
        return $this->paymentHistory()->orderBy('created_at','desc');
    }
}
