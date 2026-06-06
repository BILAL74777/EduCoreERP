<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function getAll(?string $search = null,?string $status = null) 
    {
        return Supplier::query()
            ->when(
                $search,
                fn ($q) => $q->where('name', 'like', "%{$search}%")
            )
            ->when(
                $status !== null,
                fn ($q) => $q->where('status', $status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);

        return $supplier;
    }

    public function delete(Supplier $supplier): bool
    {
        return $supplier->delete();
    }
}