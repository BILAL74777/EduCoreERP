<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Services\PartnerService;
use Inertia\Inertia;

class PartnerController extends Controller
{
    public function __construct(
        protected PartnerService $partnerService
    ) {}

    public function index()
    {
        return Inertia::render('Partners/Index', [
            'partners' => $this->partnerService->getAll(),
        ]);
    }

    public function store(PartnerRequest $request)
    {
        $this->partnerService->create(
            $request->validated()
        );

        return back()->with('success', 'Partner created successfully.');
    }

    public function update(PartnerRequest $request, Partner $partner)
    {
        $this->partnerService->update(
            $partner,
            $request->validated()
        );

        return back()->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner)
    {
        $this->partnerService->delete($partner);

        return back()->with('success', 'Partner deleted successfully.');
    }
}