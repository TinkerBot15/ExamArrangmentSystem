<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\CourseStudent;
use Illuminate\Support\Facades\DB;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::pluck('department', 'department')->unique();
    
        return view('courses.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:courses',
            'department' => 'required|string|max:255',
        ]);

        $course = new Course;
        $course->name = $validatedData['name'];
        $course->code = $validatedData['code'];
        $course->department = $validatedData['department'];
        $course->save();

        $students = DB::table('students')->where('department', $course->department)->get();

        foreach ($students as $student) {
            $courseStudent = new CourseStudent();
            $courseStudent->course_id = $course->id;
            $courseStudent->student_id = $student->id;
            $courseStudent->save();
        }


        return redirect('/courses')->with('success', 'Course added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $course = Course::findOrFail($id);
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:courses',
            'department' => 'required|string|max:255',
        ]);

        $course->name = $validatedData['name'];
        $course->code = $validatedData['code'];
        $course->department = $validatedData['department'];
        $course->save();

        return redirect('/courses')->with('success', 'Course updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
