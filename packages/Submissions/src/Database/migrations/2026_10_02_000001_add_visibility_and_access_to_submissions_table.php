<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('visibility')->default('public')->after('status');  // public | private
            $table->string('access_type')->default('free')->after('visibility'); // free | paid
            $table->decimal('price', 8, 2)->nullable()->after('access_type');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['visibility', 'access_type', 'price']);
        });
    }
};