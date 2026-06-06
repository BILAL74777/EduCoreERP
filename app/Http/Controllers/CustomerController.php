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
        return Inertia::render('Customers/Index');
    }
    public function getAll(Request $request)
    {
        $customers = $this->customerService->getAll(
            $request->search
        );

        return response()->json([
            'customers' => $customers,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

   public function store(CustomerRequest $request)
    {
        $customer = $this->customerService->create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer,
        ], 201);
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer = $this->customerService->update(
            $customer,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer,
        ]);
    }

    public function destroy(Customer $customer)
    {
        $this->customerService->delete($customer);

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully.'
        ]);
    }
}