<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicUser extends Model
{
    protected $table = 'public_users';
    protected $primaryKey = 'UserID';

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

    protected $casts = [
        'LoginHistory' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the inquiries created by this user
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'UserID', 'UserID');
    }

    /**
     * Get the notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'UserID');
    }
}
