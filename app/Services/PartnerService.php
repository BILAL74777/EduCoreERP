<?php

namespace App\Services;

use App\Models\Partner;

class PartnerService
{
    public function getAll(?string $search = null,?string $status = null) 
    {
        return Partner::query()
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

    public function create(array $data): Partner
    {
        return Partner::create($data);
    }

    public function update(Partner $partner, array $data): Partner
    {
        $partner->update($data);

        return $partner;
    }

    public function delete(Partner $partner): bool
    {
        return $partner->delete();
    }
}