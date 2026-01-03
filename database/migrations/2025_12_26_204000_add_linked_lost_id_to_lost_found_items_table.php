<?php

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
        Schema::table('lost_found_items', function (Blueprint $table) {
            $table->foreignId('linked_lost_id')->nullable()->constrained('lost_found_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lost_found_items', function (Blueprint $table) {
            $table->dropForeign(['linked_lost_id']);
            $table->dropColumn('linked_lost_id');
        });
    }
};
