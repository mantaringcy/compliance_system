<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compliance extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        // Trigger this event on soft delete
        static::deleting(function ($compliance) {
            // Soft delete related compliance requests
            $compliance->complianceRequests()->delete();
        });
    }

    protected $fillable = [
        'compliance_name',
        'department_id',
        'reference_date',
        'frequency',
        'start_working_on',
        'submit_on',
    ];

    // Define the relationship between Compliance and Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function complianceRequests()
    {
        return $this->hasMany(ComplianceRequest::class);
    }
}
