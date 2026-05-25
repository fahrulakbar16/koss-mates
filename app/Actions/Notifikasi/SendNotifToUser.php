<?php

namespace App\Actions\Notifikasi;

use App\Models\DeviceToken;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;

class SendNotifToUser
{
    /**
     * Send notification to a specific user's devices.
     *
     * @param User $user
     * @param string $title
     * @param string $body
     * @param array $data
     * @param Messaging $messaging
     * @return void
     */
    public function execute(User $user, string $title, string $body, array $data, Messaging $messaging): void
    {
        // Ambil token user, pastikan unik dan tidak kosong
        $tokens = $user->deviceTokens()
            ->whereNotNull('device_token')
            ->distinct()
            ->pluck('device_token')
            ->all();

        if (empty($tokens)) {
            return;
        }

        try {
            $message = CloudMessage::fromArray([
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $data,
            ]);

            // Send Multicast (send to multiple tokens)
            $report = $messaging->sendMulticast($message, $tokens);

            // Handle invalid tokens
            if ($report->hasFailures()) {
                $invalidTokens = array_merge($report->unknownTokens(), $report->invalidTokens());

                if (!empty($invalidTokens)) {
                    // Hapus token yang invalid milik user ini
                    DeviceToken::whereIn('device_token', $invalidTokens)->delete();
                }
            }

            // Log notification to database
            Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'type' => 'info',
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::error('FCM SendNotifToUser Error: ' . $e->getMessage());
        }
    }
}
