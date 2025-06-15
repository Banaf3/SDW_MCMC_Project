<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyStaff extends Model
{
    protected $table = 'agency_staff';
    protected $primaryKey = 'StaffID';

    protected $fillable = [
        'StaffName',
        'Password',
        'staffEmail',
        'staffPhoneNum',
        'ProfilePic',
        'LoginHistory',
        'AgencyID'
    ];

    protected $hidden = [
        'Password'
    ];

    protected $casts = [
        'LoginHistory' => 'array',
        'Password' => 'hashed'
    ];

    /**
     * Get the agency that this staff member belongs to
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'AgencyID', 'AgencyID');
    }

    /**
     * Get inquiries handled by this staff member
     * Assuming AdminID in inquiries table refers to StaffID
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'AdminID', 'StaffID');
    }
}
