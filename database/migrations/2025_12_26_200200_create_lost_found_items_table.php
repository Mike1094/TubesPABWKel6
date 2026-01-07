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
    //     Schema::create('lost_found_items', function (Blueprint $table) {
    //         $table->id();
    //         $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    //         $table->enum('type', ['lost', 'found']);
    //         $table->string('item_name');
    //         $table->text('description');
    //         $table->string('location');
    //         $table->string('image')->nullable();
    //         $table->enum('status', ['open', 'resolved'])->default('open');
    //         $table->timestamps();
    //     });
    // }
    
    public function up()
    {
        Schema::create('lost_found_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pelapor
            $table->string('nama_barang');
            $table->text('deskripsi');
            $table->string('lokasi_ditemukan')->nullable(); // Jika barang temuan
            $table->string('foto')->nullable();
            $table->enum('jenis', ['hilang', 'ditemukan']); // Lost or Found
            $table->enum('status', ['open', 'claimed', 'resolved'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_found_items');
    }
};
