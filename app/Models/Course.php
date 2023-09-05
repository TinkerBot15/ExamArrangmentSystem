<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ExaminationTimetable;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Course model

    public function students()
{
    return $this->belongsToMany(Student::class, 'course_student', 'course_id', 'student_id');
}


    public function examinationTimetables()
    {
        return $this->hasMany(ExaminationTimetable::class);
    }

    public function examinationTimetable()
    {
        return $this->belongsToMany(ExaminationTimetable::class, 'examination_timetable_course');
    }





}
