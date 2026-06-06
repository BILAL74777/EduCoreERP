<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Services\SupplierService;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierService $supplierService
    ) {}

    public function index()
    {
        return Inertia::render('Suppliers/Index', [
            'suppliers' => $this->supplierService->getAll(),
        ]);
    }

    public function store(SupplierRequest $request)
    {
        $this->supplierService->create(
            $request->validated()
        );

        return back()->with('success', 'Supplier created successfully.');
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $this->supplierService->update(
            $supplier,
            $request->validated()
        );

        return back()->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $this->supplierService->delete($supplier);

        return back()->with('success', 'Supplier deleted successfully.');
    }
}