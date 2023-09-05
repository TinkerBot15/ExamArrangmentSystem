<?php

namespace App\Models;

use App\Helpers\GraphColoring;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\seatingArrangement;

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

    public function course()
    {
        return $this->belongsTo(Course::class, 'code', 'name');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student');
    }

    public function examinationCourses()
    {
        return $this->belongsToMany(Course::class, 'examination_timetable_course');
    }



    public function examination_hall()
    {
        return $this->belongsTo(ExaminationHall::class);
    }

    // public function invigilators()
    // {
    //     return $this->hasMany(User::class, 'invigilator_id');
    // }

    public function invigilators()
    {
        return $this->belongsToMany(User::class, 'examination_timetable_user', 'examination_timetable_id', 'user_id');
    }

        public function seatingArrangement()
    {
        return $this->hasOne(SeatingArrangement::class, 'examination_timetable_id');
    }





    public function examinationHall()
    {
        return $this->belongsTo(ExaminationHall::class);
    }

    public function seatingArrangements()
    {
        return $this->hasMany(SeatingArrangement::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
    public function getStudents()
    {
        return $this->students()->orderBy('id')->get();
    }
    public function assignSeats()
    {
        $students = $this->getStudents();
        $seats = $this->examinationHall->seats;
        $adjacencyMatrix = $this->getAdjacencyMatrix($students);

        $coloring = new GraphColoring($adjacencyMatrix, count($seats));
        $colors = $coloring->getColors();

        foreach ($students as $key => $student) {
            $seatNumber = $colors[$key] % count($seats) + 1;
            $seat = $seats->where('number', $seatNumber)->first();
            $student->seat()->associate($seat);
            $student->save();
        }
    }
    private function getAdjacencyMatrix($students)
    {
        $adjacencyMatrix = [];

        foreach ($students as $student1) {
            $row = [];
            foreach ($students as $student2) {
                $row[] = $student1->courses->intersect($student2->courses)->count() > 0 ? 1 : 0;
            }
            $adjacencyMatrix[] = $row;
        }

        return $adjacencyMatrix;
    }
}
