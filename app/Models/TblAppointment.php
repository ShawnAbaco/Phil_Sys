<?php
// app/Models/TblAppointment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblAppointment extends Model
{
    protected $table = 'tbl_appointment';
    protected $primaryKey = 'n_id';
    
    // Enable timestamps to automatically handle created_at and updated_at
    // Your table already has timestamps() which creates created_at and updated_at columns
    public $timestamps = true;
    
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'q_id',
        'date',
        'queue_for',
        'status',
        'fname',
        'mname',
        'lname',
        'suffix',
        'age_category',
        'priority_type',  // Make sure priority_type is fillable
        'trn',
        'birthdate',
        'PCN',
        'window_num',
        'time_catered',
        'user_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'time_catered' => 'datetime',
        'birthdate' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    // If you need to get the operator who served the appointment
    public function operator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    // Helper method to get formatted created_at
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') : 'N/A';
    }
    
    // Helper method to get formatted updated_at
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') : 'N/A';
    }
    
    // Helper method to get priority type display
    public function getPriorityDisplayAttribute()
    {
        return match($this->priority_type) {
            'senior' => 'SENIOR',
            'infant' => 'INFANT',
            'pwd' => 'PWD',
            'pregnant' => 'PREGNANT',
            default => 'REGULAR'
        };
    }
    
    // Helper method to get priority class for styling
    public function getPriorityClassAttribute()
    {
        return $this->priority_type ?? 'regular';
    }
}