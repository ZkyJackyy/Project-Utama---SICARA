@extends('layouts.navbar_admin')

@section('title', 'Admin Dashboard')

@section('content')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Penjualan</p>
                            <p class="text-3xl font-bold">Rp 12.5M</p>
                        </div>
                        <i class="fas fa-dollar-sign text-4xl text-green-500"></i>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Pesanan Baru</p>
                            <p class="text-3xl font-bold">150</p>
                        </div>
                        <i class="fas fa-shopping-cart text-4xl text-blue-500"></i>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Pelanggan Baru</p>
                            <p class="text-3xl font-bold">85</p>
                        </div>
                        <i class="fas fa-users text-4xl text-yellow-500"></i>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Produk Terjual</p>
                            <p class="text-3xl font-bold">1,200</p>
                        </div>
                        <i class="fas fa-box-open text-4xl text-red-500"></i>
                    </div>
                </div>

                <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-4">Pesanan Terbaru</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="text-left font-bold border-b">
                                    <th class="px-6 py-3">ID Pesanan</th>
                                    <th class="px-6 py-3">Pelanggan</th>
                                    <th class="px-6 py-3">Total</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">#12345</td>
                                    <td class="px-6 py-4">Budi Santoso</td>
                                    <td class="px-6 py-4">Rp 250.000</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Selesai</span></td>
                                    <td class="px-6 py-4">18 Sep 2025</td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">#12346</td>
                                    <td class="px-6 py-4">Citra Lestari</td>
                                    <td class="px-6 py-4">Rp 150.000</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">Diproses</span></td>
                                    <td class="px-6 py-4">18 Sep 2025</td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">#12347</td>
                                    <td class="px-6 py-4">Ahmad Yani</td>
                                    <td class="px-6 py-4">Rp 500.000</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">Dibatalkan</span></td>
                                    <td class="px-6 py-4">17 Sep 2025</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endsection