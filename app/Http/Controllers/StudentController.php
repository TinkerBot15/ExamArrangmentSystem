<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\CourseStudent;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{
    // Get all students

    
    public function index()
    {
        $students = Student::all();
        return view('students.index', ['students' => $students]);
    }

    // Show the form for creating a new student
    public function create()
    {
        $courses = Course::pluck('department', 'department')->unique();
    
        return view('students.create', compact('courses'));
    }
    

    // Store a newly created student in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'matric_number' => 'required|unique:students',
            'email' => 'required|email|unique:students',
            'phone_number' => 'required',
            'department' => 'required'
        ]);

        $student = new Student;
        $student->name = $request->name;
        $student->matric_number = $request->matric_number;
        $student->email = $request->email;
        $student->phone_number = $request->phone_number;
        $student->department = $request->department;
        $student->save();

        // Get the courses with the same department as the student
        $courses = Course::where('department', $validatedData['department'])->get();

        // Prepare the course_student data
        $courseStudentData = [];
        foreach ($courses as $course) {
            $courseStudentData[] = [
                'student_id' => $student->id,
                'course_id' => $course->id,
            ];
        }

        // Insert the data into the course_student table
        CourseStudent::insert($courseStudentData);


        return redirect()->route('students.index')->with('success', 'Student created successfully');
    }

    // Show the form for editing the specified student
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', ['student' => $student]);
    }

    // Update the specified student in storage
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'matric_number' => 'required|unique:students,matric_number,'.$id,
            'email' => 'required|email|unique:students,email,'.$id,
            'phone_number' => 'required',
            'department' => 'required'
        ]);

        $student = Student::findOrFail($id);
        $student->name = $request->name;
        $student->matric_number = $request->matric_number;
        $student->email = $request->email;
        $student->phone_number = $request->phone_number;
        $student->department = $request->department;
        $student->save();

        return redirect()->route('students.index')->with('success', 'Student updated successfully');
    }

    // Remove the specified student from storage
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully');
    }
}
