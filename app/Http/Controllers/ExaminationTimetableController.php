<?php

namespace App\Http\Controllers;

use App\Models\ExaminationTimetable;
use App\Models\ExaminationHall;
use Illuminate\Http\Request;

class ExaminationTimetableController extends Controller
{

     public function index()
    {
        $examination_timetables = ExaminationTimetable::with('examinationHall')->get();
        return view('examination_timetable.index', compact('examination_timetables'));
    }

    public function create()
    {
        $halls = ExaminationHall::all();
        return view('examination_timetable.create', compact('halls'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_code' => 'required',
            'course_title' => 'required',
            'exam_date' => 'required|date',
            'exam_start_time' => 'required|date_format:H:i',
            'exam_end_time' => 'required|date_format:H:i|after:exam_start_time',
            'examination_hall_id' => 'required|exists:examination_halls,id',
        ]);

        $timetable = new ExaminationTimetable;
        $timetable->course_code = $request->course_code;
        $timetable->course_title = $request->course_title;
        $timetable->exam_date = $request->exam_date;
        $timetable->exam_start_time = $request->exam_start_time;
        $timetable->exam_end_time = $request->exam_end_time;
        $timetable->examination_hall_id = $request->examination_hall_id;
        $timetable->save();

        return redirect()->route('examination_timetable.index')
            ->with('success', 'Examination timetable added successfully.');
    }

    public function show($id)
    {
        $examination_timetable = ExaminationTimetable::find($id);
        return view('examination_timetable.show', compact('examination_timetable'));
    }

    public function edit($id)
    {
        $examination_timetable = ExaminationTimetable::findOrFail($id);
        $halls = ExaminationHall::all();
        return view('examination_timetable.edit', compact('examination_timetable', 'halls'));
    }


    public function update(Request $request, ExaminationTimetable $examinationTimetable)
    {
        $request->validate([
            'course_code' => 'required',
            'course_title' => 'required',
            'exam_date' => 'required|date',
            'exam_start_time' => 'required',
            'exam_end_time' => 'required',
            'examination_hall_id' => 'required|exists:examination_halls,id'
        ]);

        $examinationTimetable->course_code = $request->course_code;
        $examinationTimetable->course_title = $request->course_title;
        $examinationTimetable->exam_date = $request->exam_date;
        $examinationTimetable->exam_start_time = $request->exam_start_time;
        $examinationTimetable->exam_end_time = $request->exam_end_time;
        $examinationTimetable->examination_hall_id = $request->examination_hall_id;

        $examinationTimetable->save();

        return redirect()->route('examination_timetable.index')
            ->with('success', 'Examination timetable updated successfully.');
    }

public function destroy($id)
    {
        $examination_timetable = ExaminationTimetable::find($id);
        if ($examination_timetable) {
            $examination_timetable->delete();
            return redirect()->route('examination_timetable.index')->with('status', 'Examination timetable deleted successfully.');
        } else {
            return redirect()->route('examination_timetable.index')->with('status', 'Examination timetable not found.');
        }
    }


   
}
