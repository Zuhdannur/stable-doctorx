<?php

namespace App\Modules\Accounting\Models\Traits\Attribute;

trait FinanceAccountAttribute
{
    /**
     * @return string
     */

    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.account.edit', $this).'" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Ubah Akun"><i class="fa fa-edit"></i></a> &nbsp;';
    }

    /**
     * @return string
     */

    public function getJournalButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.account.journal.show', $this).'" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Lihat Jurnal"><i class="fa fa-list"></i></a> &nbsp;';
    }
    
    /**
     * @return string
     */

    public function getJournalDetailButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.account.journal.detail', $this->transaction->id).'" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Detail"><i class="fa fa-list"></i></a> &nbsp;';
    }

    /** 
     * @return string
     */

    public function getReceiptMoneyButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.cash.receipt', $this).'" class="btn btn-sm btn-outline-success" data-toggle="tooltip" data-placement="top" title="Terima Uang"><i class="fa fa-credit-card"></i></a> &nbsp;';
    }

    /** 
     * @return string
     */

    public function getTransferMoneyButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.cash.transfer', $this).'" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Transfer Uang"><i class="fa fa-credit-card"></i></a> &nbsp;';
    }

    /** 
     * @return string
     */

    public function getSendMoneyButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.cash.send', $this).'" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Kirim Uang"><i class="fa fa-credit-card"></i></a> &nbsp;';
    }

    /** 
     * @return string
     */

    public function getJournalCashButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.cash.journal', $this).'" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Lihat Jurnal"><i class="fa fa-file-text" aria-hidden="true"></i></a> &nbsp;';
    }
    
}