<?php

use App\Models\DocumentCategory;
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
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 100)->unique();
            $table->foreignIdFor(DocumentCategory::class, 'category_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->timestamp('last_modified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
