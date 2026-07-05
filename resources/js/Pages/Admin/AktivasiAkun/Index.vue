<template>
    <Head title="Aktivasi Akun" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Aktivasi Akun</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Kelola pengajuan aktivasi akun penyewa
                </p>
            </div>
        </div>

        <div class="h-full flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <!-- Content Area -->
            <div class="overflow-x-auto pb-8 flex-1" data-simplebar>
                <div v-if="aktivasi && aktivasi.length > 0">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Pengguna</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Kontak & NIK</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Info Sewa</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Tanggal Pengajuan</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                            <tr v-for="item in aktivasi" :key="item.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ item.user?.name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ item.user?.email }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ item.phone }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ item.id_card_number }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">Paket: {{ item.payment_package }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Masuk: {{ item.entry_date }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ new Date(item.created_at).toLocaleDateString() }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span :class="[
                                        'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border capitalize',
                                        item.status === 'approved'
                                            ? 'bg-green-50 text-green-700 border-green-200'
                                            : item.status === 'rejected'
                                            ? 'bg-red-50 text-red-700 border-red-200'
                                            : 'bg-yellow-50 text-yellow-700 border-yellow-200'
                                    ]">
                                        {{ item.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2" v-if="item.status === 'pending'">
                                        <Link :href="route('admin.aktivasi-akun.edit', item.id)"
                                            class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl transition-all shadow-sm"
                                            title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </Link>
                                        <button @click="approve(item.id)"
                                            class="p-2 text-green-600 bg-green-50 hover:bg-green-100 rounded-xl transition-all shadow-sm"
                                            title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                        <button @click="reject(item.id)"
                                            class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all shadow-sm"
                                            title="Tolak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                    <div v-else class="text-gray-400 text-xs">-</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="text-center py-20 bg-gray-50/50 dark:bg-gray-800/50 rounded-2xl border-dashed border border-gray-200 dark:border-gray-700 mx-4 my-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada pengajuan aktivasi</h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Swal from 'sweetalert2';

defineOptions({
    layout: AppLayout,
});

defineProps({
    aktivasi: {
        type: Array,
        required: true,
    }
});

function approve(id) {
    Swal.fire({
        title: 'Setujui Aktivasi?',
        text: 'Akun penyewa akan aktif dan dapat masuk ke aplikasi.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route('admin.aktivasi-akun.approve', id));
        }
    });
}

function reject(id) {
    Swal.fire({
        title: 'Tolak Aktivasi?',
        text: 'Data aktivasi ini akan ditolak dan penyewa dapat mengajukan ulang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444'
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route('admin.aktivasi-akun.reject', id));
        }
    });
}
</script>
