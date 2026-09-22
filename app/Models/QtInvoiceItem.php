<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QtInvoiceItem extends Model
{
    protected $table = 'qt_invoice_items';
    protected $primaryKey = 'item_id'; // Set custom primary key

    protected $fillable = [
        'quotation_id', 
        'module_id', 
        'catalog_item_id', 
        'unit_qty', 
        'days', 
        'daily_rate', 
        'mark_up', 
        'line_total'];

    public function quotation()
    {
        return $this->belongsTo(QtInvoice::class, 'quotation_id', 'quotation_Id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id'); // confirm Module's actual PK casing
    }

    public function catalogItem()
    {
        return $this->belongsTo(Item::class, 'catalog_item_id', 'item_id');
    }

}