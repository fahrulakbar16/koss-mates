<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\LogActivity;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    /**
     * Send a push notification to a specific OneSignal subscription id.
     *
     * You can optionally pass two callables to persist data after the request:
     *  - $persistNotification: function(array $data): void
     *  - $persistLog: function(array $data): void
     * The service will pass a standardized payload to these handlers so the caller can
     * decide how to store the data (e.g., using Eloquent models Notification, LogActivity, etc.).
     *
     * @param string        $headingMsg
     * @param string        $contentMsg
     * @param string        $oneSignalId
     * @param callable|null $persistNotification  optional function(array $data): void
     * @param callable|null $persistLog           optional function(array $data): void
     * @return mixed string|array
     */
    public function sendToSpecificUser(
        string $headingMsg,
        string $contentMsg,
        string $oneSignalId,
        ?callable $persistNotification = null,
        ?callable $persistLog = null
    ) {
        $oneSignalAppId = (string) env('ONESIGNAL_APP_ID');
        $oneSignalRestApiKey = (string) env('ONESIGNAL_REST_API_KEY');

        // Validate OneSignal credentials
        if (empty($oneSignalAppId) || empty($oneSignalRestApiKey)) {
            Log::error('OneSignal credentials not configured', [
                'app_id_set' => !empty($oneSignalAppId),
                'api_key_set' => !empty($oneSignalRestApiKey),
            ]);
            return [
                'error' => 'OneSignal credentials not configured. Please set ONESIGNAL_APP_ID and ONESIGNAL_REST_API_KEY in .env file'
            ];
        }

        try {
            // Log info before sending
            Log::info('OneSignal API request initiated', [
                'heading' => $headingMsg,
                'subscription_id' => $oneSignalId,
            ]);

            $fields = [
                'app_id' => $oneSignalAppId,
                'target_channel' => 'push',
                'contents' => ['en' => $contentMsg],
                'headings' => ['en' => $headingMsg],
                'include_subscription_ids' => [$oneSignalId],
            ];

            $payload = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.onesignal.com/notifications?c=push');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic ' . $oneSignalRestApiKey,
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false) {
                $err = curl_error($ch);
                curl_close($ch);

                // Log curl error
                Log::error('OneSignal API curl error', [
                    'heading' => $headingMsg,
                    'subscription_id' => $oneSignalId,
                    'error' => $err,
                ]);

                return ['error' => $err];
            }
            curl_close($ch);

            $responseData = json_decode($response, true);

            // Check for API errors (including authentication errors)
            if (isset($responseData['errors']) || $httpCode >= 400) {
                Log::error('OneSignal API error response', [
                    'heading' => $headingMsg,
                    'subscription_id' => $oneSignalId,
                    'http_code' => $httpCode,
                    'errors' => $responseData['errors'] ?? [],
                    'response' => $responseData,
                ]);

                return [
                    'error' => $responseData['errors'][0] ?? 'Unknown API error',
                    'errors' => $responseData['errors'] ?? [],
                    'http_code' => $httpCode,
                ];
            }

            // Log success
            Log::info('OneSignal API request successful', [
                'heading' => $headingMsg,
                'subscription_id' => $oneSignalId,
                'response_data' => $responseData,
                'response_id' => $responseData['id'] ?? null,
                'recipients' => $responseData['recipients'] ?? 0,
            ]);

            // // Insert ke table notifications
            // if (is_callable($persistNotification)) {
            //     $persistNotification([
            //         'one_signal_response' => $response,
            //         'data' => $response,
            //     ]);
            // }

            // // Insert ke table log activity
            // if (is_callable($persistLog)) {
            //     $persistLog([
            //         'data' => $response,
            //     ]);
            // }
            return $response;
        } catch (\Throwable $e) {
            // Log exception
            Log::error('OneSignal API exception', [
                'heading' => $headingMsg,
                'subscription_id' => $oneSignalId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);
            return ['error' => 'Could not send notification'];
        }
    }

    public function sendAndStore(
        string $headingMsg,
        string $contentMsg,
        string $oneSignalId,
        ?int $userId = null,
        ?string $modelType = null,
        ?int $modelId = null,
        int $status = 0,
        int $isSuccess = 1
    ) {
        return $this->sendToSpecificUser(
            $headingMsg,
            $contentMsg,
            $oneSignalId,
            function (array $data) use ($userId, $modelType, $modelId, $status, $isSuccess, $headingMsg, $contentMsg, $oneSignalId) {
                Notification::create([
                    'user_id' => $userId,
                    'title' => $headingMsg,
                    'heading' => $headingMsg,
                    'content' => $contentMsg,
                    'model_type' => $modelType,
                    'model_id' => $modelId,
                    'pesan' => $contentMsg,
                    'one_signal_id' => $oneSignalId,
                    'one_signal_response' => $data['one_signal_response'] ?? null,
                    'status' => $status,
                ]);
            },
            function (array $data) use ($userId, $headingMsg, $contentMsg, $oneSignalId, $modelType, $modelId) {
                $responseData = json_decode($data['data'] ?? '{}', true);

                LogActivity::create([
                    'user_id' => $userId,
                    'activity' => 'onesignal_api_call',
                    'description' => "OneSignal push notification '{$headingMsg}' dikirim via API",
                    'data' => json_encode([
                        'type' => 'onesignal_api',
                        'heading' => $headingMsg,
                        'content' => $contentMsg,
                        'subscription_id' => $oneSignalId,
                        'model_type' => $modelType,
                        'model_id' => $modelId,
                        'onesignal_response' => [
                            'id' => $responseData['id'] ?? null,
                            'recipients' => $responseData['recipients'] ?? 0,
                            'external_id' => $responseData['external_id'] ?? null,
                        ],
                        'raw_response' => $data['data'] ?? null,
                    ]),
                ]);
            }
        );
    }
}
