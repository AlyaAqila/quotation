<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_Id';

    protected $fillable = [
        'invoice_number',
        'quotation_Id',
        'invoice_date',
        'printed_date',
        'due_date',
        'description',
        'created_by',
        'updated_by',
    ];

    public function quotation()
    {
        return $this->belongsTo(QtInvoice::class, 'quotation_Id', 'quotation_Id');
    }

    // Since each invoice has ONE payment term line
    public function paymentTerm()
    {
        return $this->hasOne(PaymentTerm::class, 'invoice_Id', 'invoice_Id');
    }
}