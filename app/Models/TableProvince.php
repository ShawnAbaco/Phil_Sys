<?php
// app/Models/TableProvince.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableProvince extends Model
{
    protected $table = 'table_province';
    protected $primaryKey = 'province_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'region_id',
        'province_name'
    ];

    // Relationships
    public function region()
    {
        return $this->belongsTo(TableRegion::class, 'region_id', 'region_id');
    }

    public function municipalities()
    {
        return $this->hasMany(TableMunicipality::class, 'province_id', 'province_id');
    }
}
