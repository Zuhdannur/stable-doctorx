<?php

namespace App\Modules\Product\Models\Traits\Attribute;

/**
 * Traits for supplier
 */
trait SupplierAttribute
{
    /**
     * @return string
     */

    public function getDetailButtonAttribute()
    {
        return '<a href="'.route('admin.product.supplier.show', $this).'" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Detail Supplier"><i class="fa fa-list"></i></a> &nbsp;';
    }
}
