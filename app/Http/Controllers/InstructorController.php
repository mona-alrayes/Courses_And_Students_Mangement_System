<?php

namespace App\Http\Controllers;

use App\Http\Requests\instructors\StoreInstructorRequest;
use App\Http\Requests\instructors\UpdateInstructorRequest;
use App\Models\Instructor;
use App\Services\InstructorService;
use Exception;

class InstructorController extends Controller
{
    protected InstructorService $instructorService;

    public function __construct(InstructorService $instructorService)
    {
        $this->instructorService = $instructorService;
    }

    /**
     * Display a listing of the resource.
     * 
     * @throws Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $instructors = $this->instructorService->getInstructors();
        return self::paginated($instructors, 'instructors retrieved successfully', 200);
    }

   /**
    * Store a newly created resource in storage.
     * @throws Exception
    *
    * @param StoreInstructorRequest $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function store(StoreInstructorRequest $request): \Illuminate\Http\JsonResponse
    {
        $instructor = $this->instructorService->storeInstructor($request->validated());
        return self::success($instructor, 'instructor created successfully', 201);
    }

   /**
    * Display the specified resource.
    *
    * @param Instructor $instructor
    * @return \Illuminate\Http\JsonResponse
    */
    public function show(Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        return self::success($instructor, 'instructor retrieved successfully');
    }

    /**
     * * Update the specified resource in storage.
     * @throws Exception
     *
     * @param UpdateInstructorRequest $request
     * @param Instructor $instructor
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateInstructorRequest $request, Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        $instructor = $this->instructorService->updateInstructor($instructor, $request->validated());
        return self::success($instructor, 'instructor updated successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Instructor $instructor
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        $instructor->delete();
        return self::success($instructor, 'instructor deleted successfully');
    }
    
    /**
     * Get all softdeleted instructors from storage
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ShowSoftDeletedInstructors(): \Illuminate\Http\JsonResponse
    {
        $softDeletedInstructors = Instructor::onlyTrashed()->paginate(10);
        return self::paginated($softDeletedInstructors, 'instructors retrieved successfully', 200);
    }

    /**
     * Restore softDeleted instructor
     *
     * @param [type] $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreInstructor($id): \Illuminate\Http\JsonResponse
    {
        $instructor = Instructor::withTrashed()->findOrFail($id); // Include soft-deleted records
        $instructor->restore();
        return self::success($instructor, 'Instructor restored successfully');
    }

    /**
     * Delete forever softDeleted Instructor
     *
     * @param Instructor $instructor
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDeleteInstructor(Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        $instructor->forceDelete();
        return self::success($instructor, 'instructor deleted successfully');
    }

    /**
     * Show courses of specific instructor using hasMany 
     *
     * @param Instructor $instructor
     * @return \Illuminate\Http\JsonResponse
     */
    public function showCourses(Instructor $instructor): \Illuminate\Http\JsonResponse
    {
        $courses = $instructor->courses()->paginate(10);
        return self::paginated($courses, 'courses retrieved successfully', 200);
    }

    /**
     * Show students of Specific instructor using hasManyThrough 
     *
     * @param Instructor $instructor
     * @return \Illuminate\Http\JsonResponse
     */
    public function showStudents(Instructor $instructor): \Illuminate\Http\JsonResponse
    {
           $students = $instructor->students()->paginate(10);     //first solution 
         // $students = $instructor->studentNames($instructor)->paginate(10);    //second solution 
         // $students = $instructor->getStudents()->paginate(10);        //third solution 
         return self::paginated($students , 'students retrieved successfully', 200);
    }
}
