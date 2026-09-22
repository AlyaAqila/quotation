<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';
    protected $primaryKey = 'unit_id';

    protected $fillable = [
        'unit_name',
        'created_by',
        'updated_by',
    ];

    // Relationship: A unit can belong to many items
    public function items()
    {
        return $this->hasMany(Item::class, 'unit_id', 'unit_id');
    }
}