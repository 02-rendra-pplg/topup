<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Banner;
use App\Models\FlashSale;
use App\Models\Pembayaran;
use App\Services\GameAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TopupController extends Controller
{
    protected $gameAPIService;

    public function __construct(GameAPIService $gameAPIService)
    {
        $this->gameAPIService = $gameAPIService;
    }

    public function index()
    {
        $flashSales = FlashSale::where('status', 1)
            ->where('mulai', '<=', now())
            ->where('berakhir', '>=', now())
            ->get();

        $banners = Banner::latest()->get();
        $games   = Game::all();

        return view('pages.home', compact('flashSales', 'banners', 'games'));
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)->firstOrFail();

        $flashSales = FlashSale::where('status', 1)
            ->where('mulai', '<=', now())
            ->where('berakhir', '>=', now())
            ->get();

        $banners = Banner::orderByDesc('created_at')->get();

        // ambil list harga
        $parsedUrl = parse_url($game->url_api);
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        if (isset($queryParams['id'])) {
            $queryParams['id'] = urlencode($queryParams['id']);
        }

        $encodedUrl =
            ($parsedUrl['scheme'] ?? 'https') . '://' .
            ($parsedUrl['host'] ?? '') .
            ($parsedUrl['path'] ?? '') . '?' .
            http_build_query($queryParams);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $encodedUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        $response = curl_exec($curl);
        if ($response === false) {
            abort(500, 'Gagal menghubungi server harga: ' . curl_error($curl));
        }
        curl_close($curl);

        $list_game = json_decode($response, true);
        if (!$list_game || !isset($list_game['hrg'])) {
            abort(500, 'Gagal mengambil data harga dari server.');
        }

        $pembayarans = Pembayaran::where('status', 1)->get();

        return view('topup.form', [
            'game'         => $game,
            'slug'         => $slug,
            'namaGame'     => $game->name,
            'gameId'       => $game->id,
            'type'         => $game->tipe,
            'logo'         => $game->logo,
            'list'         => $list_game['hrg'],
            'flashSales'   => $flashSales,
            'banners'      => $banners,
            'diamondImage' => $game->logo_diamond,
            'pembayarans'  => $pembayarans,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id'       => 'required|exists:games,id',
            'user_id'       => 'required|string',
            'nominal'       => 'required|string',
            'harga'         => 'required|numeric',
            'whatsapp'      => 'required|string',
            'server_id'     => 'nullable|string',
            'pembayaran_id' => 'required|exists:pembayarans,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $game = Game::find($request->game_id);

        Log::info("Topup: {$game->name} | {$request->user_id} | {$request->server_id} | {$request->nominal} | {$request->harga} | {$request->whatsapp}");

        return back()->with('success', 'Top-up berhasil diproses!');
    }

    /**
     * CHECK NICKNAME - OPTIMIZED FOR WORKING API
     */
    public function checkNickname(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'game_id'   => 'required|exists:games,id',
            'user_id'   => 'required|string|min:3',
            'server_id' => 'nullable|string|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . $validator->errors()->first()
            ], 422);
        }

        try {
            $game = Game::findOrFail($request->game_id);

            Log::info("🎮 Check Nickname Request", [
                'game' => $game->name,
                'slug' => $game->slug,
                'user_id' => $request->user_id,
                'server_id' => $request->server_id,
                'ip' => $request->ip()
            ]);

            // Validasi khusus Mobile Legends
            if ($game->slug === 'mobile-legends' && empty($request->server_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Zone ID diperlukan untuk Mobile Legends'
                ], 422);
            }

            // Panggil service untuk mendapatkan nickname
            $result = $this->gameAPIService->getNickname(
                $game->slug,
                $request->user_id,
                $request->server_id
            );

            Log::info("📊 Nickname Check Result", [
                'success' => $result['success'],
                'has_nickname' => !empty($result['nickname']),
                'source' => $result['source'] ?? 'unknown',
                'message' => $result['message'] ?? 'N/A'
            ]);

            // Response untuk frontend
            if ($result['success'] && !empty($result['nickname'])) {
                return response()->json([
                    'success' => true,
                    'nickname' => $result['nickname'],
                    'source' => $result['source'] ?? 'official_api',
                    'message' => 'Nickname berhasil ditemukan!'
                ]);
            }

            // Jika gagal, beri pesan error yang jelas
            $errorMessage = $result['message'] ?? 'Nickname tidak ditemukan.';

            // Tambahkan saran berdasarkan game
            if ($game->slug === 'mobile-legends') {
                $errorMessage .= ' Pastikan User ID dan Zone ID benar. Cara cek: Buka game → Profile → Lihat di bawah nickname.';
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'debug' => app()->environment('local') ? $result : null
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("❌ Game not found: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Game tidak ditemukan.'
            ], 404);

        } catch (\Exception $e) {
            Log::error("💥 Check Nickname System Error", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan refresh halaman dan coba lagi.'
            ], 500);
        }
    }
}
