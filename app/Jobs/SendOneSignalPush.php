<?php

namespace App\Jobs;

use App\Models\LogActivity;
use App\Services\OneSignalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOneSignalPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public  $tries = 3;
    public  $timeout = 20; // seconds

    private $heading;
    private $content;
    private $externalId;
    private $userId;
    private $modelType;
    private $modelId;
    private $status;
    private $isSuccess;

    public function __construct($param_heading, $param_content, $param_externalId, $param_userId = null, $param_modelType = null, $param_modelId = null, $param_status = 0, $param_isSuccess = 1)
    {
        $this->heading = $param_heading;
        $this->content = $param_content;
        $this->externalId = $param_externalId;
        $this->userId = $param_userId;
        $this->modelType = $param_modelType;
        $this->modelId = $param_modelId;
        $this->status = $param_status;
        $this->isSuccess = $param_isSuccess;
        $this->onQueue('notifications');
    }

    public function backoff(): array
    {
        return [5, 30, 120];
    }

    public function tags(): array
    {
        return ['onesignal', 'push'];
    }

    public function handle(OneSignalService $oneSignal): void
    {
        try {
            // Log info before sending
            Log::info('OneSignal push notification job started', [
                'heading' => $this->heading,
                'external_id' => $this->externalId,
                'user_id' => $this->userId,
                'model_type' => $this->modelType,
                'model_id' => $this->modelId,
                'attempt' => $this->attempts(),
            ]);

            $result = $oneSignal->sendAndStore(
                $this->heading,
                $this->content,
                $this->externalId,
                $this->userId,
                $this->modelType,
                $this->modelId,
                $this->status,
                $this->isSuccess,
            );

            // Log success
            Log::info('OneSignal push notification sent successfully', [
                'heading' => $this->heading,
                'external_id' => $this->externalId,
                'user_id' => $this->userId,
                'result' => $result,
            ]);
        } catch (\Throwable $e) {
            // Log error
            Log::error('OneSignal push notification failed', [
                'heading' => $this->heading,
                'external_id' => $this->externalId,
                'user_id' => $this->userId,
                'attempt' => $this->attempts(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }
}
