<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StoreStudentFeePaymentRequest;
use App\Models\Student;
use App\Models\StudentFeePayment;
use App\Services\Finance\StudentFeePaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentFeePaymentController extends Controller
{
    public function __construct(
        protected StudentFeePaymentService $service
    ) {
    }

    /**
     * Payment history.
     */
    public function index(Request $request): JsonResponse
    {
        $payments = StudentFeePayment::query()
            ->with([
                'student',
                'enrollment.academicClass.discipline.faculty',
                'feeStructure',
            ])
            ->when(
                $request->search,
                function ($query, $search) {
                    $query->whereHas('student', function ($studentQuery) use ($search) {
                        $studentQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('admission_no', 'like', "%{$search}%")
                            ->orWhere('registration_no', 'like', "%{$search}%");
                    });
                }
            )
            ->latest('payment_date')
            ->latest('created_at')
            ->paginate($request->integer('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    /**
     * Student search.
     */
    public function students(Request $request): JsonResponse
    {
        $search = $request->get('search');

        $students = Student::query()
            ->with([
                'currentEnrollment.academicClass.discipline.faculty',
                'feeStructure',
            ])
            ->whereHas('currentEnrollment', function ($query) {
                $query->where('is_current', true)
                    ->where('status', 'Active');
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('admission_no', 'like', "%{$search}%")
                        ->orWhere('registration_no', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students,
        ]);
    }

    /**
     * Get student's current fee information.
     */
    public function fee(string $student): JsonResponse
    {
        $fee = $this->service->getStudentFee($student);

        if (!$fee) {
            return response()->json([
                'success' => false,
                'message' => 'No fee structure found for this student.',
            ], 404);
        }

        $payments = $this->service->getPaymentHistory($fee->id);

        return response()->json([
            'success' => true,
            'data' => [
                'fee_structure' => $fee,
                'payments' => $payments,
            ],
        ]);
    }

    /**
     * Collect payment.
     */
    public function store(
        StoreStudentFeePaymentRequest $request
    ): JsonResponse {
        $payment = $this->service->collect(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Fee collected successfully.',
            'data' => $payment,
        ], 201);
    }

    /**
     * Delete payment.
     */
    public function destroy(StudentFeePayment $payment): JsonResponse
    {
        $this->service->delete($payment);

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully.',
        ]);
    }
}