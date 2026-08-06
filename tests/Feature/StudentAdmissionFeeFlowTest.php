<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicSession;
use App\Models\Discipline;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\StudentFeePayment;
use App\Models\StudentFeeStructure;
use App\Services\Academic\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentAdmissionFeeFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admission_creates_fee_structure_and_initial_payment(): void
    {
        $faculty = Faculty::create([
            'name' => 'Faculty of Science',
        ]);

        $discipline = Discipline::create([
            'faculty_id' => $faculty->id,
            'discipline' => 'Computer Science',
        ]);

        $session = AcademicSession::create([
            'name' => '2026-2027',
            'is_current' => true,
        ]);

        $academicClass = AcademicClass::create([
            'discipline_id' => $discipline->id,
            'name' => '2026',
            'year' => '2026',
        ]);

        $service = new StudentService();

        $request = new class {
            public function hasFile($name) { return false; }
            public function file($name) { return null; }
        };

        $student = $service->store([
            'admission_no' => 'A-1001',
            'registration_no' => 'R-1001',
            'section' => 'A',
            'date_of_admission' => '2026-08-01',
            'name' => 'Ali Khan',
            'father_name' => 'Khan Sahib',
            'father_cnic' => '12345-6789012-3',
            'father_occupation' => 'Teacher',
            'gender' => 'Male',
            'date_of_birth' => '2010-01-01',
            'religion' => 'Islam',
            'tribe_cast' => 'Pathan',
            'contact_number' => '03001234567',
            'guardian_contact_number' => '03007654321',
            'address' => 'Peshawar',
            'is_orphan' => false,
            'is_father_shaheed' => false,
            'remarks' => 'New admission',
            'faculty_id' => $faculty->id,
            'discipline_id' => $discipline->id,
            'session_id' => $session->id,
            'class_id' => $academicClass->id,
            'roll_no' => '001',
            'annual_fee' => 12000,
            'paid_fee' => 5000,
            'payment_date' => '2026-08-01',
        ], $request);

        $this->assertInstanceOf(Student::class, $student);
        $this->assertNotNull($student->feeStructure);

        $feeStructure = StudentFeeStructure::where('student_id', $student->id)->first();
        $this->assertNotNull($feeStructure);
        $this->assertSame('5000.00', (string) $feeStructure->paid_fee);
        $this->assertSame('7000.00', (string) $feeStructure->net_fee);

        $payment = StudentFeePayment::where('student_id', $student->id)->first();
        $this->assertNotNull($payment);
        $this->assertSame('5000.00', (string) $payment->amount);
        $this->assertSame('Initial payment', $payment->note);
    }
}
