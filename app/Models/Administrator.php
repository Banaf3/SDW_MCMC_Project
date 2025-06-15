<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrator extends Authenticatable
{
    protected $table = 'administrators';
    protected $primaryKey = 'AdminID';
    public $timestamps = false;
    
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
        'Password',
    ];

    // Relationship with Inquiries
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'AdminID', 'AdminID');
    }
}
