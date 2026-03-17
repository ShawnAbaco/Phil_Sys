<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'full_name',
        'designation',
        'window_num',
        'reg',
        'prov',
        'mun',
        'brgy',
        'specific_loc',
        'username',
        'password_hashed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'password_hashed',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the appointments for the user.
     */
    public function appointments()
    {
        return $this->hasMany(TblAppointment::class, 'user_id', 'id');
    }

    /**
     * Get the logsheets for the user.
     */
    public function logsheets()
    {
        return $this->hasMany(TblLogsheet::class, 'user_id', 'id');
    }
}
