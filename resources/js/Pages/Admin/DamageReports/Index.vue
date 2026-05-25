<template>
    <Head title="Laporan Kerusakan" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Laporan Kerusakan</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Kelola dan tindak lanjuti laporan kerusakan dari penyewa
                </p>
            </div>
        </div>

        <div class="h-full flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <!-- Filter & Search Bar -->
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select v-model="status"
                        class="w-full sm:w-40 px-3 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500">
                        <option value="all">Semua Status</option>
                        <option value="pending">Menunggu</option>
                        <option value="in_progress">Sedang Diproses</option>
                        <option value="resolved">Selesai</option>
                        <option value="rejected">Ditolak</option>
                    </select>

                    <div class="relative w-full sm:w-64">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <SearchIcon class="w-5 h-5 text-gray-400" />
                        </div>
                        <input v-model="search" type="text" placeholder="Cari nama pelapor..."
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 items-center w-full sm:w-auto">
                    <button v-if="search || (status && status !== 'all')" @click="resetFilters"
                        class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-750">
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="overflow-x-auto pb-8 flex-1" data-simplebar>
                <div v-if="reports.data && reports.data.length > 0">
                    <table class="w-full text-left border-collapse min-w-[1100px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-16">
                                    No
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Pelapor
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap border-l border-gray-100 dark:border-gray-700">
                                    Detail Laporan
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-32">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-24">
                                    Foto
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right w-40">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                            <tr v-for="(report, index) in reports.data" :key="report.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-5 text-sm font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ (reports.meta?.current_page ? (reports.meta.current_page - 1) * reports.meta.per_page : 0) + index + 1 }}
                                </td>
                                <td class="px-6 py-5 min-w-[200px]">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-sm font-bold text-primary-600 dark:text-primary-400 shrink-0 border border-primary-200 dark:border-primary-800/50">
                                            {{ report.user?.name?.charAt(0) || 'U' }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1">
                                                {{ report.user?.name || 'Anonim' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 whitespace-nowrap flex items-center gap-1">
                                                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ report.created_at_formatted }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 border-l border-gray-100 dark:border-gray-800">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white mb-1 line-clamp-1" :title="report.title">
                                        {{ report.title }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed" :title="report.description">
                                        {{ report.description }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span :class="[
                                        'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border',
                                        getStatusClass(report.status)
                                    ]">
                                        <span class="w-1.5 h-1.5 rounded-full mr-2" :class="[
                                            report.status === 'pending' ? 'bg-yellow-500' :
                                            report.status === 'in_progress' ? 'bg-blue-500' :
                                            report.status === 'resolved' ? 'bg-green-500' : 'bg-red-500'
                                        ]"></span>
                                        {{ report.status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div v-if="report.photo" @click="openImageModal(report.photo)"
                                        class="w-16 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden cursor-pointer border border-gray-200 dark:border-gray-700 flex-shrink-0 relative group">
                                        <img :src="report.photo" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" alt="Preview"/>
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </div>
                                    </div>
                                    <div v-else class="text-xs text-gray-400 italic">Tidak ada foto</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link :href="route('admin.damage-reports.show', report.id)"
                                            class="p-2.5 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-xl transition-all shadow-sm"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>

                                        <button v-if="report.status === 'pending'" @click="updateStatus(report.id, 'in_progress')" :disabled="processing"
                                            class="p-2.5 text-yellow-600 bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50 rounded-xl transition-all shadow-sm"
                                            title="Tandai Sedang Diproses">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                            </svg>
                                        </button>

                                        <button v-if="report.status === 'in_progress'" @click="openResolveModal(report)" :disabled="processing"
                                            class="p-2.5 text-green-600 bg-green-50 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 rounded-xl transition-all shadow-sm"
                                            title="Tandai Selesai">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>

                                        <button v-if="report.status === 'pending'" @click="openRejectModal(report)" :disabled="processing"
                                            class="p-2.5 text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-xl disabled:opacity-50 transition-all shadow-sm"
                                            title="Tolak Laporan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-else
                    class="text-center py-20 bg-gray-50/50 dark:bg-gray-800/50 rounded-2xl border-dashed border border-gray-200 dark:border-gray-700 mx-4 my-6">
                    <div
                        class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tidak ada laporan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                        {{ search || status !== 'all' ? 'Tidak ada data yang cocok dengan filter pencarian.' : 'Belum ada laporan kerusakan yang ditemukan.' }}
                    </p>
                    <button v-if="search || status !== 'all'" @click="resetFilters"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all">
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="reports.data && reports.data.length > 0" class="border-t border-gray-100 dark:border-gray-700 p-4">
                <Pagination :pagination="{
                    ...reports.meta,
                    prev_page_url: reports.links?.prev,
                    next_page_url: reports.links?.next,
                }" />
            </div>
        </div>

        <!-- Image Modal -->
        <div v-if="showImageModal"
            class="fixed inset-0 z-[100] overflow-y-auto bg-black/90 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300"
            @click="showImageModal = false">
            <div class="relative max-w-5xl w-full p-2" @click.stop>
                <button @click="showImageModal = false"
                        class="absolute -top-12 right-0 text-white/70 hover:text-white transition-colors p-2 z-10 bg-black/50 rounded-full backdrop-blur-md border border-white/10 pr-2 pb-2 pl-2 pt-2 shadow-xl hover:bg-black/80">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <img :src="selectedImage" alt="Preview" class="w-full h-auto max-h-[85vh] object-contain rounded-2xl shadow-2xl border border-white/10" />
            </div>
        </div>

        <!-- Resolve Modal -->
        <div v-if="showResolveModal"
            class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-md w-full p-8 transform transition-all scale-100">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900/30 mb-6 border border-green-200 dark:border-green-800/50">
                    <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">
                    Tandai Selesai
                </h3>
                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                    Apakah Anda yakin ingin menandai laporan <span class="font-bold">"{{ selectedReport?.title }}"</span> sebagai selesai?
                </p>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Catatan Penyelesaian (Opsional)
                    </label>
                    <textarea v-model="adminNotes" rows="3"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all resize-none shadow-inner"
                        placeholder="Tambahkan catatan untuk penyewa..."></textarea>
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
                <div class="grid grid-cols-2 gap-4">
                    <button @click="closeResolveModal"
                        class="w-full px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white border border-gray-200 dark:border-gray-600 dark:bg-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm active:scale-95">
                        Batal
                    </button>
                    <button @click="confirmResolve" :disabled="resolveForm.processing"
                        class="w-full px-4 py-3 text-sm font-bold text-white bg-green-600 rounded-xl hover:bg-green-700 disabled:opacity-50 shadow-lg shadow-green-600/30 transition-all active:scale-95">
                        {{ resolveForm.processing ? 'Memproses...' : 'Tandai Selesai' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div v-if="showRejectModal"
            class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-md w-full p-8 transform transition-all scale-100">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-6 border border-red-200 dark:border-red-800/50">
                    <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">
                    Tolak Laporan
                </h3>
                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                    Anda akan menolak laporan <span class="font-bold">"{{ selectedReport?.title }}"</span>.
                </p>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea v-model="adminNotes" rows="3"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all resize-none shadow-inner"
                        placeholder="Berikan alasan penolakan..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <button @click="closeRejectModal"
                        class="w-full px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white border border-gray-200 dark:border-gray-600 dark:bg-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm active:scale-95">
                        Batal
                    </button>
                    <button @click="confirmReject" :disabled="processing"
                        class="w-full px-4 py-3 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 disabled:opacity-50 shadow-lg shadow-red-600/30 transition-all active:scale-95">
                        {{ processing ? 'Memproses...' : 'Tolak Laporan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router, Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/common/Pagination.vue';
import SearchIcon from '@/Components/icons/SearchIcon.vue';
import debounce from 'lodash/debounce';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    reports: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || 'all');
const processing = ref(false);
const showImageModal = ref(false);
const selectedImage = ref(null);
const showResolveModal = ref(false);
const showRejectModal = ref(false);
const selectedReport = ref(null);
const adminNotes = ref('');
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

watch([search, status], debounce(([newSearch, newStatus]) => {
    fetchReports();
}, 300));

function fetchReports() {
    router.get(
        route('admin.damage-reports.index'),
        { search: search.value, status: status.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}

const resetFilters = () => {
    search.value = '';
    status.value = 'all';
};

const getStatusClass = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-50 text-yellow-700 border border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800';
        case 'in_progress': return 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800';
        case 'resolved': return 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800';
        case 'rejected': return 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800';
        default: return 'bg-gray-50 text-gray-700 border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700';
    }
};

const openImageModal = (imageUrl) => {
    selectedImage.value = imageUrl;
    showImageModal.value = true;
};

const updateStatus = (id, newStatus) => {
    processing.value = true;
    router.put(route('admin.damage-reports.update', id), {
        status: newStatus,
    }, {
        preserveScroll: true,
        onFinish: () => { processing.value = false; },
    });
};

const openResolveModal = (report) => {
    selectedReport.value = report;
    resolveForm.admin_notes = report.admin_notes || '';
    resolveForm.repair_cost = null;
    resolveForm.repair_proof = null;
    displayRepairCost.value = '';
    showResolveModal.value = true;
};

const closeResolveModal = () => {
    showResolveModal.value = false;
    selectedReport.value = null;
    resolveForm.reset();
};

const confirmResolve = () => {
    resolveForm.post(route('admin.damage-reports.update', selectedReport.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeResolveModal();
        },
    });
};

const openRejectModal = (report) => {
    selectedReport.value = report;
    adminNotes.value = report.admin_notes || '';
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
    selectedReport.value = null;
    adminNotes.value = '';
};

const confirmReject = () => {
    processing.value = true;
    router.put(route('admin.damage-reports.update', selectedReport.value.id), {
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
