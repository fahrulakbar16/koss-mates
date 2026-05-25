<template>

    <Head :title="`Detail Pemasukan - ${room.name}`" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 md:p-8 font-sans">
        <div
            class="max-w-[1200px] mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden print:shadow-none print:rounded-none">

            <!-- Toolbar (Hidden on print) -->
            <div
                class="bg-gray-100 dark:bg-gray-700 p-4 flex justify-between items-center print:hidden border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('admin.financial-reports.recap', { id: boardingHouse.id, month: currentMonth, year: currentYear })"
                        class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </Link>
                    <h1 class="text-lg font-bold text-gray-800 dark:text-white">Detail Pemasukan per Kamar</h1>
                </div>
                <button @click="print"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Riwayat
                </button>
            </div>

            <!-- Report Content -->
            <div class="p-8 print:p-4">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Pemasukan per Kamar</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Riwayat transaksi pembayaran hunian
                        (Multi-Penyewa)</p>
                </div>

                <!-- Room Info Card -->
                <div
                    class="mb-6 bg-pink-50 dark:bg-pink-900/10 rounded-xl p-6 border border-pink-100 dark:border-pink-900/30">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-16 h-16 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ room.name }}</h3>
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-bold uppercase',
                                    room.status === 'available' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' :
                                    room.status === 'occupied' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' :
                                    room.status === 'booked' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' :
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                                ]">
                                    {{ room.status === 'available' ? 'Tersedia' : room.status === 'occupied' ? 'Terisi' : room.status === 'booked' ? 'Booked' : 'Maintenance' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                KATEGORI: {{ room.category }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="mb-6 print:hidden">
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">
                            Filter Periode</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1 uppercase">Mulai</label>
                                <input v-model="startDate" type="date"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm focus:border-primary-500 focus:ring-primary-500" />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1 uppercase">Selesai</label>
                                <input v-model="endDate" type="date"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm focus:border-primary-500 focus:ring-primary-500" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="mb-6">
                    <h3 class="font-bold text-base mb-3 text-gray-800 dark:text-white uppercase tracking-wide">Riwayat
                        Transaksi Pembayaran
                        <span
                            class="text-xs font-normal text-gray-500 dark:text-gray-400 normal-case ml-2">Multi-Penyewa
                            Terintegrasi</span>
                    </h3>
                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="bg-blue-50 dark:bg-blue-900/30">
                                <tr>
                                    <th
                                        class="p-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-600">
                                        Tanggal</th>
                                    <th
                                        class="p-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-600">
                                        Penyewa</th>
                                    <th
                                        class="p-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-600">
                                        Deskripsi</th>
                                    <th
                                        class="p-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-600">
                                        Metode</th>
                                    <th
                                        class="p-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-600">
                                        Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="(transaction, index) in transactions.data" :key="transaction.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="p-3 text-gray-600 dark:text-gray-300 whitespace-nowrap">{{
                                        formatDate(transaction.date) }}</td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                                                <span
                                                    class="text-xs font-bold text-primary-600 dark:text-primary-400">{{
                                                        getInitials(transaction.tenant) }}</span>
                                            </div>
                                            <span class="font-medium text-gray-900 dark:text-white">{{
                                                transaction.tenant }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <div class="text-gray-900 dark:text-white">{{ transaction.description }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">REGULAR MONTHLY</div>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-1.5 text-gray-700 dark:text-gray-300">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            <span class="text-sm">{{ transaction.payment_method }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-right font-semibold text-gray-900 dark:text-white">{{
                                        formatCurrency(transaction.amount) }}</td>
                                </tr>
                                <tr v-if="transactions.length === 0">
                                    <td colspan="5" class="p-8 text-center text-gray-400 italic">
                                        Tidak ada transaksi dalam periode ini
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary Box -->
                <div class="bg-red-50 dark:bg-red-900/10 rounded-xl p-6 border-2 border-red-200 dark:border-red-900/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-red-600 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div
                                    class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Total Akumulasi Pemasukan Kamar</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Mencakup semua transaksi
                                    penyewa di periode terpilih</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{
                                formatCurrency(totalIncome)
                            }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase mt-1">{{ transactions.length
                            }}
                                Transaksi</div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 print:hidden">
                    <div class="flex items-center justify-between">
                        <Link
                            :href="route('admin.financial-reports.recap', { id: boardingHouse.id, month: currentMonth, year: currentYear })"
                            class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Rekap Utama
                        </Link>
                        <div class="text-xs text-gray-400">Terakhir diperbarui: {{ currentDateTime }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    room: Object,
    transactions: Array,
    totalIncome: Number,
    filters: Object,
    boardingHouse: Object,
});
const room = ref(props.room.data);
const startDate = ref(props.filters.start_date);
const endDate = ref(props.filters.end_date);

const currentMonth = new Date().getMonth() + 1;
const currentYear = new Date().getFullYear();
const currentDateTime = new Date().toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value || 0);
};

const formatDate = (date) => {
    if (!date) return "-";
    return new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric"
    }).format(new Date(date));
};

const getInitials = (name) => {
    if (!name) return "?";
    return name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
};

const print = () => {
    window.open(route('admin.financial-reports.room-detail.print', {
        roomId: props.room.data.id,
        start_date: startDate.value,
        end_date: endDate.value
    }), '_blank');
};

// Watch for filter changes
let timeout = null;
watch([startDate, endDate], () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(
            route('admin.financial-reports.room-detail', { roomId: props.room.data.id }),
            {
                start_date: startDate.value,
                end_date: endDate.value,
            },
            {
                preserveScroll: true,
                preserveState: true,
                replace: true,
            }
        );
    }, 500);
});
</script>

<style>
@media print {
    @page {
        size: landscape;
        margin: 0.5cm;
    }

    body {
        background: white;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>
