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
                'student.photo',
                'enrollment.academicClass.discipline.faculty',
                'feeStructure.academicClass.discipline.faculty',
                'feeStructure.session',
            ])
            ->when(
                $request->search,
                function ($query, $search) {

                    $query->whereHas(
                        'student',
                        function ($studentQuery) use ($search) {

                            $studentQuery->where(function ($q) use ($search) {

                                $q->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'admission_no',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'registration_no',
                                    'like',
                                    "%{$search}%"
                                );
                            });
                        }
                    );
                }
            )
            ->latest('payment_date')
            ->latest('created_at')
            ->paginate(
                $request->integer('per_page', 10)
            );

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    /**
     * Search active students.
     */
    public function students(Request $request): JsonResponse
    {
        $search = $request->get('search');

        $students = Student::query()
            ->with([
                'photo',
                'currentEnrollment.academicClass.discipline.faculty',
                'feeStructure',
            ])
            ->whereHas(
                'currentEnrollment',
                function ($query) {
                    $query
                        ->where('is_current', true)
                        ->where('status', 'Active');
                }
            )
            ->when(
                $search,
                function ($query) use ($search) {

                    $query->where(function ($q) use ($search) {

                        $q->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'admission_no',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'registration_no',
                            'like',
                            "%{$search}%"
                        );
                    });
                }
            )
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students,
        ]);
    }

    /**
     * Complete student fee profile.
     */
    public function fee(string $student): JsonResponse
    {
        $data = $this->service->getStudentFee(
            $student
        );

        return response()->json([
            'success' => true,
            'data' => $data,
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
    public function destroy(
        StudentFeePayment $payment
    ): JsonResponse {

        $this->service->delete(
            $payment
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully.',
        ]);
    }
}