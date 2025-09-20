<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('jenis')->insert([
            [
            'jenis_produk' => 'kue bolu',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'jenis_produk' => 'kue kering',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'jenis_produk' => 'kue ulang tahun',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'jenis_produk' => 'brownies',
            'created_at' => now(),
            'updated_at' => now(),
            ],
    ]);
    }
}
