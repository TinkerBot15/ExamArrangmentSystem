@extends('layouts.app')

@section('content')
<div class="seating-chart">
    @foreach ($examinationHalls as $examinationHall)
        <div class="examination-hall">
            <h3>Examination Hall: {{ $examinationHall->name }}</h3>
            <div class="seats-container">
                <table>
                    @for ($row = 1; $row <= $hallRows[$examinationHall->id]; $row++)
                        <tr>
                            @for ($column = 1; $column <= $hallColumns[$examinationHall->id]; $column++)
                                <td>
                                    @foreach ($seatingGraph as $student)
                                        @if ($student['examination_hall_id'] == $examinationHall->id && $student['row'] == $row && $student['column'] == $column)
                                            <div class="seat">
                                                <div class="table-icon">
                                                <div class="seat-number">{{ $student['seat_number'] }}</div>
                                                    <div class="chair-icon"></div>
                                                    <div class="student-details">
                                                        <div class="student-info">
                                                            <div class="student-name">{{ $student['student'] }}</div>
                                                            <div class="student-matric">{{ $student['matric'] }}</div>
                                                            <div class="student-department">{{ $student['department'] }}</div>
                                                            <div class="student-course-title">{{ $student['course_title'] }} ({{ $student['course_code'] }})</div>
                                                            <div class="attendance-bar"><i><b>Signature</b></i></div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection



    
@section('script')
    <script>

    </script>
@endsection
