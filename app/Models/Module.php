<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $primaryKey = 'module_id';

    protected $fillable = [
        'module_name',
        'description',
        'is_active',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'module_id', 'module_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'module_id', 'module_id');
    }

}