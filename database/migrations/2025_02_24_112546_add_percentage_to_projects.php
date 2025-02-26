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
            $table->integer('exio_suggest_percentage')->default(0)->after('property_document');
            $table->integer('amenities_percentage')->default(0)->after('exio_suggest_percentage');
            $table->integer('project_plan_percentage')->default(0)->after('amenities_percentage');
            $table->integer('locality_percentage')->default(0)->after('project_plan_percentage');
            $table->integer('return_of_investment_percentage')->default(0)->after('locality_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('exio_suggest_percentage');
            $table->dropColumn('amenities_percentage');
            $table->dropColumn('project_plan_percentage');
            $table->dropColumn('locality_percentage');
            $table->dropColumn('return_of_investment_percentage');
        });
    }
};
