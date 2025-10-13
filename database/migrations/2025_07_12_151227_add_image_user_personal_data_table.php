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
        Schema::table('user_personal_data', function (Blueprint $table) {
            $table->string('avatar')
                ->nullable()
                ->default(asset('img/avatars/silhouette.jpg'))
                ->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_personal_data', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
    }
};
