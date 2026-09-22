<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'client_Id';

    protected $fillable = [
        'company_name',
        'client_address',
        'created_by',
        'updated_by',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_Id', 'client_Id');
    }
}