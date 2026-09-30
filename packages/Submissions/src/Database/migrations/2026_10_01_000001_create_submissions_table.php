<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->longText('content')->nullable();   // for articles written in-app
            $table->string('file_path')->nullable();   // for uploaded doc/PDF submissions
            $table->string('author_role');              // 'counselor' | 'client' | 'parent'
            $table->string('author_name');
            $table->string('status')->default('submitted');
            // submitted, under_review, revisions_requested, accepted, rejected, published
            $table->text('decision_notes')->nullable(); // admin's overall decision comment
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};