<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'module_id',
        'category_id',
        'service_id',
        'unit_id',
        'item_name',
        'internal_rate',
        'description',
        'created_by',
        'updated_by',
    ];

    // Automatically link to Module
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    // Automatically link to Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Automatically link to Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    //automatically link to unit
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    // Audit Trail Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    
}