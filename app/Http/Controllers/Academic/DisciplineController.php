<?php

namespace App\Http\Controllers\Academic;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Discipline;
use App\Services\Academic\DisciplineService;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Academic\Discipline\StoreDisciplineRequest;
use App\Http\Requests\Academic\Discipline\UpdateDisciplineRequest;

class DisciplineController extends BaseController
{
    public function __construct(
        protected DisciplineService $service
    ) {}

    /**
     * Listing
     */
    public function index(Request $request)
    {
        try {

            $disciplines = $this->service->index(
                $request->all()
            );

            return $this->paginated(
                $disciplines,
                'Disciplines fetched successfully.'
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
    public function store(
        StoreDisciplineRequest $request
    ) {
        try {

            $discipline = $this->service->store(
                $request->validated()
            );

            return $this->success(
                $discipline,
                'Discipline created successfully.',
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
    public function show(
        Discipline $discipline
    ) {
        return $this->success(
            $discipline->load('faculty')
        );
    }

    /**
     * Update
     */
    public function update(
        UpdateDisciplineRequest $request,
        Discipline $discipline
    ) {
        try {

            $discipline = $this->service->update(
                $discipline,
                $request->validated()
            );

            return $this->success(
                $discipline,
                'Discipline updated successfully.'
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
    public function destroy(
        Discipline $discipline
    ) {
        try {

            $this->service->destroy(
                $discipline
            );

            return $this->success(
                null,
                'Discipline deleted successfully.'
            );

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }
}