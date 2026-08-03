<?php

namespace App\Services\Academic;

use App\Models\Discipline;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
class DisciplineService
{
    /**
     * Listing
     */
    public function index(array $filters): LengthAwarePaginator
    {
        $query = Discipline::query()
            ->with('faculty');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Faculty Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['faculty_id'])) {
            $query->where(
                'faculty_id',
                $filters['faculty_id']
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

        $query->orderBy(
            $filters['sort'] ?? 'created_at',
            $filters['direction'] ?? 'desc'
        );

        return $query->paginate(
            $filters['per_page'] ?? 10
        );
    }

    /**
     * Store
     */
    public function store(array $data): Discipline
    {
        return DB::transaction(function () use ($data) {

            return Discipline::create($data);

        });
    }

    /**
     * Update
     */
    public function update(
        Discipline $discipline,
        array $data
    ): Discipline {
        return DB::transaction(function () use (
            $discipline,
            $data
        ) {

            $discipline->update($data);

            return $discipline->fresh();
        });
    }

    /**
     * Delete
     */
    public function destroy(
        Discipline $discipline
    ): bool {
        return DB::transaction(function () use (
            $discipline
        ) {

            return $discipline->delete();

        });
    }

    /**
     * Change Status
     */
    public function changeStatus(
        Discipline $discipline
    ): Discipline {
        return DB::transaction(function () use (
            $discipline
        ) {

            $discipline->update([
                'is_active' => ! $discipline->is_active,
            ]);

            return $discipline->fresh();
        });
    }

    /**
     * Faculty Dropdown
     */
    public function dropdown()
    {
        return Discipline::query()
            ->where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}