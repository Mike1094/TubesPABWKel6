<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('gates', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('name');
    //         $table->boolean('is_open')->default(true);
    //         $table->foreignId('last_updated_by')->nullable()->constrained('users');
    //         $table->timestamps();
    //     });
    // }

    public function up()
    {
        Schema::create('gates', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gerbang');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->enum('traffic_status', ['lancar', 'padat', 'macet'])->default('lancar');
            $table->string('cctv_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gates');
    }
};
