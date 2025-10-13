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
        Schema::create('navbar_menu_meta', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('route', 55)->nullable();
            $table->json('permissions')->nullable();
            $table->json('parameters')->nullable();
            $table->string('icon', 15)->nullable();
            $table->string('active_routes')->nullable();
            $table->boolean('is_sortable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navbar_menu_meta');
    }
};
