<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    protected $fillable = [
        'quotation_Id',
        'invoice_Id',
        'source_term_id',
        'name',
        'percentage',
        'condition',
        'amount',
        'created_by',
        'updated_by',
    ];

    public function quotation()
    {
        return $this->belongsTo(QtInvoice::class, 'quotation_Id', 'quotation_Id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_Id', 'invoice_Id');
    }

    // The copy of this term that was made when an invoice was generated (if any)
    public function invoiceCopy()
    {
        return $this->hasOne(PaymentTerm::class, 'source_term_id', 'id')->whereNotNull('invoice_Id');
    }
}