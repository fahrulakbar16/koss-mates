<template>
    <Head title="Laporan Keuangan" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Laporan Keuangan</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Ringkasan pemasukan dan pengeluaran tiap Boarding House
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
                        <input v-model="search" type="text" placeholder="Cari boarding house..."
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500" />
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <input v-model="startDateFilter" type="date" class="h-9 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white text-sm focus:border-primary-500 focus:ring-primary-500 w-36" />
                        <span class="text-gray-400 text-sm">—</span>
                        <input v-model="endDateFilter" type="date" class="h-9 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white text-sm focus:border-primary-500 focus:ring-primary-500 w-36" />
                        <button v-if="startDateFilter || endDateFilter" @click="startDateFilter = ''; endDateFilter = '';" class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="overflow-x-auto pb-8 flex-1" data-simplebar>
                <div v-if="boardingHouses.data && boardingHouses.data.length > 0">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-center w-16">
                                    No
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Boarding House
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Cluster
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right">
                                    Pemasukan
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right">
                                    Pengeluaran
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                            <tr v-for="(item, index) in boardingHouses.data" :key="item.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-5 whitespace-nowrap text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ (boardingHouses.meta?.current_page - 1) * boardingHouses.meta?.per_page + index + 1 || index + 1 }}
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 shrink-0 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                            <img v-if="item.thumbnail" :src="`/storage/${item.thumbnail}`"
                                                class="h-full w-full object-cover" alt="Thumbnail">
                                            <HomeIcon v-else class="h-6 w-6 text-gray-400 dark:text-gray-500" />
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                                {{ item.name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                                {{ item.address }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ item.cluster_name }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        {{ formatCurrency(item.total_income) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-bold bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">
                                        {{ formatCurrency(item.total_expense) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('admin.financial-reports.recap', { id: item.id, start_date: startDateFilter || undefined, end_date: endDateFilter || undefined })"
                                            class="p-2.5 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-xl transition-all shadow-sm flex items-center gap-2"
                                            title="Lihat Rekap">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span class="font-bold text-xs hidden sm:inline-block">Rekap</span>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-else
                    class="text-center py-20 bg-gray-50/50 dark:bg-gray-800/50 rounded-2xl border-dashed border border-gray-200 dark:border-gray-700 mx-4 my-6">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <SearchIcon class="h-10 w-10 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tidak ada data ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                        Coba sesuaikan filter bulan, tahun, cluster, atau cari dengan kata kunci lain.
                    </p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="boardingHouses.meta && boardingHouses.data.length > 0" class="border-t border-gray-100 dark:border-gray-700 p-4">
                <Pagination :pagination="normalizedPagination" />
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import Pagination from "@/Components/common/Pagination.vue";
import SearchIcon from "@/Components/icons/SearchIcon.vue";
import HomeIcon from "@/Components/icons/HomeIcon.vue";
import { ref, watch, computed } from "vue";
import { router, Head, Link } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    boardingHouses: Object,
    filters: Object,
    clusters: Array,
});

const normalizedPagination = computed(() => {
    if (!props.boardingHouses.meta) return props.boardingHouses;

    return {
        ...props.boardingHouses.meta,
        prev_page_url: props.boardingHouses.links.prev,
        next_page_url: props.boardingHouses.links.next,
    };
});

const monthNames = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];

const currentYear = new Date().getFullYear();
const years = Array.from({ length: 5 }, (_, i) => currentYear - i);

const search = ref(props.filters?.search || "");
const clusterFilter = ref(props.filters?.cluster_id || "");
const startDateFilter = ref(props.filters?.start_date || "");
const endDateFilter = ref(props.filters?.end_date || "");

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value || 0);
};

let timeout = null;

const fetchData = () => {
    const params = {
        search: search.value,
        cluster_id: clusterFilter.value,
    };
    if (startDateFilter.value && endDateFilter.value) {
        params.start_date = startDateFilter.value;
        params.end_date = endDateFilter.value;
    }
    router.get(
        route("admin.financial-reports.index"),
        params,
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }
    );
};

watch(search, () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        fetchData();
    }, 400);
});

watch([clusterFilter, startDateFilter, endDateFilter], () => {
    fetchData();
});
</script>
