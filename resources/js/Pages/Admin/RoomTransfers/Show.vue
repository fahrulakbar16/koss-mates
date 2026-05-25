<template>
    <Head title="Detail Permintaan Pindah Kamar" />

    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-0">
                            Detail Permintaan #{{ transfer.id }}
                        </h1>
                        <span :class="{
                            'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800': transfer.status === 'pending',
                            'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800': transfer.status === 'approved',
                            'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800': transfer.status === 'rejected',
                        }" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            {{ transfer.status }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Dibuat pada {{ transfer.created_at }}
                    </p>
                </div>
            </div>

            <Link :href="route('admin.room-transfers.index')"
                class="inline-flex gap-2 items-center px-4 py-2 text-sm text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="font-medium">Kembali</span>
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card: Detail Perpindahan -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 sm:px-8 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            Informasi Perpindahan
                        </h3>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">
                            <!-- Connecting Arrow (Desktop) -->
                            <div class="hidden md:flex absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-white dark:bg-gray-800 rounded-full border border-gray-200 dark:border-gray-700 items-center justify-center text-gray-400 z-10 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>

                            <!-- From -->
                            <div class="bg-gray-50 dark:bg-gray-800/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/50">
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                    Kamar Asal</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white mb-1">
                                    {{ transfer.current_room_name }}
                                </p>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span class="truncate">{{ transfer.user_room?.boarding_house?.name }}</span>
                                </div>
                            </div>

                            <!-- To -->
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-2xl border border-purple-100 dark:border-purple-800/30">
                                <p class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-2">
                                    Kamar Tujuan</p>
                                <p class="text-xl font-bold text-purple-700 dark:text-purple-300 mb-1">
                                    Kamar {{ transfer.destination_room_name }}
                                </p>
                                <div class="flex items-center text-sm text-purple-600/70 dark:text-purple-400/70 mt-2">
                                    <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span class="truncate">{{ transfer.room?.boarding_house?.name || 'Kost Tujuan' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700/50">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Rencana Pindah</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ transfer.plan_date }}</p>
                                </div>
                                <div class="mt-4 sm:mt-0">
                                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 shadow-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Jadwal Pindah
                                    </span>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Alasan Pindah</h3>
                                <div class="relative mt-4">
                                    <div class="absolute top-0 left-0 -mt-3 -ml-2 text-gray-200 dark:text-gray-700">
                                        <svg class="w-8 h-8 transform rotate-180" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14.017 21L14.017 18C14.017 16.8954 13.1216 16 12.017 16H9C9 14.8954 9.89543 14 11 14C11.5523 14 12 13.5523 12 13V7C12 6.44772 11.5523 6 11 6H5C4.44772 6 4 6.44772 4 7V13C4 13.5523 4.44772 14 5 14V16C5 18.7614 7.23858 21 10 21H14.017ZM21.017 21L21.017 18C21.017 16.8954 20.1216 16 19.017 16H16C16 14.8954 16.8954 14 18 14C18.5523 14 19 13.5523 19 13V7C19 6.44772 18.5523 6 18 6H12C11.4477 6 11 6.44772 11 7V13C11 13.5523 11.4477 14 12 14V16C12 18.7614 14.2386 21 17 21H21.017Z" />
                                        </svg>
                                    </div>
                                    <div class="relative z-10 px-6 py-5 bg-gray-50/80 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700/50">
                                        <p class="text-base text-gray-700 dark:text-gray-300 italic leading-relaxed">"{{ transfer.reason }}"</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Tenant & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Card: Tenant Info -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Data Penyewa</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col items-center mb-6">
                            <div class="w-20 h-20 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-3xl font-bold text-primary-600 dark:text-primary-400 mb-4 border border-primary-200 dark:border-primary-800/50">
                                {{ transfer.tenant_name ? transfer.tenant_name.charAt(0).toUpperCase() : '?' }}
                            </div>
                            <p class="text-lg font-bold text-gray-900 dark:text-white text-center">
                                {{ transfer.tenant_name }}
                            </p>
                            <span class="mt-2 text-xs font-bold px-3 py-1 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800 rounded-full">Penyewa</span>
                        </div>

                        <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center shrink-0 border border-gray-100 dark:border-gray-700">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 pt-0.5">
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ transfer.user_room?.user?.email || '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center shrink-0 border border-gray-100 dark:border-gray-700">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 pt-0.5">
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No. Telepon</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ transfer.user_room?.user?.phone || '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-6">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                        <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            Aksi Tersedia
                        </h3>
                    </div>

                    <div v-if="transfer.status === 'pending'" class="p-6">
                        <div class="space-y-3">
                            <button @click="approveTransfer" :disabled="processing"
                                class="w-full flex justify-center items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl disabled:opacity-50 shadow-sm hover:shadow-green-600/20 hover:shadow-md transition-all active:scale-[0.98]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Setujui Permintaan
                            </button>
                            <button @click="openRejectModal" :disabled="processing"
                                class="w-full flex justify-center items-center px-4 py-3 bg-white text-red-600 border-2 border-red-50 hover:bg-red-50 hover:border-red-100 dark:bg-gray-800 dark:text-red-400 dark:border-red-900/30 dark:hover:bg-red-900/20 font-bold rounded-xl disabled:opacity-50 transition-all active:scale-[0.98]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak Permintaan
                            </button>
                        </div>
                        <p class="mt-5 text-xs text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                            Tindakan ini akan otomatis mengirim notifikasi kepada penyewa.
                        </p>
                    </div>

                    <div v-else class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm font-medium">
                        Tidak ada aksi yang perlu diperhatikan saat ini, permintaan telah
                        <span class="font-bold underline">{{ transfer.status === 'approved' ? 'Disetujui' : 'Ditolak' }}</span>.
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div v-if="showRejectModal"
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-md w-full p-8 transform transition-all scale-100">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-50 dark:bg-red-900/30 mb-6 border border-red-100 dark:border-red-800">
                    <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">
                    Tolak Permintaan?
                </h3>
                <p class="text-center text-sm font-medium text-gray-500 dark:text-gray-400 mb-8 px-4">
                    Anda akan menolak permintaan pindah kamar dari <span
                        class="font-bold text-gray-900 dark:text-white block mt-1">{{ transfer.tenant_name }}</span>
                    Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <button @click="closeRejectModal"
                        class="w-full px-4 py-3 font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 dark:focus:ring-offset-gray-900">
                        Batal
                    </button>
                    <button @click="confirmReject" :disabled="processing"
                        class="w-full px-4 py-3 font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 disabled:opacity-50 shadow-sm hover:shadow-red-600/20 hover:shadow-md transition-all active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-900">
                        {{ processing ? 'Memproses...' : 'Ya, Tolak' }}
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    transfer: Object,
});

const transfer = props.transfer.data;
const processing = ref(false);
const showRejectModal = ref(false);

const approveTransfer = () => {
    if (confirm('Apakah Anda yakin ingin menyetujui perpindahan kamar ini? Penyewa akan dipindahkan ke kamar tujuan.')) {
        processing.value = true;
        // Use the action route
        router.post(route('admin.room-transfers.action', transfer.id), {
            action: 'approve'
        }, {
            preserveScroll: true,
            onFinish: () => { processing.value = false; },
        });
    }
};

const openRejectModal = () => {
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
};

const confirmReject = () => {
    processing.value = true;
    router.post(route('admin.room-transfers.action', transfer.id), {
        action: 'reject',
    }, {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
            closeRejectModal();
        },
    });
};
</script>
