<?php
// app/Models/TblAppointment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblAppointment extends Model
{
    protected $table = 'tbl_appointment';
    protected $primaryKey = 'n_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'q_id',
        'date',
        'queue_for',
        'fname',
        'mname',
        'lname',
        'suffix',
        'age_category',
        'trn',
        'birthdate',
        'PCN',
        'window_num',
        'time_catered'
    ];

    protected $casts = [
        'date' => 'datetime',
        'time_catered' => 'datetime',
        'birthdate' => 'date'
    ];
}
