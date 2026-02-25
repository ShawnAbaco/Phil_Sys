<?php
// app/Models/TableRegion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableRegion extends Model
{
    protected $table = 'table_region';
    protected $primaryKey = 'region_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'region_name',
        'region_description'
    ];

    // Relationships
    public function provinces()
    {
        return $this->hasMany(TableProvince::class, 'region_id', 'region_id');
    }
}
