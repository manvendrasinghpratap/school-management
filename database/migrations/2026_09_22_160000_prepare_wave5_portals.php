<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('guardians', 'user_id')) {
            Schema::table('guardians', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('school_id')->constrained('users')->nullOnDelete();
                $table->index(['school_id', 'user_id']);
            });
        }

        if (! Schema::hasTable('api_tokens')) {
            Schema::create('api_tokens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('name', 100)->default('api');
                $table->string('token', 64)->unique();
                $table->json('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'expires_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
        if (Schema::hasColumn('guardians', 'user_id')) {
            Schema::table('guardians', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropIndex(['school_id', 'user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
