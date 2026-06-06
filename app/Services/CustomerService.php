<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function getAll(?string $search = null,?string $status = null) 
    {
        return Customer::query()
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