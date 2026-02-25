<?php
// app/Models/TblUser.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblUser extends Model
{
    protected $table = 'tbl_user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'full_name',
        'designation',
        'window_num',
        'reg',
        'prov',
        'mun',
        'brgy',
        'specific_loc',
        'username',
        'password',
        'password_hashed'
    ];

    protected $hidden = [
        'password',
        'password_hashed'
    ];

    // Relationships
    public function region()
    {
        return $this->belongsTo(TableRegion::class, 'reg', 'region_id');
    }

    public function province()
    {
        return $this->belongsTo(TableProvince::class, 'prov', 'province_id');
    }

    public function municipality()
    {
        return $this->belongsTo(TableMunicipality::class, 'mun', 'municipality_id');
    }

    public function barangay()
    {
        return $this->belongsTo(TableBarangay::class, 'brgy', 'barangay_id');
    }
}
