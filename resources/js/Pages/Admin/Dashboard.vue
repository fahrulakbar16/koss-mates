<template>

    <Head title="Dashboard" />

    <div class="flex flex-col gap-8 px-4 sm:px-6 lg:px-8 py-8 h-full">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Dashboard Pengelola</h1>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                        Selamat datang kembali, mari kita lihat perkembangan kos Anda hari ini.
                    </p>
                </div>
            </div>

        <!-- Top Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Monthly Income -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-xl">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Total Pemasukan Bulan Ini</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ formatCurrency(metrics.monthly_income) }}
                </h2>
                <div class="flex items-center gap-1">
                    <span :class="[
                        'text-xs font-medium flex items-center gap-1',
                        metrics.income_percentage >= 0
                            ? 'text-green-600 dark:text-green-400'
                            : 'text-primary-600 dark:text-primary-400'
                    ]">
                        <component :is="metrics.income_percentage >= 0 ? TrendingUpIcon : TrendingDownIcon"
                            class="w-3 h-3" />
                        {{ Math.abs(metrics.income_percentage) }}%
                    </span>
                </div>
            </div>

            <!-- Monthly Expenses -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-xl">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Total Pengeluaran Bulan Ini</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ formatCurrency(metrics.monthly_expenses) }}
                </h2>
                <div class="flex items-center gap-1">
                    <span :class="[
                        'text-xs font-medium flex items-center gap-1',
                        metrics.expense_percentage <= 0
                            ? 'text-green-600 dark:text-green-400'
                            : 'text-primary-600 dark:text-primary-400'
                    ]">
                        <component :is="metrics.expense_percentage <= 0 ? TrendingDownIcon : TrendingUpIcon"
                            class="w-3 h-3" />
                        {{ Math.abs(metrics.expense_percentage) }}%
                    </span>
                </div>
            </div>

            <!-- Laba Bersih (Owner Share) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-xl">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Laba Bersih</p>
                <h2 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
                    {{ formatCurrency(metrics.owner_share) }}
                </h2>
                <div class="flex items-center gap-1">
                    <BriefcaseIcon class="w-3 h-3 text-green-600 dark:text-green-400" />
                    <span class="text-xs font-medium text-green-600 dark:text-green-400">
                        Bagi Hasil Pemilik
                    </span>
                </div>
            </div>

            <!-- Porsi Pengelola -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-xl">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Porsi Pengelola</p>
                <h2 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
                    {{ formatCurrency(metrics.management_share) }}
                </h2>
                <div class="flex items-center gap-1">
                    <UsersIcon class="w-3 h-3 text-green-600 dark:text-green-400" />
                    <span class="text-xs font-medium text-green-600 dark:text-green-400">
                        Management Fee
                    </span>
                </div>
            </div>

            <!-- Occupied Rooms -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-xl">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Kamar Terisi</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ metrics.occupied_rooms }}/{{ metrics.total_rooms }}
                </h2>
                <div class="flex items-center gap-1">
                    <UsersIcon class="w-3 h-3 text-green-600 dark:text-green-400" />
                    <span class="text-xs font-medium text-green-600 dark:text-green-400">
                        +{{ metrics.new_tenants }} Penyewa Baru
                    </span>
                </div>
            </div>

            <!-- New Reports -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-md">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Laporan Baru</p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ metrics.new_reports }} Laporan
                </h2>
                <div class="flex items-center gap-1">
                    <span class="text-xs font-medium text-primary-600 dark:text-primary-400">
                        Perlu ditindaklanjuti
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Column: Transactions (Span 8) -->
            <div class="lg:col-span-8">
                <!-- Transactions -->
                <div
                    class="bg-white h-full dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xl transition-all hover:shadow-2xl overflow-hidden">
                    <!-- Header with Tabs -->
                    <div
                        class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Transaksi Terkini</h3>
                        <div class="flex p-1 bg-gray-50 dark:bg-gray-900/50 rounded-xl w-fit">
                            <button @click="activeTab = 'income'" :class="[
                                'px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200',
                                activeTab === 'income'
                                    ? 'bg-white dark:bg-gray-800 text-primary-600 shadow-sm dark:text-primary-400'
                                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                            ]">
                                Pemasukan
                            </button>
                            <button @click="activeTab = 'expense'" :class="[
                                'px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200',
                                activeTab === 'expense'
                                    ? 'bg-white dark:bg-gray-800 text-primary-600 shadow-sm dark:text-primary-400'
                                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                            ]">
                                Pengeluaran
                            </button>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 max-h-[400px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                        <!-- Income Tab -->
                        <div v-if="activeTab === 'income'">
                            <div v-if="metrics.recent_income && metrics.recent_income.length > 0" class="space-y-1">
                                <div v-for="transaction in metrics.recent_income" :key="transaction.id"
                                    class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-green-50 dark:bg-green-900/20 rounded-xl flex items-center justify-center group-hover:bg-green-100 transition-colors">
                                        <TrendingUpIcon class="w-5 h-5 text-green-600 dark:text-green-400" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            {{ transaction.description }}
                                        </p>
                                        <p
                                            class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-0.5">
                                            {{ transaction.date }}
                                        </p>
                                    </div>
                                    <div class="text-sm font-bold text-green-600 dark:text-green-400">
                                        +{{ formatCurrency(transaction.amount) }}
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-12">
                                <TrendingUpIcon class="w-12 h-12 text-gray-200 dark:text-gray-700 mx-auto mb-3" />
                                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pemasukan</p>
                            </div>
                        </div>

                        <!-- Expense Tab -->
                        <div v-if="activeTab === 'expense'">
                            <div v-if="metrics.recent_expenses && metrics.recent_expenses.length > 0" class="space-y-1">
                                <div v-for="expense in metrics.recent_expenses" :key="expense.id"
                                    class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-orange-50 dark:bg-orange-900/20 rounded-xl flex items-center justify-center group-hover:bg-orange-100 transition-colors">
                                        <TrendingDownIcon class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            {{ expense.description }}
                                        </p>
                                        <p
                                            class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-0.5">
                                            {{ expense.date }}
                                        </p>
                                    </div>
                                    <div class="text-sm font-bold text-orange-600 dark:text-orange-400">
                                        -{{ formatCurrency(expense.amount) }}
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-12">
                                <TrendingDownIcon class="w-12 h-12 text-gray-200 dark:text-gray-700 mx-auto mb-3" />
                                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pengeluaran</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Status Unit (Span 4) -->
            <div class="lg:col-span-4">
                <!-- Boarding House Status -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xl transition-all hover:shadow-2xl overflow-hidden text-center lg:text-left h-full">
                    <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Status Unit</h3>
                    </div>

                    <div class="p-6">
                        <div v-if="metrics.boarding_houses && metrics.boarding_houses.length > 0"
                            class="grid grid-cols-1 gap-3 max-h-[500px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-gray-700 pr-2">
                            <div v-for="bh in metrics.boarding_houses" :key="bh.id"
                                class="p-4 rounded-2xl border transition-all duration-300 flex items-center justify-between"
                                :class="[
                                    bh.occupied_rooms > 0
                                        ? 'bg-green-50/50 border-green-100 dark:bg-green-900/10 dark:border-green-800/50'
                                        : 'bg-gray-50/50 border-gray-100 dark:bg-gray-700/20 dark:border-gray-600/50'
                                ]">
                                <div class="min-w-0 pr-4">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate"
                                        :title="bh.name">
                                        {{ bh.name }}
                                    </p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <div class="w-1.5 h-1.5 rounded-full"
                                            :class="bh.occupied_rooms > 0 ? 'bg-green-500' : 'bg-gray-400'"></div>
                                        <p
                                            class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tight">
                                            {{ bh.occupied_rooms }}/{{ bh.total_rooms }} TERISI
                                        </p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="relative inline-flex items-center justify-center w-10 h-10">
                                        <svg class="w-10 h-10 transform -rotate-90">
                                            <circle class="text-gray-200 dark:text-gray-700" stroke-width="3"
                                                stroke="currentColor" fill="transparent" r="16" cx="20" cy="20" />
                                            <circle :class="bh.occupied_rooms > 0 ? 'text-green-500' : 'text-gray-400'"
                                                stroke-width="3" :stroke-dasharray="100.5"
                                                :stroke-dashoffset="100.5 - (100.5 * (bh.occupied_rooms / bh.total_rooms))"
                                                stroke-linecap="round" stroke="currentColor" fill="transparent" r="16"
                                                cx="20" cy="20" />
                                        </svg>
                                        <span class="absolute text-[8px] font-black text-gray-700 dark:text-gray-300">
                                            {{ Math.round((bh.occupied_rooms / bh.total_rooms) * 100) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-12">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data unit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Reports & Activity Logs -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Column: Reports (Span 8) -->
            <div class="lg:col-span-8">
                <!-- Reports & Requests -->
                <div class="bg-white h-full dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xl transition-all hover:shadow-2xl overflow-hidden h-full">
                    <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Laporan & Permintaan</h3>
                        <Link v-if="can('users.view')" :href="route('admin.damage-reports.index')"
                            class="text-xs font-bold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 uppercase tracking-wider">
                            Lihat Semua
                        </Link>
                    </div>

                    <div class="p-6 sm:p-8 max-h-[400px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                        <div v-if="metrics.recent_reports && metrics.recent_reports.length > 0" class="space-y-3">
                            <div v-for="report in metrics.recent_reports" :key="report.id"
                                class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-all group">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110"
                                    :class="report.status === 'pending'
                                        ? 'bg-primary-50 dark:bg-primary-900/20'
                                        : 'bg-yellow-50 dark:bg-yellow-900/20'">
                                    <AlertCircleIcon class="w-6 h-6" :class="report.status === 'pending'
                                        ? 'text-primary-500 dark:text-primary-400'
                                        : 'text-yellow-500 dark:text-yellow-400'" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        {{ report.user_name }}
                                        <span
                                            class="inline-block w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                                        <span class="font-semibold text-gray-500 dark:text-gray-400">{{ report.room_name
                                            }}</span>
                                    </p>
                                    <p class="text-xs text-gray-550 dark:text-gray-400 truncate mt-0.5">
                                        {{ report.description }}
                                    </p>
                                    <p
                                        class="text-[10px] font-bold text-gray-400 dark:text-gray-500 mt-1 uppercase tracking-tighter">
                                        {{ report.created_at }}
                                    </p>
                                </div>
                                <Link v-if="can('users.view')" :href="route('admin.damage-reports.show', report.id)"
                                    class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-200 transition-all shadow-sm">
                                    <UsersIcon class="w-4 h-4" />
                                </Link>
                            </div>
                        </div>
                        <div v-else class="text-center py-12">
                            <AlertCircleIcon class="w-12 h-12 text-gray-200 dark:text-gray-700 mx-auto mb-3" />
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada laporan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Activity Logs (Span 4) -->
            <div class="lg:col-span-4">
                <!-- Activity Logs -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xl transition-all hover:shadow-2xl overflow-hidden h-full">
                    <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Aktivitas</h3>
                        <Link v-if="can('users.view')" :href="route('activity-logs.index')"
                            class="text-xs font-bold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 uppercase tracking-wider">
                            Semua
                        </Link>
                    </div>

                    <div class="p-6 max-h-[400px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                        <div v-if="metrics.recent_activities && metrics.recent_activities.length > 0"
                            class="space-y-6 relative before:absolute before:inset-y-0 before:left-4 before:w-px before:bg-gray-100 dark:before:bg-gray-700">
                            <div v-for="activity in metrics.recent_activities" :key="activity.id"
                                class="relative pl-9 group">
                                <div
                                    class="absolute left-0 top-0.5 flex-shrink-0 w-8 h-8 bg-white dark:bg-gray-800 border-2 border-primary-500 rounded-full flex items-center justify-center z-10 transition-transform group-hover:scale-110">
                                    <ActivityIcon class="w-3.5 h-3.5 text-primary-500" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white leading-snug">
                                        {{ activity.subject }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <p
                                            class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                            {{ activity.user_name }}
                                        </p>
                                        <span class="text-gray-300 dark:text-gray-600">•</span>
                                        <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500">
                                            {{ activity.human_time }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-12">
                            <ActivityIcon class="w-12 h-12 text-gray-200 dark:text-gray-700 mx-auto mb-3" />
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { useAuth } from "@/Composables/useAuth";
import { ref, computed } from "vue";
import {
    TrendingUpIcon,
    TrendingDownIcon,
    UsersIcon,
    AlertCircleIcon,
    ActivityIcon,
    BriefcaseIcon,
} from "lucide-vue-next";

defineOptions({
    layout: AppLayout,
});

const { can } = useAuth();

const props = defineProps({
    metrics: {
        type: Object,
        default: () => ({
            monthly_income: 0,
            income_percentage: 0,
            monthly_expenses: 0,
            expense_percentage: 0,
            net_profit: 0,
            owner_share: 0,
            management_share: 0,
            occupied_rooms: 0,
            total_rooms: 0,
            new_tenants: 0,
            new_reports: 0,
            boarding_houses: [],
            clusters: [],
            recent_income: [],
            recent_expenses: [],
            recent_reports: [],
            recent_activities: [],
        }),
    },
});

const activeTab = ref('income');

function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value || 0);
}
</script>
