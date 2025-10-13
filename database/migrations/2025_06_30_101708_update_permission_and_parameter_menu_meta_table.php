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
        Schema::table('menu_meta', function (Blueprint $table) {
            $table->json('parameters')->nullable()->change();
            $table->json('permissions')->nullable()->after('route');
            $table->dropColumn('permission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_meta', function (Blueprint $table) {
            $table->string('parameters')->nullable()->change();
            $table->string('permission')->nullable()->after('route');
            $table->dropColumn('permissions');
        });
    }
};
