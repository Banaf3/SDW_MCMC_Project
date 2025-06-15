<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AgencyStaff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'agency_staff';
    protected $primaryKey = 'StaffID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'StaffName',
        'staffEmail',
        'Password',
        'staffPhoneNum',
        'ProfilePic',
        'LoginHistory',
        'AgencyID',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'Password',
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'StaffID';
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }
    
    /**
     * Get the email attribute mapped to the username.
     */
    public function getEmailAttribute()
    {
        return $this->staffEmail;
    }
    
    /**
     * Get the name attribute mapped.
     */
    public function getNameAttribute()
    {
        return $this->StaffName;
    }
    
    /**
     * Get the agency this staff belongs to.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'AgencyID', 'AgencyID');
    }
}
