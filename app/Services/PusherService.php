<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Pusher\Pusher;
use Pusher\PusherException;

class PusherService
{
    private Pusher $pusher;

    public function __construct()
    {
        try {
            $cluster = config('services.pusher.cluster');

            // Dynamically construct host based on cluster
            $host = config('services.pusher.host');
            if (!$host && $cluster) {
                $host = "api-{$cluster}.pusher.com";
            }

            $this->pusher = new Pusher(
                config('services.pusher.key'),
                config('services.pusher.secret'),
                config('services.pusher.app_id'),
                [
                    'cluster' => $cluster,
                    'scheme' => config('services.pusher.scheme', 'https'),
                    'host' => $host,
                    'port' => config('services.pusher.port', 443),
                    'encrypted' => config('services.pusher.encrypted', true),
                ]
            );
        } catch (PusherException $e) {
            Log::error('Failed to initialize Pusher', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * @param mixed $employee
     * @param string $type
     * @param string $status
     * @param string $dateTime
     * @param string $message
     * @return void
     */
    public function pusherAttendanceResponse($employee, $type, $status, $dateTime, $message): void
    {
        try {
            $channel = 'attendance-channel';
            $event = 'attendance-barcode-event';

            $data = [
                'message' => $message,
                'type' => $type,
                'data' => [
                    'employee' => $employee,
                    'type' => $type,
                    'status' => $status,
                    'dateTime' => $dateTime,
                ]
            ];

            $this->pusher->trigger($channel, $event, $data);

            Log::info('Pusher attendance event broadcasted', [
                'channel' => $channel,
                'event' => $event,
                'data' => $data,
            ]);
        } catch (PusherException $e) {
            Log::error('Failed to broadcast Pusher attendance event', [
                'error' => $e->getMessage(),
                'employee' => $employee,
                'type' => $type,
            ]);
            // Silent fail - should not block attendance API
        }
    }


    /**
     * Broadcast attendance event to all clients
     */
    public function broadcastAttendance($employeeName, $attendanceType, $status, $time): void
    {
        try {
            $channel = 'attendance-channel';
            $event = 'attendance-barcode-event';

            $data = [
                'employee_name' => $employeeName,
                'attendance_type' => $attendanceType, // 'masuk' or 'keluar'
                'status' => $status,
                'time' => $time,
                'timestamp' => now('Asia/Makassar')->toIso8601String(),
            ];

            $this->pusher->trigger($channel, $event, $data);

            Log::info('Pusher attendance event broadcasted', [
                'channel' => $channel,
                'event' => $event,
                'data' => $data,
            ]);
        } catch (PusherException $e) {
            Log::error('Failed to broadcast Pusher attendance event', [
                'error' => $e->getMessage(),
                'employee_name' => $employeeName,
                'attendance_type' => $attendanceType,
            ]);
            // Silent fail - should not block attendance API
        }
    }

    /**
     * Broadcast attendance error event
     */
    public function broadcastAttendanceError($message, $details = []): void
    {
        try {
            $channel = 'attendance-channel';
            $event = 'attendance-error-event';

            $data = [
                'message' => $message,
                'details' => $details,
                'timestamp' => now('Asia/Makassar')->toIso8601String(),
            ];

            $this->pusher->trigger($channel, $event, $data);

            Log::info('Pusher attendance error event broadcasted', [
                'channel' => $channel,
                'event' => $event,
                'data' => $data,
            ]);
        } catch (PusherException $e) {
            Log::error('Failed to broadcast Pusher error event', [
                'error' => $e->getMessage(),
                'message' => $message,
            ]);
        }
    }
}
