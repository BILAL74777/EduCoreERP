<?php

namespace App\Http\Controllers\Academic;

use Throwable;
use Illuminate\Http\Request;
use App\Models\AcademicSession;
use App\Http\Controllers\BaseController;
use App\Services\Academic\AcademicSessionService;
use App\Http\Requests\Academic\AcademicSession\StoreAcademicSessionRequest;
use App\Http\Requests\Academic\AcademicSession\UpdateAcademicSessionRequest;

class AcademicSessionController extends BaseController
{
    public function __construct(
        protected AcademicSessionService $service
    ) {}

    /**
     * Display Listing
     */
    public function index(Request $request)
    {
        try {

            $sessions = $this->service->index(
                $request->all()
            );

            return $this->paginated(
                $sessions,
                'Academic sessions fetched successfully.'
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
    public function store(StoreAcademicSessionRequest $request)
    {
        try {

            $session = $this->service->store(
                $request->validated()
            );

            return $this->success(
                $session,
                'Academic session created successfully.',
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
    public function show(AcademicSession $academicSession)
    {
        return $this->success($academicSession);
    }

    /**
     * Update
     */
    public function update(
        UpdateAcademicSessionRequest $request,
        AcademicSession $academicSession
    ) {
        try {

            $academicSession = $this->service->update(
                $academicSession,
                $request->validated()
            );

            return $this->success(
                $academicSession,
                'Academic session updated successfully.'
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
    public function destroy(AcademicSession $academicSession)
    {
        try {

            $this->service->destroy(
                $academicSession
            );

            return $this->success(
                null,
                'Academic session deleted successfully.'
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
    public function changeStatus(AcademicSession $academicSession)
    {
        try {

            $academicSession = $this->service->changeStatus(
                $academicSession
            );

            return $this->success(
                $academicSession,
                'Academic session status updated successfully.'
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

           return  $this->service->dropdown();

        } catch (Throwable $e) {

            return $this->error(
                $e->getMessage()
            );

        }
    }
}
