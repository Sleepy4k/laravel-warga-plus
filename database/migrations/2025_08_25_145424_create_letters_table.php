<?php

use App\Enums\LetterType;
use App\Models\LetterClassification;
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
        Schema::create('letters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference_number', 100)->unique();
            $table->string('agenda_number', 100);
            $table->string('from', 100)->nullable();
            $table->string('to', 100)->nullable();
            $table->date('letter_date')->nullable();
            $table->date('received_date')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->enum('type', LetterType::toArray())->default(LetterType::INCOMING);
            $table->foreignIdFor(LetterClassification::class, 'classification_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
