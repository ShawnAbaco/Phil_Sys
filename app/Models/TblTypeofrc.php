<?php
// app/Models/TblTypeofrc.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblTypeofrc extends Model
{
    protected $table = 'tbl_typeofrc';
    protected $primaryKey = 'trc_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'type_rc',
        'description'
    ];
}
