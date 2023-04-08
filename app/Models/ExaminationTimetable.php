<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationTimetable extends Model
{
    use HasFactory;

    protected $table = 'examination_timetable';


    protected $fillable = [
        'course_code',
        'course_title',
        'exam_date',
        'exam_start_time',
        'exam_end_time',
        'examination_hall_id',
    ];

    public function examinationHall()
    {
        return $this->belongsTo(ExaminationHall::class);
    }
}
