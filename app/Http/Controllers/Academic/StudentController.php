<?php

namespace App\Http\Controllers\Academic;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Http\Controllers\BaseController;
use App\Services\Academic\StudentService;
use App\Http\Requests\Academic\Student\StoreStudentRequest;
use App\Http\Requests\Academic\Student\UpdateStudentRequest;
use Inertia\Inertia;
use Inertia\Response;
class StudentController extends BaseController
{
    public function __construct(
        protected StudentService $service
    ) {}

    /**
     * Listing
     */
    public function index(Request $request)
    {
        try {

            $students = $this->service->index(
                $request->all()
            );

            return $this->paginated(
                $students,
                'Students fetched successfully.'
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }

    /**
     * Store
     */
    public function store(StoreStudentRequest $request)
    {
        // dd($request);
        try {

            $student = $this->service->store(
                $request->validated(),
                $request
            );

          
             
            return $this->success(
                $student,
                'Faculty created successfully.',
                201
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }

    /**
     * Show
     */
 
public function show(Student $student): Response
{ 
    return Inertia::render(
        'Academic/Students/Show',
        [
            'student' => $this->service->find($student->id),
        ]
    );
}
public function edit(Student $student): Response
{ 
    return Inertia::render(
        'Academic/Students/Edit',
        [
            'student' => $this->service->find($student->id),
        ]
    );
}

    /**
     * Update
     */
    public function update(
        UpdateStudentRequest $request,
        Student $student
    ) {
        
        // dd($student,$request);
        try {

            $student = $this->service->update(
                $student,
                $request->validated(),
                $request
            );

            return 'success';
            return $this->success(
                $student,
                'Student updated successfully.'
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }

    /**
     * Delete
     */
    public function destroy(Student $student)
    {
        try {

            $this->service->destroy(
                $student
            );

            return $this->success(
                null,
                'Student deleted successfully.'
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }

    /**
     * Dropdown
     */
    // public function getDropdown()
    // {
    //     try {

    //         return $this->success([
    //             'students' => $this->service->dropdown(),
    //         ]);

    //     } catch (Throwable $e) {

    //         return $this->error(
    //             $e->getMessage()
    //         );

    //     }
    // }

    public function getDropdowns()
    {
        return response()->json(
            $this->service->getDropdowns()
        );
    }

    public function getDisciplines(string $faculty)
    { 
        return response()->json([
            'disciplines' => $this->service->getDisciplines($faculty),
        ]);
    }

    public function getClasses(string $discipline)
    {
        return response()->json([
            'classes' => $this->service->getClasses($discipline),
        ]);
    }
    
}