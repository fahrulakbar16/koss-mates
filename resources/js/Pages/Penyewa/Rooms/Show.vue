<template>

    <Head title="Kamar Saya" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <!-- Header -->
            <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Kamar Saya</h1>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                        Selamat Datang, {{ $page.props.auth.user.name }}! Kelola kos Anda dengan mudah di sini.
                    </p>
                </div>
            </div>

            <div v-if="activeRoom" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column (Main Content) -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Main Room Card -->
                    <!-- Single Combined Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 relative overflow-hidden">
                        <!-- Active Status Badge (Top Right) -->
                        <div
                            class="absolute top-6 right-6 z-10 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full shadow-sm">
                            <span class="text-xs font-bold text-red-500 uppercase tracking-widest">AKTIF</span>
                        </div>

                        <!-- Room Image (Full Width Top) -->
                        <div class="w-full h-64 bg-gray-100 dark:bg-gray-800 relative">
                            <img :src="getRoomImage()" :alt="activeRoom.name" class="w-full h-full object-cover"
                                @error="$event.target.src = '/images/placeholder.png'">
                        </div>

                        <!-- Card Content -->
                        <div class="p-8">
                            <!-- Room Info Section -->
                            <div
                                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Info Kos Anda
                                    </p>
                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ activeRoom.room?.name || 'Kamar' }} - {{ activeRoom.name }}
                                    </h2>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{ activeRoom.address || 'Alamat tidak tersedia' }}
                                    </p>
                                </div>
                                <div
                                    class="flex items-center gap-2 bg-red-50 dark:bg-red-900/20 text-red-500 px-4 py-2 rounded-full text-sm font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Verifikasi Kamar
                                </div>
                            </div>

                            <!-- Payment Section (Conditional) -->
                            <div v-if="nextPayment">
                                <div class="h-px bg-gray-100 dark:bg-gray-800 w-full mb-8"></div>
                                <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Pembayaran
                                            berikutnya</p>
                                        <div class="flex items-baseline gap-3">
                                            <span class="text-xl font-bold text-gray-900 dark:text-white">{{
                                                formatDate(nextPayment.created_at) }}</span>
                                            <span class="text-3xl font-bold text-red-500">{{
                                                formatPrice(nextPayment.total_price) }}</span>
                                        </div>
                                    </div>
                                    <Link :href="route('penyewa.transactions.show', nextPayment.id)"
                                        class="w-full md:w-auto bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-xl transition-colors shadow-lg shadow-red-500/30 text-center">
                                        Bayar Sekarang
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Lapor Kerusakan -->
                        <Link :href="route('penyewa.damage-reports.create')"
                            class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 flex items-center gap-4 hover:shadow-2xl transition-all text-left group">
                            <div
                                class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">Lapor Kerusakan</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ajukan perbaikan fasilitas</p>
                            </div>
                        </Link>

                        <!-- Pindah Kamar -->
                        <Link v-if="$page.props.auth.user.tenant?.is_moved"
                            :href="activeRoom?.pending_transfer ? route('penyewa.transfers.index') : route('penyewa.transfers.create')"
                            class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 flex items-center gap-4 hover:shadow-2xl transition-all text-left group">
                            <div
                                class="w-12 h-12 bg-orange-50 dark:bg-orange-900/20 rounded-2xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">Pindah Kamar</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ajukan perpindahan unit</p>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Right Column (History) -->
                <div class="space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Terbaru</h3>
                            <Link :href="route('penyewa.transactions.index')"
                                class="text-sm font-bold text-red-500 hover:text-red-600 flex items-center gap-1">
                                Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </Link>
                        </div>

                        <div class="space-y-6">
                            <div v-if="recentTransactions.length > 0">
                                <div v-for="transaction in recentTransactions" :key="transaction.id"
                                    class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white text-sm">Pembayaran {{
                                            formatDateMonth(transaction.created_at) }}</p>
                                        <p class="text-xs text-gray-500">{{ formatDate(transaction.created_at) }}</p>
                                    </div>
                                    <div class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap" :class="{
                                        'bg-green-100 text-green-600': transaction.status === 'completed',
                                        'bg-yellow-100 text-yellow-600': transaction.status === 'pending',
                                        'bg-red-100 text-red-600': transaction.status === 'cancelled' || transaction.status === 'failed'
                                    }">
                                        {{ transaction.status === 'completed' ? 'Lunas' : (transaction.status ===
                                            'pending' ? 'Pending' : 'Gagal') }}
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-4 text-gray-500 text-sm">
                                Belum ada riwayat transaksi.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Empty State -->
            <div v-else
                class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center shadow-xl border border-gray-200 dark:border-gray-700 min-h-[60vh] flex flex-col items-center justify-center">
                <div
                    class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Kamar Aktif</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8">Anda belum menyewa kamar apapun saat ini.</p>
                <Link :href="route('boarding-houses.public.index')"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all font-bold text-base group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Cari Hunian Baru</span>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head } from "@inertiajs/vue3";
import { computed } from 'vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    activeRoom: Object,
});

const activeRoom = props.activeRoom?.data;

// Computed
const sortedTransactions = computed(() => {
    if (!activeRoom?.transactions) return [];
    return [...activeRoom.transactions].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

const nextPayment = computed(() => {
    // Find the latest pending transaction or the one that needs payment
    // Assuming 'pending' status means unpaid
    return sortedTransactions.value.find(t => t.status === 'pending' || t.payment_status === 'pending');
});

const recentTransactions = computed(() => {
    return sortedTransactions.value.slice(0, 5);
});

// Utils
const formatPrice = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(value);
};

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

const formatDateMonth = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '-';
    return date.toLocaleDateString('id-ID', {
        month: 'long'
    });
};

const getRoomImage = () => {
    console.log(activeRoom.room.boarding_house.thumbnail);
    if (activeRoom?.room?.boarding_house?.thumbnail) {
        return `/storage/${activeRoom.room.boarding_house.thumbnail}`;
    }
    return '/images/placeholder.png';
};

// Damage Report



</script>
