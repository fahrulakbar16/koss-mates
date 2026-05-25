<template>
    <Head title="Detail Pemilik" />

    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-0">
                        Detail Pemilik
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Informasi lengkap akun dan properti pemilik
                    </p>
                </div>
            </div>

            <Link :href="route('admin.owners.index')"
                class="inline-flex gap-2 items-center px-4 py-2 text-sm text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="font-medium">Kembali</span>
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col items-center">
                            <div class="w-24 h-24 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mb-4 border border-primary-200 dark:border-primary-800/50">
                                <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                                    {{ owner.name.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white text-center">{{ owner.name }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 font-medium">@{{ owner.username }}</p>
                            <span class="mt-3 px-3 py-1 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800 text-xs font-bold rounded-full">
                                Pemilik
                            </span>
                        </div>

                        <div class="mt-8 space-y-4 border-t border-gray-100 dark:border-gray-700 pt-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center shrink-0 border border-gray-100 dark:border-gray-700">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 pt-0.5">
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ owner.email }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center shrink-0 border border-gray-100 dark:border-gray-700">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 pt-0.5">
                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Terdaftar Sejak</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ new Date(owner.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4 border-t border-gray-100 dark:border-gray-700 pt-6">
                            <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Informasi Bank
                            </h3>

                            <div v-if="owner.owner" class="space-y-4 mt-3">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center shrink-0 border border-gray-100 dark:border-gray-700">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0 pt-0.5">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Bank</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ owner.owner.bank_name || '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center shrink-0 border border-gray-100 dark:border-gray-700">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.49 4.49h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0 pt-0.5">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nomor Rekening</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ owner.owner.bank_account_number || '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center shrink-0 border border-gray-100 dark:border-gray-700">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0 pt-0.5">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Atas Nama</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ owner.owner.bank_account_name || '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-sm font-medium text-gray-500 dark:text-gray-400 bg-gray-50/50 dark:bg-gray-800/50 p-4 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 text-center">
                                Belum ada detail informasi bank.
                            </div>
                        </div>

                        <div class="mt-8">
                            <Link :href="route('admin.owners.edit', owner.id)"
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-white dark:bg-gray-800 border-2 border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 text-primary-600 dark:text-primary-400 font-bold rounded-xl transition-all duration-200 shadow-sm hover:shadow-primary-500/10 active:scale-[0.98]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Akun Pemilik
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Properties List -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden h-full flex flex-col">
                    <div class="px-6 sm:px-8 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Daftar Properti (Kos)
                        </h3>
                    </div>

                    <div class="p-6 sm:p-8 flex-1">
                        <div v-if="!owner.boarding_houses || owner.boarding_houses.length === 0" class="h-full flex flex-col items-center justify-center py-12 text-center rounded-xl border border-dashed border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-sm mb-4 border border-gray-100 dark:border-gray-700">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1">Tidak ada properti</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pemilik ini belum mendaftarkan properti kos.</p>
                        </div>

                        <div v-else class="space-y-4">
                            <div v-for="kos in owner.boarding_houses" :key="kos.id"
                                class="group relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:border-primary-300 dark:hover:border-primary-700 hover:shadow-md transition-all duration-300 flex flex-col justify-between">

                                <div class="flex items-start justify-between">
                                    <div class="pr-8">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                            {{ kos.name }}
                                        </h4>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-start gap-1.5 mt-2">
                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="leading-relaxed">{{ kos.address }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-2">
                                        <div class="bg-primary-50 dark:bg-primary-900/30 px-3 py-1.5 rounded-lg border border-primary-100 dark:border-primary-800">
                                            <span class="text-xs font-semibold text-primary-600 dark:text-primary-400 uppercase tracking-wider">Total Kamar:</span>
                                            <span class="text-sm font-bold text-primary-700 dark:text-primary-300 ml-1.5">{{ kos.rooms?.length || 0 }}</span>
                                        </div>
                                    </div>

                                    <Link :href="route('boarding-houses.show', kos.id)"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/20 dark:hover:bg-primary-900/40 rounded-lg transition-colors">
                                        Lihat Detail Properti
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    owner: {
        type: Object,
        required: true,
    },
});
</script>
