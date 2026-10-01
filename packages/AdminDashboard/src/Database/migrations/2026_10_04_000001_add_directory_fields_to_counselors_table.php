<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            $table->string('display_title')->nullable()->after('status');
            $table->json('specialties')->nullable()->after('display_title');
            $table->json('languages')->nullable()->after('specialties');
            $table->json('session_modes')->nullable()->after('languages');
            $table->string('state')->nullable()->after('session_modes');
            $table->unsignedSmallInteger('years_experience')->nullable()->after('state');
            $table->text('bio')->nullable()->after('years_experience');
            $table->decimal('rate_individual', 8, 2)->nullable()->after('bio');
            $table->boolean('is_sample')->default(false)->after('rate_individual');
        });
    }

    public function down(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            $table->dropColumn([
                'display_title',
                'specialties',
                'languages',
                'session_modes',
                'state',
                'years_experience',
                'bio',
                'rate_individual',
                'is_sample',
            ]);
        });
    }
};
