<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Optional: You can define inverse relationship here if needed
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
