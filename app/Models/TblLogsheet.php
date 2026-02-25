<?php
// app/Models/TblLogsheet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblLogsheet extends Model
{
    protected $table = 'tbl_logsheet';
    protected $primaryKey = 'ls_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ls_fname', 'ls_mname', 'ls_lname', 'ls_suf', 'ls_category',
        'ls_accompany', 'ls_accom_pcn', 'ls_overseas', 'ls_dswd', 'ls_trn',
        'ls_ephlid_stat', 'ls_ephilid_date', 'ls_contact', 'ls_services_availed',
        'ls_date', 'ls_oldtrn_recapture', 'ls_rko', 'ls_cntr_mun', 'ls_cntr_brgy',
        'ls_cntr_spcloc', 'ls_cbms', 'ls_cbms_addr', 'ls_birth', 'ls_ffname',
        'ls_fmname', 'ls_flname', 'ls_fsuf', 'ls_mfname', 'ls_mmname', 'ls_mlname',
        'ls_authentication', 'ls_birthdate'
    ];

    protected $casts = [
        'ls_ephilid_date' => 'date',
        'ls_date' => 'date',
        'ls_birth' => 'date',
        'ls_birthdate' => 'date'
    ];
}
