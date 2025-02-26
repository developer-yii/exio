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
        Schema::create('actual_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->date('date')->nullable();
            $table->string('timeline')->nullable();
            $table->string('work_completed')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment("1:Completed, 0:In Progress");
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
        Schema::dropIfExists('actual_progress');
    }
};
