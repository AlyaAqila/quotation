<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $primaryKey = 'project_Id';

    protected $fillable = [
        'number',
        'name',
        'period',
        'client_Id',
        'pic_name',
        'pic_no',
        'created_by',
        'updated_by',
        'location', 
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_Id', 'client_Id');
    }

    public function lineItems()
    {
        return $this->hasMany(QtInvoice::class, 'project_Id', 'project_Id');
    }
}