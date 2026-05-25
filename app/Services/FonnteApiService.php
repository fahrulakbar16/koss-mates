<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteApiService
{
    protected string $apiToken;
    protected string $baseUrl;
    protected string $deviceToken;

    public function __construct()
    {
        $this->apiToken = config('services.fonnte.api_token', env('FONNTE_API_TOKEN', ''));
        $this->deviceToken = "dD3SzN3MvN23bts1EbPv";
        $this->baseUrl = config('services.fonnte.base_url', 'https://api.fonnte.com');
    }

    /**
     * Get device status
     */
    public function getDeviceStatus(): array
    {
        try {
            // Fonnte API requires POST method for device status
            $response = Http::withHeaders([
                'Authorization' => $this->deviceToken,
            ])->post("{$this->baseUrl}/device");
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }


            Log::error('Fonnte API: Failed to get device status', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengambil status perangkat',
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte API Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghubungi API',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get QR code for device connection
     */
    public function getQRCode(?string $deviceToken = null): array
    {
        try {
            // Use provided token or fall back to stored device token
            $token = $deviceToken ?? $this->deviceToken;

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post("{$this->baseUrl}/qr", [
                'type' => 'qr',
            ]);

            if ($response->successful() || $response->status() === 200) {
                $data = $response->json();

                // If device is already connected
                if (isset($data['status']) && !$data['status'] && isset($data['reason'])) {
                    return [
                        'success' => false,
                        'message' => $data['reason'],
                        'already_connected' => str_contains(strtolower($data['reason']), 'already connect'),
                    ];
                }

                // If QR code is available
                if (isset($data['url']) && strlen($data['url']) > 0) {
                    return [
                        'success' => true,
                        'qr_code' => 'data:image/png;base64,' . $data['url'],
                        'data' => $data,
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data['reason'] ?? 'Gagal mendapatkan QR code',
                ];
            }

            Log::error('Fonnte API: Failed to get QR code', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mendapatkan QR code',
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte API Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendapatkan QR code',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get all devices for the account
     */
    public function getDevices(): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiToken,
            ])->post("{$this->baseUrl}/get-devices");

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status']) {
                    return [
                        'success' => true,
                        'data' => $data,
                        'devices' => $data['data'] ?? [],
                        'connected' => $data['connected'] ?? 0,
                        'total_devices' => $data['devices'] ?? 0,
                    ];
                }

                return [
                    'success' => false,
                    'message' => $data['reason'] ?? 'Gagal mendapatkan daftar perangkat',
                ];
            }

            Log::error('Fonnte API: Failed to get devices list', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mendapatkan daftar perangkat',
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte API Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendapatkan daftar perangkat',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Connect device
     */
    public function connectDevice(?string $deviceToken = null): array
    {
        try {
            // Use provided token or fall back to stored API token
            $token = $deviceToken ?? $this->apiToken;

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post("{$this->baseUrl}/connect");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghubungkan perangkat',
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte API Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghubungkan perangkat',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Disconnect device
     */
    public function disconnectDevice(?string $deviceToken = null): array
    {
        try {
            // Use provided token or fall back to stored device token
            $token = $deviceToken ?? $this->deviceToken;

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post("{$this->baseUrl}/disconnect");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal memutuskan perangkat',
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte API Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memutuskan perangkat',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send message
     */
    public function sendMessage(string $target, string $message, array $options = []): array
    {
        try {
            $data = array_merge([
                'target' => $target,
                'message' => $message,
            ], $options);

            $response = Http::withHeaders([
                'Authorization' => $this->deviceToken,
            ])->post("{$this->baseUrl}/send", $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan',
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte API Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim pesan',
                'error' => $e->getMessage(),
            ];
        }
    }
}
