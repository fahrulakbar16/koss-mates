<template>

    <Head title="Menunggu Verifikasi" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                    Menunggu Verifikasi
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Foto kamar Anda sedang diverifikasi oleh admin
                </p>
            </div>

            <!-- Waiting Status Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="p-6 sm:p-8">
                    <!-- Room Info -->
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ activeRoom.name }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ activeRoom.address || '-' }} • Kamar {{ activeRoom.room?.name || '-' }}
                        </p>
                    </div>

                    <!-- Status Icon and Message -->
                    <div class="text-center py-8">
                        <div class="relative inline-block mb-6">
                            <!-- Animated Circle -->
                            <div
                                class="w-24 h-24 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center animate-pulse">
                                <svg class="w-12 h-12 text-yellow-600 dark:text-yellow-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <!-- Decorative rings -->
                            <div
                                class="absolute inset-0 w-24 h-24 border-4 border-yellow-200 dark:border-yellow-800 rounded-full animate-ping opacity-20">
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                            Foto Sedang Diverifikasi
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-6">
                            Terima kasih telah mengupload foto kamar. Admin kami akan segera memverifikasi foto Anda.
                            Mohon tunggu beberapa saat.
                        </p>

                        <!-- Status Badge -->
                        <div
                            class="inline-flex items-center gap-2 bg-yellow-50 dark:bg-yellow-900/20 px-6 py-3 rounded-full border border-yellow-200 dark:border-yellow-800">
                            <div class="w-2 h-2 bg-yellow-600 dark:bg-yellow-400 rounded-full animate-pulse"></div>
                            <span class="text-sm font-semibold text-yellow-700 dark:text-yellow-300">
                                Sedang Ditinjau
                            </span>
                        </div>
                    </div>

                    <!-- Uploaded Photo Preview -->
                    <div v-if="activeRoom.foto_kamar" class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Foto yang Diupload
                        </h4>
                        <div class="rounded-2xl overflow-hidden border-2 border-gray-200 dark:border-gray-700">
                            <img :src="getPhotoUrl()" alt="Foto Kamar" class="w-full h-64 object-cover"
                                @error="$event.target.src = '/images/placeholder.png'" />
                        </div>
                    </div>

                    <div
                        class="h-px bg-gradient-to-r from-transparent via-gray-200 dark:via-gray-700 to-transparent my-6" />

                    <!-- Additional Information -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-5 border border-blue-200 dark:border-blue-800">
                        <div class="flex gap-3">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1">
                                <h3 class="font-bold text-blue-900 dark:text-blue-100 mb-2">
                                    Informasi Penting
                                </h3>
                                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                                    <li>• Proses verifikasi biasanya memakan waktu 1-24 jam</li>
                                    <li>• Anda akan menerima notifikasi setelah verifikasi selesai</li>
                                    <li>• Setelah diverifikasi, Anda dapat mengakses detail kamar lengkap</li>
                                    <li>• Jika ada pertanyaan, silakan hubungi pemilik kos</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Owner -->
                    <div v-if="activeRoom.owner"
                        class="mt-6 bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Pemilik Kos
                        </h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-lg">
                                    {{ activeRoom.owner.name }}
                                </p>
                                <p v-if="activeRoom.phone" class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ activeRoom.phone }}
                                </p>
                            </div>
                            <a v-if="activeRoom.phone" :href="`tel:${activeRoom.phone}`"
                                class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg shadow-primary-500/30 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Hubungi
                            </a>
                        </div>
                    </div>

                    <!-- Refresh Button -->
                    <div class="flex gap-4 pt-6">
                        <Link :href="route('penyewa.rooms.index')" method="get" as="button"
                            class="flex-1 py-4 px-6 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Refresh Status
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    activeRoom: Object,
});

const activeRoom = props.activeRoom.data;

const getPhotoUrl = () => {
    if (activeRoom.foto_kamar) {
        return `/storage/${activeRoom.foto_kamar}`;
    }
    return '/images/placeholder.png';
};
</script>
