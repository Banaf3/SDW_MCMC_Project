<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquiryAuditLog extends Model
{
    protected $primaryKey = 'AuditLogID';

    protected $fillable = [
        'Action',
        'PerformedBy',
        'ActionDate',
        'InquiryID'
    ];

    protected $dates = [
        'ActionDate'
    ];

    /**
     * Get the inquiry that owns this audit log entry
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'InquiryID', 'InquiryID');
    }
}
