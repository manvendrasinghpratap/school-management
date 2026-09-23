<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schoolId = DB::table('schools')->orderBy('id')->value('id');
        if (!$schoolId) {
            throw new RuntimeException('Wave 4 migration requires at least one school.');
        }

        $schoolTables = [
            'suppliers', 'inventory_items', 'visitors',
            'complaints', 'disciplinary_records', 'medical_records',
            'staff_documents',
        ];

        foreach ($schoolTables as $table) {
            if (!Schema::hasTable($table) || Schema::hasColumn($table, 'school_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedBigInteger('school_id')->nullable()->after('id');
                $blueprint->index('school_id');
            });

            DB::table($table)->whereNull('school_id')->update(['school_id' => $schoolId]);
        }

        if (Schema::hasTable('designations')) {
            DB::table('designations')
                ->whereNull('account_id')
                ->update(['account_id' => $schoolId]);
        }

        if (!Schema::hasTable('admission_applications')) {
            Schema::create('admission_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
                $table->string('application_number', 100);
                $table->string('first_name');
                $table->string('middle_name')->nullable();
                $table->string('last_name');
                $table->date('date_of_birth')->nullable();
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
                $table->string('phone', 50)->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('guardian_name')->nullable();
                $table->string('guardian_phone', 50)->nullable();
                $table->string('guardian_email')->nullable();
                $table->string('guardian_relationship', 100)->nullable();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
                $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
                $table->enum('status', ['draft', 'submitted', 'under_review', 'accepted', 'rejected', 'converted'])->default('submitted');
                $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
                $table->timestamp('applied_at')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->unique(['school_id', 'application_number']);
                $table->index(['school_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_applications');

        foreach ([
            'staff_documents', 'medical_records', 'disciplinary_records',
            'complaints', 'visitors', 'inventory_items', 'suppliers',
        ] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'school_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropIndex(['school_id']);
                    $blueprint->dropColumn('school_id');
                });
            }
        }
    }
};
