<template>
    <Head title="Akun Penyewa" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Akun Penyewa</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Kelola akun penyewa yang memiliki kamar
                </p>
            </div>
            <Link v-if="is('Superadmin') || is('Pengelola')" :href="route('admin.tenants.create')"
                class="inline-flex gap-2 items-center px-5 py-2.5 text-white rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transition-all duration-300 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="font-medium">Tambah Penyewa</span>
            </Link>
        </div>

        <div class="h-full flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <!-- Filter & Search Bar -->
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <SearchIcon class="w-5 h-5 text-gray-400" />
                        </div>
                        <input v-model="searchQuery" type="text" placeholder="Cari penyewa..."
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500" />
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="overflow-x-auto pb-8 flex-1" data-simplebar>
                <div v-if="tenants.data && tenants.data.length > 0">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Penyewa
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Kontak
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Kamar Aktif
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Tagihan Pending
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Status Sewa
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Status Pindah
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                            <tr v-for="tenant in tenants.data" :key="tenant.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-sm font-bold text-primary-600 dark:text-primary-400 shrink-0 border border-primary-200 dark:border-primary-800/50">
                                            {{ tenant.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ tenant.name }}
                                            </div>
                                            <div v-if="tenant.username" class="text-xs text-gray-500 dark:text-gray-400">
                                                @{{ tenant.username }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ tenant.email }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ tenant.phone || '-' }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ tenant.room_name || '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ tenant.boarding_house_name || '-' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span :class="[
                                        'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border',
                                        tenant.is_has_pending_transactions
                                            ? 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800'
                                            : 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800'
                                    ]">
                                        {{ tenant.is_has_pending_transactions ? 'Ada' : 'Tidak Ada' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span :class="[
                                        'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border capitalize',
                                        tenant.room_status === 'checked_in'
                                            ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800'
                                            : tenant.room_status === 'booked'
                                            ? 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800'
                                            : 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-900/30 dark:text-gray-400 dark:border-gray-800'
                                    ]">
                                        {{ tenant.room_status === 'checked_in' ? 'Ditempati' : tenant.room_status === 'booked' ? 'Dipesan' : (tenant.room_status || '-') }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span :class="[
                                        'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border',
                                        tenant.is_moved
                                            ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800'
                                            : 'bg-yellow-50 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800'
                                    ]">
                                        <span class="w-1.5 h-1.5 rounded-full mr-2" :class="[
                                            tenant.is_moved ? 'bg-green-500' : 'bg-yellow-500'
                                        ]"></span>
                                        {{ tenant.is_moved ? 'YA' : 'TIDAK' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link :href="route('admin.tenants.show', tenant.id)"
                                            class="p-2.5 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-xl transition-all shadow-sm"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link v-if="is('Superadmin') || is('Pengelola')" :href="route('admin.tenants.edit', tenant.id)"
                                            class="p-2.5 text-yellow-600 bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50 rounded-xl transition-all shadow-sm"
                                            title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </Link>
                                        <button v-if="is('Superadmin') || is('Pengelola')" @click="confirmDelete(tenant)"
                                            class="p-2.5 text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 rounded-xl transition-all shadow-sm"
                                            title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-else
                    class="text-center py-20 bg-gray-50/50 dark:bg-gray-800/50 rounded-2xl border-dashed border border-gray-200 dark:border-gray-700 mx-4 my-6">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada Penyewa</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                        Belum ada data penyewa kos yang ditemukan. Silakan tambahkan penyewa baru.
                    </p>
                    <Link v-if="is('Superadmin') || is('Pengelola')" :href="route('admin.tenants.create')"
                        class="inline-flex gap-2 items-center px-5 py-2.5 text-white rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transition-all duration-300 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Sekarang
                    </Link>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="tenants.data && tenants.data.length > 0" class="border-t border-gray-100 dark:border-gray-700 p-4">
                <Pagination :pagination="tenants.meta" />
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal"
            class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-md w-full p-8 transform transition-all scale-100">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-6 border border-red-200 dark:border-red-800/50">
                    <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">
                    Hapus Akun Penyewa
                </h3>
                <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                    Apakah Anda yakin ingin menghapus akun <strong>{{ tenantToDelete?.name }}</strong>?
                    Semua data terkait akan dihapus. Aksi ini tidak dapat dibatalkan.
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <button @click="showDeleteModal = false"
                        class="w-full px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white border border-gray-200 dark:border-gray-600 dark:bg-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm active:scale-95">
                        Batal
                    </button>
                    <button @click="deleteTenant"
                        class="w-full px-4 py-3 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-lg shadow-red-600/30 transition-all active:scale-95">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Link, router, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/common/Pagination.vue';
import SearchIcon from '@/Components/icons/SearchIcon.vue';
import { useAuth } from '@/Composables/useAuth';

defineOptions({
    layout: AppLayout,
});

const { is } = useAuth();

const props = defineProps({
    tenants: {
        type: Object,
        required: true,
    },
    search: {
        type: String,
        default: '',
    }
});

const searchQuery = ref(props.search);

let timeout = null;
watch(searchQuery, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(
            route('admin.tenants.index'),
            { search: value },
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, 300);
});

const showDeleteModal = ref(false);
const tenantToDelete = ref(null);

function confirmDelete(tenant) {
    tenantToDelete.value = tenant;
    showDeleteModal.value = true;
}

function deleteTenant() {
    if (tenantToDelete.value) {
        router.delete(route('admin.tenants.destroy', tenantToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                tenantToDelete.value = null;
            },
        });
    }
}
</script>
