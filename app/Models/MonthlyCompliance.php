<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    protected $appends = ['days_left'];

    public function getDaysLeftAttribute()
    {
        $today = Carbon::now()->startOfDay(); // Ensures we're comparing from the start of today
        $deadline = Carbon::parse($this->computed_deadline)->startOfDay();

        // Calculate days left
        $daysLeft = $today->diffInDays($deadline, false); // `false` includes negative values

        return $daysLeft;
    }
}
