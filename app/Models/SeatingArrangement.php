<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatingArrangement extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'examination_hall_id',
        'seat_number',
        'examination_timetable_id',
        'examination_hall_id',
        'hall_name',
        'color',
        'student_name',
        'course',
        'course_code'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function examinationHall()
    {
        return $this->belongsTo(ExaminationHall::class);
    }

    public function examinationTimetable()
    {
        return $this->belongsTo(ExaminationTimetable::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
