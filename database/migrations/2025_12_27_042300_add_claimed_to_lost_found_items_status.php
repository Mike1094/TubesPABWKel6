<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lost_found_items', function (Blueprint $table) {
            DB::statement("ALTER TABLE lost_found_items MODIFY COLUMN status ENUM('open', 'resolved', 'pending', 'claimed') NOT NULL DEFAULT 'open'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lost_found_items', function (Blueprint $table) {
            DB::statement("ALTER TABLE lost_found_items MODIFY COLUMN status ENUM('open', 'resolved', 'pending') NOT NULL DEFAULT 'open'");
        });
    }
};
