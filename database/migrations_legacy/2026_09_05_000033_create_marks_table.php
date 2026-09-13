<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 8, 2);
            $table->decimal('maximum_score', 8, 2);
            $table->foreignId('grade_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['draft','submitted','approved','published'])->default('draft');
            $table->foreignId('entered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->unique(['student_id','examination_id','course_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
