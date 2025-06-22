<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquiryAuditLog extends Model
{
    protected $primaryKey = 'AuditLogID';

    protected $fillable = [
        'InquiryID',
        'AdminID',
        'Action',
        'OldStatus',
        'NewStatus',
        'ActionDate',
        'Reason',
        'Notes',
        'PerformedBy'
    ];

    protected $casts = [
        'ActionDate' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the inquiry that owns this audit log entry
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'InquiryID', 'InquiryID');
    }

    /**
     * Get the administrator who performed this action
     */
    public function administrator()
    {
        return $this->belongsTo(Administrator::class, 'AdminID', 'AdminID');
    }
}
