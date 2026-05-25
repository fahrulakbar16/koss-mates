<template>
    <AppLayout title="Pengembalian Dana Saya">
        <template #header>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                Pengembalian Dana
            </h2>
        </template>

        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8 sm:py-12">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                 <!-- Header -->
                    <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Pengembalian Dana Saya</h1>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                                Daftar pengembalian dana dari perpindahan kamar Anda
                            </p>
                        </div>
                    </div>
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 min-h-[60vh] flex flex-col overflow-hidden">

                    <div class="overflow-x-auto p-4 sm:p-6 flex-1">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Bukti Transfer</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="refund in refunds.data" :key="refund.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ formatDate(refund.created_at) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-primary-600 dark:text-primary-400">Rp {{ formatCurrency(refund.amount)
                                            }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="statusClass(refund.status)"
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm">
                                            {{ statusLabel(refund.status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <a v-if="refund.proof" :href="'/storage/' + refund.proof" target="_blank"
                                            class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium flex items-center gap-1.5 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Bukti
                                        </a>
                                        <span v-else class="text-gray-400 dark:text-gray-500 italic">-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button v-if="refund.status === 'menunggu konfirmasi'"
                                            @click="confirmReceipt(refund)"
                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-500 hover:to-green-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider shadow-sm shadow-green-500/30 hover:shadow-md transition-all active:scale-95">
                                            Konfirmasi Diterima
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="refunds.data.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            <span class="text-base font-medium">Belum ada pengembalian dana.</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    refunds: Object,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID').format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric'
    });
};

const statusClass = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'process': return 'bg-blue-100 text-blue-800';
        case 'menunggu konfirmasi': return 'bg-orange-100 text-orange-800';
        case 'selesai': return 'bg-green-100 text-green-800';
        case 'ditolak': return 'bg-primary-100 text-primary-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const statusLabel = (status) => {
    switch (status) {
        case 'pending': return 'Menunggu Diproses';
        case 'process': return 'Sedang Diproses';
        case 'menunggu konfirmasi': return 'Konfirmasi Penerimaan';
        case 'selesai': return 'Selesai';
        case 'ditolak': return 'Ditolak';
        default: return status;
    }
}

const confirmReceipt = (refund) => {
    if (confirm('Apakah Anda yakin dana pengembalian sudah masuk ke rekening Anda?')) {
        router.post(route('penyewa.refunds.confirm', refund.id));
    }
};
</script>
