<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Every premium paper now has the same price (RM 5, see MockBilling::ARTICLE_TOKENS).
 * Brings existing rows in line. The old prices are not restored on rollback.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('submissions')->where('access_type', 'paid')->update(['price' => 5.00]);
        DB::table('submissions')->where('access_type', 'free')->update(['price' => null]);
    }

    public function down(): void
    {
        //
    }
};
