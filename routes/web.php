<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SeatingArrangementController;
use App\Http\Controllers\ExaminationTimetableController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Http\Controllers\CourseStudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/map-students-courses', [CourseStudentController::class, 'mapStudentsToCoursesWithSameDepartment']);

    Route::get('/examination_halls', [App\Http\Controllers\ExaminationHallController::class, 'index'])->name('examination_halls.index');
    Route::get('/examination_halls/create', [App\Http\Controllers\ExaminationHallController::class, 'create'])->name('examination_halls.create');
    Route::get('/examination_halls/{id}', [App\Http\Controllers\ExaminationHallController::class, 'show'])->name('examination_halls.show');
    Route::get('/examination_halls/{examination_hall}/edit', [App\Http\Controllers\ExaminationHallController::class, 'edit'])->name('examination_halls.edit');
    Route::delete('/examination_halls/{hall}', [App\Http\Controllers\ExaminationHallController::class, 'destroy'])->name('examination_halls.destroy');
    Route::post('/examination_halls', [App\Http\Controllers\ExaminationHallController::class, 'store'])->name('examination_halls.store');
    Route::get('/examination_halls/{examination_hall}', [App\Http\Controllers\ExaminationHallController::class, 'show'])->name('examination_halls.show');
    Route::put('/examination_halls/{hall}', [App\Http\Controllers\ExaminationHallController::class, 'update'])->name('examination_halls.update');

    Route::get('/examination_timetable/invigilator', [App\Http\Controllers\InvigilatorController::class, 'index'])
    ->name('examination_timetable.invigilator.index');

Route::get('/examination_timetable/invigilator/{timetable}', [App\Http\Controllers\InvigilatorController::class, 'show'])
    ->name('examination_timetable.invigilator.show');



    Route::get('/examination_timetable', [App\Http\Controllers\ExaminationTimetableController::class, 'index'])->name('examination_timetable.index');
    Route::get('/examination_timetable/create', [App\Http\Controllers\ExaminationTimetableController::class, 'create'])->name('examination_timetable.create');
    Route::post('/examination_timetable', [App\Http\Controllers\ExaminationTimetableController::class, 'store'])->name('examination_timetable.store');
    Route::get('/examination_timetable/{id}', [App\Http\Controllers\ExaminationTimetableController::class, 'show'])->name('examination_timetable.show');
    Route::get('/examination_timetable/{id}/edit', [App\Http\Controllers\ExaminationTimetableController::class, 'edit'])->name('examination_timetable.edit');
    Route::put('/examination_timetable/{id}', [App\Http\Controllers\ExaminationTimetableController::class, 'update'])->name('examination_timetable.update');
    Route::delete('/examination_timetable/{id}', [App\Http\Controllers\ExaminationTimetableController::class, 'destroy'])->name('examination_timetable.destroy');
    Route::post('examination_timetable/check_capacity', 'ExaminationTimetableController@checkCapacity')->name('examination_timetable.check_capacity');
    //Route::post('/examination_timetable/checkCapacity', [ExaminationTimetableController::class, 'checkCapacity'])->name('examination_timetable.checkCapacity');
    Route::post('/examination_timetable/getStudentCount', [ExaminationTimetableController::class, 'getStudentCount'])->name('examination_timetable.getStudentCount');
    Route::post('/examination_timetable/getSeatingCapacity', [ExaminationTimetableController::class, 'getSeatingCapacity'])->name('examination_timetable.getSeatingCapacity');
    Route::post('/examination_timetable/store', [ExaminationTimetableController::class, 'store'])->name('examination_timetable.store');
    Route::get('/examination_timetable/{id}/generate-seating-arrangement', [ExaminationTimetableController::class, 'generateSeatingArrangement'])->name('examination_timetable.generateSeatingArrangement');
    Route::get('/examination_timetable/{timetable}/seating_arrangement', [ExaminationTimetableController::class, 'seatingArrangement'])->name('examination_timetable.seating_arrangement');
    Route::get('/examination/send-sms-notifications/{timetableId}', [ExaminationTimetableController::class, 'sendSMSNotifications'])->name('examination_timetable.sendSMSNotifications');
    Route::get('/examination_timetable/{timetableId}/fetchStudentList', [ExaminationTimetableController::class, 'getStudentList'])->name('examination_timetable.student_list');
    Route::get('examination_timetable/{timetable}/seating-arrangement-pdf', [ExaminationTimetableController::class, 'downloadSeatingArrangementPDF'])->name('examination_timetable.seating-arrangement-pdf');



    Route::get('/examination_timetable/invigilator', [App\Http\Controllers\InvigilatorController::class, 'index'])->name('examination_timetable.invigilator.index');
    Route::get('/examination_timetable/invigilator/{timetable}', [App\Http\Controllers\InvigilatorController::class, 'show'])->name('examination_timetable.invigilator.show');
    Route::get('/examination_timetable/invigilator/{timetable}/seating_arrangement', [App\Http\Controllers\InvigilatorController::class, 'seatingArrangement'])->name('examination_timetable.invigilator.seating_arrangement');
    Route::get('/examination_timetable/invigilator/{timetable}/seating-arrangement-pdf', [App\Http\Controllers\InvigilatorController::class, 'downloadSeatingArrangementPDF'])->name('examination_timetable.invigilator.seating_arrangement_pdf');




    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    Route::prefix('student')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('students.index');
        Route::get('/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/store', [StudentController::class, 'store'])->name('students.store');
        Route::get('/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    });

    Route::get('/timetables/details', 'SeatingArrangementController@fetchTimetableDetails')->name('timetables.details');


});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Email verification routes

Route::get('/email/verify', [VerificationController::class, 'show'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');


