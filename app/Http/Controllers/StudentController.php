<?php

namespace App\Http\Controllers;

use App\Http\Requests\students\StorestudentRequest;
use App\Http\Requests\students\UpdatestudentRequest;
use App\Models\Student;
use App\Services\StudentsService;

class StudentController extends Controller
{
    protected StudentsService $StudentsService;

    public function __construct(StudentsService $StudentsService){
        $this->StudentsService = $StudentsService;
    }

    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $students =$this->StudentsService->getStudents();
        return self::paginated($students,'students retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(StorestudentRequest $request): \Illuminate\Http\JsonResponse
    {
        $student= $this->StudentsService->storeCourse($request->validated());
        return self::success($student,'student created successfully',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): \Illuminate\Http\JsonResponse
    {
        return self::success($student,'student retrieved successfully',200);
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(UpdatestudentRequest $request, Student $student): \Illuminate\Http\JsonResponse
    {
        $student = $this->StudentsService->updateStudent($student,$request->validated());
        return self::success($student,'student updated successfully',200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): \Illuminate\Http\JsonResponse
    {
        $student->delete();
        return self::success($student,'student deleted successfully',200);
    }
    /**
     * Undocumented function
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showDeletedStudents(): \Illuminate\Http\JsonResponse
    {
        $students = Student::onlyTrashed()->get();
        if (!$students){
            return self::error('No students deleted', 404);
        }
        return self::success($students,'Deleted students retrieved successfully',200);
    }
    /**
     * Undocumented function
     *
     * @param student $student
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreStudent(Student $student): \Illuminate\Http\JsonResponse
    {
        if($student->Trashed() === null){
            return self::error('Student is already exists not Deleted !! ', 404);
        }else{
            $student->restore();
            return self::success($student,'student restored successfully',200);
        }
    }
    /**
     * Undocumented function
     *
     * @param student $student
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDeleteStudent(Student $student): \Illuminate\Http\JsonResponse
    {
        if($student->Trashed() === null) {
            return self::error('Student is not Deleted !! ', 404);
        }else{
            $student->forceDelete();
            return self::success($student,'student forever Deleted successfully',200);
        }
    }
}
