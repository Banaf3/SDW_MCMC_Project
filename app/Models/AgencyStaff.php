<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AgencyStaff extends Authenticatable
{
    use Notifiable;

    protected $table = 'agency_staff';
    protected $primaryKey = 'StaffID';

    protected $fillable = [
        'StaffName',
        'Username',
        'Password',
        'staffEmail',
        'staffPhoneNum',
        'ProfilePic',
        'LoginHistory',
        'AgencyID',
        'password_change_required',
        'password_changed_at'
    ];

    protected $hidden = [
        'Password'
    ];

    protected $casts = [
        'LoginHistory' => 'array',
        'password_change_required' => 'boolean',
        'password_changed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'StaffID';
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
        return $this->staffEmail;
    }
    
    /**
     * Get the name attribute.
     */
    public function getNameAttribute()
    {
        return $this->StaffName;
    }

    /**
     * Find staff by username or email for login
     */
    public static function findForLogin($loginField)
    {
        return static::where('Username', $loginField)
                    ->orWhere('staffEmail', $loginField)
                    ->first();
    }
    
    /**
     * Find staff by username only
     */
    public static function findByUsername($username)
    {
        return static::where('Username', $username)->first();
    }
    
    /**
     * Find staff by email only
     */
    public static function findByEmail($email)
    {
        return static::where('staffEmail', $email)->first();
    }

    /**
     * Check if username is unique
     */
    public static function isUsernameUnique($username, $excludeId = null)
    {
        $query = static::where('Username', $username);
        if ($excludeId) {
            $query->where('StaffID', '!=', $excludeId);
        }
        return !$query->exists();
    }

    /**
     * Generate a unique username for staff
     */
    public static function generateUniqueUsername($staffName, $agencyId = null)
    {
        // Create base username from staff name or use generic name if not provided
        if (!empty($staffName)) {
            $baseUsername = strtolower(str_replace(' ', '', $staffName));
            $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
        } else {
            $baseUsername = 'staff';
        }
        
        // Add agency prefix if available
        if ($agencyId) {
            $agency = Agency::find($agencyId);
            if ($agency) {
                $agencyPrefix = strtolower(substr($agency->AgencyName, 0, 3));
                $baseUsername = $agencyPrefix . '_' . $baseUsername;
            }
        }
        
        // Ensure uniqueness
        $username = $baseUsername;
        $counter = 1;
        while (!static::isUsernameUnique($username)) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }

    /**
     * Get the agency that this staff member belongs to
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'AgencyID', 'AgencyID');
    }

    /**
     * Get inquiries handled by this staff member
     * Assuming AdminID in inquiries table refers to StaffID
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'AdminID', 'StaffID');
    }
}
