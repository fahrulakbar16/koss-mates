<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineOptions({
    layout: PublicLayout,
});

const props = defineProps({
    data: {
        type: Object,
        default: () => ({ data: [], meta: {} }),
    },
    cluster: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    }
});

const searchQuery = ref(props.filters?.search || '');
const selectedCluster = ref(props.filters?.cluster_id || '');
const selectedGender = ref(props.filters?.gender || '');
const activeFilter = ref('semua'); // Default active filter
const loading = ref(false);
const userLocation = ref({ lat: null, long: null });

console.log(props.data.meta.has_more_pages);
// Initialize boardingHouses from props
const boardingHouses = ref(props.data.data);
const pagination = ref({
    hasMore: props.data.meta?.next_cursor !== null ? true : false,
    nextCursor: props.data.meta?.next_cursor || null,
});

// Update local state when filters are applied or load more is triggered
const updateData = (newData, append = false) => {
    if (append) {
        boardingHouses.value = [...boardingHouses.value, ...newData.data];
    } else {
        boardingHouses.value = newData.data;
    }

    pagination.value = {
        hasMore: newData.meta?.has_more_pages || false,
        nextCursor: newData.meta?.next_cursor || null,
    };

    // If active filter is "termurah", maintain sort after update
    if (activeFilter.value === 'termurah') {
        boardingHouses.value.sort((a, b) => (getMinPrice(a) || 0) - (getMinPrice(b) || 0));
    }
};

const applyFilters = () => {
    const params = {
        search: searchQuery.value,
        cluster_id: selectedCluster.value,
        gender: selectedGender.value,
    };

    if (activeFilter.value === 'terdekat' && userLocation.value.lat && userLocation.value.long) {
        params.lat = userLocation.value.lat;
        params.long = userLocation.value.long;
    }

    router.get(route('boarding-houses.public.index'), params, {
        preserveState: true,
        preserveScroll: true,
        only: ['data'],
        onStart: () => loading.value = true,
        onFinish: () => loading.value = false,
        onSuccess: (page) => {
            updateData(page.props.data, false);
        }
    });
};

// Get user location
const getUserLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                userLocation.value = {
                    lat: position.coords.latitude,
                    long: position.coords.longitude,
                };
                // If user selects "terdekat", we trigger search
                if (activeFilter.value === 'terdekat') {
                    applyFilters();
                }
            },
            (error) => {
                console.warn('Error getting location:', error);
            }
        );
    }
};

// Watch for filter changes
watch([selectedCluster, selectedGender], () => {
    applyFilters();
});

watch(activeFilter, (newVal) => {
    if (newVal === 'terdekat') {
        if (!userLocation.value.lat) {
            getUserLocation(); // Try getting location first
        } else {
            applyFilters();
        }
    } else {
        // standard filter, maybe just reload or client sort?
        // If 'termurah', we can sort client side if needed, or if backend supported it.
        // Backend `GetBoardingHousePaginate` does NOT seem to support `sort` param for price.
        // So we will stick to client-side sorting for `boardingHouses` strictly.
        if (newVal === 'termurah') {
            boardingHouses.value.sort((a, b) => (getMinPrice(a) || 0) - (getMinPrice(b) || 0));
        } else if (newVal === 'terpopuler') {
            // No logic for popularity yet, revert to default or whatever
        } else {
            // 'semua' -> reload to get default order?
            applyFilters();
        }
    }
});


// Debounce search
let searchTimeout = null;
watch(searchQuery, () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
});

const loadMore = () => {
    if (loading.value || !pagination.value.nextCursor) return;

    const params = {
        search: searchQuery.value,
        cluster_id: selectedCluster.value,
        gender: selectedGender.value,
        cursor: pagination.value.nextCursor,
    };

    if (activeFilter.value === 'terdekat' && userLocation.value.lat) {
        params.lat = userLocation.value.lat;
        params.long = userLocation.value.long;
    }

    router.get(route('boarding-houses.public.index'), params, {
        preserveState: true,
        preserveScroll: true,
        only: ['data'],
        onStart: () => loading.value = true,
        onFinish: () => loading.value = false,
        onSuccess: (page) => {
            updateData(page.props.data, true);
        }
    });
};

onMounted(() => {
    // Check for filters passed from backend (if any) to sync state
    // but we use internal state mostly.

    // Initial user location fetch
    getUserLocation();
});

const getMinPrice = (kos) => {
    // Accessor for the price from the resource structure
    return kos.price || 0;
};

const formatPrice = (price) => {
    if (!price || price === 0) return 'Harga belum tersedia';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);
};

const getImageUrl = (boardingHouse) => {
    return boardingHouse.thumbnail || '/images/placeholder.png';
};
</script>

<template>

    <Head title="Daftar Kos - Tharahub" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="mb-10 text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-3 tracking-tight">Daftar Kos
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">Temukan kos impianmu dengan mudah dan cepat</p>
        </div>

        <!-- Search and Filters -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 shadow-theme-sm border border-gray-100 dark:border-gray-700/50 mb-10">
            <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
                <!-- Search Input -->
                <div class="lg:col-span-2 md:col-span-2">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input v-model="searchQuery" type="text"
                            placeholder="Cari kos berdasarkan nama, alamat, atau deskripsi..."
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-gray-700 rounded-2xl leading-5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300 font-medium" />
                    </div>
                </div>

                <!-- Cluster Filter -->
                <div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <select v-model="selectedCluster"
                            class="block w-full pl-11 pr-10 py-3.5 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300 appearance-none font-medium cursor-pointer">
                            <option value="">Semua Cluster</option>
                            <option v-for="c in cluster" :key="c.id" :value="c.id">
                                {{ c.name }}
                            </option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">

                        </div>
                    </div>
                </div>

                <!-- Gender Filter -->
                <div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <select v-model="selectedGender"
                            class="block w-full pl-11 pr-10 py-3.5 border border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300 appearance-none font-medium cursor-pointer">
                            <option value="">Semua Gender</option>
                            <option value="L">Putra</option>
                            <option value="P">Putri</option>
                            <option value="C">Campur</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">

                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Pills -->
            <div
                class="flex flex-wrap gap-2 p-1 bg-gray-50 dark:bg-gray-900/50 rounded-xl w-full">
                <button v-for="filter in ['semua', 'terdekat', 'termurah', 'terpopuler']" :key="filter"
                    @click="activeFilter = filter"
                    class="flex-1 px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 capitalize relative"
                    :class="activeFilter === filter
                        ? 'text-primary-600 dark:text-primary-400 bg-white dark:bg-gray-800 shadow-sm ring-1 ring-black/5 dark:ring-white/5'
                        : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-800/50'">
                    {{ filter }}
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading && boardingHouses.length === 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <div v-for="i in 8" :key="i"
                class="bg-white dark:bg-gray-800 rounded-2xl h-[400px] animate-pulse shadow-sm border border-gray-100 dark:border-gray-700">
            </div>
        </div>

        <!-- Kos Grid -->
        <div v-else-if="boardingHouses.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <div v-for="kos in boardingHouses" :key="kos.id"
                class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-theme-xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700/50 flex flex-col h-full">
                <!-- Image -->
                <div class="relative h-60 overflow-hidden">
                    <img :src="getImageUrl(kos)" :alt="kos.name"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                        @error="$event.target.src = '/images/placeholder.png'" />
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80 group-hover:opacity-100 transition-opacity">
                    </div>

                    <!-- Price Badge -->
                    <div
                        class="absolute top-4 right-4 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-full shadow-lg border border-white/50">
                        <span class="text-primary-600 font-bold text-sm">{{ formatPrice(kos.price) }}</span>
                        <span class="text-xs text-gray-500 font-medium">/bulan</span>
                    </div>

                    <!-- Distance Badge (if available) -->
                    <div v-if="kos.distance"
                        class="absolute top-4 left-4 bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/20 text-white">
                        <span class="text-xs font-semibold">{{ kos.distance.toFixed(1) }} km</span>
                    </div>

                    <!-- Gender Badge -->
                    <div class="absolute bottom-4 left-4">
                        <div v-if="kos.gender === 'L'"
                            class="flex items-center gap-1.5 bg-primary-600/90 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg border border-white/20">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="4" r="2.5" />
                                <path d="M17,11c0-1.657-1.343-3-3-3H10c-1.657,0-3,1.343-3,3v5h2v6h2v-4h2v4h2v-6h2V11z" />
                            </svg>
                            <span>PUTRA</span>
                        </div>
                        <div v-else-if="kos.gender === 'P'"
                            class="flex items-center gap-1.5 bg-primary-600/90 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg border border-white/20">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="3.5" r="2" />
                                <path
                                    d="M17.5,13.5L15,8c-0.2-0.5-0.7-1-1.3-1h-3.4C9.7,7,9.2,7.5,9,8l-2.5,5.5C6.3,13.8,6.5,14.3,7,14.3h2V18c0,0.6,0.4,1,1,1h1v3h2v-3h1c0.6,0,1-0.4,1-1v-3.7h2C17.5,14.3,17.7,13.8,17.5,13.5z" />
                            </svg>
                            <span>PUTRI</span>
                        </div>
                        <div v-else-if="kos.gender === 'C'"
                            class="flex items-center gap-1.2 bg-primary-600/90 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg border border-white/20">
                            <div class="flex items-center -space-x-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="4" r="2.5" />
                                    <path d="M17,11c0-1.657-1.343-3-3-3H10c-1.657,0-3,1.343-3,3v5h2v6h2v-4h2v4h2v-6h2V11z" />
                                </svg>
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="3.5" r="2" />
                                    <path
                                        d="M17.5,13.5L15,8c-0.2-0.5-0.7-1-1.3-1h-3.4C9.7,7,9.2,7.5,9,8l-2.5,5.5C6.3,13.8,6.5,14.3,7,14.3h2V18c0,0.6,0.4,1,1,1h1v3h2v-3h1c0.6,0,1-0.4,1-1v-3.7h2C17.5,14.3,17.7,13.8,17.5,13.5z" />
                                </svg>
                            </div>
                            <span>CAMPUR</span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 flex flex-col flex-grow">
                    <div class="mb-4">
                        <h3
                            class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-1 group-hover:text-primary-600 transition-colors tracking-tight">
                            {{ kos.name }}
                        </h3>

                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 text-sm">
                            <svg class="w-4 h-4 flex-shrink-0 text-primary-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate font-medium">{{ kos.cluster?.name || kos.address }}</span>
                        </div>

                        <!-- Room Stats -->
                        <div class="mt-3 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-1.5" title="Total Kamar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>{{ kos.rooms_count }} Kamar</span>
                            </div>
                            <div class="flex items-center gap-1.5"
                                :class="kos.rooms_available_count > 0 ? 'text-green-600 dark:text-green-400' : 'text-primary-500'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span class="font-medium">{{ kos.rooms_available_count }} Tersedia</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-auto pt-5 border-t border-gray-100 dark:border-gray-700/50 flex justify-between items-center">
                        <span v-if="kos.rooms_available_count > 0"
                            class="text-xs font-bold px-3 py-1.5 bg-green-50 text-green-700 rounded-lg border border-green-100 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800 tracking-wide uppercase">
                            Tersedia
                        </span>
                        <span v-else
                            class="text-xs font-bold px-3 py-1.5 bg-primary-50 text-primary-700 rounded-lg border border-primary-100 dark:bg-primary-900/20 dark:text-primary-400 dark:border-primary-800 tracking-wide uppercase">
                            Penuh
                        </span>
                        <Link :href="route('boarding-houses.public.show', kos.id)"
                            class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1.5 group/btn">
                            Detail
                            <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else
            class="text-center py-24 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
            <div
                class="inline-flex w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full items-center justify-center mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tidak Ada Kos Ditemukan</h3>
            <p class="text-gray-500 dark:text-gray-400 text-lg max-w-md mx-auto">Coba ubah kata kunci pencarian atau
                filter yang Anda gunakan.</p>
        </div>

        <!-- Load More Button -->
        <div v-if="pagination.hasMore && !loading" class="text-center mt-12">
            <button @click="loadMore"
                class="px-8 py-3.5 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg shadow-md shadow-primary-600/20">
                Muat Lebih Banyak
            </button>
        </div>

        <!-- Loading More Indicator -->
        <div v-if="loading && boardingHouses.length > 0" class="flex justify-center mt-12">
            <div
                class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-full shadow-sm text-gray-500 dark:text-gray-400 text-sm font-medium">
                <svg class="animate-spin h-4 w-4 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Memuat data kos...
            </div>
        </div>
    </div>
</template>
