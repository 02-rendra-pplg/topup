<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GameAPIService
{
    /**
     * Get nickname from game API - FIX SLUG MATCHING
     */
    public function getNickname($gameSlug, $userId, $serverId = null)
    {
        // Normalize slug untuk handle berbagai variasi
        $normalizedSlug = $this->normalizeGameSlug($gameSlug);

        Log::info("🎯 Normalized Game Slug", [
            'original' => $gameSlug,
            'normalized' => $normalizedSlug
        ]);

        // Handle berbagai kemungkinan slug untuk Mobile Legends
        if (in_array($normalizedSlug, ['mobile-legends', 'mobile-legend', 'ml', 'mobilelegends'])) {
            return $this->getMobileLegendsNickname($userId, $serverId);
        }

        return [
            'success' => false,
            'message' => 'Fitur cek nickname untuk game ini sedang dalam pengembangan.'
        ];
    }

    /**
     * Normalize game slug untuk handle berbagai variasi
     */
    private function normalizeGameSlug($slug)
    {
        return strtolower(trim($slug));
    }

    /**
     * MOBILE LEGENDS NICKNAME CHECK - USING WORKING API
     */
    private function getMobileLegendsNickname($userId, $zoneId)
    {
        $cacheKey = "ml_nickname_{$userId}_{$zoneId}";

        // Check cache first (30 minutes)
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        Log::info("🔍 Mobile Legends Nickname Check", [
            'user_id' => $userId,
            'zone_id' => $zoneId
        ]);

        // METHOD 1: API ISAN.EU.ORG (YANG SUDAH TERBUKTI WORK)
        $result = $this->tryIsanEuOrgAPI($userId, $zoneId);
        if ($result['success']) {
            Cache::put($cacheKey, $result, 1800); // 30 minutes cache
            return $result;
        }

        // METHOD 2: Backup API
        $result = $this->tryBackupAPI($userId, $zoneId);
        if ($result['success']) {
            Cache::put($cacheKey, $result, 1800);
            return $result;
        }

        // ALL METHODS FAILED
        return [
            'success' => false,
            'message' => 'Nickname tidak ditemukan. Pastikan User ID dan Zone ID benar.'
        ];
    }

    /**
     * METHOD 1: API ISAN.EU.ORG - SUDAH TERBUKTI WORK DI POSTMAN
     */
    private function tryIsanEuOrgAPI($userId, $zoneId)
    {
        try {
            $url = "https://api.isan.eu.org/nickname/ml?id={$userId}&server={$zoneId}";

            Log::info("🌐 Trying ISAN.EU.ORG API", ['url' => $url]);

            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])
                ->get($url);

            Log::info("📦 ISAN API Response Status", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::info("📊 ISAN API Full Response", $data);

                // Response format dari API yang work:
                // {
                //     "success": true,
                //     "game": "Mobile Legends: Bang Bang",
                //     "id": 151203645,
                //     "server": 2769,
                //     "name": "VarhanViper."
                // }

                if (isset($data['success']) && $data['success'] === true && isset($data['name'])) {
                    Log::info("✅ ISAN API Success", [
                        'nickname' => $data['name'],
                        'user_id' => $data['id'],
                        'server' => $data['server']
                    ]);

                    return [
                        'success' => true,
                        'nickname' => $data['name'],
                        'source' => 'isan_eu_org',
                        'raw_data' => $data // untuk debugging
                    ];
                }
            }

            Log::warning("❌ ISAN API failed", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error("🚨 ISAN API Error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }

        return ['success' => false];
    }

    /**
     * METHOD 2: Backup API (DazelPro)
     */
    private function tryBackupAPI($userId, $zoneId)
    {
        try {
            $url = "https://api.dazelpro.com/mobile-legends/player/{$zoneId}/{$userId}";

            Log::info("🌐 Trying Backup API", ['url' => $url]);

            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'application/json'
                ])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                $nickname = $data['player']['username']
                         ?? $data['player']['name']
                         ?? $data['username']
                         ?? null;

                if ($nickname) {
                    Log::info("✅ Backup API Success", ['nickname' => $nickname]);
                    return [
                        'success' => true,
                        'nickname' => $nickname,
                        'source' => 'backup_api'
                    ];
                }
            }

        } catch (\Exception $e) {
            Log::error("Backup API Error: " . $e->getMessage());
        }

        return ['success' => false];
    }
}
