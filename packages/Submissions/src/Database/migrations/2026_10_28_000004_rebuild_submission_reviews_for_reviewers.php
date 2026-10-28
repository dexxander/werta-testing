<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reviews now come from approved professional reviewers, not admins.
 * submission_reviews was never written to by the app, so it is rebuilt
 * with reviewer_id pointing at `reviewers` plus the evaluation fields.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('submission_reviews');

        Schema::create('submission_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('reviewers')->cascadeOnDelete();
            $table->string('status')->default('assigned');  // assigned | completed | declined
            // Criteria, each 1–5
            $table->unsignedTinyInteger('score_accuracy')->nullable();
            $table->unsignedTinyInteger('score_originality')->nullable();
            $table->unsignedTinyInteger('score_clarity')->nullable();
            $table->unsignedTinyInteger('score_relevance')->nullable();
            $table->unsignedTinyInteger('score_ethics')->nullable();
            $table->string('recommendation')->nullable();    // accept | minor_revision | major_revision | reject
            $table->boolean('suitable_for_sale')->nullable(); // reviewer's view on premium vs free
            $table->text('comments')->nullable();            // shared with the author
            $table->text('suggestions')->nullable();         // required changes, shared with the author
            $table->text('confidential_notes')->nullable();  // admin only
            $table->boolean('conflict_of_interest')->default(false);
            $table->boolean('show_on_article')->default(true); // reviewer agrees to be named on the article
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['submission_id', 'reviewer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_reviews');

        Schema::create('submission_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('administrators')->cascadeOnDelete();
            $table->string('status')->default('assigned');
            $table->text('comments')->nullable();
            $table->text('suggestions')->nullable();
            $table->string('recommendation')->nullable();
            $table->unsignedTinyInteger('score')->nullable();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }
};
