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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('amenities')->nullable()->after('age_of_construction');
            $table->string('property_document_title')->nullable()->after('amenities');
            $table->string('property_document')->nullable()->after('property_document_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('amenities');
            $table->dropColumn('property_document_title');
            $table->dropColumn('property_document');
        });
    }
};
