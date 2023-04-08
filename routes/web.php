<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/examination_halls', [App\Http\Controllers\ExaminationHallController::class, 'index'])->name('examination_halls.index');
    Route::get('/examination_halls/create', [App\Http\Controllers\ExaminationHallController::class, 'create'])->name('examination_halls.create');
    Route::get('/examination_halls/{id}', [App\Http\Controllers\ExaminationHallController::class, 'show'])->name('examination_halls.show');
    Route::get('/examination_halls/{examination_hall}/edit', [App\Http\Controllers\ExaminationHallController::class, 'edit'])->name('examination_halls.edit');
    Route::delete('/examination_halls/{hall}', [App\Http\Controllers\ExaminationHallController::class, 'destroy'])->name('examination_halls.destroy');
    Route::post('/examination_halls', [App\Http\Controllers\ExaminationHallController::class, 'store'])->name('examination_halls.store');
    Route::get('/examination_halls/{examination_hall}', [App\Http\Controllers\ExaminationHallController::class, 'show'])->name('examination_halls.show');
    Route::put('/examination_halls/{hall}', [App\Http\Controllers\ExaminationHallController::class, 'update'])->name('examination_halls.update');


Route::get('/examination_timetable', [App\Http\Controllers\ExaminationTimetableController::class, 'index'])->name('examination_timetable.index');
Route::get('/examination_timetable/create', [App\Http\Controllers\ExaminationTimetableController::class, 'create'])->name('examination_timetable.create');
Route::post('/examination_timetable', [App\Http\Controllers\ExaminationTimetableController::class, 'store'])->name('examination_timetable.store');
Route::get('/examination_timetable/{id}', [App\Http\Controllers\ExaminationTimetableController::class, 'show'])->name('examination_timetable.show');
Route::get('/examination_timetable/{id}/edit', [App\Http\Controllers\ExaminationTimetableController::class, 'edit'])->name('examination_timetable.edit');
Route::put('/examination_timetable/{id}', [App\Http\Controllers\ExaminationTimetableController::class, 'update'])->name('examination_timetable.update');
Route::delete('/examination_timetable/{id}', [App\Http\Controllers\ExaminationTimetableController::class, 'destroy'])->name('examination_timetable.destroy');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
