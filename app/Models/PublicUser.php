<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PublicUser extends Authenticatable
{
    protected $table = 'public_users';
    protected $primaryKey = 'UserID';
    public $timestamps = false;
    
    protected $fillable = [
        'UserName',
        'UserPhoneNum',
        'Useraddress',
        'ProfilePic',
        'UserEmail',
        'Password',
        'LoginHistory'
    ];

    protected $hidden = [
        'Password',
    ];

    // Relationship with Inquiries
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'UserID', 'UserID');
    }
}
