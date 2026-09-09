<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Normalize roll numbers per session+class to avoid duplicates before adding unique index.
        $groups = DB::table('student_enrollments')
            ->select('session_id', 'class_id')
            ->distinct()
            ->get();

        foreach ($groups as $g) {
            $enrollments = DB::table('student_enrollments')
                ->where('session_id', $g->session_id)
                ->where('class_id', $g->class_id)
                ->orderBy('created_at')
                ->get();

            $counter = 1;

            foreach ($enrollments as $e) {
                $roll = str_pad((string) $counter, 3, '0', STR_PAD_LEFT);

                DB::table('student_enrollments')
                    ->where('id', $e->id)
                    ->update(['roll_no' => $roll]);

                $counter++;
            }
        }

        Schema::table('student_enrollments', function (Blueprint $table) {
            // Ensure roll numbers are unique within a session + class
            $table->unique(['session_id', 'class_id', 'roll_no'], 'student_enrollments_scoped_roll_unique');

            // Ensure card numbers are unique within a session + class
            $table->unique(['session_id', 'class_id', 'card_no'], 'student_enrollments_scoped_card_unique');
        });
    }

    public function down(): void
    {
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->dropUnique('student_enrollments_scoped_roll_unique');
            $table->dropUnique('student_enrollments_scoped_card_unique');
        });
    }
};
