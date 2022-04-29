<?php

use Illuminate\Support\Facades\Route;

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



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';


// -------------------------

/* Added by adarshthavarool@gmail.com on (28 April 2022 at 9:10 PM) */

Route::group(['middleware' => ['auth']], function () {
    // These routes, are authenticated

    Route::resource('students', \App\Http\Controllers\StudentController::class);
    Route::resource('marks', \App\Http\Controllers\MarksController::class);
    Route::get('teachers-data', [\App\Http\Controllers\StudentController::class, 'teachersData'])->name('teachers-data');
    Route::get('students-data', [\App\Http\Controllers\StudentController::class, 'studentsData'])->name('students-data');
    Route::view('marklist', 'students.marks')->name('marks.list');
    Route::view('/', 'students.list')->name('students.list');

});
