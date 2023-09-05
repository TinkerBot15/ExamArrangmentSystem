<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Student model

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id');
    }


    // public function courses()
    // {
    //     return $this->belongsToMany(Course::class);
    // }

    public function examinationTimetables()
    {
        return $this->belongsToMany(ExaminationTimetable::class);
    }

    public function seatingArrangements()
    {
        return $this->hasMany(SeatingArrangement::class);
    }
}
