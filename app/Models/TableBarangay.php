<?php
// app/Models/TableBarangay.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableBarangay extends Model
{
    protected $table = 'table_barangay';
    protected $primaryKey = 'barangay_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'municipality_id',
        'barangay_name'
    ];

    // Relationships
    public function municipality()
    {
        return $this->belongsTo(TableMunicipality::class, 'municipality_id', 'municipality_id');
    }
}
