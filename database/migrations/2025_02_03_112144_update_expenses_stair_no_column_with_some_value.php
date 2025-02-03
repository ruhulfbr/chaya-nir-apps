<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('expenses')
          ->whereNull('stair_no')
          ->update(['stair_no' => 7]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('expenses')
          ->where('stair_no', 7)
          ->update(['stair_no' => null]);
    }
};
