<template>
    <Head title="Permintaan Pindah Kamar" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Permintaan Pindah Kamar</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Kelola permintaan pindah kamar dari penyewa
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
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>

                    <div class="relative w-full sm:w-64">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <SearchIcon class="w-5 h-5 text-gray-400" />
                        </div>
                        <input v-model="search" type="text" placeholder="Cari nama penyewa..."
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
                <div v-if="transfers.data && transfers.data.length > 0">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-16">
                                    No
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-40">
                                    ID / Tanggal
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Penyewa
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Dari
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Ke
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Rencana Pindah
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                            <tr v-for="(transfer, index) in transfers.data" :key="transfer.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-5 text-sm font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ (transfers.meta.current_page - 1) * transfers.meta.per_page + index + 1 }}
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        #{{ transfer.id }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ transfer.created_at }}
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-sm font-bold text-primary-600 dark:text-primary-400 shrink-0">
                                            {{ transfer.tenant_name.charAt(0) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ transfer.tenant_name }}
                                            </div>
                                            <div v-if="transfer.reason"
                                                class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 max-w-[200px]"
                                                :title="transfer.reason">
                                                "{{ transfer.reason }}"
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                        {{ transfer.current_room_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                        {{ transfer.destination_room_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ transfer.plan_date }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span :class="[
                                        'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border',
                                        transfer.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800' :
                                        transfer.status === 'approved' ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800' :
                                        'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800'
                                    ]">
                                        <span class="w-1.5 h-1.5 rounded-full mr-2" :class="[
                                            transfer.status === 'pending' ? 'bg-amber-500' :
                                            transfer.status === 'approved' ? 'bg-green-500' :
                                            'bg-red-500'
                                        ]"></span>
                                        {{
                                            transfer.status === 'pending' ? 'Menunggu' :
                                            transfer.status === 'approved' ? 'Disetujui' : 'Ditolak'
                                        }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link :href="route('admin.room-transfers.show', transfer.id)"
                                            class="p-2.5 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-xl transition-all shadow-sm"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <template v-if="transfer.status === 'pending'">
                                            <button @click="approveTransfer(transfer.id)" :disabled="processing"
                                                class="p-2.5 text-green-600 bg-green-50 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 rounded-xl disabled:opacity-50 transition-all shadow-sm"
                                                title="Setujui">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                            <button @click="openRejectModal(transfer)" :disabled="processing"
                                                class="p-2.5 text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-xl disabled:opacity-50 transition-all shadow-sm"
                                                title="Tolak">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </template>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tidak ada permintaan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                        {{ search || status !== 'all' ? 'Tidak ada data yang cocok dengan filter pencarian.' : 'Belum ada permintaan pindah kamar baru.' }}
                    </p>
                    <button v-if="search || status !== 'all'" @click="resetFilters"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all">
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="transfers.data && transfers.data.length > 0" class="border-t border-gray-100 dark:border-gray-700 p-4">
                <Pagination :pagination="{
                    ...transfers.meta,
                    prev_page_url: transfers.links?.prev,
                    next_page_url: transfers.links?.next,
                }" />
            </div>

            <!-- Reject Modal -->
            <div v-if="showRejectModal"
                class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900/50 flex items-center justify-center p-4 backdrop-blur-sm">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6 transform transition-all">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                        Tolak Permintaan Pindah
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Anda akan menolak permintaan pindah kamar dari <span
                            class="font-bold text-gray-900 dark:text-white">{{
                                selectedTransfer?.tenant_name
                            }}</span>.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button @click="closeRejectModal"
                            class="px-4 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <button @click="confirmReject" :disabled="processing"
                            class="px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 disabled:opacity-50 shadow-lg shadow-red-500/30 transition-all">
                            {{ processing ? 'Memproses...' : 'Tolak Permintaan' }}
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
    transfers: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || 'all');
const processing = ref(false);
const showRejectModal = ref(false);
const selectedTransfer = ref(null);

watch([search, status], debounce(([newSearch, newStatus]) => {
    fetchTransfers();
}, 300));

function fetchTransfers() {
    router.get(
        route('admin.room-transfers.index'),
        { search: search.value, status: status.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}

const resetFilters = () => {
    search.value = '';
    status.value = 'all';
};

const approveTransfer = (id) => {
    if (confirm('Apakah Anda yakin ingin menyetujui perpindahan kamar ini? Penyewa akan dipindahkan ke kamar tujuan.')) {
        processing.value = true;
        router.post(route('admin.room-transfers.action', id), {
            action: 'approve'
        }, {
            preserveScroll: true,
            onFinish: () => { processing.value = false; },
        });
    }
};

const openRejectModal = (transfer) => {
    selectedTransfer.value = transfer;
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
    selectedTransfer.value = null;
};

const confirmReject = () => {
    processing.value = true;
    router.post(route('admin.room-transfers.action', selectedTransfer.value.id), {
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
