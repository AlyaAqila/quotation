<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'module_id',
        'category_name',
        'is_active',
    ];

    // Hubungan: Belongs to Module (Jika Module -> Category)
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id');
    }

    // Hubungan: Satu Category ada banyak Item
    public function items()
    {
        return $this->hasMany(Item::class, 'category_id', 'category_id');
    }

    // Jika Service di bawah Category
    public function services()
    {
        return $this->hasMany(Service::class, 'category_id', 'category_id');
    }
}
