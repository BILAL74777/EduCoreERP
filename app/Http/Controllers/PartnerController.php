<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Services\PartnerService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function __construct(
        protected PartnerService $partnerService
    ) {}

    public function index(Request $request)
    {
        return Inertia::render('Partners/Index');
    }
    public function getAll(Request $request)
    {
        $partners = $this->partnerService->getAll(
            $request->search
        );

        return response()->json([
            'partners' => $partners,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

   public function store(PartnerRequest $request)
    {
        $partner = $this->partnerService->create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Partner created successfully',
            'data' => $partner,
        ], 201);
    }

    public function update(PartnerRequest $request, Partner $partner)
    {
        $partner = $this->partnerService->update(
            $partner,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Partner updated successfully',
            'data' => $partner,
        ]);
    }

    public function destroy(Partner $partner)
    {
        $this->partnerService->delete($partner);

        return response()->json([
            'success' => true,
            'message' => 'Partner deleted successfully.'
        ]);
    }
}