<?php

namespace App\Modules\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Billing\Models\Traits\Attribute\BillingAttribute;

class PaymentHistory extends Model
{
    protected $fillable = [
    	'invoice_id', 
        'total_pay', 
        'in_paid',
    ];

    public function marketing()
    {
        return $this->hasOne('App\Modules\Crm\Models\CrmMarketing', 'id', 'marketing_id');
    }
}
