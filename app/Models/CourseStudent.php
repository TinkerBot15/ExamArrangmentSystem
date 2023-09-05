<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseStudent extends Model
{
    protected $table = 'course_student';

    protected $fillable = [
        'course_id',
        'student_id',
    ];

    // Define any relationships or additional methods you may need
    // For example, you can define a relationship with the Course model and the Student model

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
