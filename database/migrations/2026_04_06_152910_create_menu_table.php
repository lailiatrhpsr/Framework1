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
        Schema::create('menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nama_menu');
            $table->decimal('harga');
            $table->unsignedBigInteger('id_vendor');
            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->onDelete('cascade');
            $table->string('path_gambar', 255)->nullable()->change();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
