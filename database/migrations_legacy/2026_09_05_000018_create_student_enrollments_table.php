<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained()->nullOnDelete();
            $table->date('enrollment_date');
            $table->enum('status', ['active','completed','withdrawn','transferred'])->default('active');
            $table->text('notes')->nullable();
            $table->unique(['student_id','academic_year_id','term_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_enrollments');
    }
};
