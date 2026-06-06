<?php

namespace App\Services;

use App\Models\Partner;

class PartnerService
{
    public function getAll()
    {
        return Partner::latest()->paginate(10)->withQueryString();
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