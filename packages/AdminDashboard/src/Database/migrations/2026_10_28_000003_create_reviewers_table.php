<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviewers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title');                       // e.g. Clinical Psychologist
            $table->string('field');                       // Submission::CATEGORIES key
            $table->string('institution')->nullable();
            $table->string('qualifications')->nullable();  // e.g. PhD Clinical Psychology
            $table->string('registration_no')->nullable(); // professional body number
            $table->string('email')->unique();
            $table->text('bio')->nullable();
            $table->string('proof_path')->nullable();      // uploaded credentials (private disk in the real build)
            $table->string('status')->default('pending');  // pending | approved | rejected | suspended
            $table->boolean('show_publicly')->default(true);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviewers');
    }
};
