<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\BoardingHouseController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Penyewa\TransactionController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\LogActivityController;
use \App\Http\Controllers\Penyewa\RoomController as PenyewaRoomController;

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/kos', [WelcomeController::class, 'list'])->name('boarding-houses.public.index');
Route::get('/kos/{boardingHouse}', [WelcomeController::class, 'show'])->name('boarding-houses.public.show');

// Activity Logs
Route::get('/activity-logs', [LogActivityController::class, 'index'])->name('activity-logs.index')->middleware('auth');

// Profile & Dashboard
Route::prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'showDetail'])->name('profile.show');
    Route::put('/tenant', [ProfileController::class, 'updateTenant'])->name('profile.tenant.update');
    Route::post('/tenant/send-otp', [ProfileController::class, 'sendTenantPhoneOtp'])->name('profile.tenant.send-otp');
    Route::get('/tenant/verify-otp', [ProfileController::class, 'showVerifyOtp'])->name('profile.tenant.verify-otp.show');
    Route::post('/tenant/verify-otp', [ProfileController::class, 'verifyTenantPhoneOtp'])->name('profile.tenant.verify-otp');
});

Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Penyewa\VerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('penyewa.verification.verify');

// Penyewa Routes
Route::prefix('penyewa')->middleware(['auth'])->group(function () {
    Route::get('/verify-email', function (\Illuminate\Http\Request $request) {
        return \Inertia\Inertia::render('Penyewa/Auth/VerifyEmail', [
            'status' => session('status'),
            'emailVerified' => (bool)$request->query('email_verified', false),
            'phoneVerified' => (bool)$request->query('phone_verified', false),
        ]);
    })->name('penyewa.verification.notice');

    Route::post('/email/verification-notification', [\App\Http\Controllers\Penyewa\VerificationController::class, 'resend'])
        ->middleware('throttle:1,1')
        ->name('penyewa.verification.send');
});


Route::prefix('penyewa')->middleware(['auth', 'penyewa.verified'])->group(function () {
    Route::resource('transactions', TransactionController::class)->only(['index', 'show'])->names('penyewa.transactions');
    Route::post('transactions/{transaction}/payment', [TransactionController::class, 'createPayment'])->name('penyewa.transactions.payment.create');
    Route::delete('transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('penyewa.transactions.cancel');
    Route::get('payment/finish', \App\Http\Controllers\Penyewa\PaymentFinishController::class)->name('penyewa.payment.finish');

    // My Room
    Route::get('rooms', [PenyewaRoomController::class, 'index'])->name('penyewa.rooms.index');

    // Check-in
    Route::post('rooms/checkin', [PenyewaRoomController::class, 'submitCheckin'])->name('penyewa.rooms.checkin.submit');

    // Damage Reports
    Route::resource('damage-reports', \App\Http\Controllers\Penyewa\DamageReportController::class)->only(['index', 'create', 'store', 'show'])->names('penyewa.damage-reports');

    // Room Transfers
    Route::get('room-transfers', [\App\Http\Controllers\Penyewa\RoomTransferController::class, 'index'])->name('penyewa.transfers.index');
    Route::get('room-transfers/create', [\App\Http\Controllers\Penyewa\RoomTransferController::class, 'create'])->name('penyewa.transfers.create'); // Added Route
    Route::post('room-transfers/calculate-cost', [\App\Http\Controllers\Penyewa\RoomTransferController::class, 'calculateCost'])->name('penyewa.transfers.calculate-cost');
    Route::post('room-transfers', [\App\Http\Controllers\Penyewa\RoomTransferController::class, 'store'])->name('penyewa.transfers.store');
    Route::get('room-transfers/available-rooms/{userRoom}', [\App\Http\Controllers\Penyewa\RoomTransferController::class, 'getAvailableRooms'])->name('penyewa.transfers.available-rooms');

    // Refunds
    Route::get('refunds', [\App\Http\Controllers\Penyewa\RefundController::class, 'index'])->name('penyewa.refunds.index');
    Route::post('refunds/{refund}/confirm', [\App\Http\Controllers\Penyewa\RefundController::class, 'confirm'])->name('penyewa.refunds.confirm');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Bookings
Route::post('/bookings', [\App\Http\Controllers\Public\BookingController::class, 'store'])->name('bookings.store')->middleware('auth');
Route::post('/midtrans/callback', [\App\Http\Controllers\Public\PaymentCallbackController::class, 'handle'])->name('midtrans.callback');

// Roles — Superadmin only
Route::prefix('roles')->middleware(['auth', 'role:Superadmin'])->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/{role}/stats', [RoleController::class, 'stats'])->name('roles.stats');
});

// Users — Superadmin only
Route::prefix('users')->middleware(['auth', 'role:Superadmin'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Settings — Superadmin only
Route::prefix('settings')->middleware(['auth', 'role:Superadmin'])->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/update-multiple', [SettingController::class, 'updateMultiple'])->name('settings.update-multiple');
});

// WhatsApp Configuration
// WhatsApp Config — Superadmin only
Route::prefix('whatsapp-config')->middleware(['auth', 'role:Superadmin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\WhatsAppConfigController::class, 'index'])->name('whatsapp-config.index');
    Route::get('/status', [\App\Http\Controllers\WhatsAppConfigController::class, 'getStatus'])->name('whatsapp-config.status');
    Route::post('/qr-code', [\App\Http\Controllers\WhatsAppConfigController::class, 'getQRCode'])->name('whatsapp-config.qr-code');
    Route::get('/devices', [\App\Http\Controllers\WhatsAppConfigController::class, 'getDevices'])->name('whatsapp-config.devices');
    Route::post('/select-device', [\App\Http\Controllers\WhatsAppConfigController::class, 'selectDevice'])->name('whatsapp-config.select-device');
    Route::post('/connect', [\App\Http\Controllers\WhatsAppConfigController::class, 'connect'])->name('whatsapp-config.connect');
    Route::post('/disconnect', [\App\Http\Controllers\WhatsAppConfigController::class, 'disconnect'])->name('whatsapp-config.disconnect');
});

// Check-in Requests Management
Route::prefix('admin/checkin-requests')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\CheckinRequestController::class, 'index'])->name('admin.checkin-requests.index');
    Route::post('/{id}/approve', [\App\Http\Controllers\Admin\CheckinRequestController::class, 'approve'])->name('admin.checkin-requests.approve');
    Route::post('/{id}/reject', [\App\Http\Controllers\Admin\CheckinRequestController::class, 'reject'])->name('admin.checkin-requests.reject');
});

// Room Transfer Requests Management
Route::prefix('admin/room-transfers')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\RoomTransferController::class, 'index'])->name('admin.room-transfers.index');
    Route::get('/{transfer}', [\App\Http\Controllers\Admin\RoomTransferController::class, 'show'])->name('admin.room-transfers.show');
    Route::post('/{transfer}/action', [\App\Http\Controllers\Admin\RoomTransferController::class, 'action'])->name('admin.room-transfers.action');
});

// Damage Reports Management
Route::prefix('admin/damage-reports')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DamageReportController::class, 'index'])->name('admin.damage-reports.index');
    Route::get('/{damageReport}', [\App\Http\Controllers\Admin\DamageReportController::class, 'show'])->name('admin.damage-reports.show');
    Route::put('/{damageReport}', [\App\Http\Controllers\Admin\DamageReportController::class, 'update'])->name('admin.damage-reports.update');
});

// Tenant Management
Route::prefix('admin/tenants')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\TenantController::class, 'index'])->name('admin.tenants.index');
    Route::get('/create', [\App\Http\Controllers\Admin\TenantController::class, 'create'])->name('admin.tenants.create');
    Route::post('/', [\App\Http\Controllers\Admin\TenantController::class, 'store'])->name('admin.tenants.store');
    Route::get('/{id}', [\App\Http\Controllers\Admin\TenantController::class, 'show'])->name('admin.tenants.show');
    Route::get('/{id}/edit', [\App\Http\Controllers\Admin\TenantController::class, 'edit'])->name('admin.tenants.edit');
    Route::put('/{id}', [\App\Http\Controllers\Admin\TenantController::class, 'update'])->name('admin.tenants.update');
    Route::put('/{id}/move-status', [\App\Http\Controllers\Admin\TenantController::class, 'updateMoveStatus'])->name('admin.tenants.update-move-status');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\TenantController::class, 'destroy'])->name('admin.tenants.destroy');
});

// Owner Management
Route::prefix('admin/owners')->middleware(['auth'])->group(function () {
    Route::resource('/', \App\Http\Controllers\Admin\OwnerController::class)
        ->parameters(['' => 'owner'])
        ->names([
            'index' => 'admin.owners.index',
            'create' => 'admin.owners.create',
            'store' => 'admin.owners.store',
            'show' => 'admin.owners.show',
            'edit' => 'admin.owners.edit',
            'update' => 'admin.owners.update',
            'destroy' => 'admin.owners.destroy',
        ]);
});

// Refund Management
Route::prefix('admin/refunds')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\RefundController::class, 'index'])->name('admin.refunds.index');
    Route::put('/{refund}', [\App\Http\Controllers\Admin\RefundController::class, 'update'])->name('admin.refunds.update');
});

// Financial Reports
Route::prefix('admin/financial-reports')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\FinancialReportController::class, 'index'])->name('admin.financial-reports.index');
    Route::get('/{id}/recap', [\App\Http\Controllers\Admin\FinancialReportController::class, 'recap'])->name('admin.financial-reports.recap');
    Route::get('/{id}/recap/print', [\App\Http\Controllers\Admin\FinancialReportController::class, 'printRecap'])->name('admin.financial-reports.recap.print');
    Route::get('/room/{roomId}/detail', [\App\Http\Controllers\Admin\FinancialReportController::class, 'roomDetail'])->name('admin.financial-reports.room-detail');
    Route::get('/room/{roomId}/detail/print', [\App\Http\Controllers\Admin\FinancialReportController::class, 'printRoomDetail'])->name('admin.financial-reports.room-detail.print');
    Route::get('/{id}', [\App\Http\Controllers\Admin\FinancialReportController::class, 'show'])->name('admin.financial-reports.show');
});

// Transaction Management
Route::prefix('admin/transactions')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('admin.transactions.index');
    Route::post('/income', [\App\Http\Controllers\Admin\TransactionController::class, 'storeIncome'])->name('admin.transactions.store-income');
    Route::post('/expense', [\App\Http\Controllers\Admin\TransactionController::class, 'storeExpense'])->name('admin.transactions.store-expense');
});

// Import Management
Route::prefix('admin/import')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\ImportController::class, 'index'])->name('admin.import.index');
    Route::post('/process', [\App\Http\Controllers\Admin\ImportController::class, 'process'])->name('admin.import.process');
});

// Clusters
Route::prefix('clusters')->group(function () {
    Route::get('/', [ClusterController::class, 'index'])->name('clusters.index');
    Route::get('/create', [ClusterController::class, 'create'])->name('clusters.create');
    Route::post('/', [ClusterController::class, 'store'])->name('clusters.store');
    Route::get('/{cluster}', [ClusterController::class, 'show'])->name('clusters.show');
    Route::get('/{cluster}/edit', [ClusterController::class, 'edit'])->name('clusters.edit');
    Route::put('/{cluster}', [ClusterController::class, 'update'])->name('clusters.update');
    Route::delete('/{cluster}', [ClusterController::class, 'destroy'])->name('clusters.destroy');

    // Nested Boarding Houses under Clusters
    Route::post('/{cluster}/boarding-houses', [BoardingHouseController::class, 'store'])->name('clusters.boarding-houses.store');
    Route::put('/{cluster}/boarding-houses/{boardingHouse}', [BoardingHouseController::class, 'update'])->name('clusters.boarding-houses.update');
    Route::delete('/{cluster}/boarding-houses/{boardingHouse}', [BoardingHouseController::class, 'destroy'])->name('clusters.boarding-houses.destroy');
});

// Boarding Houses
Route::prefix('boarding-houses')->group(function () {
    Route::get('/', [BoardingHouseController::class, 'index'])->name('boarding-houses.index');
    Route::get('/create', [BoardingHouseController::class, 'create'])->name('boarding-houses.create');
    Route::post('/', [BoardingHouseController::class, 'store'])->name('boarding-houses.store');
    Route::get('/{boardingHouse}', [BoardingHouseController::class, 'show'])->name('boarding-houses.show');
    Route::get('/{boardingHouse}/edit', [BoardingHouseController::class, 'edit'])->name('boarding-houses.edit');
    Route::put('/{boardingHouse}', [BoardingHouseController::class, 'update'])->name('boarding-houses.update');
    Route::delete('/{boardingHouse}', [BoardingHouseController::class, 'destroy'])->name('boarding-houses.destroy');

    // Boarding House Rooms
    Route::post('/{boardingHouse}/rooms', [RoomController::class, 'store'])->name('boarding-houses.rooms.store');
    Route::post('/{boardingHouse}/rooms/batch', [RoomController::class, 'batchStore'])->name('boarding-houses.rooms.batch-store');
    Route::get('/{boardingHouse}/rooms/{room}', [RoomController::class, 'show'])->name('boarding-houses.rooms.show');
    Route::put('/{boardingHouse}/rooms/{room}', [RoomController::class, 'update'])->name('boarding-houses.rooms.update');
    Route::delete('/{boardingHouse}/rooms/{room}', [RoomController::class, 'destroy'])->name('boarding-houses.rooms.destroy');
    Route::get('/{boardingHouse}/rooms/{room}/prices', [RoomController::class, 'editPrices'])->name('boarding-houses.rooms.prices.edit');
    Route::post('/{boardingHouse}/rooms/{room}/prices', [RoomController::class, 'storePrices'])->name('boarding-houses.rooms.prices.store');
    Route::post('/{boardingHouse}/rooms/{room}/cancel-booking', [RoomController::class, 'cancelBooking'])->name('boarding-houses.rooms.cancel-booking');
    Route::post('/{boardingHouse}/rooms/{room}/checkout', [RoomController::class, 'checkout'])->name('boarding-houses.rooms.checkout');

    // Boarding House Expenses
    Route::post('/{boardingHouse}/expenses', [\App\Http\Controllers\Admin\ExpenseController::class, 'store'])->name('boarding-houses.expenses.store');
    Route::put('/{boardingHouse}/expenses/{expense}', [\App\Http\Controllers\Admin\ExpenseController::class, 'update'])->name('boarding-houses.expenses.update');
    Route::delete('/{boardingHouse}/expenses/{expense}', [\App\Http\Controllers\Admin\ExpenseController::class, 'destroy'])->name('boarding-houses.expenses.destroy');

    // Room Expenses
    Route::post('/{boardingHouse}/rooms/{room}/expenses', [ExpenseController::class, 'store'])->name('boarding-houses.rooms.expenses.store');
    Route::put('/{boardingHouse}/rooms/{room}/expenses/{expense}', [ExpenseController::class, 'update'])->name('boarding-houses.rooms.expenses.update');
    Route::delete('/{boardingHouse}/rooms/{room}/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('boarding-houses.rooms.expenses.destroy');

    // Boarding House Images
    Route::post('/{boardingHouse}/images', [BoardingHouseController::class, 'storeImages'])->name('boarding-houses.images.store');
    Route::delete('/{boardingHouse}/images/{boardingHouseImage}', [BoardingHouseController::class, 'destroyImage'])->name('boarding-houses.images.destroy');
});

// Web logout for Inertia (used by router.post(route('logout')))
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');




Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'auth'])->name('auth');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
    Route::post('/login-with-google', [AuthController::class, 'loginWithGoogle'])->name('login.google');
});


//testing nitifikasi
Route::get('/test-notification', function (\App\Actions\Notifikasi\SendAll $sendAll, \Kreait\Firebase\Contract\Messaging $messaging) {
    try {
        $user = \App\Models\User::get();
        foreach ($user as $u) {

            app(\App\Actions\Notifikasi\SendNotifToUser::class)->execute($u, 'Test Broadcast', 'Ini adalah pesan test broadcast cbdhjbsjhcdsbjh.', ['type' => 'test'], $messaging);
        }
        return 'Notifikasi berhasil dikirim!';
    } catch (\Throwable $e) {
        return 'Gagal mengirim notifikasi: ' . $e->getMessage();
    }
});
