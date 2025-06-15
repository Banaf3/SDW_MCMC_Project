<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrator extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'administrators';
    protected $primaryKey = 'AdminID';    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'AdminName',
        'AdminEmail',
        'Password',
        'AdminRole',
        'AdminPhoneNum',
        'AdminAddress',
        'LoginHistory',
        'ProfilePicture',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'Password',
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'AdminID';
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }
    
    /**
     * Get the email attribute mapped to the username.
     */
    public function getEmailAttribute()
    {
        return $this->AdminEmail;
    }
    
    /**
     * Get the name attribute mapped.
     */
    public function getNameAttribute()
    {
        return $this->AdminName;
    }
}
