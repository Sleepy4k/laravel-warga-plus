<?php

use App\Models\NavbarMenuMeta;
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
        Schema::create('navbar_menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 30);
            $table->integer('order')->default(0);
            $table->foreignIdFor(NavbarMenuMeta::class, 'meta_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_spacer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navbar_menus');
    }
};
