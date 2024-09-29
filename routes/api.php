<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/courses/showDeleted', [CourseController::class, 'ShowSoftDeletedCourses']);
Route::put('/courses/restore/{id}', [CourseController::class, 'restoreCourse']);
Route::delete('/courses/delete/{id}', [CourseController::class, 'deleteCourse']);
Route::get('/instructors/showDeleted', [InstructorController::class, 'ShowSoftDeletedInstructors']);
Route::put('/instructors/restore/{id}', [InstructorController::class, 'restoreInstructor']);
Route::delete('/instructors/delete/{id}', [InstructorController::class, 'deleteInstructor']);
Route::get('/instructors/{instructor}/courses', [InstructorController::class, 'showCourses']);
Route::get('/instructors/{instructor}/students', [InstructorController::class, 'showStudents']);
Route::apiResource('/instructors', InstructorController::class);
Route::apiResource('/courses', CourseController::class);

