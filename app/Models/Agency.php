<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = 'agencies';
    protected $primaryKey = 'AgencyID';
    public $timestamps = false;
    
    protected $fillable = [
        'AgencyName',
        'AgencyEmail',
        'AgencyPhoneNum',
        'AgencyType'
    ];

    // Relationship with Inquiries
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'AgencyID', 'AgencyID');
    }

    // Relationship with Agency Staff
    public function staff()
    {
        return $this->hasMany(AgencyStaff::class, 'AgencyID', 'AgencyID');
    }
}
