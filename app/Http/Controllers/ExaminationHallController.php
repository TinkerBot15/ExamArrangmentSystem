<?php

namespace App\Http\Controllers;

use App\Models\ExaminationHall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ExaminationHallController extends Controller
{
    // Display a listing of the examination halls
        public function index()
    {
        $examinationHalls = ExaminationHall::all();
        return view('examination_halls.index', compact('examinationHalls'));
    }


    // Show the form for creating a new examination hall
    public function create()
    {
        return view('examination_halls.create');
    }

    // Store a newly created examination hall in storage
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'seating_capacity' => 'required|numeric',
            'rows' => 'required|numeric',
            'columns' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return redirect('examination_halls/create')
                        ->withErrors($validator)
                        ->withInput();
        }


        $examinationHall = ExaminationHall::create([
            'name' => $request->name,
            'seating_capacity' => $request->seating_capacity,
            'rows' => $request->rows,
            'columns' => $request->columns,
        ]);

        \Log::debug(print_r($examinationHall->toArray(), true));

        return redirect()->route('examination_halls.index')->with('success', 'Examination Hall created successfully');
    }

    // Show the form for editing the specified examination hall
    public function edit($id)
    {
        $examinationHall = ExaminationHall::findOrFail($id);
        return view('examination_halls.edit', compact('examinationHall'));
    }



    // Update the specified examination hall in storage
    public function update(Request $request, ExaminationHall $hall)
    {
        $request->validate([
            'name' => 'required|unique:examination_halls,name,'.$hall->id,
            'seating_capacity' => 'required|integer|min:1',
            'rows' => 'required|numeric',
            'columns' => 'required|numeric'
        ]);

        $hall->update($request->all());

        return redirect()->route('examination_halls.index')
            ->with('success', 'Examination hall updated successfully.');
    }

    public function show($id)
{
    $hall = ExaminationHall::findOrFail($id);

    return view('examination_halls.show', compact('hall'));
}




    // Remove the specified examination hall from storage
    public function destroy(ExaminationHall $hall)
    {
        $hall->delete();

        return redirect()->route('examination_halls.index')
            ->with('success', 'Examination hall deleted successfully.');
    }
}
