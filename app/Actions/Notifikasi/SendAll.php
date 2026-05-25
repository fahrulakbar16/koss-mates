<?php

namespace App\Actions\Notifikasi;

use App\Models\DeviceToken;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;

class SendAll
{
    /**
     * Send broadcast notification to all devices.
     *
     * @param string $title
     * @param string $body
     * @param array $data
     * @param Messaging $messaging
     * @return void
     */
    public function execute(string $title, string $body, array $data, Messaging $messaging): void
    {
        // Ambil semua token, pastikan unik dan tidak kosong
        $deviceTokens = DeviceToken::whereNotNull('device_token')
            ->distinct()
            ->pluck('device_token')
            ->all();

        if (empty($deviceTokens)) {
            return;
        }

        // Firebase limit 500 token per multicast
        $chunks = array_chunk($deviceTokens, 500);

        foreach ($chunks as $chunk) {
            try {
                $message = CloudMessage::fromArray([
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                ]);

                // Send Multicast
                $report = $messaging->sendMulticast($message, $chunk);

                // Hapus token yang invalid/unknown
                if ($report->hasFailures()) {
                    $invalidTokens = array_merge($report->unknownTokens(), $report->invalidTokens());

                    if (!empty($invalidTokens)) {
                        DeviceToken::whereIn('device_token', $invalidTokens)->delete();
                    }
                }
            } catch (\Throwable $e) {
                Log::error('FCM Broadcast Error: ' . $e->getMessage());
            }
        }

        // Log broadcast notification to database (System-wide)
        // Since it's a broadcast, we log it with user_id = null
        Notification::create([
            'user_id' => null,
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'type' => 'info', // Default type
            'is_read' => false,
        ]);
    }
}
