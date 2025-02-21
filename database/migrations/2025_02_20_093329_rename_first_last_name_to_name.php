<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add the new nullable "name" column
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        // 2. Merge existing first_name and last_name into "name"
        // CONCAT_WS will gracefully handle null values by ignoring them.
        DB::statement("UPDATE users SET name = CONCAT_WS(' ', first_name, last_name)");

        // 3. Remove the old columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Re-add the old columns (set as nullable)
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
        });

        // 2. Split "name" back into first_name and last_name
        // This basic approach uses the first space as a divider.
        DB::statement("UPDATE users SET first_name = SUBSTRING_INDEX(name, ' ', 1), last_name = TRIM(SUBSTR(name, LENGTH(SUBSTRING_INDEX(name, ' ', 1)) + 1))");

        // 3. Drop the "name" column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
