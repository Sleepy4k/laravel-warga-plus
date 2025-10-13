<?php

use App\Models\Letter;
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
        Schema::create('letter_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('path')->nullable();
            $table->string('file_name');
            $table->string('file_size')->nullable();
            $table->string('extension', 25)->default('pdf');
            $table->foreignIdFor(Letter::class, 'letter_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_attachments');
    }
};
