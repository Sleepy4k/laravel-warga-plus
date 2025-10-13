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
        Schema::create('sitemap_data', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->string('last_modified', 20)->nullable();
            $table->string('change_frequency', 35)->nullable()->default('daily');
            $table->float('priority', 3, 2)->default(0.5);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sitemap_data');
    }
};
