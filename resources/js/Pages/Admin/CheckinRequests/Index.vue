<template>
    <Head title="Permintaan Check-in" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Permintaan Check-in</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Kelola permintaan check-in dari penyewa yang perlu diverifikasi.
                </p>
            </div>
        </div>

        <div class="h-full flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <!-- Filter & Search Bar -->
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <SearchIcon class="w-5 h-5 text-gray-400" />
                        </div>
                        <input v-model="search" type="text" placeholder="Cari nama penyewa..."
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 items-center w-full sm:w-auto">
                    <button v-if="search" @click="resetFilters"
                        class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-750">
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="overflow-x-auto pb-8 flex-1" data-simplebar>
                <div v-if="requests.data && requests.data.length > 0">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-16">
                                    No
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Penyewa
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Lokasi Kamar
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Periode
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Foto
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                            <tr v-for="(request, index) in requests.data" :key="request.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-5 text-sm font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ (requests.current_page - 1) * requests.per_page + index + 1 }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ request.user_name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ request.user_phone }}
                                        </div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500">
                                            Diajukan: {{ request.submitted_at }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 w-fit">
                                            {{ request.boarding_house_name }}
                                        </span>
                                        <span class="text-sm font-bold text-primary-600 dark:text-primary-400">
                                            Kamar {{ request.room_name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                        <span class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">
                                            {{ request.start_date }}
                                        </span>
                                        <span class="text-gray-400">&rarr;</span>
                                        <span class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">
                                            {{ request.end_date }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div v-if="request.foto_kamar" @click="openImageModal(request.foto_kamar)"
                                        class="w-16 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden cursor-pointer border border-gray-200 dark:border-gray-700 flex-shrink-0 relative group">
                                        <img :src="request.foto_kamar" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" alt="Preview"/>
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </div>
                                    </div>
                                    <div v-else class="text-xs text-gray-400 italic">Tidak ada foto</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="approveRequest(request.id)" :disabled="processing"
                                            class="p-2.5 text-green-600 bg-green-50 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 rounded-xl disabled:opacity-50 transition-all shadow-sm"
                                            title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button @click="openRejectModal(request)" :disabled="processing"
                                            class="p-2.5 text-primary-600 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:text-primary-400 dark:hover:bg-primary-900/50 rounded-xl disabled:opacity-50 transition-all shadow-sm"
                                            title="Tolak">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tidak ada permintaan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                        {{ search ? 'Tidak ada data yang cocok dengan filter pencarian.' : 'Belum ada permintaan check-in baru yang perlu diverifikasi saat ini.' }}
                    </p>
                    <button v-if="search" @click="resetFilters"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all">
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="requests.data && requests.data.length > 0" class="border-t border-gray-100 dark:border-gray-700 p-4">
                <Pagination :pagination="{
                    ...requests,
                    prev_page_url: requests.prev_page_url,
                    next_page_url: requests.next_page_url,
                }" />
            </div>

            <!-- Image Modal -->
            <div v-if="showImageModal"
                class="fixed inset-0 z-50 overflow-y-auto bg-black/90 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity duration-300"
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

            <!-- Reject Modal -->
            <div v-if="showRejectModal"
                class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-md w-full p-8 transform transition-all scale-100">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary-100 dark:bg-primary-900/30 mb-6 border border-primary-200 dark:border-primary-800/50">
                        <svg class="h-8 w-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">
                        Tolak Permintaan Check-in
                    </h3>
                    <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                        Anda akan menolak permintaan dari <span class="font-bold text-gray-900 dark:text-white">{{ selectedRequest?.user_name }}</span>.
                    </p>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Alasan Penolakan (Opsional)
                        </label>
                        <textarea v-model="rejectReason" rows="3"
                            class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all resize-none shadow-inner"
                            placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button @click="closeRejectModal"
                            class="w-full px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white border border-gray-200 dark:border-gray-600 dark:bg-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm active:scale-95">
                            Batal
                        </button>
                        <button @click="confirmReject" :disabled="processing"
                            class="w-full px-4 py-3 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 disabled:opacity-50 shadow-lg shadow-primary-600/30 transition-all active:scale-95">
                            {{ processing ? 'Memproses...' : 'Tolak' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router, Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/common/Pagination.vue';
import SearchIcon from '@/Components/icons/SearchIcon.vue';
import debounce from 'lodash/debounce';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    requests: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const processing = ref(false);
const showImageModal = ref(false);
const selectedImage = ref(null);
const showRejectModal = ref(false);
const selectedRequest = ref(null);
const rejectReason = ref('');

watch(search, debounce((newSearch) => {
    fetchRequests();
}, 300));

function fetchRequests() {
    router.get(
        route('admin.checkin-requests.index'),
        { search: search.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}

const resetFilters = () => {
    search.value = '';
};

const openImageModal = (imageUrl) => {
    selectedImage.value = imageUrl;
    showImageModal.value = true;
};

const approveRequest = (id) => {
    if (confirm('Apakah Anda yakin ingin menyetujui permintaan check-in ini?')) {
        processing.value = true;
        router.post(route('admin.checkin-requests.approve', id), {}, {
            preserveScroll: true,
            onFinish: () => { processing.value = false; },
        });
    }
};

const openRejectModal = (request) => {
    selectedRequest.value = request;
    rejectReason.value = '';
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
    selectedRequest.value = null;
    rejectReason.value = '';
};

const confirmReject = () => {
    processing.value = true;
    router.post(route('admin.checkin-requests.reject', selectedRequest.value.id), {
        reason: rejectReason.value,
    }, {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
            closeRejectModal();
        },
    });
};
</script>
