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
        Schema::create('transaksi', function (Blueprint $table) {
    $table->bigIncrements('id'); // gunakan bigIncrements
    $table->unsignedBigInteger('user_id');
    $table->string('metode_pembayaran');
    $table->string('bukti_pembayaran');
    $table->decimal('total', 12, 2);
    $table->string('status')->default('Menunggu Konfirmasi');
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
