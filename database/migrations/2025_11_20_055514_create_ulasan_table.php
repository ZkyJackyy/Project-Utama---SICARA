<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUlasanTable extends Migration
{
    public function up()
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pesanan_id'); 
            $table->unsignedBigInteger('user_id');
            $table->integer('rating'); 
            $table->text('ulasan')->nullable();
            $table->timestamps();

            $table->foreign('pesanan_id')->references('id')->on('transaksi')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ulasan');
    }
}
