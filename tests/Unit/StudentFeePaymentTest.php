<?php

namespace Tests\Unit;

use App\Models\StudentFeePayment;
use PHPUnit\Framework\TestCase;

class StudentFeePaymentTest extends TestCase
{
    public function test_fillable_fields_include_payment_details(): void
    {
        $model = new StudentFeePayment();

        $this->assertContains('student_id', $model->getFillable());
        $this->assertContains('enrollment_id', $model->getFillable());
        $this->assertContains('fee_structure_id', $model->getFillable());
        $this->assertContains('amount', $model->getFillable());
        $this->assertContains('payment_date', $model->getFillable());
        $this->assertContains('note', $model->getFillable());
    }
}
