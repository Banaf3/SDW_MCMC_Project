<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $primaryKey = 'AgencyID';

    protected $fillable = [
        'AgencyName',
        'AgencyEmail',
        'AgencyPhoneNum',
        'AgencyType'
    ];

    /**
     * Get the inquiries assigned to this agency
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'AgencyID', 'AgencyID');
    }

    /**
     * Get the staff members of this agency
     */
    public function staff()
    {
        return $this->hasMany(AgencyStaff::class, 'AgencyID', 'AgencyID');
    }
}
