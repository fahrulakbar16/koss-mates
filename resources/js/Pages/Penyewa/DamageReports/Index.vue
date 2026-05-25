<template>
    <Head title="Laporan Kerusakan" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Laporan Kerusakan</h1>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                        Kelola dan pantau status laporan kerusakan Anda.
                    </p>
                </div>
                <!-- Action Button -->
                <Link :href="route('penyewa.damage-reports.create')"
                    class="w-full sm:w-auto inline-flex items-center justify-center py-3 px-6 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all font-bold text-base group">
                    <svg class="w-5 h-5 mr-2 -ml-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Buat Laporan Baru</span>
                </Link>
            </div>

            <!-- Reports List -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 min-h-[60vh] flex flex-col overflow-hidden">
                <div v-if="reports.data.length > 0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kamar & Tanggal</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul Laporan</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <tr v-for="report in reports.data" :key="report.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white">
                                                {{ report.user_room?.room?.name || 'Kamar Tidak Diketahui' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ formatDate(report.created_at) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white line-clamp-1">
                                            {{ report.title }}
                                        </div>
                                        <div class="text-sm text-gray-500 line-clamp-1">
                                            {{ report.description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                            :class="{
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400': report.status === 'pending',
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400': report.status === 'processed',
                                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400': report.status === 'resolved',
                                                'bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400': report.status === 'rejected',
                                            }">
                                            {{ report.status }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                     <div v-if="reports.meta && reports.meta.last_page > 1" class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex justify-center">
                        <div class="flex items-center gap-2">
                             <Link v-for="link in reports.meta.links" :key="link.label"
                                :href="link.url || '#'"
                                class="px-3 py-1 rounded-md text-sm font-medium transition-colors"
                                :class="link.active
                                    ? 'bg-primary-600 text-white'
                                    : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800'"
                                v-html="link.label"
                                :preserve-scroll="true"
                             />
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="p-12 text-center flex-1 flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Belum ada laporan</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-sm mx-auto">Anda belum pernah membuat laporan kerusakan.</p>
                    <Link :href="route('penyewa.damage-reports.create')"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all font-bold text-base group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Buat Laporan Sekarang</span>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

defineProps({
    reports: Object,
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
};
</script>
