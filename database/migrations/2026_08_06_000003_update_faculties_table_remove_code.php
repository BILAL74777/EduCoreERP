<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('faculties', 'code')) {
            Schema::table('faculties', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('faculties', 'code')) {
            Schema::table('faculties', function (Blueprint $table) {
                $table->string('code', 20)->nullable()->unique();
            });
        }
    }
};
