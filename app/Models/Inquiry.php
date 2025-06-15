<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $table = 'inquiries';
    protected $primaryKey = 'InquiryID';
    public $timestamps = false; // Since we're using custom SubmissionDate
    
    protected $fillable = [
        'InquiryTitle',
        'SubmitionDate', 
        'InquiryStatus',
        'InquiryDescription',
        'InquiryEvidence',
        'AdminComment',
        'ResolvedExplanation',
        'ResolvedSupportingDocs',
        'AgencyRejectionComment',
        'AdminID',
        'AgencyID',
        'UserID'
    ];

    protected $casts = [
        'InquiryEvidence' => 'array',
        'SubmissionDate' => 'datetime',
    ];

    // Relationship with PublicUser
    public function user()
    {
        return $this->belongsTo(PublicUser::class, 'UserID', 'UserID');
    }

    // Relationship with Administrator
    public function administrator()
    {
        return $this->belongsTo(Administrator::class, 'AdminID', 'AdminID');
    }

    // Relationship with Agency
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'AgencyID', 'AgencyID');
    }
}
