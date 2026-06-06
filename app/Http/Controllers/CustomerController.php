<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService
    ) {}

    public function index(Request $request)
    {
        return Inertia::render('Customers/Index', [
            'customers' => $this->customerService->getAll(
                $request->search
            ),
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

    public function store(CustomerRequest $request)
    {
        $this->customerService->create(
            $request->validated()
        );

        return back()->with('success', 'Customer created successfully.');
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $this->customerService->update(
            $customer,
            $request->validated()
        );

        return back()->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->customerService->delete($customer);

        return back()->with('success', 'Customer deleted successfully.');
    }
}