<?php

use App\Models\Shortcut;
use App\Models\User;
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
        Schema::create('user_has_shortcuts', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Shortcut::class, 'shortcut_id')->constrained()->cascadeOnDelete();

            $table->primary(['user_id', 'shortcut_id'], 'user_shortcut_primary_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_has_shortcuts');
    }
};
