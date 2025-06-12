<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Administrator extends Model
{
    protected $primaryKey = 'AdminID';

    protected $fillable = [
        'AdminName',
        'Password',
        'AdminEmail',
        'AdminRole',
        'AdminPhoneNum',
        'AdminAddress',
        'LoginHistory'
    ];

    protected $hidden = [
        'Password'
    ];

    protected $casts = [
        'LoginHistory' => 'array',
        'Password' => 'hashed'
    ];

    /**
     * Get inquiries directly managed by this administrator
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'AdminID', 'AdminID');
    }

    /**
     * Get inquiries assigned through assigned_inquiries table
     */
    public function assignedInquiries(): BelongsToMany
    {
        return $this->belongsToMany(Inquiry::class, 'assigned_inquiries', 'AdminID', 'InquiryID')
                    ->withPivot('AgencyID', 'AssignedDate')
                    ->withTimestamps();
    }

    /**
     * Get agencies through assigned_inquiries relationship
     */
    public function assignedAgencies(): BelongsToMany
    {
        return $this->belongsToMany(Agency::class, 'assigned_inquiries', 'AdminID', 'AgencyID')
                    ->withPivot('InquiryID', 'AssignedDate')
                    ->withTimestamps();
    }
}
