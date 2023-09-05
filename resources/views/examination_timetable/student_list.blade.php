@extends('layouts.app') 

@section('content')
<div class="container">
    <h2>Student List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Seat Label</th>
                <th>Department</th>
                <th>Course</th>
                <th>SMS Status</th>
                <th>Message</th> <!-- New column for displaying the message -->
            </tr>
        </thead>
        <tbody>
            @foreach ($seatingArrangements as $seatingArrangement)
            <tr>
                <td>{{ $seatingArrangement->student_name }}</td>
                <td>{{ $seatingArrangement->seat_number }}</td>
                <td>{{ $seatingArrangement->color}}</td>
                <td>{{ $seatingArrangement->course }}</td>
                <td>
                    @if ($seatingArrangement->processed)
                        <span class="text-success">Sent</span>
                    @else
                        <span class="text-danger">Not Sent</span>
                    @endif
                </td>
                <td>{{ $seatingArrangement->message }}</td> <!-- Display the message -->
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
