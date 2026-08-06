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
        if (Schema::hasColumn('disciplines', 'code')) {
            Schema::table('disciplines', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }

        if (Schema::hasColumn('disciplines', 'duration_years')) {
            Schema::table('disciplines', function (Blueprint $table) {
                $table->dropColumn('duration_years');
            });
        }

        if (Schema::hasColumn('disciplines', 'name')) {
            Schema::table('disciplines', function (Blueprint $table) {
                $table->renameColumn('name', 'discipline');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('disciplines', 'discipline')) {
            Schema::table('disciplines', function (Blueprint $table) {
                $table->renameColumn('discipline', 'name');
            });
        }

        if (! Schema::hasColumn('disciplines', 'code')) {
            Schema::table('disciplines', function (Blueprint $table) {
                $table->string('code', 20)->nullable()->unique();
            });
        }

        if (! Schema::hasColumn('disciplines', 'duration_years')) {
            Schema::table('disciplines', function (Blueprint $table) {
                $table->unsignedTinyInteger('duration_years')->default(4);
            });
        }
    }
};
