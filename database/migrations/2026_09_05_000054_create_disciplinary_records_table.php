<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplinary_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('incident_date');
            $table->string('incident_type');
            $table->longText('description');
            $table->text('action_taken')->nullable();
            $table->text('outcome')->nullable();
            $table->boolean('parent_notified')->default(false);
            $table->enum('status', ['open','resolved','closed'])->default('open');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplinary_records');
    }
};
