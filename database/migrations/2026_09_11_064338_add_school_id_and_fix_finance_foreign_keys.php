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
        /*
        |--------------------------------------------------------------------------
        | Add school_id to Finance Tables
        |--------------------------------------------------------------------------
        */

        Schema::table('fee_categories', function (Blueprint $table) {
            $table->foreignId('school_id')
                ->after('id')
                ->constrained('schools')
                ->cascadeOnDelete();
        });

        Schema::table('fee_structures', function (Blueprint $table) {
            $table->foreignId('school_id')
                ->after('id')
                ->constrained('schools')
                ->cascadeOnDelete();
        });

        Schema::table('scholarships', function (Blueprint $table) {
            $table->foreignId('school_id')
                ->after('id')
                ->constrained('schools')
                ->cascadeOnDelete();
        });

        Schema::table('student_fees', function (Blueprint $table) {
            $table->foreignId('school_id')
                ->after('id')
                ->constrained('schools')
                ->cascadeOnDelete();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('school_id')
                ->after('id')
                ->constrained('schools')
                ->cascadeOnDelete();
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->foreignId('school_id')
                ->after('id')
                ->constrained('schools')
                ->cascadeOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('school_id')
                ->after('id')
                ->constrained('schools')
                ->cascadeOnDelete();
        });

        Schema::table('receipts', function (Blueprint $table) {
            $table->foreignId('school_id')
                ->after('id')
                ->constrained('schools')
                ->cascadeOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | Fix Legacy users_bk Foreign Keys
        |--------------------------------------------------------------------------
        |
        | These Finance tables were incorrectly referencing users_bk.
        | The active application user table is users.
        |
        */

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['created_by']);

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['received_by']);

            $table->foreign('received_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Restore Legacy Foreign Keys
        |--------------------------------------------------------------------------
        */

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['created_by']);

            $table->foreign('created_by')
                ->references('id')
                ->on('users_bk')
                ->nullOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['received_by']);

            $table->foreign('received_by')
                ->references('id')
                ->on('users_bk')
                ->nullOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | Remove school_id
        |--------------------------------------------------------------------------
        */

        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('student_fees', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('fee_structures', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('fee_categories', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
    }
};