<template>

    <Head title="Daftar Cluster" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Cluster</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data cluster dan properti kos anda</p>
            </div>

            <button v-if="can('clusters.create')" @click="openAddModal" type="button"
                class="inline-flex gap-2 items-center px-5 py-2.5 text-white rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transition-all duration-300 transform hover:-translate-y-0.5">
                <PlusSquareIcon class="w-5 h-5" />
                <span class="font-medium">Tambah Cluster</span>
            </button>
        </div>

        <div
            class="h-full flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <!-- Filter & Search Bar -->
            <div
                class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-2">
                    <Breadcrumb :items="breadcrumbs" class="text-sm" />
                </div>

                <div class="relative w-full sm:w-72">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                        <SearchIcon class="w-5 h-5 text-gray-400" />
                    </div>
                    <input v-model="search" type="text" placeholder="Cari cluster..."
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500" />
                </div>
            </div>
            <div ref="scrollContainer" class="overflow-auto px-4 sm:px-6 lg:px-8 pb-8 flex-1" data-simplebar
                @scroll="handleScroll">
                <div v-if="allClusters.length > 0" class="space-y-8 py-6">
                    <div v-for="cluster in allClusters" :key="cluster.id"
                        class="group/cluster p-6 sm:p-8 rounded-3xl border border-gray-100 bg-white dark:bg-gray-800 dark:border-gray-700 shadow-xl shadow-gray-200/40 dark:shadow-none hover:shadow-2xl hover:shadow-gray-200/60 transition-all duration-300 relative overflow-hidden">
                        <!-- Decorative gradient background -->
                        <div
                            class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary-500 via-blue-500 to-purple-500 opacity-0 group-hover/cluster:opacity-100 transition-opacity duration-300">
                        </div>

                        <!-- Cluster Header -->
                        <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-8">
                            <div class="flex-1">
                                <div class="flex items-start gap-5">
                                    <div
                                        class="p-3.5 rounded-2xl bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 shrink-0 shadow-sm">
                                        <OfficeIcon class="w-8 h-8" />
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-3">
                                            <h3
                                                class="text-2xl font-bold text-gray-900 dark:text-white group-hover/cluster:text-primary-600 transition-colors">
                                                {{ cluster.name }}
                                            </h3>
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100/80 text-gray-600 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                Cluster
                                            </span>
                                        </div>
                                        <p v-if="cluster.address"
                                            class="text-base text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ cluster.address }}
                                        </p>
                                        <p v-if="cluster.description"
                                            class="text-sm text-gray-500/80 dark:text-gray-400/80 mt-2 max-w-2xl text-pretty">
                                            {{ cluster.description }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 mt-5 ml-16">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-4 py-1.5 text-sm font-medium rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                        <HomeIcon class="w-4 h-4" />
                                        {{ cluster.boarding_houses_count }} Unit Kos
                                    </span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 shrink-0">
                                <button v-if="can('boarding_houses.create')" @click="openAddBoardingHouseModal(cluster)"
                                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-md shadow-primary-500/20 transition-all duration-200"
                                    title="Tambah Kos">
                                    <PlusSquareIcon class="w-4 h-4" />
                                    Tambah Kos
                                </button>
                                <div class="w-px h-10 bg-gray-200 dark:bg-gray-700 mx-1 self-center"></div>
                                <button v-if="can('clusters.edit')" @click="openEditModal(cluster)"
                                    class="p-2.5 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 dark:text-gray-400 dark:hover:bg-yellow-900/20 rounded-xl transition-all duration-200 group/edit"
                                    title="Edit Cluster">
                                    <EditIcon class="w-5 h-5 group-hover/edit:scale-110 transition-transform" />
                                </button>
                                <button v-if="can('clusters.delete')" @click="openConfirmModal(cluster)"
                                    class="p-2.5 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200 group/delete"
                                    title="Hapus Cluster">
                                    <TrashIcon class="w-5 h-5 group-hover/delete:scale-110 transition-transform" />
                                </button>
                            </div>
                        </div>

                        <!-- Kos Grid -->
                        <div v-if="cluster.boarding_houses && cluster.boarding_houses.length > 0"
                            class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                <div v-for="boardingHouse in cluster.boarding_houses" :key="boardingHouse.id"
                                    class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 dark:hover:shadow-none hover:-translate-y-1 transition-all duration-300">
                                    <div class="relative aspect-[4/3] overflow-hidden">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60 z-10">
                                        </div>
                                        <img v-if="boardingHouse.thumbnail" :src="`/storage/${boardingHouse.thumbnail}`"
                                            :alt="boardingHouse.name"
                                            class="object-cover w-full h-full transform group-hover:scale-110 transition-transform duration-700" />
                                        <div v-else
                                            class="flex items-center justify-center w-full h-full bg-gray-50 dark:bg-gray-700 text-gray-300 dark:text-gray-600">
                                            <HomeIcon class="w-16 h-16" />
                                        </div>

                                        <!-- Status Badge -->
                                        <span :class="[
                                            'absolute top-3 right-3 px-3 py-1 text-xs font-bold rounded-full shadow-lg z-20 backdrop-blur-md border border-white/20',
                                            boardingHouse.status === 'active'
                                                ? 'bg-green-500/90 text-white'
                                                : boardingHouse.status === 'maintenance'
                                                    ? 'bg-yellow-500/90 text-white'
                                                    : 'bg-red-500/90 text-white',
                                        ]">
                                            {{ boardingHouse.status === 'active' ? 'Aktif' : boardingHouse.status ===
                                                'maintenance' ? 'Maintenance' : 'Tidak Aktif' }}
                                        </span>

                                        <!-- Action Buttons Overlay -->
                                        <div v-if="can('boarding_houses.view') || can('boarding_houses.edit') || can('boarding_houses.delete')"
                                            class="absolute inset-0 z-30 bg-black/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                                            <Link v-if="can('boarding_houses.view')"
                                                :href="route('boarding-houses.show', boardingHouse.id)"
                                                class="px-3 py-1.5 text-xs font-bold text-white bg-blue-500/90 hover:bg-blue-600 rounded-lg backdrop-blur-sm transition-all transform hover:scale-105">
                                                Detail
                                            </Link>
                                            <button v-if="can('boarding_houses.edit')"
                                                @click.stop="openEditBoardingHouseModal(cluster, boardingHouse)"
                                                class="p-2 text-white bg-yellow-500/90 hover:bg-yellow-600 rounded-lg backdrop-blur-sm transition-all transform hover:scale-105">
                                                <EditIcon class="w-4 h-4" />
                                            </button>
                                            <button v-if="can('boarding_houses.delete')"
                                                @click.stop="openConfirmBoardingHouseModal(cluster, boardingHouse)"
                                                class="p-2 text-white bg-red-500/90 hover:bg-red-600 rounded-lg backdrop-blur-sm transition-all transform hover:scale-105">
                                                <TrashIcon class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <h4
                                            class="font-bold text-lg text-gray-900 dark:text-white mb-1 line-clamp-1 group-hover:text-primary-600 transition-colors">
                                            {{ boardingHouse.name }}
                                        </h4>
                                        <div
                                            class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-3">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="truncate">Owner: {{ boardingHouse.owner?.name || 'Unknown'
                                                }}</span>
                                        </div>
                                        <p v-if="boardingHouse.address"
                                            class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 min-h-[2.5em] mb-3">
                                            {{ boardingHouse.address }}
                                        </p>
                                        <div
                                            class="pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                            <div
                                                class="text-xs font-semibold text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                                {{ boardingHouse.rooms_count ?? 0 }} Kamar
                                            </div>
                                            <span
                                                class="text-xs font-medium text-primary-600 dark:text-primary-400 group-hover:underline">Lihat
                                                Detail</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty Kos State within Cluster -->
                        <div v-else
                            class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700 text-center py-8 bg-gray-50/50 dark:bg-gray-800/50 rounded-2xl border-dashed border border-gray-200 dark:border-gray-700">
                            <HomeIcon class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" />
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada unit kos di cluster ini
                            </p>
                            <button v-if="can('boarding_houses.create')" @click="openAddBoardingHouseModal(cluster)"
                                class="mt-3 text-sm text-primary-600 font-medium hover:text-primary-700 hover:underline">
                                Tambah Unit Kos Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else-if="allClusters.length === 0 && !isLoadingMore"
                    class="h-full flex flex-col items-center justify-center p-12 text-center">
                    <div
                        class="w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6 shadow-sm animate-pulse-slow">
                        <SearchIcon class="w-10 h-10 text-gray-300 dark:text-gray-600" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tidak ada cluster ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                        Kami tidak dapat menemukan cluster dengan kata kunci tersebut. Coba cari dengan kata kunci lain.
                    </p>
                    <button v-if="search" @click="search = ''"
                        class="px-5 py-2.5 text-sm font-medium text-primary-600 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:text-primary-400 dark:hover:bg-primary-900/50 rounded-xl transition-colors">
                        Hapus Pencarian
                    </button>
                    <button v-else-if="can('clusters.create')" @click="openAddModal"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-lg shadow-primary-500/30 transition-all">
                        Tambah Cluster Baru
                    </button>
                </div>

                <!-- Loading More Indicator -->
                <div v-if="isLoadingMore" class="py-12 text-center">
                    <div
                        class="inline-flex items-center gap-3 px-4 py-2 bg-white dark:bg-gray-800 rounded-full shadow-lg border border-gray-100 dark:border-gray-700">
                        <svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Memuat data lagi...</span>
                    </div>
                </div>

                <!-- Load More Button (fallback) -->
                <div v-if="hasNextPage && !isLoadingMore" class="py-8 text-center">
                    <button @click="loadMore"
                        class="px-6 py-2.5 text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-750 dark:hover:text-gray-200 rounded-xl transition-all shadow-sm">
                        Muat Lebih Banyak
                    </button>
                </div>
            </div>

            <!-- Cluster Modal -->
            <Modal :show="isModalOpen" :title="isEditMode ? `Edit ${selectedItem?.name}` : 'Tambah Cluster Baru'"
                confirmText="Simpan" maxWidth="lg" @close="closeModal" @confirm="saveCluster">
                <div class="space-y-5">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Nama Cluster <span class="text-red-500">*</span>
                        </label>
                        <input id="name"
                            class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                            type="text" v-model="form.name" required placeholder="Contoh: Cluster Melati" />
                        <div v-if="form.errors.name" class="text-xs text-red-500 mt-1 font-medium">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="address" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Alamat Lengkap
                        </label>
                        <input id="address"
                            class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                            type="text" v-model="form.address" placeholder="Masukkan alamat lengkap cluster" />
                        <div v-if="form.errors.address" class="text-xs text-red-500 mt-1 font-medium">
                            {{ form.errors.address }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Deskripsi
                        </label>
                        <textarea id="description"
                            class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400 resize-none"
                            v-model="form.description" rows="3"
                            placeholder="Tuliskan deskripsi singkat mengenai cluster ini..."></textarea>
                        <div v-if="form.errors.description" class="text-xs text-red-500 mt-1 font-medium">
                            {{ form.errors.description }}
                        </div>
                    </div>
                </div>
            </Modal>


            <!-- Confirm Modals -->
            <ConfirmModal :show="isConfirmModalOpen" :question="`Yakin ingin menghapus`"
                :selected="`${selectedItem?.name}`" title="Hapus Cluster" confirmText="Ya, Hapus!" maxWidth="md"
                @close="closeConfirmModal" @confirm="destroyData" />

            <ConfirmModal :show="isConfirmBoardingHouseModalOpen" :question="`Yakin ingin menghapus`"
                :selected="`${selectedBoardingHouse?.name}`" title="Hapus Kos" confirmText="Ya, Hapus!" maxWidth="md"
                @close="closeConfirmBoardingHouseModal" @confirm="destroyBoardingHouse" />

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
import OfficeIcon from "@/Components/icons/OfficeIcon.vue";
import PageIcon from "@/Components/icons/PageIcon.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import Modal from "@/Components/common/Modal.vue";
import ConfirmModal from "@/Components/common/ConfirmModal.vue";
import { useAuth } from "@/Composables/useAuth";
import { ref, watch, computed, onMounted, onBeforeUnmount } from "vue";
import { useForm, router, Head, Link } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    clusters: Object,
    owners: Array,
    search: String,
});

const { can } = useAuth();

const breadcrumbs = [{ label: "Properti" }, { label: "Cluster" }];

const search = ref(props.search || "");
const allClusters = ref(props.clusters?.data || []);
const nextCursor = ref(props.clusters?.next_cursor || null);
const hasNextPage = computed(() => !!nextCursor.value);
const isLoadingMore = ref(false);
const scrollContainer = ref(null);

// Track if we're loading more or new search
const isInitialLoad = ref(true);

// Initialize clusters from props
watch(() => props.clusters, (newClusters) => {
    if (newClusters?.data) {
        const currentSearch = props.search || '';
        const wasSearching = search.value && search.value !== currentSearch;

        // Check if this is a new search or initial load
        if (isInitialLoad.value || wasSearching || allClusters.value.length === 0) {
            // Reset if search changed or initial load
            allClusters.value = [...newClusters.data];
            isInitialLoad.value = false;
        } else {
            // Append if loading more
            allClusters.value = [...allClusters.value, ...newClusters.data];
        }
        nextCursor.value = newClusters.next_cursor || null;
        isLoadingMore.value = false;
    }
}, { immediate: true });

let timeout = null;
watch(search, (newSearch, oldSearch) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        // Reset clusters when searching
        allClusters.value = [];
        nextCursor.value = null;
        isLoadingMore.value = false;
        isInitialLoad.value = true;
        router.get(
            route("clusters.index"),
            { search: search.value },
            {
                preserveScroll: false,
                preserveState: false,
                replace: true,
            }
        );
    }, 400);
});

// Load more function
function loadMore() {
    if (isLoadingMore.value || !hasNextPage.value) return;

    isLoadingMore.value = true;
    router.get(
        route("clusters.index"),
        {
            search: search.value,
            cursor: nextCursor.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
            only: ['clusters'],
            onSuccess: () => {
                // Data will be appended via watch
            },
            onError: () => {
                isLoadingMore.value = false;
            },
        }
    );
}

// Handle scroll for infinite scroll
function handleScroll(event) {
    if (isLoadingMore.value || !hasNextPage.value) return;

    const container = event.target;
    // Check if it's simplebar wrapper
    const scrollElement = container.classList.contains('simplebar-content-wrapper')
        ? container
        : container.querySelector('.simplebar-content-wrapper') || container;

    const scrollTop = scrollElement.scrollTop;
    const scrollHeight = scrollElement.scrollHeight;
    const clientHeight = scrollElement.clientHeight;

    // Load more when scrolled to 80% of the container
    if (scrollTop + clientHeight >= scrollHeight * 0.8) {
        loadMore();
    }
}

// Cluster Modal
const isModalOpen = ref(false);
const isEditMode = ref(false);
const form = useForm({
    id: null,
    name: "",
    address: "",
    description: "",
});

function openAddModal() {
    if (!can("clusters.create")) {
        return;
    }
    form.reset();
    isEditMode.value = false;
    isModalOpen.value = true;
}

function openEditModal(cluster) {
    if (!can("clusters.edit")) {
        return;
    }
    form.id = cluster.id;
    form.name = cluster.name;
    form.address = cluster.address || "";
    form.description = cluster.description || "";
    selectedItem.value = cluster;
    isEditMode.value = true;
    isModalOpen.value = true;
}

function closeModal() {
    isModalOpen.value = false;
    selectedItem.value = null;
    form.reset();
    form.clearErrors();
}

function saveCluster() {
    if (isEditMode.value) {
        if (!can("clusters.edit")) {
            return;
        }
        form.put(route("clusters.update", form.id), {
            onSuccess: () => {
                closeModal();
                // Refresh data
                allClusters.value = [];
                nextCursor.value = null;
                isInitialLoad.value = true;
                router.reload({ only: ['clusters'] });
            },
        });
    } else {
        if (!can("clusters.create")) {
            return;
        }
        form.post(route("clusters.store"), {
            onSuccess: () => {
                closeModal();
                // Refresh data
                allClusters.value = [];
                nextCursor.value = null;
                isInitialLoad.value = true;
                router.reload({ only: ['clusters'] });
            },
        });
    }
}

const selectedItem = ref(null);
const isConfirmModalOpen = ref(false);
const openConfirmModal = (item) => {
    if (!can("clusters.delete")) {
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
    if (!can("clusters.delete")) {
        return;
    }
    router.delete(route("clusters.destroy", selectedItem.value.id), {
        onSuccess: () => {
            closeConfirmModal();
            // Refresh data
            allClusters.value = [];
            nextCursor.value = null;
            isInitialLoad.value = true;
            router.reload({ only: ['clusters'] });
        },
        preserveScroll: true,
    });
};

// Kos functions - redirect to new pages
function openAddBoardingHouseModal(cluster) {
    if (!can("boarding_houses.create")) {
        return;
    }
    // Redirect to create page with cluster pre-selected
    router.visit(route("boarding-houses.create", { cluster_id: cluster.id }));
}

function openEditBoardingHouseModal(cluster, boardingHouse) {
    if (!can("boarding_houses.edit")) {
        return;
    }
    // Redirect to edit page
    router.visit(route("boarding-houses.edit", boardingHouse.id));
}

// Confirm delete boarding house
const isConfirmBoardingHouseModalOpen = ref(false);
const selectedCluster = ref(null);
const selectedBoardingHouse = ref(null);

const openConfirmBoardingHouseModal = (cluster, boardingHouse) => {
    if (!can("boarding_houses.delete")) {
        return;
    }
    selectedCluster.value = cluster;
    selectedBoardingHouse.value = boardingHouse;
    isConfirmBoardingHouseModalOpen.value = true;
};

const closeConfirmBoardingHouseModal = () => {
    selectedCluster.value = null;
    selectedBoardingHouse.value = null;
    isConfirmBoardingHouseModalOpen.value = false;
};

const destroyBoardingHouse = () => {
    if (!can("boarding_houses.delete")) {
        return;
    }
    router.delete(route("clusters.boarding-houses.destroy", [selectedCluster.value.id, selectedBoardingHouse.value.id]), {
        onSuccess: () => {
            closeConfirmBoardingHouseModal();
            // Refresh data
            allClusters.value = [];
            nextCursor.value = null;
            isInitialLoad.value = true;
            router.reload({ only: ['clusters'] });
        },
        preserveScroll: true,
    });
};
</script>
