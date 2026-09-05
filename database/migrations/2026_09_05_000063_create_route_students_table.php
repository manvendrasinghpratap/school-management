<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('pickup_point')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unique(['route_id','student_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_students');
    }
};
