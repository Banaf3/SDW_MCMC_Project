<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $table = 'agencies';
    protected $primaryKey = 'AgencyID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'AgencyName',
        'AgencyEmail',
        'AgencyPhoneNum',
        'AgencyType',
    ];

    /**
     * Get staff belonging to this agency.
     */
    public function staff()
    {
        return $this->hasMany(AgencyStaff::class, 'AgencyID', 'AgencyID');
    }
}
