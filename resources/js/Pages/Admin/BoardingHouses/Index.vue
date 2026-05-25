<template>

    <Head title="Daftar Boarding House" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Daftar Boarding House</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Kelola data boarding house dan properti kos anda
                </p>
            </div>
        </div>

        <div
            class="h-full flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <!-- Filter & Search Bar -->
            <div
                class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-2">
                    <select v-model="clusterFilter"
                        class="w-full sm:w-44 px-3 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500">
                        <option value="">Semua Cluster</option>
                        <option v-for="cluster in clusters" :key="cluster.id" :value="cluster.id">
                            {{ cluster.name }}
                        </option>
                    </select>

                    <select v-model="genderFilter"
                        class="w-full sm:w-40 px-3 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500">
                        <option value="">Semua Tipe</option>
                        <option value="L">Putra</option>
                        <option value="P">Putri</option>
                        <option value="C">Campur</option>
                    </select>

                    <div class="relative w-full sm:w-64">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <SearchIcon class="w-5 h-5 text-gray-400" />
                        </div>
                        <input v-model="search" type="text" placeholder="Cari boarding house..."
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500" />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 items-center w-full sm:w-auto">
                    <Link v-if="can('boarding_houses.create')" :href="route('boarding-houses.create', { cluster_id: clusterFilter })"
                        class="inline-flex gap-2 items-center px-5 py-2.5 text-white rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transition-all duration-300 transform hover:-translate-y-0.5">
                        <PlusSquareIcon class="w-5 h-5" />
                        <span class="font-medium">Tambah Boarding House</span>
                    </Link>
                </div>
            </div>

            <!-- Content Area -->
            <div class="overflow-x-auto pb-8 flex-1" data-simplebar>
                <div v-if="boardingHouses.data && boardingHouses.data.length > 0">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-16">
                                    No
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Nomor Kos</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Cluster</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Informasi</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                            <tr v-for="(boardingHouse, index) in boardingHouses.data" :key="boardingHouse.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-5 text-sm font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ (boardingHouses.current_page - 1) * boardingHouses.per_page + index + 1 }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="relative w-14 h-14 rounded-xl bg-gray-100 dark:bg-gray-800 overflow-hidden shrink-0 border border-gray-200 dark:border-gray-700 shadow-sm">
                                            <img v-if="boardingHouse.thumbnail"
                                                :src="`/storage/${boardingHouse.thumbnail}`" :alt="boardingHouse.name"
                                                class="w-full h-full object-cover" />
                                            <HomeIcon v-else
                                                class="w-6 h-6 text-gray-400 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" />
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">
                                                {{ boardingHouse.name }}
                                            </h3>
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <div v-if="boardingHouse.gender === 'L'"
                                                    class="inline-flex items-center gap-1 bg-primary-600/10 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 px-2 py-0.5 rounded-md text-[10px] font-bold border border-primary-200 dark:border-primary-800">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="4" r="2.5" />
                                                        <path d="M17,11c0-1.657-1.343-3-3-3H10c-1.657,0-3,1.343-3,3v5h2v6h2v-4h2v4h2v-6h2V11z" />
                                                    </svg>
                                                    PUTRA
                                                </div>
                                                <div v-else-if="boardingHouse.gender === 'P'"
                                                    class="inline-flex items-center gap-1 bg-primary-600/10 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 px-2 py-0.5 rounded-md text-[10px] font-bold border border-primary-200 dark:border-primary-800">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="3.5" r="2" />
                                                        <path d="M17.5,13.5L15,8c-0.2-0.5-0.7-1-1.3-1h-3.4C9.7,7,9.2,7.5,9,8l-2.5,5.5C6.3,13.8,6.5,14.3,7,14.3h2V18c0,0.6,0.4,1,1,1h1v3h2v-3h1c0.6,0,1-0.4,1-1v-3.7h2C17.5,14.3,17.7,13.8,17.5,13.5z" />
                                                    </svg>
                                                    PUTRI
                                                </div>
                                                <div v-else-if="boardingHouse.gender === 'C'"
                                                    class="inline-flex items-center gap-1 bg-primary-600/10 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 px-2 py-0.5 rounded-md text-[10px] font-bold border border-primary-200 dark:border-primary-800">
                                                    <div class="flex items-center -space-x-1">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="4" r="2.5" />
                                                            <path d="M17,11c0-1.657-1.343-3-3-3H10c-1.657,0-3,1.343-3,3v5h2v6h2v-4h2v4h2v-6h2V11z" />
                                                        </svg>
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="3.5" r="2" />
                                                            <path d="M17.5,13.5L15,8c-0.2-0.5-0.7-1-1.3-1h-3.4C9.7,7,9.2,7.5,9,8l-2.5,5.5C6.3,13.8,6.5,14.3,7,14.3h2V18c0,0.6,0.4,1,1,1h1v3h2v-3h1c0.6,0,1-0.4,1-1v-3.7h2C17.5,14.3,17.7,13.8,17.5,13.5z" />
                                                        </svg>
                                                    </div>
                                                    CAMPUR
                                                </div>
                                            </div>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400 line-clamp-1 max-w-[200px]"
                                                :title="boardingHouse.address">
                                                {{ boardingHouse.address || 'Alamat belum diatur' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                        {{ boardingHouse.cluster ? boardingHouse.cluster.name : 'Non-Cluster' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex flex-col gap-1 text-sm">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{
                                            boardingHouse.rooms_count }} Kamar</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Total kapasitas</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span :class="[
                                        'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border',
                                        boardingHouse.rooms_available_count > 0
                                            ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800'
                                            : 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800',
                                    ]">
                                        <span class="w-1.5 h-1.5 rounded-full mr-2" :class="[
                                            boardingHouse.rooms_available_count > 0 ? 'bg-green-500' : 'bg-red-500'
                                        ]"></span>
                                        {{ boardingHouse.rooms_available_count > 0 ? 'Tersedia' : 'Kosong' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link :href="route('boarding-houses.show', boardingHouse.id)"
                                            class="p-2.5 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-xl transition-all shadow-sm"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link v-if="can('boarding_houses.edit')"
                                            :href="route('boarding-houses.edit', boardingHouse.id)"
                                            class="p-2.5 text-yellow-600 bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50 rounded-xl transition-all shadow-sm"
                                            title="Edit Data">
                                            <EditIcon class="w-4 h-4" />
                                        </Link>
                                        <button v-if="can('boarding_houses.delete')"
                                            @click="openConfirmModal(boardingHouse)"
                                            class="p-2.5 text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-xl transition-all shadow-sm"
                                            title="Hapus Data">
                                            <TrashIcon class="w-4 h-4" />
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
                        <HomeIcon class="w-10 h-10 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada boarding house</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                        Belum ada data boarding house yang ditemukan. Silakan tambahkan boarding house baru.
                    </p>
                    <Link v-if="can('boarding_houses.create')" :href="route('boarding-houses.create')"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all">
                        <PlusSquareIcon class="w-5 h-5 mr-2" />
                        Tambah Sekarang
                    </Link>
                </div>
            </div>

            <div v-if="boardingHouses.data && boardingHouses.data.length > 0" class="border-t border-gray-100 dark:border-gray-700 p-4">
                <Pagination :pagination="boardingHouses" />
            </div>

            <ConfirmModal :show="isConfirmModalOpen" :question="`Yakin ingin menghapus`"
                :selected="`${selectedItem?.name}`" title="Hapus Boarding House" confirmText="Ya, Hapus!" maxWidth="md"
                @close="closeConfirmModal" @confirm="destroyData" />
        </div>

    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PlusSquareIcon from "@/Components/icons/PlusSquareIcon.vue";
import SearchIcon from "@/Components/icons/SearchIcon.vue";
import EditIcon from "@/Components/icons/EditIcon.vue";
import TrashIcon from "@/Components/icons/TrashIcon.vue";
import HomeIcon from "@/Components/icons/HomeIcon.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import ConfirmModal from "@/Components/common/ConfirmModal.vue";
import Pagination from "@/Components/common/Pagination.vue";
import { useAuth } from "@/Composables/useAuth";
import { ref, watch } from "vue";
import { router, Head, Link } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    boardingHouses: Object,
    clusters: Array,
    owners: Array,
    search: String,
    cluster_id: String,
    gender: String,
});

const { can } = useAuth();

const breadcrumbs = [{ label: "Properti" }, { label: "Boarding House" }];

const search = ref(props.search || "");
const clusterFilter = ref(props.cluster_id || "");
const genderFilter = ref(props.gender || "");

let timeout = null;
watch(search, () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        fetchBoardingHouses();
    }, 400);
});

watch(clusterFilter, () => {
    fetchBoardingHouses();
});

watch(genderFilter, () => {
    fetchBoardingHouses();
});

function fetchBoardingHouses() {
    const urlParams = new URLSearchParams(window.location.search);
    const perPage = urlParams.get('per_page') || 10;

    router.get(
        route("boarding-houses.index"),
        {
            search: search.value,
            cluster_id: clusterFilter.value,
            gender: genderFilter.value,
            per_page: perPage
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }
    );
}



const selectedItem = ref(null);
const isConfirmModalOpen = ref(false);
const openConfirmModal = (item) => {
    if (!can("boarding_houses.delete")) {
        return;
    }
    selectedItem.value = item;
    isConfirmModalOpen.value = true;
};
const closeConfirmModal = () => {
    selectedItem.value = null;
    isConfirmModalOpen.value = false;
};
const destroyData = () => {
    if (!can("boarding_houses.delete")) {
        return;
    }
    router.delete(route("boarding-houses.destroy", selectedItem.value.id), {
        onSuccess: () => {
            closeConfirmModal();
        },
        preserveScroll: true,
    });
};
</script>
