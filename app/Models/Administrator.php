<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrator extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'administrators';
    protected $primaryKey = 'AdminID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'AdminName',
        'Username',
        'AdminEmail',
        'Password',        'AdminRole',
        'AdminPhoneNum',
        'AdminAddress',
        'LoginHistory',
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
     * The attributes that should be cast.
     *
     * @var array
     */    protected $casts = [
        'LoginHistory' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
     * Get the email attribute for authentication.
     */
    public function getEmailAttribute()
    {
        return $this->AdminEmail;
    }
    
    /**
     * Get the name attribute.
     */
    public function getNameAttribute()
    {
        return $this->AdminName;
    }    /**
     * Find admin by username or email for login
     */
    public static function findForLogin($loginField)
    {
        return static::where('Username', $loginField)
                    ->orWhere('AdminEmail', $loginField)
                    ->first();
    }
    
    /**
     * Find admin by username only
     */
    public static function findByUsername($username)
    {
        return static::where('Username', $username)->first();
    }
    
    /**
     * Find admin by email only
     */
    public static function findByEmail($email)
    {
        return static::where('AdminEmail', $email)->first();
    }/**
     * Check if username is unique
     */
    public static function isUsernameUnique($username, $excludeId = null)
    {
        $query = static::where('Username', $username);
        if ($excludeId) {
            $query->where('AdminID', '!=', $excludeId);
        }
        return !$query->exists();
    }

    /**
     * Generate a unique username for administrator
     */
    public static function generateUniqueUsername($adminName)
    {
        // Create base username from admin name
        $baseUsername = strtolower(str_replace(' ', '', $adminName));
        $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
        
        // Add admin prefix
        $baseUsername = 'admin_' . $baseUsername;
        
        // Ensure uniqueness
        $username = $baseUsername;
        $counter = 1;
        while (!static::isUsernameUnique($username)) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}
