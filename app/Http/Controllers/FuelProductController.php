<?php

namespace App\Http\Controllers;

use App\Http\Requests\FuelProductRequest;
use App\Models\FuelProduct;
use App\Services\FuelProductService;
use Inertia\Inertia;

class FuelProductController extends Controller
{
    public function __construct(
        protected FuelProductService $fuelProductService
    ) {}

    public function index()
    {
        return Inertia::render('FuelProducts/Index', [
            'fuelProducts' => $this->fuelProductService->getAll(),
        ]);
    }

    public function store(FuelProductRequest $request)
    {
        $this->fuelProductService->create(
            $request->validated()
        );

        return back()->with('success', 'Fuel product created successfully.');
    }

    public function update(
        FuelProductRequest $request,
        FuelProduct $fuelProduct
    ) {
        $this->fuelProductService->update(
            $fuelProduct,
            $request->validated()
        );

        return back()->with('success', 'Fuel product updated successfully.');
    }

    public function destroy(FuelProduct $fuelProduct)
    {
        $this->fuelProductService->delete($fuelProduct);

        return back()->with('success', 'Fuel product deleted successfully.');
    }
}