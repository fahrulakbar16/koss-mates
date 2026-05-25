<template>

    <Head :title="`Laporan Keuangan - ${boardingHouse.name}`" />

    <div class="space-y-6 px-4 py-4 md:px-6 md:py-8 font-sans">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="space-y-1">
                <Breadcrumb :items="breadcrumbs" class="text-sm" />
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ boardingHouse.name }}
                </h2>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <HomeIcon class="h-4 w-4" />
                    <span>{{ boardingHouse.address }}</span>
                </div>
            </div>

            <div
                class="flex items-center gap-0.5 rounded-lg bg-white p-1 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 w-fit">
                <select v-model="monthFilter"
                    class="h-9 border-0 bg-transparent py-0 pl-3 pr-8 text-sm font-medium text-gray-700 outline-none focus:ring-0 dark:text-gray-200 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-md transition-colors">
                    <option v-for="(name, index) in monthNames" :key="index" :value="index + 1">
                        {{ name }}
                    </option>
                </select>
                <div class="h-5 w-px bg-gray-200 dark:bg-gray-700 mx-1"></div>
                <select v-model="yearFilter"
                    class="h-9 border-0 bg-transparent py-0 pl-3 pr-8 text-sm font-medium text-gray-700 outline-none focus:ring-0 dark:text-gray-200 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-md transition-colors">
                    <option v-for="y in years" :key="y" :value="y">
                        {{ y }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Pemasukan -->
            <div
                class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</p>
                        <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ formatCurrency(summary.total_income) }}
                        </h3>
                    </div>
                    <div class="rounded-lg bg-emerald-50 p-3 dark:bg-emerald-900/20">
                        <MoneyIcon class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">
                        {{ incomes.length }} Transaksi
                    </span>
                    <span class="mx-2 text-gray-300 dark:text-gray-600">•</span>
                    <span class="text-gray-500 dark:text-gray-400">Bulan ini</span>
                </div>
            </div>

            <!-- Pengeluaran -->
            <div
                class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                        <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                            {{ formatCurrency(summary.total_expense) }}
                        </h3>
                    </div>
                    <div class="rounded-lg bg-primary-50 p-3 dark:bg-primary-900/20">
                        <ChartIcon class="h-6 w-6 text-primary-600 dark:text-primary-400" />
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-primary-600 dark:text-primary-400 font-medium">
                        {{ expenses.length }} Item
                    </span>
                    <span class="mx-2 text-gray-300 dark:text-gray-600">•</span>
                    <span class="text-gray-500 dark:text-gray-400">Bulan ini</span>
                </div>
            </div>

            <!-- Laba Bersih -->
            <div
                class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Laba Bersih</p>
                        <h3
                            :class="['mt-2 text-2xl font-bold', summary.net_profit >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-primary-600 dark:text-primary-400']">
                            {{ formatCurrency(summary.net_profit) }}
                        </h3>
                    </div>
                    <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                        <BriefCase class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span
                        :class="['font-medium', summary.net_profit >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-primary-600 dark:text-primary-400']">
                        {{ summary.total_income > 0 ? ((summary.net_profit / summary.total_income) * 100).toFixed(1) : 0
                        }}% Margin
                    </span>
                    <span class="mx-2 text-gray-300 dark:text-gray-600">•</span>
                    <span class="text-gray-500 dark:text-gray-400">Net Profit</span>
                </div>
            </div>
        </div>

        <!-- Share Breakdown (Optional) -->
        <div v-if="summary.owner_share !== undefined" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
                class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                            Bagi Hasil Pemilik ({{ boardingHouse.persentasi_pemilik ?? 100 }}%)
                        </p>
                        <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-white">
                            {{ formatCurrency(summary.owner_share) }}
                        </h3>
                    </div>
                </div>
                <div class="absolute -right-4 -top-4 opacity-[0.03] dark:opacity-[0.05]">
                    <MoneyIcon class="h-24 w-24" />
                </div>
            </div>
            <div
                class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                            Porsi Pengelola ({{ 100 - (boardingHouse.persentasi_pemilik ?? 100) }}%)
                        </p>
                        <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-white">
                            {{ formatCurrency(summary.management_share) }}
                        </h3>
                    </div>
                </div>
                <div class="absolute -right-4 -top-4 opacity-[0.03] dark:opacity-[0.05]">
                    <BriefCase class="h-24 w-24" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Income Table -->
            <div
                class="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 h-[500px]">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Rincian Pemasukan
                    </h3>
                </div>
                <div class="flex-1 overflow-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead
                            class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-700/50 dark:text-gray-400 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Tanggal</th>
                                <th class="px-6 py-3 font-semibold">Keterangan</th>
                                <th class="px-6 py-3 font-semibold text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            <template v-if="incomes.length > 0">
                                <tr v-for="income in incomes" :key="income.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        {{ formatDate(income.transaction_date) }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ income.description || 'Pemasukan Lain' }}
                                        </div>
                                        <div v-if="income.room" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ income.room.name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right font-medium text-emerald-600 dark:text-emerald-400">
                                        + {{ formatCurrency(income.amount) }}
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-full bg-gray-50 p-3 dark:bg-gray-800">
                                            <SearchIcon class="h-6 w-6 text-gray-400" />
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada data
                                            pemasukan</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Expense Table -->
            <div
                class="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 h-[500px]">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-primary-500"></span>
                        Rincian Pengeluaran
                    </h3>
                </div>
                <div class="flex-1 overflow-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead
                            class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-700/50 dark:text-gray-400 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Tanggal</th>
                                <th class="px-6 py-3 font-semibold">Keterangan</th>
                                <th class="px-6 py-3 font-semibold text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            <template v-if="expenses.length > 0">
                                <tr v-for="expense in expenses" :key="expense.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        {{ formatDate(expense.expense_date) }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ expense.category }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">
                                            {{ expense.description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right font-medium text-primary-600 dark:text-primary-400">
                                        - {{ formatCurrency(expense.amount) }}
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-full bg-gray-50 p-3 dark:bg-gray-800">
                                            <SearchIcon class="h-6 w-6 text-gray-400" />
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada data
                                            pengeluaran</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import MoneyIcon from "@/Components/icons/MoneyIcon.vue";
import ChartIcon from "@/Components/icons/ChartIcon.vue";
import BriefCase from "@/Components/icons/BriefCase.vue";
import HomeIcon from "@/Components/icons/HomeIcon.vue";
import SearchIcon from "@/Components/icons/SearchIcon.vue";
import { ref, watch } from "vue";
import { router, Head } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    boardingHouse: Object,
    incomes: Array,
    expenses: Array,
    summary: Object,
    filters: Object,
});

const breadcrumbs = [
    { label: "Laporan", href: route('admin.financial-reports.index') },
    { label: "Keuangan", href: route('admin.financial-reports.index') },
    { label: "Detail" }
];

const monthNames = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];

const currentYear = new Date().getFullYear();
const years = Array.from({ length: 5 }, (_, i) => currentYear - i);

const monthFilter = ref(parseInt(props.filters.month) || new Date().getMonth() + 1);
const yearFilter = ref(parseInt(props.filters.year) || currentYear);

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

const fetchData = () => {
    router.get(
        route("admin.financial-reports.show", { id: props.boardingHouse.id }),
        {
            month: monthFilter.value,
            year: yearFilter.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }
    );
};

watch([monthFilter, yearFilter], () => {
    fetchData();
});
</script>
