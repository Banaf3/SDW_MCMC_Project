<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $primaryKey = 'InquiryID';

    protected $fillable = [
        'InquiryTitle',
        'SubmitionDate',
        'InquiryStatus',
        'StatusHistory',
        'InquiryDescription',
        'InquiryEvidence',
        'AdminComment',
        'ResolvedExplanation',
        'ResolvedSupportingDocs',
        'AgencyRejectionComment',
        'AgencyID',
        'AdminID',
        'UserID'
    ];    protected $casts = [
        'SubmitionDate' => 'datetime',
        'StatusHistory' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function timeline()
    {
        return $this->hasMany(InquiryAuditLog::class, 'InquiryID')->orderBy('ActionDate');
    }

    public function assignedAgency()
    {
        return $this->belongsTo(Agency::class, 'AgencyID', 'AgencyID');
    }

    public function administrator()
    {
        return $this->belongsTo(Administrator::class, 'AdminID', 'AdminID');
    }

    public function user()
    {
        return $this->belongsTo(PublicUser::class, 'UserID', 'UserID');
    }
}
