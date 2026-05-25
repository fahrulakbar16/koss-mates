<template>
    <AppLayout title="Konfigurasi WhatsApp">
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Konfigurasi WhatsApp
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Kelola perangkat WhatsApp untuk pengiriman notifikasi
                    </p>
                </div>

                <!-- Error Alert -->
                <div v-if="error"
                    class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                {{ error }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Device List Card -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Daftar Perangkat Fonnte
                            </h2>
                            <button @click="fetchDevicesList" :disabled="loadingDevices"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg :class="['w-4 h-4 mr-2', loadingDevices ? 'animate-spin' : '']" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Refresh List
                            </button>
                        </div>

                        <!-- Devices Grid -->
                        <div v-if="devices.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="device in devices" :key="device.device"
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-red-300 dark:hover:border-red-700 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ device.name || 'Unnamed Device' }}
                                    </h3>
                                    <span :class="[
                                        'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium',
                                        device.status === 'connect'
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ]">
                                        {{ device.status === 'connect' ? 'Connected' : 'Disconnected' }}
                                    </span>
                                </div>
                                <div class="space-y-1 text-xs mb-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Number:</span>
                                        <span class="text-gray-900 dark:text-gray-100 font-medium">{{ device.device
                                        }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Package:</span>
                                        <span class="text-gray-900 dark:text-gray-100">{{ device.package }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Quota:</span>
                                        <span class="text-gray-900 dark:text-gray-100">{{ device.quota }}</span>
                                    </div>
                                </div>
                                <!-- Show Connect button if disconnected, Disconnect if connected -->
                                <button v-if="device.status === 'connect'" @click="disconnectDevice(device)"
                                    :disabled="selectingDevice"
                                    class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Disconnect
                                </button>
                                <button v-else @click="connectDevice(device)" :disabled="selectingDevice"
                                    class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Connect
                                </button>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div v-else-if="loadingDevices" class="flex items-center justify-center py-12">
                            <svg class="animate-spin h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        <!-- No Devices -->
                        <div v-else class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada perangkat</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Klik Refresh List untuk memuat perangkat dari Fonnte
                            </p>
                        </div>
                    </div>
                </div>

                <!-- QR Code Modal -->
                <div v-if="showQRModal"
                    class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Scan QR Code
                                </h3>
                                <button @click="closeQRModal"
                                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div v-if="selectedDevice" class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Menghubungkan perangkat: <span
                                        class="font-semibold text-gray-900 dark:text-white">{{
                                            selectedDevice.name
                                        }}</span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Nomor: <span class="font-semibold text-gray-900 dark:text-white">{{
                                        selectedDevice.device }}</span>
                                </p>
                            </div>

                            <!-- QR Code Display -->
                            <div v-if="qrCode" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg mb-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 text-center">
                                    Scan QR Code dengan WhatsApp Anda:
                                </p>
                                <div class="flex flex-col items-center">
                                    <img :src="qrCode" alt="QR Code"
                                        class="mx-auto w-64 h-64 border-4 border-white dark:border-gray-600 rounded-lg shadow-lg" />
                                    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400 text-center">
                                        Halaman akan otomatis refresh ketika perangkat terhubung
                                    </p>
                                    <div class="mt-3 flex items-center text-xs text-blue-600 dark:text-blue-400">
                                        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Menunggu koneksi...
                                    </div>
                                </div>
                            </div>

                            <!-- QR Error -->
                            <div v-else-if="qrError"
                                class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg mb-4">
                                <p class="text-sm text-red-800 dark:text-red-200">
                                    {{ qrError }}
                                </p>
                            </div>

                            <div class="flex justify-end">
                                <button @click="closeQRModal"
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    deviceStatus: Object,
    error: String,
});

const loading = ref(false);
const qrCode = ref(null);
const qrError = ref(null);
const pollingInterval = ref(null);
const devices = ref([]);
const loadingDevices = ref(false);
const selectingDevice = ref(false);
const showQRModal = ref(false);
const selectedDevice = ref(null);

// Fetch list of all devices from Fonnte
const fetchDevicesList = async () => {
    loadingDevices.value = true;
    try {
        const response = await fetch(route('whatsapp-config.devices'));
        const data = await response.json();

        if (data.success) {
            devices.value = data.devices || [];
        } else {
            console.error('Failed to fetch devices:', data.message);
        }
    } catch (error) {
        console.error('Failed to fetch devices list:', error);
    } finally {
        loadingDevices.value = false;
    }
};

// Update device (select and set as active device)
const updateDevice = async (device) => {
    selectingDevice.value = true;
    try {
        const response = await fetch(route('whatsapp-config.select-device'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                device_token: device.token,
                device_number: device.device,
            }),
        });

        const data = await response.json();

        if (data.success) {
            alert('Perangkat berhasil diperbarui: ' + device.name);
            await fetchDevicesList();
        } else {
            alert('Gagal memperbarui perangkat: ' + data.message);
        }
    } catch (error) {
        console.error('Failed to update device:', error);
        alert('Terjadi kesalahan saat memperbarui perangkat');
    } finally {
        selectingDevice.value = false;
    }
};

// Disconnect specific device
const disconnectDevice = async (device) => {
    if (!confirm('Apakah Anda yakin ingin memutuskan perangkat ' + device.name + '?')) {
        return;
    }

    selectingDevice.value = true;
    try {
        const response = await fetch(route('whatsapp-config.disconnect'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                device_token: device.token,
            }),
        });

        const data = await response.json();

        if (data.success) {
            alert('Perangkat berhasil diputuskan: ' + device.name);
            await fetchDevicesList();
        } else {
            alert('Gagal memutuskan perangkat: ' + data.message);
        }
    } catch (error) {
        console.error('Failed to disconnect device:', error);
        alert('Terjadi kesalahan saat memutuskan perangkat');
    } finally {
        selectingDevice.value = false;
    }
};

// Connect a disconnected device - Show QR Code
const connectDevice = async (device) => {
    selectingDevice.value = true;
    qrError.value = null;

    try {
        // Fetch QR code for this specific device
        const response = await fetch(route('whatsapp-config.qr-code'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                device_token: device.token,
            }),
        });

        const data = await response.json();

        if (data.success && data.qr_code) {
            qrCode.value = data.qr_code;
            selectedDevice.value = device;
            showQRModal.value = true;
            // Start polling to check device status
            startDevicePolling(device);
        } else if (data.already_connected) {
            qrError.value = 'Perangkat sudah terhubung';
            alert('Perangkat sudah terhubung');
            await fetchDevicesList();
        } else {
            qrError.value = data.message || 'Gagal mendapatkan QR code';
            alert(qrError.value);
        }
    } catch (error) {
        console.error('Failed to fetch QR code:', error);
        alert('Terjadi kesalahan saat mengambil QR code');
    } finally {
        selectingDevice.value = false;
    }
};

// Close QR Modal
const closeQRModal = () => {
    showQRModal.value = false;
    qrCode.value = null;
    selectedDevice.value = null;
    stopPolling();
};

// Start polling for specific device
const startDevicePolling = (device) => {
    // Clear existing interval
    if (pollingInterval.value) {
        clearInterval(pollingInterval.value);
    }

    // Poll every 3 seconds to check if device is connected
    pollingInterval.value = setInterval(async () => {
        try {
            const response = await fetch(route('whatsapp-config.devices'));
            const data = await response.json();

            if (data.success && data.devices) {
                const updatedDevice = data.devices.find(d => d.token === device.token);

                if (updatedDevice && updatedDevice.status === 'connect') {
                    // Device is now connected
                    stopPolling();
                    closeQRModal();
                    alert('Perangkat berhasil terhubung: ' + device.name);
                    await fetchDevicesList();
                }
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }, 3000);
};

// Fetch QR code from API
const fetchQRCode = async () => {
    try {
        qrError.value = null;
        const response = await fetch(route('whatsapp-config.qr-code'));
        const data = await response.json();

        if (data.success && data.qr_code) {
            qrCode.value = data.qr_code;
            // Start polling to check device status
            startPolling();
        } else if (data.already_connected) {
            qrError.value = 'Perangkat sudah terhubung';
            // Refresh page to show connected status
            setTimeout(() => {
                router.reload({ only: ['deviceStatus', 'error'] });
            }, 1500);
        } else {
            qrError.value = data.message || 'Gagal mendapatkan QR code';
        }
    } catch (error) {
        console.error('Failed to fetch QR code:', error);
        qrError.value = 'Terjadi kesalahan saat mengambil QR code';
    }
};

// Start polling to check if device is connected
const startPolling = () => {
    // Clear existing interval
    if (pollingInterval.value) {
        clearInterval(pollingInterval.value);
    }

    // Poll every 3 seconds
    pollingInterval.value = setInterval(async () => {
        try {
            const response = await fetch(route('whatsapp-config.status'));
            const data = await response.json();

            if (data.success && data.data && data.data.connected) {
                // Device is now connected, stop polling and reload
                stopPolling();
                qrCode.value = null;
                router.reload({ only: ['deviceStatus', 'error'] });
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }, 3000);
};

// Stop polling
const stopPolling = () => {
    if (pollingInterval.value) {
        clearInterval(pollingInterval.value);
        pollingInterval.value = null;
    }
};

// Load devices on mount
onMounted(() => {
    fetchDevicesList();
});

// Cleanup on component unmount
onBeforeUnmount(() => {
    stopPolling();
});

const refreshStatus = async () => {
    loading.value = true;
    stopPolling(); // Stop any active polling
    qrCode.value = null; // Clear QR code

    try {
        const response = await fetch(route('whatsapp-config.status'));
        const data = await response.json();

        if (data.success) {
            router.reload({ only: ['deviceStatus', 'error'] });
        }
    } catch (error) {
        console.error('Failed to refresh status:', error);
    } finally {
        loading.value = false;
    }
};

const handleConnect = async () => {
    loading.value = true;
    qrError.value = null;

    // Fetch QR code when connect button is clicked
    await fetchQRCode();

    loading.value = false;
};

const handleDisconnect = () => {
    if (confirm('Apakah Anda yakin ingin memutuskan perangkat WhatsApp?')) {
        stopPolling(); // Stop polling when disconnecting
        router.post(route('whatsapp-config.disconnect'), {}, {
            preserveScroll: true,
            onStart: () => { loading.value = true; },
            onFinish: () => { loading.value = false; },
        });
    }
};
</script>
