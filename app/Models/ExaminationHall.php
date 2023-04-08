<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationHall extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'seating_capacity', 'rows', 'columns'];

    
    public function timetable()
    {
        return $this->hasMany(ExaminationTimetable::class);
    }
}
