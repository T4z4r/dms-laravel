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
        Schema::table('files', function (Blueprint $table) {
            $table->json('allowed_users')->nullable(); // Array of user IDs who can access
            $table->json('restricted_departments')->nullable(); // Array of department IDs that cannot access
            $table->enum('access_type', ['view_only', 'downloadable'])->default('downloadable'); // View only or downloadable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn(['allowed_users', 'restricted_departments', 'access_type']);
        });
    }
};
