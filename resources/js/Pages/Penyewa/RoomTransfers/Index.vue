<template>

    <Head title="Riwayat Pindah Kamar" />

    <div class="min-h-screen bg-[#F8FAFC] dark:bg-[#0F172A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
            <!-- Header -->
            <!-- Header -->
            <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Riwayat Pindah Kamar</h1>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                        Pantau status pengajuan pindah kamar Anda
                    </p>
                </div>
                <!-- Action Button -->
                <Link :href="route('penyewa.transfers.create')"
                    class="w-full sm:w-auto inline-flex items-center justify-center py-3 px-6 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all font-bold text-base group">
                    <svg class="w-5 h-5 mr-2 -ml-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Ajukan Pindah</span>
                </Link>
            </div>

            <!-- Content -->
            <div
                :class="[
                    'bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden',
                    transfers.length === 0 ? 'min-h-[60vh] flex flex-col items-center justify-center' : ''
                ]">
                <div v-if="transfers.length === 0" class="p-8 sm:p-16 text-center">
                    <div
                        class="w-20 h-20 bg-slate-50 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Belum ada riwayat</h3>
                    <p class="text-slate-500 dark:text-slate-400">Anda belum pernah mengajukan pindah kamar.</p>
                </div>

                <div v-else class="grid gap-8">
                    <div v-for="transfer in transfers" :key="transfer.id"
                        class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 sm:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200 dark:border-slate-700 relative overflow-hidden group transition-all hover:scale-[1.01]">

                        <!-- Status Badge & Date Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-primary-50 dark:bg-primary-900/20 rounded-2xl">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-900 dark:text-white">Pengajuan Pindah</h3>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                        ID: #{{ transfer.id.toString().padStart(6, '0') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span v-if="transfer.status === 'pending'"
                                    class="inline-flex items-center px-4 py-2 rounded-2xl text-xs font-black bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800 uppercase tracking-widest">
                                    <span class="w-2 h-2 bg-amber-500 rounded-full mr-2 animate-pulse"></span>
                                    Menunggu Persetujuan
                                </span>
                                <span v-else-if="transfer.status === 'approved'"
                                    class="inline-flex items-center px-4 py-2 rounded-2xl text-xs font-black bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800 uppercase tracking-widest">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    Disetujui
                                </span>
                                <span v-else-if="transfer.status === 'rejected'"
                                    class="inline-flex items-center px-4 py-2 rounded-2xl text-xs font-black bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 uppercase tracking-widest">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    Ditolak
                                </span>
                            </div>
                        </div>

                        <!-- Transfer Details Route -->
                        <div
                            class="bg-slate-50 dark:bg-slate-900/50 rounded-[2rem] p-6 sm:p-8 border border-slate-100 dark:border-slate-800 mb-8">
                            <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative">
                                <!-- Source Room -->
                                <div class="w-full md:w-5/12 text-center md:text-left">
                                    <p
                                        class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">
                                        Kamar Saat Ini</p>
                                    <h4 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-1">
                                        {{ transfer.user_room?.room?.name || 'Kamar Tidak Diketahui' }}
                                    </h4>
                                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">
                                        {{ transfer.user_room?.boarding_house?.name || 'Standard' }} Room
                                    </p>
                                </div>

                                <!-- Arrow Indicator -->
                                <div class="flex flex-col items-center justify-center relative z-10">
                                    <div
                                        class="w-12 h-12 bg-white dark:bg-slate-800 rounded-full shadow-lg border-4 border-slate-50 dark:border-slate-900 flex items-center justify-center transform group-hover:scale-110 transition-transform duration-500">
                                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Destination Room -->
                                <div class="w-full md:w-5/12 text-center md:text-right">
                                    <p
                                        class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">
                                        Kamar Tujuan</p>
                                    <h4
                                        class="text-xl sm:text-2xl font-black text-primary-600 dark:text-primary-400 mb-1">
                                        Kamar {{ transfer.room?.name || '-' }}
                                    </h4>
                                    <p class="text-sm font-bold text-slate-500 dark:text-slate-400">
                                        {{ transfer.room?.boarding_house?.name || 'Hunian Baru' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Plan Date -->
                            <div
                                class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <div
                                    class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">
                                        Tanggal Rencana</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{
                                        formatDate(transfer.plan_date) }}</p>
                                </div>
                            </div>

                            <!-- Submitted Date -->
                            <div
                                class="flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <div
                                    class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">
                                        Diajukan Pada</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{
                                        formatDate(transfer.created_at) }}</p>
                                </div>
                            </div>

                            <!-- Reason -->
                            <div
                                class="sm:col-span-2 lg:col-span-1 flex items-start gap-4 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <div
                                    class="w-10 h-10 bg-pink-50 dark:bg-pink-900/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">
                                        Alasan Pindah</p>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 italic">"{{
                                        transfer.reason }}"</p>
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
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

defineProps({
    transfers: Array,
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '-';
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>
