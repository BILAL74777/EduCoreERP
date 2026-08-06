<?php

namespace App\Services\Academic;

use App\Models\Faculty;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
class FacultyService
{
    /**
     * Faculty Listing
     */
    public function index(array $filters): LengthAwarePaginator
    {
        $query = Faculty::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('faculty', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('is_active', $filters)) {
            $query->where('is_active', $filters['is_active']);
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $sort = $filters['sort'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';

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
     * Store Faculty
     */
    public function store(array $data): Faculty
    {
        return DB::transaction(function () use ($data) {
            return Faculty::create($data);
        });
    }

    /**
     * Update Faculty
     */
    public function update(Faculty $faculty, array $data): Faculty
    {
        return DB::transaction(function () use ($faculty, $data) {

            $faculty->update($data);

            return $faculty->fresh();
        });
    }

    /**
     * Delete Faculty
     */
    public function destroy(Faculty $faculty): bool
    {
        return DB::transaction(function () use ($faculty) {
            return $faculty->delete();
        });
    }

    /**
     * Change Status
     */
    public function changeStatus(Faculty $faculty): Faculty
    {
        return DB::transaction(function () use ($faculty) {

            $faculty->update([
                'is_active' => ! $faculty->is_active,
            ]);

            return $faculty->fresh();
        });
    }

    /**
     * Find Faculty
     */
    public function find(int|string $id): Faculty
    {
        return Faculty::findOrFail($id);
    }

    /**
     * Faculty Dropdown
     */
    public function dropdown()
    {
        return Faculty::query()
            ->where('is_active', true)
            ->select('id', 'faculty')
            ->orderBy('faculty')
            ->get();
    }
}