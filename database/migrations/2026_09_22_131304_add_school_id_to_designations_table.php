<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('designations', 'school_id')) {
            Schema::table('designations', function (Blueprint $table) {
                $table->foreignId('school_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('schools')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->index(['school_id', 'status']);
            });
        }

        /*
         * Existing designations belong to the existing school.
         * We deliberately preserve account_id because legacy code may
         * still depend on it.
         */
        $schoolId = DB::table('schools')->value('id');

        if ($schoolId !== null) {
            DB::table('designations')
                ->whereNull('school_id')
                ->update([
                    'school_id' => $schoolId,
                ]);
        }

        /*
         * Once existing records have been backfilled, school_id becomes
         * mandatory for all future designation records.
         */
        Schema::table('designations', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('designations', 'school_id')) {
            Schema::table('designations', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropIndex(['school_id', 'status']);
                $table->dropColumn('school_id');
            });
        }
    }
};