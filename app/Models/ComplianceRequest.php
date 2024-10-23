<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'compliance_id',
        'user_id',
        'action',
        'changes',
        'approved'
    ];
    
    public function compliance()
    {
        return $this->belongsTo(Compliance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
