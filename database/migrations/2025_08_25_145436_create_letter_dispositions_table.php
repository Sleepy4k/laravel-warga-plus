<?php

use App\Models\Letter;
use App\Models\LetterStatus;
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
        Schema::create('letter_dispositions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('to', 100);
            $table->date('due_date');
            $table->text('content');
            $table->text('note')->nullable();
            $table->foreignIdFor(LetterStatus::class, 'letter_status_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('letter_dispositions');
    }
};
