<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedInquiry extends Model
{
    protected $table = 'assigned_inquiries';
    
    // No auto-incrementing primary key since this uses composite key
    public $incrementing = false;
    protected $primaryKey = null;
    
    // Disable timestamps since this table doesn't have them
    public $timestamps = false;

    protected $fillable = [
        'AdminID',
        'AgencyID', 
        'InquiryID',
        'AssignedDate'
    ];

    protected $casts = [
        'AssignedDate' => 'date'
    ];

    /**
     * Get the inquiry that is assigned
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'InquiryID', 'InquiryID');
    }

    /**
     * Get the agency that the inquiry is assigned to
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'AgencyID', 'AgencyID');
    }

    /**
     * Get the administrator who made the assignment
     */
    public function administrator()
    {
        return $this->belongsTo(Administrator::class, 'AdminID', 'AdminID');
    }
}
