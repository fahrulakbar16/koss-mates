<template>
    <Head title="Detail Laporan Kerusakan" />

    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8">
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-0">
                            Detail Laporan #{{ report.id }}
                        </h1>
                        <span :class="[
                            'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider',
                            getStatusClass(report.status)
                        ]">
                            {{ report.status_label }}
                        </span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 ml-4 sm:ml-4">
                    Dibuat pada {{ report.created_at_formatted }}
                </p>
            </div>

            <Link :href="route('admin.damage-reports.index')"
                class="inline-flex gap-2 items-center px-4 py-2 text-sm text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="font-medium">Kembali</span>
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Photo & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Photo Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="aspect-[4/3] w-full relative group cursor-pointer bg-gray-50 dark:bg-gray-800/50"
                        @click="report.photo && openImageModal(report.photo)">
                        <img v-if="report.photo" :src="report.photo" alt="Foto Kerusakan"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div v-if="report.photo"
                            class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <svg class="w-10 h-10 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                        <div v-else
                            class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-6">
                            <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-sm mb-4 border border-gray-100 dark:border-gray-700">
                                <svg class="w-8 h-8 text-gray-400 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-500">Tidak ada foto disertakan</span>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Aksi Laporan
                    </h3>
                    <div class="space-y-3">
                        <button v-if="report.status === 'pending'" @click="updateStatus('in_progress')"
                            :disabled="processing"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm hover:shadow-blue-600/20 hover:shadow-md transition-all active:scale-[0.98] disabled:opacity-50 disabled:scale-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Mulai Proses Perbaikan
                        </button>

                        <button v-if="report.status === 'in_progress'" @click="openResolveModal"
                            :disabled="processing"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-xl shadow-sm hover:shadow-green-600/20 hover:shadow-md transition-all active:scale-[0.98] disabled:opacity-50 disabled:scale-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Tandai Masalah Selesai
                        </button>

                        <button v-if="report.status !== 'rejected' && report.status !== 'resolved'"
                            @click="openRejectModal" :disabled="processing"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-red-600 bg-white border-2 border-red-50 hover:bg-red-50 hover:border-red-100 dark:bg-gray-800 dark:text-red-400 dark:border-red-900/30 dark:hover:bg-red-900/20 rounded-xl transition-all active:scale-[0.98] disabled:opacity-50 disabled:scale-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Tolak Laporan
                        </button>

                        <div v-if="report.status === 'resolved' || report.status === 'rejected'"
                            class="text-center p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                Status laporan ini sudah <span class="font-bold uppercase tracking-wide px-1" :class="report.status === 'resolved' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">{{ report.status_label }}</span>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden h-full flex flex-col">
                    <div class="p-6 sm:p-8 flex-1">
                        <div class="mb-8">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white leading-tight">
                                {{ report.title }}
                            </h2>
                        </div>

                        <!-- Reporter Info -->
                        <div class="bg-gray-50/50 dark:bg-gray-800/50 rounded-xl p-5 sm:p-6 border border-gray-100 dark:border-gray-700 mb-8">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-5 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Detail Pelapor & Lokasi
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold border border-primary-100 dark:border-primary-800 shrink-0">
                                        {{ report.user?.name ? report.user.name.charAt(0).toUpperCase() : '?' }}
                                    </div>
                                    <div class="pt-0.5">
                                        <p class="text-xs font-semibold tracking-wider text-gray-500 dark:text-gray-400 uppercase">Nama Penyewa</p>
                                        <p class="text-base font-bold text-gray-900 dark:text-white mt-0.5">{{ report.user?.name || '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 border border-purple-100 dark:border-purple-800 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="pt-0.5">
                                        <p class="text-xs font-semibold tracking-wider text-gray-500 dark:text-gray-400 uppercase">Properti (Kos) & Kamar</p>
                                        <p class="text-base font-bold text-gray-900 dark:text-white mt-0.5 leading-snug">
                                            {{ report.user_room?.room?.boarding_house?.name || '-' }}
                                            <span class="text-gray-400 dark:text-gray-600 mx-1.5">•</span>
                                            <span class="text-primary-600 dark:text-primary-400">{{ report.user_room?.room?.name || '-' }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-8">
                            <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">
                                Penjelasan Keluhan / Masalah
                            </h3>
                            <div class="prose dark:prose-invert max-w-none">
                                <p class="whitespace-pre-wrap text-base text-gray-700 dark:text-gray-300 leading-relaxed">{{ report.description }}</p>
                            </div>
                        </div>

                        <!-- Admin Notes -->
                        <div v-if="report.admin_notes"
                            class="p-5 sm:p-6 bg-amber-50/80 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800/50 mt-auto">
                            <h3 class="flex items-center gap-2 text-xs font-bold text-amber-800 dark:text-amber-500 uppercase tracking-wider mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3" />
                                </svg>
                                Pesan / Catatan Tindakan Admin
                            </h3>
                            <p class="text-amber-900 dark:text-amber-200 font-medium italic whitespace-pre-wrap text-sm leading-relaxed">
                                "{{ report.admin_notes }}"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div v-if="showImageModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/95 backdrop-blur-sm transition-opacity duration-300"
            @click="showImageModal = false">
            <div class="relative max-w-5xl w-full flex flex-col items-center" @click.stop>
                <div class="w-full flex justify-end mb-4">
                    <button class="text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full transition-all p-2"
                        @click="showImageModal = false">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <img :src="selectedImage" alt="Preview Laporan Kerusakan" class="w-full h-auto max-h-[80vh] object-contain rounded-xl shadow-2xl ring-1 ring-white/10" />
            </div>
        </div>

        <!-- Resolve Modal -->
        <div v-if="showResolveModal"
            class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-md w-full p-8 transform transition-all scale-100">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-50 dark:bg-green-900/30 mb-6 border border-green-100 dark:border-green-800">
                    <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">
                    Tandai Masalah Selesai
                </h3>
                <p class="text-center text-sm font-medium text-gray-500 dark:text-gray-400 mb-6 px-4">
                    Anda akan menyelesaikan laporan <br><strong class="text-gray-700 dark:text-gray-300 px-1 py-0.5 rounded bg-gray-100 dark:bg-gray-700 mt-1 inline-block">"{{ report.title }}"</strong>
                </p>

                <div class="mb-8">
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Pesan Singkat Untuk Penyewa (Opsional)
                    </label>
                    <textarea v-model="adminNotes" rows="3"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all resize-none font-medium"
                        placeholder="Cth: Perbaikan pompa sudah dilakukan teknisi..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Biaya Perbaikan (Opsional)
                    </label>
                    <input v-model="displayRepairCost" type="text"
                        @input="handleRepairCostInput"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all shadow-inner"
                        placeholder="Masukkan nominal biaya...">
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Bukti Perbaikan (Opsional)
                    </label>
                    <input type="file" @input="resolveForm.repair_proof = $event.target.files[0]"
                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-all shadow-inner">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button @click="closeResolveModal"
                        class="w-full px-4 py-3 font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 dark:focus:ring-offset-gray-900">
                        Kembali
                    </button>
                    <button @click="confirmResolve" :disabled="resolveForm.processing"
                        class="w-full px-4 py-3 font-bold text-white bg-green-600 rounded-xl hover:bg-green-700 disabled:opacity-50 shadow-sm hover:shadow-green-600/20 hover:shadow-md transition-all active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-900">
                        {{ resolveForm.processing ? 'Memproses...' : 'Ya, Selesaikan' }}
                    </button>
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
                    Tolak Laporan Masalah
                </h3>
                <p class="text-center text-sm font-medium text-gray-500 dark:text-gray-400 mb-6 px-4">
                    Anda akan menolak laporan keluhan <br><strong class="text-gray-700 dark:text-gray-300 px-1 py-0.5 rounded bg-gray-100 dark:bg-gray-700 mt-1 inline-block">"{{ report.title }}"</strong>
                </p>

                <div class="mb-8">
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Deskripsikan Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea v-model="adminNotes" rows="3"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition-all resize-none font-medium"
                        placeholder="Jelaskan alasan laporan di luar tanggung jawab pengelola..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button @click="closeRejectModal"
                        class="w-full px-4 py-3 font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 dark:focus:ring-offset-gray-900">
                        Batal
                    </button>
                    <button @click="confirmReject" :disabled="processing || !adminNotes.trim()"
                        class="w-full px-4 py-3 font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 disabled:opacity-50 shadow-sm hover:shadow-red-600/20 hover:shadow-md transition-all active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-900">
                        {{ processing ? 'Memproses...' : 'Tolak Laporan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'; // Import computed
import { router, Link, Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    report: Object,
});

// Use computed to make report reactive to prop updates
const report = computed(() => props.report.data);

const processing = ref(false);
const showImageModal = ref(false);
const selectedImage = ref(null);
const showResolveModal = ref(false);
const showRejectModal = ref(false);
// Use .value to access computed property, but initialize with current value
const adminNotes = ref(report.value.admin_notes || '');
const displayRepairCost = ref('');

const formatRupiah = (value) => {
    if (!value) return '';
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

const handleRepairCostInput = (e) => {
    let value = e.target.value.replace(/\./g, '');
    if (isNaN(value)) {
        displayRepairCost.value = displayRepairCost.value.replace(/[^0-9]/g, '');
        return;
    }
    resolveForm.repair_cost = value ? parseInt(value) : null;
    displayRepairCost.value = formatRupiah(value);
};

const resolveForm = useForm({
    status: 'resolved',
    admin_notes: '',
    repair_cost: null,
    repair_proof: null,
    _method: 'PUT'
});

const getStatusClass = (status) => {
    switch (status) {
        case 'pending':
            return 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800';
        case 'in_progress':
            return 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800';
        case 'resolved':
            return 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800';
        case 'rejected':
            return 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800';
        default:
            return 'bg-gray-50 text-gray-700 dark:bg-gray-800/50 dark:text-gray-300 border border-gray-200 dark:border-gray-700';
    }
};

const openImageModal = (imageUrl) => {
    selectedImage.value = imageUrl;
    showImageModal.value = true;
};

const updateStatus = (status) => {
    processing.value = true;
    router.put(route('admin.damage-reports.update', report.value.id), {
        status: status,
    }, {
        preserveScroll: true,
        onFinish: () => { processing.value = false; },
    });
};

const openResolveModal = () => {
    resolveForm.admin_notes = report.value.admin_notes || '';
    resolveForm.repair_cost = null;
    resolveForm.repair_proof = null;
    displayRepairCost.value = '';
    showResolveModal.value = true;
};

const closeResolveModal = () => {
    showResolveModal.value = false;
    resolveForm.reset();
};

const confirmResolve = () => {
    resolveForm.post(route('admin.damage-reports.update', report.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeResolveModal();
        },
    });
};

const openRejectModal = () => {
    adminNotes.value = report.value.admin_notes || '';
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
    adminNotes.value = '';
};

const confirmReject = () => {
    if(!adminNotes.value.trim()) return;
    processing.value = true;
    router.put(route('admin.damage-reports.update', report.value.id), {
        status: 'rejected',
        admin_notes: adminNotes.value,
    }, {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
            closeRejectModal();
        },
    });
};
</script>
