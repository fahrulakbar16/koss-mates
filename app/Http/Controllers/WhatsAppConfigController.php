<?php

namespace App\Http\Controllers;

use App\Actions\WhatsApp\ConnectDeviceAction;
use App\Actions\WhatsApp\DisconnectDeviceAction;
use App\Actions\WhatsApp\GetDeviceStatusAction;
use App\Http\Resources\WhatsAppDeviceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class WhatsAppConfigController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
        ];
    }

    /**
     * Display WhatsApp configuration page
     */
    public function index(GetDeviceStatusAction $getDeviceStatus)
    {
        // abort_unless(Gate::allows('whatsapp_config.view'), 403, 'Anda tidak memiliki akses untuk melihat konfigurasi WhatsApp');

        // Get device status
        $statusResult = $getDeviceStatus->execute();

        return Inertia::render('Admin/WhatsAppConfig/Index', [
            'deviceStatus' => $statusResult['success']
                ? new WhatsAppDeviceResource($statusResult['data'])
                : null,
            'error' => !$statusResult['success'] ? $statusResult['message'] : null,
        ]);
    }

    /**
     * Get device status via AJAX
     */
    public function getStatus(GetDeviceStatusAction $getDeviceStatus)
    {
        // abort_unless(Gate::allows('whatsapp_config.view'), 403, 'Anda tidak memiliki akses untuk melihat konfigurasi WhatsApp');

        $result = $getDeviceStatus->execute();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'data' => new WhatsAppDeviceResource($result['data']),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }

    /**
     * Get QR code for device connection
     */
    public function getQRCode(Request $request, \App\Actions\WhatsApp\GetQRCodeAction $getQRCode)
    {
        // abort_unless(Gate::allows('whatsapp_config.view'), 403, 'Anda tidak memiliki akses untuk melihat konfigurasi WhatsApp');

        // Get device token from request if provided
        $deviceToken = $request->input('device_token');

        $result = $getQRCode->execute($deviceToken);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'qr_code' => $result['qr_code'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'already_connected' => $result['already_connected'] ?? false,
        ], $result['already_connected'] ?? false ? 200 : 400);
    }

    /**
     * Connect a device
     */
    public function connect(Request $request, ConnectDeviceAction $connectDevice)
    {
        // abort_unless(Gate::allows('whatsapp_config.edit'), 'Anda tidak memiliki akses untuk mengubah konfigurasi WhatsApp');

        // Get device token from request
        $deviceToken = $request->input('device_token');

        $result = $connectDevice->execute($deviceToken);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil dihubungkan',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }

    /**
     * Disconnect device
     */
    public function disconnect(Request $request, DisconnectDeviceAction $disconnectDevice)
    {
        // abort_unless(Gate::allows('whatsapp_config.edit'), 403, 'Anda tidak memiliki akses untuk mengubah konfigurasi WhatsApp');

        // If device_token is provided, use it; otherwise use the stored one
        $deviceToken = $request->input('device_token');

        $result = $disconnectDevice->execute($deviceToken);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil diputuskan',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }

    /**
     * Get list of all devices
     */
    public function getDevices(\App\Actions\WhatsApp\GetDevicesAction $getDevices)
    {
        // abort_unless(Gate::allows('whatsapp_config.view'), 403, 'Anda tidak memiliki akses untuk melihat konfigurasi WhatsApp');

        $result = $getDevices->execute();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'devices' => $result['devices'],
                'connected' => $result['connected'],
                'total_devices' => $result['total_devices'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }

    /**
     * Select a device to use
     */
    public function selectDevice(Request $request, \App\Actions\WhatsApp\UpdateSelectedDeviceAction $updateDevice)
    {
        // abort_unless(Gate::allows('whatsapp_config.edit'), 403, 'Anda tidak memiliki akses untuk mengubah konfigurasi WhatsApp');

        $request->validate([
            'device_token' => 'required|string',
            'device_number' => 'required|string',
        ]);

        $result = $updateDevice->execute(
            $request->input('device_token'),
            $request->input('device_number')
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }
}
