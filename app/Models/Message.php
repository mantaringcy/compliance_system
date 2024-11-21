<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['monthly_compliance_id', 'user_id', 'message'];

    public function monthlyCompliance()
    {
        return $this->belongsTo(MonthlyCompliance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
