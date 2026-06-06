<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function getAll(?string $search = null)
    {
        return Customer::query()
            ->when(
                $search,
                fn ($query) => $query->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
            )
            ->latest()
            ->paginate(1)
            ->withQueryString();
    }

    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer;
    }

    public function delete(Customer $customer): bool
    {
        return $customer->delete();
    }
}