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
        Schema::dropIfExists('terms_conditions');
        Schema::rename('privacy_policies', 'cms_pages');

        Schema::table('cms_pages', function (Blueprint $table) {
            $table->string('page_name')->after('id');
            $table->string('page_label')->after('page_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('cms_pages', 'privacy_policies');

        Schema::table('privacy_policies', function (Blueprint $table) {
            $table->dropColumn('page_name');
            $table->dropColumn('page_label');
        });

        Schema::create('terms_conditions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
