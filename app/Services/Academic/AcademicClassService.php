<?php

namespace App\Services\Academic;

use App\Models\AcademicClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
class AcademicClassService
{
    /**
     * Academic Class Listing
     */
    public function index(array $filters): LengthAwarePaginator
    {
        $query = AcademicClass::with([
            'discipline',
            'nextClass',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where('year', 'like', "%{$search}%")
                    ->orWhere('next_class', 'like', "%{$search}%")
                    ->orWhere('previous_class', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Discipline Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['discipline_id'])) {

            $query->where(
                'discipline_id',
                $filters['discipline_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
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

        $sort = $filters['sort'] ?? 'level';
        $direction = $filters['direction'] ?? 'asc';

        $query->orderBy($sort, $direction);

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
     * Store Academic Class
     */
    public function store(array $data): AcademicClass
    {
        return DB::transaction(function () use ($data) {

            return AcademicClass::create($data);

        });
    }

    /**
     * Update Academic Class
     */
    public function update(
        AcademicClass $academicClass,
        array $data
    ): AcademicClass {

        return DB::transaction(function () use (
            $academicClass,
            $data
        ) {

            $academicClass->update($data);

            return $academicClass->fresh([
                'discipline',
                'nextClass',
            ]);

        });
    }

    /**
     * Delete Academic Class
     */
    public function destroy(
        AcademicClass $academicClass
    ): bool {

        return DB::transaction(function () use (
            $academicClass
        ) {

            return $academicClass->delete();

        });
    }

    /**
     * Change Status
     */
    public function changeStatus(
        AcademicClass $academicClass
    ): AcademicClass {

        return DB::transaction(function () use (
            $academicClass
        ) {

            $academicClass->update([
                'is_active' => ! $academicClass->is_active,
            ]);

            return $academicClass->fresh([
                'discipline',
                'nextClass',
            ]);

        });
    }

    /**
     * Find Academic Class
     */
    public function find(
        string $id
    ): AcademicClass {

        return AcademicClass::with([
            'discipline',
            'nextClass',
        ])->findOrFail($id);
    }

    /**
     * Dropdown
     */
    public function dropdown(): Collection
    {
        return AcademicClass::query()
            ->where('is_active', true)
            ->select(
                'id',
                'year',
                'level',
                'discipline_id'
            )
            ->orderBy('level')
            ->get();
    }
}