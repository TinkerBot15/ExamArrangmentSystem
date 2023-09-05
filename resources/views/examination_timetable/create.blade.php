@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Examination Timetable</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <form id="examinationForm" method="POST" action="{{ route('examination_timetable.store') }}">
            @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="examination_hall_id">Examination Hall:</label>
                    <select name="examination_hall_id" id="examination_hall_id" class="form-control">
                        @foreach($halls as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="exam_date">Exam Date</label>
                    <input type="date" name="exam_date" id="exam_date" class="form-control" value="{{ old('exam_date') }}">
                </div>

                <div class="form-group">
                    <label for="exam_start_time">Exam Start Time</label>
                    <input type="time" name="exam_start_time" id="exam_start_time" class="form-control" value="{{ old('exam_start_time') }}">
                </div>

                <div class="form-group">
                    <label for="exam_end_time">Exam End Time</label>
                    <input type="time" name="exam_end_time" id="exam_end_time" class="form-control" value="{{ old('exam_end_time') }}">
                </div>

                <div class="form-group">
                    <label for="invigilators">Select Invigilators:</label>
                        @foreach ($invigilators as $invigilator)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="invigilators[]" value="{{ $invigilator->id }}" id="invigilators">
                                <label class="form-check-label" for="{{ $invigilator->id }}">{{ $invigilator->name }}</label>
                            </div>
                        @endforeach
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Courses</label><br>
                    <div class="course-column">
                        @foreach($courses as $id => $courseCode)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="courses[]" value="{{ $id }}" id="course_{{ $id }}">
                                <label class="form-check-label" for="course_{{ $id }}">{{ $courseCode }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        
            <button type="button" id="checkCapacityBtn" class="btn btn-primary">Check Capacity</button>

            <div>
                Seating Capacity: <span id="seatingCapacity"></span>
            </div>

            <div>
                <p id="capacityMessage"></p>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
    </div>
@endsection

@section('scripts')
    <script>

        $(document).ready(function() {
            $('#saveBtn').click(function() {
                $('#examinationForm').submit();
            });
        });

       $(document).ready(function () {
    // Set the CSRF token in the headers for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#checkCapacityBtn').click(function () {
        var hallId = $('#examination_hall_id').val();
        var courseIds = $('input[name="courses[]"]:checked').map(function () {
            return $(this).val();
        }).get();
        
        console.log('Course IDs:', courseIds);
        console.log('Button clicked');

        // Make an AJAX request to get the seating capacity
        $.ajax({
            url: '{{ route("examination_timetable.getSeatingCapacity") }}',
            type: 'POST',
            data: {
                hall_id: hallId
            },
            success: function (response) {
                console.log('Seating capacity response:', response);
                var seatingCapacity = response.capacity;
                // console.log(seatingCapacity);
                // $('#seatingCapacity').text(seatingCapacity);

                // Make another AJAX request to get the student count
                $.ajax({
                    url: '{{ route("examination_timetable.getStudentCount") }}',
                    type: 'POST',
                    data: {
                        courses: courseIds
                    },
                    success: function (response) {
                        console.log('Student count response:', response);
                        var studentCount = response.student_count;
                    //  console.log(seatingCapacity);
                        if (parseInt(seatingCapacity) >= parseInt(studentCount)) {
                            $('#capacityMessage').text('Seating capacity is sufficient for selected students.');
                        } else {
                            $('#capacityMessage').text('Seating capacity is less than the number of students. Please select another examination hall or reduce the number of students.');
                        }
                    },
                });
            },
        });
    });
});

    </script>
@endsection
