<?php

namespace App\Http\Controllers\Academic;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Http\Controllers\BaseController;
use App\Services\Academic\FacultyService;
use App\Http\Requests\Academic\FacultyStoreRequest;
use App\Http\Requests\Academic\FacultyUpdateRequest;

class FacultyController extends BaseController
{
    public function __construct(
        protected FacultyService $service
    ) {}

    /**
     * Display Listing
     */
    public function index(Request $request)
    {
        try {

            $faculties = $this->service->index(
                $request->all()
            );

            return $this->paginated(
                $faculties,
                'Faculties fetched successfully.'
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
    public function store(FacultyStoreRequest $request)
    { 
        try {

            $faculty = $this->service->store(
                $request->validated()
            );

            return $this->success(
                $faculty,
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
    public function show(Faculty $faculty)
    {
        return $this->success($faculty);
    }

    /**
     * Update
     */
    public function update(
        FacultyUpdateRequest $request,
        Faculty $faculty
    ) {

        try {

            $faculty = $this->service->update(
                $faculty,
                $request->validated()
            );

            return $this->success(
                $faculty,
                'Faculty updated successfully.'
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
    public function destroy(Faculty $faculty)
    {
        try {

            $this->service->destroy($faculty);

            return $this->success(
                null,
                'Faculty deleted successfully.'
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }
}