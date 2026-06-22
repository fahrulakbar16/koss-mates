<template>

    <Head :title="`Rekap Keuangan - ${boardingHouse.name}`" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 md:p-8 font-sans">
        <div
            class="max-w-[1200px] mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden print:shadow-none print:rounded-none">

            <!-- Toolbar (Hidden on print) -->
            <div
                class="bg-gray-100 dark:bg-gray-700 p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 print:hidden border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center gap-3">
                    <Link :href="route('admin.financial-reports.index')"
                        class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </Link>
                    <h1 class="text-lg font-bold text-gray-800 dark:text-white ml-auto sm:ml-0">Rekap Data</h1>
                </div>
                <button @click="print"
                    class="w-full sm:w-auto justify-center bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak / Simpan PDF
                </button>
            </div>

            <!-- Report Content -->
            <div class="p-8 print:p-6">
                <!-- Header -->
                <div class="mb-8 border-b-2 border-primary-500 pb-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wide">{{
                        boardingHouse.name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ boardingHouse.address }}</p>
                    <div class="mt-4 flex justify-between items-end">
                        <p class="font-medium text-gray-800 dark:text-gray-200">
                            Laporan Keuangan Periode: <span class="text-primary-600 font-bold uppercase">{{ periodLabel }}</span>
                        </p>
                        <div class="text-xs text-gray-400">Dicetak pada: {{ currentDate }}</div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="mb-8">
                    <h3 class="font-bold text-lg mb-3 text-gray-800 dark:text-white border-l-4 border-emerald-500 pl-3">
                        Daftar Transaksi Pemasukan</h3>
                    <div
                        class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                        <table class="w-full text-left text-xs md:text-sm border-collapse">
                            <thead>
                                <tr class="bg-emerald-50 dark:bg-emerald-900/30 text-gray-700 dark:text-gray-200">
                                    <th
                                        class="p-3 border-b border-gray-300 dark:border-gray-600 font-semibold text-center w-12 uppercase text-[11px]">
                                        NO</th>
                                    <th
                                        class="p-3 border-b border-gray-300 dark:border-gray-600 font-semibold uppercase text-[11px] w-24">
                                        KAMAR</th>
                                    <th
                                        class="p-3 border-b border-gray-300 dark:border-gray-600 font-semibold uppercase text-[11px]">
                                        NAMA PENYEWA</th>
                                    <th
                                        class="p-3 border-b border-gray-300 dark:border-gray-600 font-semibold uppercase text-[11px]">
                                        KETERANGAN</th>
                                    <th
                                        class="p-3 border-b border-gray-300 dark:border-gray-600 font-semibold text-center uppercase text-[11px] w-32">
                                        METODE</th>
                                    <th
                                        class="p-3 border-b border-gray-300 dark:border-gray-600 font-semibold text-right uppercase text-[11px] w-32">
                                        JUMLAH</th>
                                    <th
                                        class="p-3 border-b border-gray-300 dark:border-gray-600 font-semibold text-center uppercase text-[11px] w-20 print:hidden">
                                        AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="(transaction, index) in transactions.data" :key="transaction.id"
                                    class="hover:bg-emerald-50/50 dark:hover:bg-gray-800/50">
                                    <td class="p-3 text-center text-gray-500 dark:text-gray-400">{{ index + 1 }}</td>
                                    <td class="p-3 text-center">
                                        <span class="font-bold text-primary-600 dark:text-primary-400">{{
                                            transaction.room_name }}</span>
                                    </td>
                                    <td class="p-3 font-medium text-gray-900 dark:text-white">{{ transaction.tenant }}
                                    </td>
                                    <td class="p-3 text-gray-600 dark:text-gray-300 font-medium">{{
                                        transaction.description }}</td>
                                    <td class="p-3 text-center">
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold uppercase">
                                            {{ transaction.payment_method }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-3 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ formatCurrency(transaction.amount) }}
                                    </td>
                                    <td class="p-3 text-center print:hidden">
                                        <Link
                                            :href="route('admin.financial-reports.room-detail', { roomId: transaction.room_id })"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 dark:hover:text-primary-400 transition-all"
                                            title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                    </td>
                                </tr>
                                <!-- Subtotal Row -->
                                <tr
                                    class="bg-gray-100 dark:bg-gray-700/50 font-bold border-t-2 border-gray-300 dark:border-gray-600">
                                    <td colspan="5"
                                        class="p-4 text-right uppercase text-xs tracking-wider text-gray-700 dark:text-gray-300">
                                        TOTAL PEMASUKAN PERIODE INI:
                                    </td>
                                    <td
                                        class="p-4 text-right text-emerald-600 dark:text-emerald-400 font-bold text-lg font-mono">
                                        {{ formatCurrency(summary.total_income) }}
                                    </td>
                                    <td class="p-4 print:hidden"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Expenses Table -->
                    <div>
                        <h3
                            class="font-bold text-lg mb-3 text-gray-800 dark:text-white border-l-4 border-primary-500 pl-3">
                            Rekapitulasi Pengeluaran</h3>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <table class="w-full text-xs md:text-sm">
                                <thead class="bg-primary-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                    <tr>
                                        <th class="p-3 border-b w-10 text-center">No</th>
                                        <th class="p-3 border-b text-left">Keterangan</th>
                                        <th class="p-3 border-b text-right w-32">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <template v-if="expenses.data.length > 0">
                                        <tr v-for="(expense, index) in expenses.data" :key="expense.id"
                                            class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <td class="p-2 text-center text-gray-500">{{ index + 1 }}</td>
                                            <td class="p-2 text-gray-700 dark:text-gray-300">
                                                <div class="font-medium">{{ expense.category }}</div>
                                                <div class="text-xs text-gray-500">{{ expense.description }}</div>
                                            </td>
                                            <td class="p-2 text-right text-primary-600 dark:text-primary-400 font-mono">{{
                                                formatCurrency(expense.amount) }}</td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td colspan="3" class="p-4 text-center text-gray-400 italic">Tidak ada data
                                                pengeluaran</td>
                                        </tr>
                                    </template>

                                    <tr class="bg-gray-50 dark:bg-gray-700/30 font-bold border-t-2 border-gray-300">
                                        <td colspan="2" class="p-3 text-right uppercase text-xs tracking-wider">Total
                                            Pengeluaran:</td>
                                        <td class="p-3 text-right text-primary-600 dark:text-primary-400 font-mono">{{
                                            formatCurrency(summary.total_expense) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary Box -->
                    <div>
                        <h3
                            class="font-bold text-lg mb-3 text-gray-800 dark:text-white border-l-4 border-gray-500 pl-3">
                            Ringkasan Akhir
                        </h3>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <table class="w-full text-sm font-medium">
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="p-3 bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-400">
                                            Total Cash In</td>
                                        <td
                                            class="p-3 text-right text-emerald-600 dark:text-emerald-400 bg-white dark:bg-gray-800 font-mono font-bold">
                                            {{ formatCurrency(summary.total_income) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-400">
                                            Total Cash Out</td>
                                        <td
                                            class="p-3 text-right text-primary-600 dark:text-primary-400 bg-white dark:bg-gray-800 font-mono font-bold">
                                            - {{ formatCurrency(summary.total_expense) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-3 bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-400 font-bold uppercase tracking-wider text-[10px]">
                                            Porsi Pengelola ({{ 100 - (boardingHouse.persentasi_pemilik ?? 100) }}%)</td>
                                        <td
                                            class="p-3 text-right text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800 font-mono font-bold">
                                            {{ formatCurrency(summary.management_share) }}</td>
                                    </tr>
                                    <tr class="border-t-2 border-gray-300 dark:border-gray-500">
                                        <td
                                            class="p-4 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white font-bold uppercase tracking-wider">
                                            Laba Bersih</td>
                                        <td
                                            :class="['p-4 text-right font-mono text-lg font-bold bg-white dark:bg-gray-800', summary.net_profit >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-primary-600 dark:text-primary-400']">
                                           {{ formatCurrency(summary.owner_share) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Spacer for notes or signature -->
                        <div
                            class="mt-8 pt-8 border-t border-dashed border-gray-300 text-center text-gray-400 text-sm italic print:block hidden">
                            ( Tanda Tangan / Validasi )
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    boardingHouse: Object,
    transactions: Object,
    expenses: Object,
    summary: Object,
    filters: Object,
});

const monthNames = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];

const monthName = monthNames[parseInt(props.filters.month) - 1];

const periodLabel = computed(() => {
    if (props.filters.start_date && props.filters.end_date) {
        const fmt = (d) => new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        return fmt(props.filters.start_date) + ' — ' + fmt(props.filters.end_date);
    }
    return monthName + ' ' + props.filters.year;
});

const currentDate = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value || 0);
};

const print = () => {
    const params = {
        id: props.boardingHouse.id,
        month: props.filters.month,
        year: props.filters.year,
    };
    if (props.filters.start_date && props.filters.end_date) {
        params.start_date = props.filters.start_date;
        params.end_date = props.filters.end_date;
    }
    window.open(route('admin.financial-reports.recap.print', params), '_blank');
};
</script>

<style>
@media print {
    @page {
        size: landscape;
        margin: 0.5cm;
    }

    body {
        background: white !important;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    /* Preserve all background colors */
    .bg-blue-50 {
        background-color: #eff6ff !important;
    }

    .bg-pink-50 {
        background-color: #fdf2f8 !important;
    }

    .bg-primary-50 {
        background-color: #fff1f2 !important;
    }

    .bg-gray-50 {
        background-color: #f9fafb !important;
    }

    .bg-gray-100 {
        background-color: #f3f4f6 !important;
    }

    .bg-white {
        background-color: #ffffff !important;
    }

    /* Preserve text colors */
    .text-primary-600 {
        color: #dc2626 !important;
    }

    .text-emerald-600 {
        color: #059669 !important;
    }

    .text-primary-600 {
        color: #e11d48 !important;
    }

    .text-blue-600 {
        color: #2563eb !important;
    }

    .text-green-700 {
        color: #15803d !important;
    }

    .text-primary-700 {
        color: #b91c1c !important;
    }

    /* Preserve badge backgrounds */
    .bg-green-100 {
        background-color: #dcfce7 !important;
    }

    .bg-primary-100 {
        background-color: #fee2e2 !important;
    }

    /* Preserve borders */
    .border-primary-500 {
        border-color: #6366f1 !important;
    }

    .border-primary-500 {
        border-color: #ef4444 !important;
    }

    .border-primary-500 {
        border-color: #f43f5e !important;
    }

    .border-gray-500 {
        border-color: #6b7280 !important;
    }

    .border-gray-200 {
        border-color: #e5e7eb !important;
    }

    .border-gray-300 {
        border-color: #d1d5db !important;
    }

    /* Ensure rounded corners are visible */
    .rounded-lg,
    .rounded-xl {
        border-radius: 0.5rem !important;
    }

    .rounded {
        border-radius: 0.25rem !important;
    }

    /* Keep padding consistent */
    .p-8 {
        padding: 2rem !important;
    }

    .p-6 {
        padding: 1.5rem !important;
    }

    .p-4 {
        padding: 1rem !important;
    }

    .p-3 {
        padding: 0.75rem !important;
    }

    /* Ensure tables display properly */
    table {
        border-collapse: collapse !important;
        width: 100% !important;
    }

    /* Keep shadows subtle for print */
    .shadow-lg {
        box-shadow: none !important;
    }

    /* Ensure grid layout works in print */
    .grid {
        display: grid !important;
    }

    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
    }

    .md\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .gap-8 {
        gap: 2rem !important;
    }

    /* Fallback for browsers that don't support grid in print */
    @supports not (display: grid) {
        .grid {
            display: flex !important;
            flex-wrap: wrap !important;
        }

        .grid>div {
            flex: 1 1 45% !important;
            margin: 1rem !important;
        }
    }

    /* Prevent page breaks inside important sections */
    .grid>div {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
}
</style>
