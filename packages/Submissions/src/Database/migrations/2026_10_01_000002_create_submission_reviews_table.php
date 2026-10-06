<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('administrators')->cascadeOnDelete();
            $table->string('status')->default('assigned'); // assigned | completed
            $table->text('comments')->nullable();
            $table->text('suggestions')->nullable();
            $table->string('recommendation')->nullable();
            // accept | reject | minor_revision | major_revision
            $table->unsignedTinyInteger('score')->nullable(); // e.g. 1–10
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_reviews');
    }
};