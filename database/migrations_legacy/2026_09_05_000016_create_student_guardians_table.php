<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guardian_id')->constrained()->cascadeOnDelete();
            $table->string('relationship');
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_emergency')->default(false);
            $table->unique(['student_id','guardian_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_guardians');
    }
};
