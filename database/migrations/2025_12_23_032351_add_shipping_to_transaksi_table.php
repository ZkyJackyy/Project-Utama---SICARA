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
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('shipping_method')->nullable()->after('status');
        $table->integer('shipping_cost')->default(0)->after('shipping_method');
        $table->text('shipping_address')->nullable()->after('shipping_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('shipping_method');
            $table->dropColumn('shipping_cost');
            $table->dropColumn('shipping_address');
        });
    }
};
