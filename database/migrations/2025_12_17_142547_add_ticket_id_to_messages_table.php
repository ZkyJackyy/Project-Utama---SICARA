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
        Schema::table('messages', function (Blueprint $table) {
            // Hubungkan pesan ke tiket tertentu
        $table->foreignId('ticket_id')->after('id')->constrained()->onDelete('cascade');
        // 'to_id' jadi nullable karena pesan di dalam tiket otomatis tertuju ke admin/pemilik tiket
        $table->foreignId('to_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            //
            $table->dropForeign(['ticket_id']);
            $table->dropColumn('ticket_id');
            $table->foreignId('to_id')->change();
            

        });
    }
};
