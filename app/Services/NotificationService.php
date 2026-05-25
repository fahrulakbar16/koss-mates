<?php

namespace App\Services;

use App\Jobs\SendOneSignalPush;
use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to admin/SPV when receiving submissions
     */
    public function notifyAdminsOnSubmission($submissionType, $submissionId, $submittedBy, $details = [])
    {
        try {
            // Get admin and supervisor users
            $adminUsers = User::role(['Admin', 'Superadmin'])
                ->whereNotNull('external_id')
                ->get();

            // Log the admin users found
            Log::info('Admin users retrieved for notification', [
                'submission_type' => $submissionType,
                'admin_count' => $adminUsers->count(),
                'admin_ids' => $adminUsers->pluck('id')->toArray(),
                'admin_external_ids' => $adminUsers->pluck('external_id')->toArray(),
            ]);

            $title = $this->getSubmissionTitle($submissionType);
            $message = $this->getSubmissionMessage($submissionType, $submittedBy, $details);

            $recipientIds = [];
            foreach ($adminUsers as $user) {
                SendOneSignalPush::dispatch(
                    $title,
                    $message,
                    $user->external_id,
                    $submittedBy->id ?? null,
                    $this->getModelClass($submissionType),
                    $submissionId,
                    0,
                    1
                );
                $recipientIds[] = $user->external_id;
            }

            // Log info
            Log::info('Submission notification sent', [
                'submission_type' => $submissionType,
                'submission_id' => $submissionId,
                'submitted_by' => $submittedBy->name ?? 'Unknown',
                'submitted_by_id' => $submittedBy->id ?? null,
                'recipients_count' => count($recipientIds),
                'recipients' => $recipientIds,
                'title' => $title,
                'message' => $message,
            ]);

            // // Log activity
            // LogActivity::create([
            //     'users_id' => $submittedBy->id ?? null,
            //     'activity' => 'notification_sent',
            //     'description' => "Notifikasi pengajuan {$submissionType} dikirim ke " . count($recipientIds) . " admin/supervisor",
            //     'data' => json_encode([
            //         'type' => 'submission_notification',
            //         'submission_type' => $submissionType,
            //         'submission_id' => $submissionId,
            //         'title' => $title,
            //         'message' => $message,
            //         'recipients' => $recipientIds,
            //         'details' => $details,
            //     ]),
            // ]);
        } catch (\Throwable $e) {
            // Log error
            Log::error('Failed to send submission notification', [
                'submission_type' => $submissionType,
                'submission_id' => $submissionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Silent fail - notification should not block API
            report($e);
        }
    }

    /**
     * Send notification to staff when receiving feedback on submissions
     */
    public function notifyStaffOnFeedback($submissionType, $submissionId, $staffUserId, $feedbackStatus, $details = [])
    {
        try {
            $staff = User::find($staffUserId);
            if (!$staff || !$staff->subscription_id) {
                return;
            }

            $title = $this->getFeedbackTitle($submissionType, $feedbackStatus);
            $message = $this->getFeedbackMessage($submissionType, $feedbackStatus, $details);

            SendOneSignalPush::dispatch(
                $title,
                $message,
                $staff->subscription_id,
                $staffUserId,
                $this->getModelClass($submissionType),
                $submissionId,
                0,
                1
            );

            // Log info
            Log::info('Feedback notification sent', [
                'submission_type' => $submissionType,
                'submission_id' => $submissionId,
                'feedback_status' => $feedbackStatus,
                'recipient' => $staff->name,
                'recipient_id' => $staffUserId,
                'title' => $title,
                'message' => $message,
                'approved_by' => Auth::user()->name ?? 'System',
            ]);

            // Log activity
            LogActivity::create([
                'users_id' => Auth::id() ?? null,
                'activity' => 'notification_sent',
                'description' => "Notifikasi feedback {$feedbackStatus} untuk pengajuan {$submissionType} dikirim ke {$staff->name}",
                'data' => json_encode([
                    'type' => 'feedback_notification',
                    'submission_type' => $submissionType,
                    'submission_id' => $submissionId,
                    'feedback_status' => $feedbackStatus,
                    'title' => $title,
                    'message' => $message,
                    'recipient_id' => $staffUserId,
                    'recipient_name' => $staff->name,
                    'details' => $details,
                ]),
            ]);
        } catch (\Throwable $e) {
            // Log error
            Log::error('Failed to send feedback notification', [
                'submission_type' => $submissionType,
                'submission_id' => $submissionId,
                'staff_user_id' => $staffUserId,
                'feedback_status' => $feedbackStatus,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Silent fail - notification should not block API
            report($e);
        }
    }

    /**
     * Send notification to all staff during check-in/check-out
     */
    public function notifyAllStaffOnAttendance($employeeName, $attendanceType, $time)
    {
        try {
            // Get all users with subscription IDs
            $allUsers = User::whereNotNull('subscription_id')->get();

            $title = $attendanceType === 'masuk' ? 'Absensi Masuk' : 'Absensi Keluar';
            $message = "{$employeeName} melakukan {$attendanceType} pada {$time}";

            $recipientIds = [];
            foreach ($allUsers as $user) {
                SendOneSignalPush::dispatch(
                    $title,
                    $message,
                    $user->subscription_id,
                    null,
                    'App\\Models\\Attendance',
                    null,
                    0,
                    1
                );
                $recipientIds[] = $user->id;
            }

            // Log info
            Log::info('Attendance notification sent', [
                'attendance_type' => $attendanceType,
                'employee_name' => $employeeName,
                'time' => $time,
                'recipients_count' => count($recipientIds),
                'title' => $title,
                'message' => $message,
            ]);

            // Log activity
            LogActivity::create([
                'users_id' => null,
                'activity' => 'notification_sent',
                'description' => "Notifikasi absensi {$attendanceType} dari {$employeeName} dikirim ke " . count($recipientIds) . " pengguna",
                'data' => json_encode([
                    'type' => 'attendance_notification',
                    'attendance_type' => $attendanceType,
                    'employee_name' => $employeeName,
                    'time' => $time,
                    'title' => $title,
                    'message' => $message,
                    'recipients' => $recipientIds,
                ]),
            ]);
        } catch (\Throwable $e) {
            // Log error
            Log::error('Failed to send attendance notification', [
                'employee_name' => $employeeName,
                'attendance_type' => $attendanceType,
                'time' => $time,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Silent fail - notification should not block API
            report($e);
        }
    }

    /**
     * Send notification to SPV when staff sends checklist
     */
    public function notifySupervisorsOnChecklist($checklistName, $submittedBy, $departmentId, $inspectionId)
    {
        try {
            // Get supervisors in the same department
            $supervisors = User::role('Supervisor')
                ->whereHas('employee', function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })
                ->whereNotNull('subscription_id')
                ->get();

            $title = 'Checklist Diselesaikan';
            $message = "{$submittedBy} menyelesaikan checklist {$checklistName}";

            $recipientIds = [];
            foreach ($supervisors as $supervisor) {
                SendOneSignalPush::dispatch(
                    $title,
                    $message,
                    $supervisor->subscription_id,
                    $submittedBy->id ?? null,
                    'App\\Models\\Inspection',
                    $inspectionId,
                    0,
                    1
                );
                $recipientIds[] = $supervisor->id;
            }

            // Log info
            Log::info('Checklist notification sent', [
                'checklist_name' => $checklistName,
                'inspection_id' => $inspectionId,
                'department_id' => $departmentId,
                'submitted_by' => $submittedBy,
                'recipients_count' => count($recipientIds),
                'recipients' => $recipientIds,
                'title' => $title,
                'message' => $message,
            ]);
        } catch (\Throwable $e) {
            // Log error
            Log::error('Failed to send checklist notification', [
                'checklist_name' => $checklistName,
                'inspection_id' => $inspectionId,
                'department_id' => $departmentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Silent fail - notification should not block API
            report($e);
        }
    }

    /**
     * Get submission title based on type
     */
    private function getSubmissionTitle($submissionType)
    {
        $titles = [
            'sick' => 'Pengajuan Sakit',
            'leave' => 'Pengajuan Cuti',
            'overtime' => 'Pengajuan Lembur',
            'procurement' => 'Pengajuan Pengadaan',
            'loan' => 'Pengajuan Piutang',
            'usage' => 'Pengajuan Penggunaan',
            'daily_report' => 'Laporan Harian',
            'employee' => 'Pengajuan Karyawan',
        ];

        return $titles[$submissionType] ?? 'Pengajuan Baru';
    }

    /**
     * Get submission message based on type
     */
    private function getSubmissionMessage($submissionType, $submittedBy, $details = [])
    {
        $employeeName = $submittedBy->name ?? 'Karyawan';

        switch ($submissionType) {
            case 'sick':
                return "{$employeeName} mengajukan sakit";
            case 'leave':
                $startDate = $details['start_date'] ?? '';
                $endDate = $details['end_date'] ?? '';
                return "{$employeeName} mengajukan cuti {$startDate} - {$endDate}";
            case 'overtime':
                $date = $details['date'] ?? '';
                return "{$employeeName} mengajukan lembur pada {$date}";
            case 'procurement':
                return "{$employeeName} mengajukan permintaan pengadaan";
            case 'loan':
                $amount = $details['amount'] ?? '';
                return "{$employeeName} mengajukan piutang sebesar {$amount}";
            case 'usage':
                return "{$employeeName} mengajukan penggunaan barang";
            case 'daily_report':
                return "{$employeeName} mengirim laporan harian";
            case 'employee':
                return "{$employeeName} mengajukan data karyawan";
            default:
                return "{$employeeName} mengirim pengajuan baru";
        }
    }

    /**
     * Get feedback title based on type and status
     */
    private function getFeedbackTitle($submissionType, $feedbackStatus)
    {
        $statusText = $feedbackStatus === 'approved' ? 'Disetujui' : 'Ditolak';
        $typeText = $this->getSubmissionTitle($submissionType);

        return "{$typeText} {$statusText}";
    }

    /**
     * Get feedback message based on type and status
     */
    private function getFeedbackMessage($submissionType, $feedbackStatus, $details = [])
    {
        $statusText = $feedbackStatus === 'approved' ? 'disetujui' : 'ditolak';
        $typeText = strtolower($this->getSubmissionTitle($submissionType));
        $note = $details['note'] ?? '';

        $message = "Pengajuan {$typeText} Anda telah {$statusText}";
        if ($note) {
            $message .= ". Catatan: {$note}";
        }

        return $message;
    }

    /**
     * Get model class based on submission type
     */
    private function getModelClass($submissionType)
    {
        $models = [
            'sick' => 'App\\Models\\EmployeeLeaveRequest',
            'leave' => 'App\\Models\\EmployeeLeaveRequest',
            'overtime' => 'App\\Models\\EmployeeOvertime',
            'procurement' => 'App\\Models\\MaterialRequest',
            'loan' => 'App\\Models\\Receivable',
            'usage' => 'App\\Models\\GoodIssue',
            'daily_report' => 'App\\Models\\Submission',
            'employee' => 'App\\Models\\Submission',
        ];

        return $models[$submissionType] ?? 'App\\Models\\Submission';
    }
}
