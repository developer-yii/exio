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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->longText('project_about')->nullable();
            $table->foreignId('builder_id')->constrained('builders')->nullable();
            $table->foreignId('city_id')->constrained('cities')->nullable();
            $table->foreignId('location_id')->constrained('locations')->nullable();
            $table->string('property_type')->nullable();
            $table->string('property_sub_types')->nullable();
            $table->date('possession_by')->nullable();
            $table->string('rera_number')->nullable();
            $table->integer('price_from')->nullable();
            $table->string('price_from_unit')->nullable();
            $table->integer('price_to')->nullable();
            $table->string('price_to_unit')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('carpet_area')->nullable();
            $table->string('total_floors')->nullable();
            $table->string('total_tower')->nullable();
            $table->string('age_of_construction')->nullable();
            $table->tinyInteger('status')->default(1)->comment("1:active, 0:inactive")->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
