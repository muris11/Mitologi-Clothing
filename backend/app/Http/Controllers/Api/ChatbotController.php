<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'history' => 'array',
        ]);

        $message = $validated['message'];
        $history = $validated['history'] ?? [];

        // Support multiple API keys (comma separated)
        $apiKeysString = config('services.groq.api_keys');
        $apiKeys = array_filter(explode(',', $apiKeysString));

        if (empty($apiKeys)) {
            return $this->successResponse([
                'reply' => 'Chatbot sedang dalam mode demo. Mohon konfigurasi GROQ_API_KEYS di backend .env untuk mengaktifkan AI.',
            ], 'Chatbot dalam mode demo');
        }

        $apiKey = trim($apiKeys[array_rand($apiKeys)]);

        try {
            // Fetch Product Data for Context (Limit to 30 to save tokens)
            $products = \App\Models\Product::with(['variants', 'categories'])
                ->where('available_for_sale', true)
                ->limit(30)
                ->get()
                ->map(function ($p) {
                    $price = $p->variants->min('price');
                    $stock = $p->variants->sum('stock');
                    $category = $p->categories->first()->name ?? 'Umum';

                    return "- {$p->title} (Rp ".number_format($price, 0, ',', '.').") [Stok: {$stock}] - Kategori: {$category}";
                })->implode("\n");

            // Build System Prompt with Product Context
            $systemPrompt = "Anda adalah Asisten AI Profesional untuk 'Mitologi Clothing', merek fashion premium "
             ."yang memadukan gaya modern dengan kekayaan seni budaya Nusantara.\n\n"
             ."PANDUAN UTAMA & GAYA BAHASA:\n"
             ."1. Gunakan Bahasa Indonesia ragam baku, profesional, sopan, namun tetap ramah.\n"
             .'2. DILARANG menggunakan marker formatting mentah seperti asteris ganda (**) untuk penegasan kata biasa. '
             ."Gunakan formatting hanya untuk struktur tulisan.\n"
             ."3. WAJIB menggunakan format List (Bulleted atau Numbered) saat menjabarkan lebih dari 2 poin.\n"
             ."4. Jawablah dengan tuntas namun ringkas (maksimal 3 paragraf utama).\n"
             ."5. Berikan rekomendasi spesifik dari DAFTAR PRODUK jika relevan.\n\n"
             ."INFORMASI TOKO:\n"
             ."- Waktu Operasional: Senin-Minggu, 09:00 - 17:00 WIB. Pengiriman setiap jam 15:00.\n"
             ."- Ekspedisi: JNE, J&T Express, SiCepat, AnterAja.\n"
             ."- Pembayaran: BCA, Mandiri, BNI, BRI, Permata, GoPay, dan QRIS.\n"
             ."- Panduan Ukuran (Panjang x Lebar):\n"
             ."  S: 68x48 cm | M: 70x50 cm | L: 72x52 cm | XL: 74x54 cm | XXL: 76x56 cm\n"
             ."- Kebijakan Retur: Maksimal 3 hari sejak pesanan diterima, wajib menyertakan video unboxing utuh. Retur hanya berlaku untuk salah ukuran/cacat produksi.\n\n"
             ."DAFTAR PRODUK TERSEDIA:\n"
             .$products."\n\n"
             .'Pertanyaan Pelanggan:';

            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
            ];

            foreach ($history as $msg) {
                if (isset($msg['role']) && isset($msg['content'])) {
                    $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
                }
            }

            $messages[] = ['role' => 'user', 'content' => $message];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => $messages,
                'stream' => false,
                'temperature' => 0.7,
                'max_tokens' => 1024,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak mengerti pertanyaan Anda.';

                return $this->successResponse(['reply' => $reply], 'Respons chatbot berhasil diambil');
            } else {
                Log::error('Groq API Error: '.$response->body());

                return $this->errorResponse('chatbot_service_error', 'Gagal menghubungi layanan chatbot', 502);
            }

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: '.$e->getMessage());

            return $this->errorResponse('internal_error', 'Terjadi kesalahan internal', 500);
        }
    }
}
