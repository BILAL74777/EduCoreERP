<?php

namespace App\Services;

use App\Models\FuelProduct;

class FuelProductService
{
    public function getAll()
    {
        return FuelProduct::latest()->paginate(10)->withQueryString();
    }

    public function create(array $data): FuelProduct
    {
        return FuelProduct::create($data);
    }

    public function update(FuelProduct $fuelProduct, array $data): FuelProduct
    {
        $fuelProduct->update($data);

        return $fuelProduct;
    }

    public function delete(FuelProduct $fuelProduct): bool
    {
        return $fuelProduct->delete();
    }
}