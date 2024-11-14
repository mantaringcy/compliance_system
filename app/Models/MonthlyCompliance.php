<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyCompliance extends Model
{
    use HasFactory;

    protected $fillable = [
        'compliance_id', 
        'compliance_name', 
        'department_id', 
        'status', 
        'file_path', 
        'approve', 
        'approved_at', 
        'computed_start_date', 
        'computed_deadline', 
        'computed_submit_date'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'approved_at',
        'computed_start_date',
        'computed_deadline',
        'computed_submit_date'
    ];

    public function compliance()
    {
        return $this->belongsTo(Compliance::class);
    }

    // Define relationships with the Department if necessary
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
