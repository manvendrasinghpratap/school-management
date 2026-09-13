<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code');
            $table->decimal('min_score', 8, 2);
            $table->decimal('max_score', 8, 2);
            $table->decimal('grade_point', 5, 2)->default(0);
            $table->enum('result', ['pass','fail'])->default('pass');
            $table->unique(['school_id','code']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
