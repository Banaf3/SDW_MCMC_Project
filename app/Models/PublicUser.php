<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PublicUser extends Authenticatable
{
    use Notifiable;

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
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'UserID';
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }

    /**
     * Get the email attribute for authentication.
     */
    public function getEmailAttribute()
    {
        return $this->UserEmail;
    }
    
    /**
     * Get the name attribute.
     */
    public function getNameAttribute()
    {
        return $this->UserName;
    }

    /**
     * Find public user by email for login (email-only login)
     */
    public static function findForLogin($email)
    {
        return static::where('UserEmail', $email)->first();
    }

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
