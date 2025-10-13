<?php

use App\Models\MenuMeta;
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
        Schema::table('menus', function (Blueprint $table) {
            $table->boolean('is_spacer')->default(false)->after('meta_id');
            $table->foreignIdFor(MenuMeta::class, 'meta_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('is_spacer');
            $table->foreignIdFor(MenuMeta::class, 'meta_id')->change();
        });
    }
};
