<?php
// app/Models/TableMunicipality.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableMunicipality extends Model
{
    protected $table = 'table_municipality';
    protected $primaryKey = 'municipality_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'province_id',
        'municipality_name'
    ];

    // Relationships
    public function province()
    {
        return $this->belongsTo(TableProvince::class, 'province_id', 'province_id');
    }

    public function barangays()
    {
        return $this->hasMany(TableBarangay::class, 'municipality_id', 'municipality_id');
    }
}
