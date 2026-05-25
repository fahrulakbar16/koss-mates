<?php

use App\Http\Controllers\Admin\FinancialReportController;
use App\Http\Controllers\Api\v1\AuthController as ApiAuthController;
use App\Http\Controllers\Api\v1\BoardingHouseController as ApiBoardingHouseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\ProfileController as ApiProfileController;
use App\Http\Controllers\Api\v1\TransaksiController;
use App\Http\Controllers\Api\v1\CheckinController;
use App\Http\Controllers\Api\v1\RoomActiveController;
use App\Http\Controllers\Api\v1\DamageReportController as ApiDamageReportController;
use App\Http\Controllers\Api\v1\Pengelola\ClusterController as ApiAdminClusterController;
use App\Http\Controllers\Api\v1\Pengelola\BoardingHouseController as ApiAdminBoardingHouseController;
use App\Http\Controllers\Api\v1\Pengelola\FinancialReportController as ApiAdminFinancialReportController;
use App\Http\Controllers\Api\v1\Pengelola\RoomController as ApiAdminRoomController;
use App\Http\Controllers\Api\v1\Pengelola\RoomPriceController as ApiAdminRoomPriceController;
use App\Http\Controllers\Api\v1\Pengelola\CheckinRequestController as ApiAdminCheckinRequestController;
use App\Http\Controllers\Api\v1\Pengelola\DamageReportController as ApiAdminDamageReportController;
use App\Http\Controllers\Api\v1\Pengelola\ExpenseController as ApiAdminExpenseController;
use App\Http\Controllers\Api\v1\Pengelola\IncomeController as ApiAdminIncomeController;
use App\Http\Controllers\Api\v1\Pengelola\TenantController as ApiAdminTenantController;
use App\Http\Controllers\Api\v1\Pengelola\OwnerController as ApiAdminOwnerController;
use App\Http\Controllers\Api\v1\LogActivityController as ApiLogActivityController;
use App\Http\Controllers\Api\v1\Penyewa\RoomTransferController as ApiPenyewaRoomTransferController;
use App\Http\Controllers\Api\v1\Penyewa\RefundController as ApiPenyewaRefundController;
use App\Http\Controllers\Api\v1\Pengelola\RefundController as ApiAdminRefundController;
use App\Http\Controllers\Api\v1\Pengelola\RoomTransferController as ApiAdminRoomTransferController;

// --- Auth ---


Route::prefix('/boarding-houses')->group(function () {
    Route::get('/', [ApiBoardingHouseController::class, 'index'])->name('api.boarding-houses.index');
    Route::get('/{id}', [ApiBoardingHouseController::class, 'show'])->name('api.boarding-houses.show');

    // --- Booking ---
    Route::post('/{id}/booking', [TransaksiController::class, 'booking'])->middleware('auth:sanctum')->name('api.bookings.index');
});

Route::prefix('/auth')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/login-google', [ApiAuthController::class, 'loginGoogle']);
    Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    // --- Verify Email Resend ---
    Route::post('/email/resend', [\App\Http\Controllers\Api\v1\Penyewa\VerificationController::class, 'resend'])
       ->name('api.verification.send');

    // --- Profile ---
    Route::prefix('/profile')->group(function () {
        Route::get('/', [ApiProfileController::class, 'index']);
        Route::get('/{id}', [ApiProfileController::class, 'show']);
        Route::put('/', [ApiProfileController::class, 'update']);
        Route::put('/password', [ApiProfileController::class, 'updatePassword']);
        Route::post('/send-otp', [ApiProfileController::class, 'sendTenantPhoneOtp']);
        Route::post('/verify-otp', [ApiProfileController::class, 'verifyTenantPhoneOtp']);
    });

    // --- Activity Logs ---
    Route::get('/activity-logs', [ApiLogActivityController::class, 'index']);

    // --- Verified Penyewa Routes ---
    Route::middleware('penyewa.verified')->group(function () {
        Route::prefix('/bills')->group(function () {
            Route::get('/', [TransaksiController::class, 'index'])->name('api.bills.index');
            Route::get('/{id}', [TransaksiController::class, 'show'])->name('api.bills.show');
            Route::delete('/{id}', [TransaksiController::class, 'cancelBooking'])->name('api.bills.cancel');

            // --- Payments ---
            Route::post('{id}/payment', [TransaksiController::class, 'payment'])->name('api.bills.payment');
            Route::get('{id}/payments', [TransaksiController::class, 'paymentHistory'])->name('api.bills.payments.history');
        });

        // --- Check-in ---
        Route::prefix('/checkin')->group(function () {
            Route::get('/status', [CheckinController::class, 'getStatus'])->name('api.checkin.submit');
            Route::post('/submit', [CheckinController::class, 'submitCheckin'])->name('api.checkin.submit');
        });

        //roomactive
        Route::prefix('/room-active')->group(function () {
            Route::get('/', [RoomActiveController::class, 'index'])->name('api.room-active.index');
            Route::get('/damage-report', [ApiDamageReportController::class, 'index'])->name('api.room-active.damage-report.index');
            Route::post('/damage-report', [ApiDamageReportController::class, 'store'])->name('api.room-active.damage-report.store');
        });

        //room-transfer
        Route::prefix('/room-transfer')->group(function () {
            Route::get('/', [ApiPenyewaRoomTransferController::class, 'index']);
            Route::post('/', [ApiPenyewaRoomTransferController::class, 'store']);
            Route::get('/calculate-cost', [ApiPenyewaRoomTransferController::class, 'calculateCost']);
        });

        // --- Refunds ---
        Route::prefix('/refunds')->group(function () {
            Route::get('/', [ApiPenyewaRefundController::class, 'index']);
            Route::post('/{refundId}/confirm', [ApiPenyewaRefundController::class, 'confirm']);
        });
    });
});



Route::prefix('/pengelola')->middleware('auth:sanctum')->group(function () {

    // --- Clusters ---
    Route::prefix('/clusters')->group(function () {
        Route::get('/', [ApiAdminClusterController::class, 'index']);
        Route::post('/', [ApiAdminClusterController::class, 'store']);
        Route::get('/{id}', [ApiAdminClusterController::class, 'show']);
        Route::put('/{id}', [ApiAdminClusterController::class, 'update']);
        Route::delete('/{id}', [ApiAdminClusterController::class, 'destroy']);

        // --- Boarding Houses ---
        Route::prefix('{clusterId}/boarding-houses')->group(function () {
            Route::get('/', [ApiAdminBoardingHouseController::class, 'index']);
            Route::get('/{id}', [ApiAdminBoardingHouseController::class, 'show']);
            Route::post('/', [ApiAdminBoardingHouseController::class, 'store']);
            Route::put('/{id}', [ApiAdminBoardingHouseController::class, 'update']);
            Route::delete('/{id}', [ApiAdminBoardingHouseController::class, 'destroy']);


            // --- Expenses ---
            Route::prefix('{boardingHouseId}/expenses')->group(function () {
                Route::get('/', [ApiAdminExpenseController::class, 'index']);
                Route::post('/', [ApiAdminExpenseController::class, 'store']);
                Route::put('/{id}', [ApiAdminExpenseController::class, 'update']);
                Route::delete('/{id}', [ApiAdminExpenseController::class, 'destroy']);
            });

            // --- Incomes ---
            Route::prefix('{boardingHouseId}/incomes')->group(function () {
                Route::get('/', [ApiAdminIncomeController::class, 'index']);
                Route::post('/', [ApiAdminIncomeController::class, 'store']);
            });

            // --- Rooms ---
            Route::prefix('{boardingHouseId}/rooms')->group(function () {
                Route::get('/', [ApiAdminRoomController::class, 'index']);
                Route::post('/', [ApiAdminRoomController::class, 'store']);
                Route::post('/batch', [ApiAdminRoomController::class, 'batchStore']);
                Route::put('/{id}', [ApiAdminRoomController::class, 'update']);
                Route::delete('/{id}', [ApiAdminRoomController::class, 'destroy']);
                Route::post('/{id}/cancel-booking', [ApiAdminRoomController::class, 'cancelBooking']);
                Route::post('/{id}/checkout', [ApiAdminRoomController::class, 'checkout']);

                // --- Room Prices ---
                Route::prefix('{roomId}/room-prices')->group(function () {
                    Route::get('/', [ApiAdminRoomPriceController::class, 'index']);
                    Route::post('/', [ApiAdminRoomPriceController::class, 'store']);
                    Route::put('/{id}', [ApiAdminRoomPriceController::class, 'update']);
                    Route::delete('/{id}', [ApiAdminRoomPriceController::class, 'destroy']);
                });
            });
        });
    });

    //checkin-request
    Route::prefix('/checkin-request')->group(function () {
        Route::get('/', [ApiAdminCheckinRequestController::class, 'index']);
        Route::put('/{id}/approve', [ApiAdminCheckinRequestController::class, 'approve']);
        Route::put('/{id}/reject', [ApiAdminCheckinRequestController::class, 'reject']);
    });

    //damage-report
    Route::prefix('/damage-report')->group(function () {
        Route::get('/', [ApiAdminDamageReportController::class, 'index']);
        Route::get('/{id}', [ApiAdminDamageReportController::class, 'show']);
        Route::put('/{id}/status', [ApiAdminDamageReportController::class, 'updateStatus']);
    });

    //akun penyewa
    Route::prefix('/tenants')->group(function () {
        Route::get('/', [ApiAdminTenantController::class, 'index']);
        Route::post('/', [ApiAdminTenantController::class, 'store']);
        Route::get('/{id}', [ApiAdminTenantController::class, 'show']);
        Route::put('/{id}', [ApiAdminTenantController::class, 'update']);
        Route::delete('/{id}', [ApiAdminTenantController::class, 'destroy']);
    });

    //akun pemilik
    Route::prefix('/owners')->group(function () {
        Route::get('/', [ApiAdminOwnerController::class, 'index']);
        Route::post('/', [ApiAdminOwnerController::class, 'store']);
        Route::get('/{id}', [ApiAdminOwnerController::class, 'show']);
        Route::put('/{id}', [ApiAdminOwnerController::class, 'update']);
        Route::delete('/{id}', [ApiAdminOwnerController::class, 'destroy']);
    });

    // keuangan
    Route::prefix('/financial-report')->group(function () {
        Route::get('/', [ApiAdminFinancialReportController::class, 'index']);
        Route::get('/{id}', [ApiAdminFinancialReportController::class, 'show']);
        Route::get('/{id}/recap', [ApiAdminFinancialReportController::class, 'recap']);
        Route::get('/room/{roomId}/detail', [ApiAdminFinancialReportController::class, 'roomDetail']);


        //print
        Route::get('/{roomId}/recap/print', [FinancialReportController::class, 'printRecap'])->name('api.admin.financial-reports.recap.print');
        Route::get('/room/{roomId}/detail/print', [FinancialReportController::class, 'printRoomDetail'])->name('api.admin.financial-reports.room-detail.print');
    });

    // --- Refunds ---
    Route::prefix('/refunds')->group(function () {
        Route::get('/', [ApiAdminRefundController::class, 'index']);
        Route::put('/{refund}', [ApiAdminRefundController::class, 'update']);
    });

    // --- Room Transfers ---
    Route::prefix('/room-transfer')->group(function () {
        Route::get('/', [ApiAdminRoomTransferController::class, 'index']);
        Route::get('/{id}', [ApiAdminRoomTransferController::class, 'show']);
        Route::put('/{id}/action', [ApiAdminRoomTransferController::class, 'action']);
    });
});
