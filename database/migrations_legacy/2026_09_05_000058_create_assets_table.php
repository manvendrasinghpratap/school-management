<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('asset_number')->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['active','maintenance','disposed','lost'])->default('active');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
