<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('/instructors/showDeleted', [InstructorController::class, 'ShowSoftDeletedInstructors']);
Route::put('/instructors/restore/{id}', [InstructorController::class, 'restoreInstructor']);
Route::delete('/instructors/delete/{id}', [InstructorController::class, 'deleteInstructor']);
Route::get('/instructors/{instructor}/courses', [InstructorController::class, 'showCourses']);
Route::get('/instructors/{instructor}/students', [InstructorController::class, 'showStudents']);
Route::apiResource('/instructors', InstructorController::class);
Route::get('/courses/showDeleted', [CourseController::class, 'ShowSoftDeletedCourses']);
Route::put('/courses/restore/{id}', [CourseController::class, 'restoreCourse']);
Route::delete('/courses/delete/{id}', [CourseController::class, 'deleteCourse']);
Route::get('/courses/{id}/students', [CourseController::class, 'showStudents']);
Route::apiResource('/courses', CourseController::class);
Route::get('/students/showDeleted', [StudentController::class, 'showDeletedStudents']);
Route::put('/students/restore/{id}', [StudentController::class, 'restoreStudent']);
Route::delete('/students/delete/{id}', [StudentController::class, 'forceDeleteStudent']);
Route::apiResource('/students', StudentController::class);  // in post method you can add the course_id or not and same for update 

