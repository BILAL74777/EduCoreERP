<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Services\SupplierService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierService $supplierService
    ) {}

    public function index(Request $request)
    {
        return Inertia::render('Suppliers/Index');
    }
    public function getAll(Request $request)
    {
        $suppliers = $this->supplierService->getAll(
            $request->search
        );
 
        return response()->json([
            'suppliers' => $suppliers,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

   public function store(SupplierRequest $request)
    {
        $supplier = $this->supplierService->create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully',
            'data' => $supplier,
        ], 201);
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier = $this->supplierService->update(
            $supplier,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully',
            'data' => $supplier,
        ]);
    }

    public function destroy(Supplier $supplier)
    {
        $this->supplierService->delete($supplier);

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully.'
        ]);
    }
}