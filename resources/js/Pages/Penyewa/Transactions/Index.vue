<template>

    <Head title="Riwayat Transaksi" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <!-- Header -->
            <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Riwayat Tagihan</h1>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                        Lihat dan kelola riwayat pembayaran sewa Anda
                    </p>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="mb-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-2">
                    <div class="grid grid-cols-3 gap-2">
                        <button @click="activeTab = 'ongoing'" :class="[
                            'px-6 py-4 rounded-xl font-bold text-sm transition-all duration-300',
                            activeTab === 'ongoing'
                                ? 'bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg shadow-primary-500/30'
                                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50'
                        ]">
                            <div class="flex flex-col items-center gap-1">
                                <span>Sedang Berjalan</span>
                                <span :class="[
                                    'text-xs px-2 py-0.5 rounded-full',
                                    activeTab === 'ongoing' ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700'
                                ]">{{ ongoingCount }}</span>
                            </div>
                        </button>
                        <button @click="activeTab = 'completed'" :class="[
                            'px-6 py-4 rounded-xl font-bold text-sm transition-all duration-300',
                            activeTab === 'completed'
                                ? 'bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg shadow-primary-500/30'
                                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50'
                        ]">
                            <div class="flex flex-col items-center gap-1">
                                <span>Selesai</span>
                                <span :class="[
                                    'text-xs px-2 py-0.5 rounded-full',
                                    activeTab === 'completed' ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700'
                                ]">{{ completedCount }}</span>
                            </div>
                        </button>
                        <button @click="activeTab = 'canceled'" :class="[
                            'px-6 py-4 rounded-xl font-bold text-sm transition-all duration-300',
                            activeTab === 'canceled'
                                ? 'bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg shadow-primary-500/30'
                                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50'
                        ]">
                            <div class="flex flex-col items-center gap-1">
                                <span>Gagal</span>
                                <span :class="[
                                    'text-xs px-2 py-0.5 rounded-full',
                                    activeTab === 'canceled' ? 'bg-white/20' : 'bg-gray-100 dark:bg-gray-700'
                                ]">{{ canceledCount }}</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Transaction List -->
            <div class="space-y-5">
                <!-- Empty State -->
                <div v-if="!filteredTransactions.length"
                    class="text-center py-16 sm:py-20 bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700">
                    <div
                        class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/30 dark:to-primary-800/20 w-20 h-20 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-primary-500/20">
                        <Receipt class="w-10 h-10 text-primary-600 dark:text-primary-400" />
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                        {{ getEmptyStateTitle() }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto text-base leading-relaxed mb-8">
                        {{ getEmptyStateDescription() }}
                    </p>
                    <Link v-if="activeTab === 'ongoing' && allTransactions.length === 0"
                        :href="route('boarding-houses.public.index')"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all font-bold text-base group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Mulai Cari Kos</span>
                    </Link>
                </div>

                <!-- List Card -->
                <div v-for="transaction in filteredTransactions" :key="transaction.id"
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-2xl hover:border-primary-300 dark:hover:border-primary-700 transition-all duration-300 group">
                    <div class="p-6 sm:p-8 flex flex-col sm:flex-row gap-6">
                        <!-- Image -->
                        <div
                            class="relative w-full sm:w-40 h-40 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-2xl overflow-hidden shadow-md ring-2 ring-gray-100 dark:ring-gray-700 group-hover:ring-primary-200 dark:group-hover:ring-primary-800 transition-all">
                            <img :src="transaction.room.boarding_house.thumbnail_url || '/images/placeholder.png'"
                                :alt="transaction.room.boarding_house.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                @error="$event.target.src = '/images/placeholder.png'">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent" />
                        </div>

                        <!-- Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                                <div class="flex-1 min-w-0">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors mb-2">
                                        {{ transaction.room.boarding_house.name }}
                                    </h3>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="text-base text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                            <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                                            Kamar {{ transaction.room.name }}
                                        </p>
                                        <span :class="[
                                            'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold',
                                            transaction.type === 'booked'
                                                ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800'
                                                : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800'
                                        ]">
                                            {{ transaction.type === 'booked' ? 'Booking Baru' : 'Perpanjangan' }}
                                        </span>
                                    </div>
                                </div>
                                <span :class="[
                                    'inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold shadow-sm',
                                    getStatusClass(transaction.status).class
                                ]">
                                    {{ getStatusClass(transaction.status).label }}
                                </span>
                            </div>

                            <div class="flex flex-wrap gap-6 text-sm text-gray-600 dark:text-gray-400 mt-5">
                                <div
                                    class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 px-4 py-2.5 rounded-xl">
                                    <Calendar class="w-4 h-4 text-primary-500" />
                                    <span class="font-medium">{{ formatDate(transaction.created_at) }}</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 bg-primary-50 dark:bg-primary-900/20 px-4 py-2.5 rounded-xl border border-primary-200 dark:border-primary-800">
                                    <Tag class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                                    <span class="font-bold text-primary-700 dark:text-primary-300">{{
                                        formatPrice(transaction.total_price) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action -->
                        <div
                            class="flex flex-col justify-center sm:items-end gap-3 mt-4 sm:mt-0 pt-6 sm:pt-0 border-t sm:border-0 border-gray-100 dark:border-gray-700">
                            <Link :href="route('penyewa.transactions.show', transaction.id)"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all duration-300 w-full sm:w-auto group/btn">
                                <span>Lihat Detail</span>
                                <ArrowRight class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State & Sentinel -->
            <div ref="sentinel" class="mt-10 flex justify-center py-6">
                <div v-if="loading" class="flex flex-col items-center gap-4">
                    <div class="flex gap-2">
                        <div class="w-3 h-3 rounded-full bg-primary-600 animate-bounce [animation-delay:-0.3s]"></div>
                        <div class="w-3 h-3 rounded-full bg-primary-600 animate-bounce [animation-delay:-0.15s]"></div>
                        <div class="w-3 h-3 rounded-full bg-primary-600 animate-bounce"></div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold animate-pulse">Memuat tagihan
                        lainnya...</p>
                </div>
                <div v-else-if="!nextUrl && allTransactions.length > 0"
                    class="text-gray-500 dark:text-gray-400 text-sm font-medium bg-gray-100 dark:bg-gray-800 px-6 py-3 rounded-full">
                    ✓ Semua tagihan telah ditampilkan
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head } from "@inertiajs/vue3";
import { ChevronLeft, Calendar, Tag, Receipt, ArrowRight } from "lucide-vue-next";
import { ref, onMounted, onUnmounted, computed } from "vue";
import axios from "axios";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    transactions: Object,
});

const allTransactions = ref([...props.transactions.data]);
const nextUrl = ref(props.transactions.next_page_url);
const loading = ref(false);
const sentinel = ref(null);
const activeTab = ref('ongoing');

const filteredTransactions = computed(() => {
    switch (activeTab.value) {
        case 'ongoing':
            return allTransactions.value.filter(t =>
                t.status === 'pending' || t.status === 'incomplete'
            );
        case 'completed':
            return allTransactions.value.filter(t =>
                t.status === 'completed' || t.status === 'paid' || t.status === 'settlement'
            );
        case 'canceled':
            return allTransactions.value.filter(t =>
                t.status === 'failed' || t.status === 'cancel' || t.status === 'canceled' || t.status === 'expire'
            );
        default:
            return allTransactions.value;
    }
});

const ongoingCount = computed(() => {
    return allTransactions.value.filter(t =>
        t.status === 'pending' || t.status === 'incomplete'
    ).length;
});

const completedCount = computed(() => {
    return allTransactions.value.filter(t =>
        t.status === 'completed' || t.status === 'paid' || t.status === 'settlement'
    ).length;
});

const canceledCount = computed(() => {
    return allTransactions.value.filter(t =>
        t.status === 'failed' || t.status === 'cancel' || t.status === 'canceled' || t.status === 'expire'
    ).length;
});

const getEmptyStateTitle = () => {
    switch (activeTab.value) {
        case 'ongoing':
            return allTransactions.value.length === 0 ? 'Belum Ada Tagihan' : 'Tidak Ada Tagihan Berjalan';
        case 'completed':
            return 'Tidak Ada Tagihan Selesai';
        case 'canceled':
            return 'Tidak Ada Tagihan Gagal';
        default:
            return 'Belum Ada Tagihan';
    }
};

const getEmptyStateDescription = () => {
    switch (activeTab.value) {
        case 'ongoing':
            return allTransactions.value.length === 0
                ? 'Anda belum memiliki riwayat tagihan apapun saat ini. Mulai cari kos impian Anda sekarang!'
                : 'Tidak ada tagihan yang sedang berjalan saat ini.';
        case 'completed':
            return 'Belum ada tagihan yang selesai dibayar.';
        case 'canceled':
            return 'Tidak ada tagihan yang gagal atau dibatalkan.';
        default:
            return 'Anda belum memiliki riwayat tagihan apapun saat ini.';
    }
};

const loadMore = async () => {
    if (loading.value || !nextUrl.value) return;

    loading.value = true;
    try {
        const response = await axios.get(nextUrl.value, {
            headers: {
                'X-Inertia': true,
                'X-Inertia-Partial-Component': 'Penyewa/Transactions/Index',
                'X-Inertia-Partial-Data': 'transactions'
            }
        });

        const newTransactions = response.data.props.transactions;
        allTransactions.value = [...allTransactions.value, ...newTransactions.data];
        nextUrl.value = newTransactions.next_page_url;
    } catch (error) {
        console.error("Failed to load more transactions:", error);
    } finally {
        loading.value = false;
    }
};

let observer;

onMounted(() => {
    observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            loadMore();
        }
    }, {
        rootMargin: '100px',
    });

    if (sentinel.value) {
        observer.observe(sentinel.value);
    }
});

onUnmounted(() => {
    if (observer) {
        observer.disconnect();
    }
});

const getStatusClass = (status) => {
    switch (status) {
        case 'paid':
        case 'completed':
        case 'settlement':
            return {
                class: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                label: 'Lunas'
            };
        case 'pending':
            return {
                class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                label: 'Belum Dibayar'
            };
        case 'failed':
        case 'cancel':
        case 'incomplete':
            return {
                class: 'bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400',
                label: 'Belum Lunas'
            };
        default:
            return {
                class: 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
                label: status
            };
    }
};

const formatPrice = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(value);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>
