<?php

namespace App\Services\Academic;

use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\StudentFeeStructure;
use App\Models\Faculty;
use App\Models\AcademicSession;
use App\Models\Discipline;
use App\Models\Upload;
use App\Models\AcademicClass;
use App\Models\StudentFeePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
 
 
class StudentService
{

    /**
     * Student Listing
     */
    public function index(array $filters): LengthAwarePaginator
    {
        $query = Student::query()
            ->with([
                'currentEnrollment.academicClass.discipline.faculty',
                'photo',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('father_name', 'like', "%{$search}%")
                    ->orWhere('admission_no', 'like', "%{$search}%")
                    ->orWhere('registration_no', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhere('section', 'like', "%{$search}%");

            });

        }

        if (!empty($filters['faculty_id'])) {
            $query->whereHas('currentEnrollment', function ($q) use ($filters) {
                $q->where('faculty_id', $filters['faculty_id']);
            });
        }

        if (!empty($filters['discipline_id'])) {
            $query->whereHas('currentEnrollment', function ($q) use ($filters) {
                $q->where('discipline_id', $filters['discipline_id']);
            });
        }

        if (!empty($filters['class_id'])) {
            $query->whereHas('currentEnrollment', function ($q) use ($filters) {
                $q->where('class_id', $filters['class_id']);
            });
        }

        if (!empty($filters['session_id'])) {
            $query->whereHas('currentEnrollment', function ($q) use ($filters) {
                $q->where('session_id', $filters['session_id']);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            $filters['sort'] ?? 'created_at',
            $filters['direction'] ?? 'desc'
        );


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        return $query->paginate(
            $filters['per_page'] ?? 10
        );
    }



    /**
     * Store Student
    */
   public function store(array $data, $request): Student
    {
        return DB::transaction(function () use ($data, $request) {

            /*
            |--------------------------------------------------------------------------
            | Upload Student Photo
            |--------------------------------------------------------------------------
            */

            $photoId = null;

            if ($request->hasFile('photo')) {

                $file = $request->file('photo');

                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                $folder = 'students/photos';

                $file->storeAs($folder, $fileName, 'public');

                $upload = Upload::create([
                    'file_original_name' => $file->getClientOriginalName(),
                    'file_name'          => $fileName,
                    'file_size'          => $file->getSize(),
                    'extension'          => $file->getClientOriginalExtension(),
                    'mime_type'          => $file->getMimeType(),
                    'disk'               => 'public',
                    'folder'             => $folder,
                ]);

                $photoId = $upload->id;
            }

            /*
            |--------------------------------------------------------------------------
            | Form B value
            |--------------------------------------------------------------------------
            */

            $formBValue = $data['form_b'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | Student
            |--------------------------------------------------------------------------
            */

            $student = Student::create([

                'photo'               => $photoId,
                'form_b'              => $formBValue,

                'admission_no'        => $data['admission_no'],
                'registration_no'     => $data['registration_no'] ?? null,
                'section'             => $data['section'] ?? null,
                'date_of_admission'   => $data['date_of_admission'],

                'name'                => $data['name'],
                'father_name'         => $data['father_name'],
                'father_cnic'         => $data['father_cnic'] ?? null,
                'father_occupation'   => $data['father_occupation'] ?? null,

                'gender'              => $data['gender'],
                'date_of_birth'       => $data['date_of_birth'] ?? null,
                'religion'            => $data['religion'] ?? null,
                'tribe_cast'          => $data['tribe_cast'] ?? null,

                'contact_number'      => $data['contact_number'] ?? null,
                'guardian_contact_number' => $data['guardian_contact_number'] ?? null,
                'address'             => $data['address'] ?? null,

                'is_orphan'           => $data['is_orphan'] ?? false,
                'is_father_shaheed'   => $data['is_father_shaheed'] ?? false,

                'remarks'             => $data['remarks'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Enrollment
            |--------------------------------------------------------------------------
            */

            // Determine roll number (auto-generate if not provided)
            $rollNo = $data['roll_no'] ?? null;

            if (!$rollNo) {
                $rollNo = $this->generateRollNo(
                    $data['session_id'],
                    $data['class_id'],
                    $student->section ?? null
                );
            }

            $cardNo = $data['card_no'] ?? $this->generateCardNo(
                $data['session_id'],
                $data['class_id'],
                $rollNo,
                $student->section ?? null
            );

            $enrollment = StudentEnrollment::create([
                'student_id'      => $student->id,

                'faculty_id'      => $data['faculty_id'],
                'discipline_id'   => $data['discipline_id'],

                'session_id'      => $data['session_id'],
                'class_id'        => $data['class_id'],

                'roll_no'         => $rollNo,
                'card_no'         => $cardNo,

                'status'          => 'Active',
                'is_current'      => true,

                'remarks'         => $data['remarks'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Fee Structure
            |--------------------------------------------------------------------------
            */

            $feeStructure = StudentFeeStructure::create([

                'student_id'      => $student->id,

                'enrollment_id'   => $enrollment->id,

                // 'faculty_id'      => $data['faculty_id'],
                // 'discipline_id'   => $data['discipline_id'],

                'session_id'      => $data['session_id'],
                'class_id'        => $data['class_id'],

                'annual_fee'      => $data['annual_fee'],

                'paid_fee'        => $data['paid_fee'] ?? 0,

                'net_fee'         => $data['annual_fee'] - ($data['paid_fee'] ?? 0),

                'remarks'         => $data['remarks'] ?? null,
            ]);

            if (!empty($data['paid_fee']) && (float) $data['paid_fee'] > 0) {
                StudentFeePayment::create([
                    'student_id' => $student->id,
                    'enrollment_id' => $enrollment->id,
                    'fee_structure_id' => $feeStructure->id,
                    'amount' => $data['paid_fee'],
                    'payment_date' => $data['payment_date'] ?? now()->toDateString(),
                    
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Return
            |--------------------------------------------------------------------------
            */

            return $student->load([
                'photo',
                'currentEnrollment.academicClass.discipline.faculty',
                'feeStructure',
            ]);
        });
    }

    /**
 * Update Student
 */
public function update(
    Student $student,
    array $data,
    $request
): Student {

    return DB::transaction(function () use (
        $student,
        $data,
        $request
    ) {

        /*
        |--------------------------------------------------------------------------
        | Validate Faculty -> Discipline -> Class
        |--------------------------------------------------------------------------
        */

        $class = AcademicClass::with('discipline')->findOrFail($data['class_id']);

        if ($class->discipline_id != $data['discipline_id']) {
            throw new \Exception(
                'Selected class does not belong to the selected discipline.'
            );
        }

        if ($class->discipline->faculty_id != $data['faculty_id']) {
            throw new \Exception(
                'Selected discipline does not belong to the selected faculty.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload New Photo (if provided)
        |--------------------------------------------------------------------------
        */

        $photoId = $student->photo;

        if ($request->hasFile('photo')) {

            // Delete old photo
            if ($student->photo) {

                $oldUpload = Upload::find($student->photo);

                if ($oldUpload) {

                    Storage::disk($oldUpload->disk)->delete(
                        $oldUpload->folder . '/' . $oldUpload->file_name
                    );

                    $oldUpload->delete();
                }
            }

            // Upload new photo
            $file = $request->file('photo');

            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $folder = 'students/photos';

            $file->storeAs($folder, $fileName, 'public');

            $upload = Upload::create([
                'file_original_name' => $file->getClientOriginalName(),
                'file_name'          => $fileName,
                'file_size'          => $file->getSize(),
                'extension'          => $file->getClientOriginalExtension(),
                'mime_type'          => $file->getMimeType(),
                'disk'               => 'public',
                'folder'             => $folder,
            ]);

            $photoId = $upload->id;
        }

        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */

        $student->update([

            'photo' => $photoId,

            'form_b' => $data['form_b'] ?? null,

            'admission_no' => $data['admission_no'],

            'registration_no' => $data['registration_no'] ?? null,

            'section' => $data['section'] ?? null,

            'date_of_admission' => $data['date_of_admission'],

            'name' => $data['name'],

            'father_name' => $data['father_name'],

            'father_cnic' => $data['father_cnic'] ?? null,

            'father_occupation' => $data['father_occupation'] ?? null,

            'gender' => $data['gender'],

            'date_of_birth' => $data['date_of_birth'] ?? null,

            'religion' => $data['religion'] ?? null,

            'tribe_cast' => $data['tribe_cast'] ?? null,

            'contact_number' => $data['contact_number'] ?? null,

            'guardian_contact_number' => $data['guardian_contact_number'] ?? null,

            'address' => $data['address'] ?? null,

            'is_orphan' => $data['is_orphan'] ?? false,

            'is_father_shaheed' => $data['is_father_shaheed'] ?? false,

            'remarks' => $data['remarks'] ?? null,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Enrollment
        |--------------------------------------------------------------------------
        */

        $student->currentEnrollment()->update([

            'faculty_id' => $data['faculty_id'],

            'discipline_id' => $data['discipline_id'],

            'session_id' => $data['session_id'],

            'class_id' => $data['class_id'],

            'roll_no' => $data['roll_no'],

            'remarks' => $data['remarks'] ?? null,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Fee Structure
        |--------------------------------------------------------------------------
        */

        $student->feeStructure()->update([

            'session_id' => $data['session_id'],

            'class_id' => $data['class_id'],

            'annual_fee' => $data['annual_fee'],

            'paid_fee' => $data['paid_fee'] ?? 0,

            'net_fee' => $data['annual_fee'] - ($data['paid_fee'] ?? 0),

            'remarks' => $data['remarks'] ?? null,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Return Updated Student
        |--------------------------------------------------------------------------
        */

        return $this->find($student->id);
    });
}

    /**
     * Generate a unique card number based on session, class, section and roll.
     */
    private function generateCardNo(
        string $sessionId,
        string $classId,
        ?string $rollNo,
        ?string $section
    ): string {
        $session = AcademicSession::find($sessionId);
        $class = AcademicClass::find($classId);

        $sessionCode = $session?->session_name ?? substr($sessionId, 0, 4);
        $classCode = $class?->name ?? substr($classId, 0, 4);

        // sanitize and shorten codes
        $sanitize = function ($v) {
            return preg_replace('/[^A-Z0-9]/', '', strtoupper(substr($v, 0, 8)));
        };

        $sessionCode = $sanitize($sessionCode);
        $classCode = $sanitize($classCode);

        $sectionCode = $section ? $sanitize($section) : '';

        if ($rollNo) {
            $number = (int) $rollNo;
        } else {
            $count = StudentEnrollment::query()
                ->where('session_id', $sessionId)
                ->where('class_id', $classId)
                ->when($section, fn($q) => $q->whereHas('student', fn($q2) => $q2->where('section', $section)))
                ->count();

            $number = $count + 1;
        }

        $numPadded = str_pad((string) $number, 3, '0', STR_PAD_LEFT);

        $base = $sessionCode . $classCode . $sectionCode . $numPadded;

        $cardNo = $base;
        $suffix = 1;

        // ensure uniqueness
        while (
            StudentEnrollment::query()
                ->where('card_no', $cardNo)
                ->exists()
        ) {
            $cardNo = $base . sprintf('%02d', $suffix);
            $suffix++;
        }

        return $cardNo;
    }

    /**
     * Generate the next roll number (3-digit) for a session+class+section.
     */
    private function generateRollNo(
        string $sessionId,
        string $classId,
        ?string $section
    ): string {
        $query = StudentEnrollment::query()
            ->where('session_id', $sessionId)
            ->where('class_id', $classId);

        if ($section !== null && $section !== '') {
            $query->whereHas('student', function ($q) use ($section) {
                $q->where('section', $section);
            });
        }

        // Find max numeric roll_no in the scope
        $max = $query->get()->map(function ($e) {
            return is_numeric($e->roll_no) ? (int) $e->roll_no : null;
        })->filter()->max();

        $next = ($max ? $max : 0) + 1;

        return str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }



    /**
     * Delete
     */
    public function destroy(Student $student): bool
    {
        return DB::transaction(function () use ($student) {

            /*
            |--------------------------------------------------------------------------
            | Delete Student Photo
            |--------------------------------------------------------------------------
            */

            if ($student->photo) {

                $upload = $student->photo;

                $filePath = $upload->folder . '/' . $upload->file_name;

                if (Storage::disk($upload->disk)->exists($filePath)) {
                    Storage::disk($upload->disk)->delete($filePath);
                }

                $upload->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | Delete Student
            |--------------------------------------------------------------------------
            */

            return $student->delete();
        });
    }



   /**
     * Find
     */
    public function find(string $id): Student
    {
        return Student::with([

            'photo', 

            'feeStructure',

            'currentEnrollment.academicSession',

            'currentEnrollment.academicClass.discipline.faculty',

        ])->findOrFail($id);
    }



    /**
     * Dropdown
     */
    // public function dropdown()
    // {
    //     return Student::select(
    //             'id',
    //             'name',
    //             'father_name',
    //             'admission_no'
    //         )
    //         ->orderBy('name')
    //         ->get();
    // }

    public function getDropdowns()
{
    return [
        'faculties' => Faculty::where('is_active', true)
            ->select('id', 'faculty')
            ->orderBy('faculty')
            ->get(),

        'sessions' => AcademicSession::where('is_active', true)
            ->select('id', 'session_name')
            ->orderByDesc('start_date')
            ->get(),
    ];
}

public function getDisciplines(string $facultyId)
{
    return Discipline::where('faculty_id', $facultyId)
        ->where('is_active', true)
        ->select('id', 'discipline')
        ->orderBy('discipline')
        ->get();
}

public function getClasses(string $disciplineId)
{
    return AcademicClass::where('discipline_id', $disciplineId)
        ->where('is_active', true)
        ->orderBy('level')
        ->select('id', 'name', 'level')
        ->get();
}

}
