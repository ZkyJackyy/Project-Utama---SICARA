<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    protected $apiKey;
    protected $origin;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->origin = env('RAJAONGKIR_ORIGIN'); // Pastikan ini ID Kota, misal: 153
    }

    // 1. Ambil Provinsi (Endpoint Komerce)
    public function getProvinces()
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat provinsi Komerce',
                    'error'   => $response->body()
                ], $response->status());
            }

            // Komerce biasanya mengembalikan data di dalam key 'data'
            $data = $response->json()['data'] ?? [];

            return response()->json([
                'success' => true,
                'data'    => $data
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // 2. Ambil Kota (Endpoint Komerce)
    public function getCities($provinceId)
    {
        try {
            // PERBAIKAN PENTING:
            // Endpoint Komerce mengharuskan ID provinsi masuk ke dalam URL path
            // Format: .../destination/city/{province_id}
            
            $url = "https://rajaongkir.komerce.id/api/v1/destination/city/{$provinceId}";

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'key' => $this->apiKey
                ])->get($url);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat kota Komerce',
                    'error'   => $response->body()
                ], $response->status());
            }

            $data = $response->json()['data'] ?? [];

            return response()->json([
                'success' => true,
                'data'    => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Koneksi Gagal',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

// 3. Cek Ongkir (MENGGUNAKAN ENDPOINT KOMERCE)
    public function checkOngkir(Request $request)
    {
        if (!$request->destination || !$request->weight || !$request->courier) {
            return response()->json(['success' => false, 'message' => 'Data tidak lengkap'], 400);
        }

        try {
            $url = 'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost';

            // PERBAIKAN: originType dan destinationType WAJIB ADA
            // agar API tahu ID yang dikirim adalah ID Kota, bukan Kecamatan.
            $response = Http::asForm() 
                ->withHeaders([
                    'key' => $this->apiKey
                ])->post($url, [
                    'origin'          => $this->origin,
                    'originType'      => 'city',       // <--- INI WAJIB DIAKTIFKAN
                    'destination'     => $request->destination,
                    'destinationType' => 'city',       // <--- INI WAJIB DIAKTIFKAN
                    'weight'          => $request->weight,
                    'courier'         => $request->courier,
                ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komerce Error: ' . $response->body()
                ], $response->status());
            }

            $body = $response->json();
            $data = $body['data'] ?? [];

            return response()->json([
                'success' => true,
                'data'    => $data
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}