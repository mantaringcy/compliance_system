<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compliance extends Model
{
    use HasFactory;

    protected $fillable = [
        'compliance_name',
        'department_id',
        'reference_date',
        'frequency',
        'start_on',
        'submit_on',
    ];
}
