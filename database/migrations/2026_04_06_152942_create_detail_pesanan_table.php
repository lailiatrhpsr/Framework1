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
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('iddetail_pesanan');
            $table->unsignedBigInteger('id_menu');
            $table->unsignedBigInteger('id_pesanan');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->string('catatan', 255)->nullable();
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps();
            $table->foreign('id_menu')->references('id_menu')->on('menu')->onDelete('cascade');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
