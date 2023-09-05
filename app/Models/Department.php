<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function seatingArrangements()
    {
        return $this->hasMany(SeatingArrangement::class);
    }
}
