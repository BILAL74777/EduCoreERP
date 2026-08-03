<?php

namespace App\Services\Academic;

use App\Models\AcademicSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
class AcademicSessionService
{
    /**
     * Listing
     */
    public function index(array $filters): LengthAwarePaginator
    {
        $query = AcademicSession::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $query->where(function ($q) use ($filters) {

                $q->where('session_name', 'like', "%{$filters['search']}%")
                    ->orWhere('remarks', 'like', "%{$filters['search']}%");

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('is_active', $filters)) {

            $query->where(
                'is_active',
                $filters['is_active']
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            $filters['sort'] ?? 'start_date',
            $filters['direction'] ?? 'desc'
        );

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        return $query->paginate(
            $filters['per_page'] ?? 10
        );
    }

    /**
     * Store
     */
    public function store(array $data): AcademicSession
    {
        return DB::transaction(function () use ($data) {

            if (!empty($data['is_active'])) {

                AcademicSession::query()->update([
                    'is_active' => false,
                ]);

            }

            return AcademicSession::create($data);

        });
    }

    /**
     * Update
     */
    public function update(
        AcademicSession $academicSession,
        array $data
    ): AcademicSession {

        return DB::transaction(function () use (
            $academicSession,
            $data
        ) {

            if (!empty($data['is_active'])) {

                AcademicSession::where(
                    'id',
                    '!=',
                    $academicSession->id
                )->update([
                    'is_active' => false,
                ]);

            }

            $academicSession->update($data);

            return $academicSession->fresh();

        });
    }

    /**
     * Delete
     */
    public function destroy(
        AcademicSession $academicSession
    ): bool {

        return DB::transaction(function () use (
            $academicSession
        ) {

            return $academicSession->delete();

        });
    }

    /**
     * Change Status
     */
    public function changeStatus(
        AcademicSession $academicSession
    ): AcademicSession {

        return DB::transaction(function () use (
            $academicSession
        ) {

            if (!$academicSession->is_active) {

                AcademicSession::query()->update([
                    'is_active' => false,
                ]);

            }

            $academicSession->update([
                'is_active' => !$academicSession->is_active,
            ]);

            return $academicSession->fresh();

        });
    }

    /**
     * Dropdown
     */
    public function dropdown()
    {
        return AcademicSession::where(
                'is_active',
                true
            )
            ->orderBy('start_date', 'desc')
            ->get([
                'id',
                'session_name',
            ]);
    }

    /**
     * Find
     */
    public function find(string $id): AcademicSession
    {
        return AcademicSession::findOrFail($id);
    }
}
