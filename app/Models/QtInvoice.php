<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QtInvoice extends Model
{
    // Table name specification
    protected $table = 'qt_invoice';
    protected $primaryKey = 'quotation_Id'; // Set custom primary key

    protected $fillable = [
        'project_Id', 
        'quotation_no', 
        'payment_terms',
        'additional_notes',
        'grand_total', 
        'created_by', 
        'updated_by'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_Id', 'project_Id');
    }

    public function items()
    {
        return $this->hasMany(QtInvoiceItem::class, 'quotation_id', 'quotation_Id');
    }

    public function paymentTerms()
    {
        return $this->hasMany(PaymentTerm::class, 'quotation_Id', 'quotation_Id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'quotation_Id', 'quotation_Id');
    }
}