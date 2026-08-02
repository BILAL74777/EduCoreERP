<?php

namespace App\Http\Controllers\Academic;

use Throwable;
use Illuminate\Http\Request;
use App\Models\AcademicClass;
use App\Http\Controllers\BaseController;
use App\Services\Academic\AcademicClassService;
use App\Http\Requests\Academic\AcademicClass\StoreAcademicClassRequest;
use App\Http\Requests\Academic\AcademicClass\UpdateAcademicClassRequest;

class AcademicClassController extends BaseController
{
    public function __construct(
        protected AcademicClassService $service
    ) {}

    /**
     * Listing
     */
    public function index(Request $request)
    {
        try {

            $classes = $this->service->index(
                $request->all()
            );

            return $this->paginated(
                $classes,
                'Academic classes fetched successfully.'
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
    public function store(StoreAcademicClassRequest $request)
    {
        try {

            $class = $this->service->store(
                $request->validated()
            );

            return $this->success(
                $class,
                'Academic class created successfully.',
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
    public function show(AcademicClass $academicClass)
    {
        return $this->success(
            $academicClass->load([
                'discipline',
                'nextClass',
            ])
        );
    }

    /**
     * Update
     */
    public function update(
        UpdateAcademicClassRequest $request,
        AcademicClass $academicClass
    ) {
        try {

            $academicClass = $this->service->update(
                $academicClass,
                $request->validated()
            );

            return $this->success(
                $academicClass,
                'Academic class updated successfully.'
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
    public function destroy(AcademicClass $academicClass)
    {
        try {

            $this->service->destroy(
                $academicClass
            );

            return $this->success(
                null,
                'Academic class deleted successfully.'
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }

    /**
     * Change Status
     */
    public function changeStatus(AcademicClass $academicClass)
    {
        try {

            $academicClass = $this->service->changeStatus(
                $academicClass
            );

            return $this->success(
                $academicClass,
                'Academic class status updated successfully.'
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
    public function getDropdown()
    {
        try {

            return response()->json([
                'classes' => $this->service->dropdown(),
            ]);

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }
}