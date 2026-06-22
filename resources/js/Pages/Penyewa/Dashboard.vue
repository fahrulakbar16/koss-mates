<template>

    <Head title="Dashboard Penyewa" />

    <div class="p-6 space-y-8">
        <!-- Header -->
        <div class="flex items-end justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Dashboard
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    Selamat datang kembali, kelola hunian Anda dengan mudah
                </p>
            </div>
            <div class="hidden sm:block">
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-600 dark:text-gray-300 shadow-sm">
                    {{ new Date().toLocaleDateString('id-ID', {
                        weekday: 'long', year: 'numeric', month: 'long', day:
                            'numeric'
                    }) }}
                </span>
            </div>
        </div>

        <!-- Welcome Card -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl shadow-xl shadow-primary-500/20 p-8 text-white">
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-bold mb-3">
                        Halo, {{ user?.name || 'Penyewa' }}! 👋
                    </h2>
                    <p class="text-primary-100 text-lg max-w-xl">
                        Senang melihat Anda kembali. Cek status sewa dan tagihan Anda hari ini.
                    </p>
                </div>
                <div class="p-4 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20">
                    <Home class="w-10 h-10 text-white" />
                </div>
            </div>

            <!-- Decorational Patterns -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Sewa -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-theme-sm hover:shadow-theme-md transition-all duration-300 border border-gray-100 dark:border-gray-700 group">
                <div class="flex items-center gap-5">
                    <div
                        class="p-4 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <FileText class="w-7 h-7" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Total Riwayat Sewa
                        </p>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ stats?.total_sewa || 0 }}
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Sewa Aktif -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-theme-sm hover:shadow-theme-md transition-all duration-300 border border-gray-100 dark:border-gray-700 group">
                <div class="flex items-center gap-5">
                    <div
                        class="p-4 rounded-2xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                        <CheckCircle class="w-7 h-7" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Kamar Aktif
                        </p>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ stats?.sewa_aktif || 0 }}
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Pending -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-theme-sm hover:shadow-theme-md transition-all duration-300 border border-gray-100 dark:border-gray-700 group">
                <div class="flex items-center gap-5">
                    <div
                        class="p-4 rounded-2xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                        <Clock class="w-7 h-7" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                            Menunggu Pembayaran
                        </p>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ stats?.pembayaran_pending || 0 }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Quick Actions -->
            <div class="lg:col-span-2 space-y-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-primary-600 rounded-full"></span>
                    Akses Cepat
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <Link :href="route('penyewa.rooms.index')"
                        class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-lg hover:border-primary-500/50 hover:-translate-y-1 transition-all duration-300 group">
                        <div
                            class="w-12 h-12 rounded-2xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600 dark:text-primary-400 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300 mb-3">
                            <Home class="w-6 h-6" />
                        </div>
                        <span
                            class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-primary-600 dark:group-hover:text-primary-400">
                            Kamar Saya
                        </span>
                    </Link>

                    <Link :href="route('penyewa.transactions.index')"
                        class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-lg hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300 group">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 mb-3">
                            <CreditCard class="w-6 h-6" />
                        </div>
                        <span
                            class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400">
                            Tagihan
                        </span>
                    </Link>

                    <button
                        class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-lg hover:border-green-500/50 hover:-translate-y-1 transition-all duration-300 group">
                        <div
                            class="w-12 h-12 rounded-2xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300 mb-3">
                            <MessageSquare class="w-6 h-6" />
                        </div>
                        <span
                            class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-green-600 dark:group-hover:text-green-400">
                            Pesan
                        </span>
                    </button>

                    <Link :href="route('profile.show')"
                        class="flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-lg hover:border-purple-500/50 hover:-translate-y-1 transition-all duration-300 group">
                        <div
                            class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300 mb-3">
                            <User class="w-6 h-6" />
                        </div>
                        <span
                            class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400">
                            Profil
                        </span>
                    </Link>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="lg:col-span-1 space-y-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-primary-600 rounded-full"></span>
                    Aktivitas Terbaru
                </h3>
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="space-y-6">
                        <div v-if="!activities || activities.length === 0"
                            class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <div
                                class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                <Bell class="w-8 h-8 text-gray-300 dark:text-gray-500" />
                            </div>
                            <p class="text-sm">Belum ada aktivitas terbaru</p>
                        </div>
                        <div v-for="(activity, index) in activities" :key="index"
                            class="relative pl-6 pb-6 last:pb-0 border-l border-gray-200 dark:border-gray-700 last:border-l-0">
                            <!-- Timeline Dot -->
                            <div
                                class="absolute left-[-5px] top-1 w-2.5 h-2.5 rounded-full bg-primary-500 ring-4 ring-white dark:ring-gray-800">
                            </div>

                            <div class="flex flex-col">
                                <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">
                                    {{ activity.title }}
                                </p>
                                <span
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded w-fit">
                                    {{ activity.date }}
                                </span>
                            </div>
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
import {
    Home,
    FileText,
    CheckCircle,
    Clock,
    CreditCard,
    MessageSquare,
    User,
    Bell,
} from "lucide-vue-next";

defineOptions({
    layout: AppLayout,
});

const { user } = useAuth();

// Props from backend
const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_sewa: 0,
            sewa_aktif: 0,
            pembayaran_pending: 0,
        }),
    },
    activities: {
        type: Array,
        default: () => [],
    },
});
</script>
