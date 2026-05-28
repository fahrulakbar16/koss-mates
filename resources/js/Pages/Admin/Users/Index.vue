<template>
    <Head title="Manajemen Pengelola" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Manajemen Pengelola</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">Kelola akun pengelola dan hak akses sistem</p>
            </div>
            <button
                v-if="can('users.create')"
                @click="openAddModal"
                class="inline-flex gap-2 items-center px-5 py-2.5 text-white rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transition-all duration-300 transform hover:-translate-y-0.5"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="font-medium">Tambah Pengguna</span>
            </button>
        </div>

        <!-- Card Container -->
        <div class="flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">

            <!-- Filter Bar -->
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <!-- Role Filter -->
                    <select v-model="roleFilter"
                        class="px-3 py-2 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
                        <option value="">Semua Peran</option>
                        <option v-for="role in props.roles" :key="role.id" :value="role.name">{{ role.name }}</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="relative w-full sm:w-64">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                        <SearchIcon class="w-4 h-4 text-gray-400" />
                    </div>
                    <input v-model="search" type="text" placeholder="Cari pengguna..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500" />
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto flex-1" data-simplebar>
                <table v-if="users.data && users.data.length > 0" class="w-full text-left border-collapse min-w-[800px]">
                    <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Pengguna
                            </th>
                            <th @click="changeSort('username')" class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer select-none hover:text-gray-700 dark:hover:text-gray-300">
                                <div class="flex items-center gap-1">
                                    Username
                                    <span class="flex flex-col ml-1">
                                        <UpIcon :class="['-mb-1 w-3 h-3', sortBy === 'username' && sortDirection === 'asc' ? 'text-primary-600' : 'text-gray-300 dark:text-gray-600']" />
                                        <DownIcon :class="['-mt-1 w-3 h-3', sortBy === 'username' && sortDirection === 'desc' ? 'text-primary-600' : 'text-gray-300 dark:text-gray-600']" />
                                    </span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Peran
                            </th>
                            <th @click="changeSort('last_seen')" class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer select-none hover:text-gray-700 dark:hover:text-gray-300">
                                <div class="flex items-center gap-1">
                                    Terakhir Online
                                    <span class="flex flex-col ml-1">
                                        <UpIcon :class="['-mb-1 w-3 h-3', sortBy === 'last_seen' && sortDirection === 'asc' ? 'text-primary-600' : 'text-gray-300 dark:text-gray-600']" />
                                        <DownIcon :class="['-mt-1 w-3 h-3', sortBy === 'last_seen' && sortDirection === 'desc' ? 'text-primary-600' : 'text-gray-300 dark:text-gray-600']" />
                                    </span>
                                </div>
                            </th>
                            <th v-if="can('users.edit') || can('users.delete')"
                                class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50 bg-white dark:bg-gray-900">
                        <tr v-for="(user, index) in users.data" :key="user.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <!-- Pengguna -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="relative flex-shrink-0">
                                        <img v-if="user.profile_photo_path"
                                            :src="`/storage/${user.profile_photo_path}`"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-gray-100 dark:border-gray-700"
                                            :alt="user.name" />
                                        <div v-else
                                            class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-sm font-bold text-primary-600 dark:text-primary-400 border-2 border-primary-200/50 dark:border-primary-800/50">
                                            {{ (user.name || 'U').charAt(0).toUpperCase() }}
                                        </div>
                                        <span v-if="user.last_seen && isOnline(user.last_seen)"
                                            class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-900 rounded-full"></span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ user.name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ user.email }}</div>
                                    </div>
                                </div>
                            </td>
                            <!-- Username -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400 font-mono bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-md">
                                    {{ user.username || '-' }}
                                </span>
                            </td>
                            <!-- Peran -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold',
                                    user.roles[0]?.name === 'Superadmin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' :
                                    user.roles[0]?.name === 'Pengelola'  ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' :
                                    user.roles[0]?.name === 'Pemilik'    ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' :
                                    user.roles[0]?.name === 'Penyewa'    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                    'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400'
                                ]">
                                    {{ user.roles[0]?.name || 'Tanpa Peran' }}
                                </span>
                            </td>
                            <!-- Terakhir Online -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div v-if="user.last_seen && isOnline(user.last_seen)"
                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-teal-600 dark:text-teal-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                                    Online
                                </div>
                                <span v-else-if="user.last_seen" class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ formatTime(user.last_seen) }}
                                </span>
                                <span v-else class="text-xs text-gray-400 italic">Belum pernah login</span>
                            </td>
                            <!-- Aksi -->
                            <td v-if="can('users.edit') || can('users.delete')" class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button v-if="can('users.edit')" @click.stop="openEditModal(user)"
                                        class="p-2.5 text-yellow-600 bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400 dark:hover:bg-yellow-900/50 rounded-xl transition-all shadow-sm"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button v-if="can('users.delete')" @click.stop="openConfirmModal(user)"
                                        class="p-2.5 text-primary-600 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:text-primary-400 dark:hover:bg-primary-900/50 rounded-xl transition-all shadow-sm"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div v-else class="text-center py-20 mx-4 my-6 rounded-2xl border-dashed border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tidak ada pengguna ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">Coba ubah filter atau tambahkan pengguna baru.</p>
                    <button v-if="can('users.create')" @click="openAddModal"
                        class="inline-flex gap-2 items-center px-5 py-2.5 text-white rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 shadow-lg shadow-primary-500/30 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Sekarang
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="users.data && users.data.length > 0" class="border-t border-gray-100 dark:border-gray-700 p-4">
                <Pagination :pagination="users" />
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-lg w-full p-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                {{ isEditMode ? `Edit ${selectedItem?.name}` : 'Tambah Pengguna Baru' }}
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Lengkap</label>
                    <input v-model="form.name" type="text" placeholder="Masukkan nama lengkap"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all" />
                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Username</label>
                    <input v-model="form.username" type="text" placeholder="Masukkan username"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all" />
                    <p v-if="form.errors.username" class="mt-1 text-xs text-red-500">{{ form.errors.username }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                    <input v-model="form.email" type="email" placeholder="Masukkan email"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-white placeholder:text-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all" />
                    <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Peran</label>
                    <select v-model="form.role"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 text-sm text-gray-900 dark:text-white focus:bg-white dark:focus:bg-gray-900 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
                        <option value="">Pilih peran</option>
                        <option v-for="role in props.roles" :key="role.name" :value="role.name">{{ role.name }}</option>
                    </select>
                    <p v-if="form.errors.role" class="mt-1 text-xs text-red-500">{{ form.errors.role }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-8">
                <button @click="closeModal"
                    class="w-full px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white border border-gray-200 dark:border-gray-600 dark:bg-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                    Batal
                </button>
                <button @click="saveUser"
                    class="w-full px-4 py-3 text-sm font-bold text-white bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 rounded-xl shadow-lg shadow-primary-600/30 transition-all active:scale-95">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div v-if="isConfirmModalOpen" class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 max-w-md w-full p-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary-100 dark:bg-primary-900/30 mb-6 border border-primary-200 dark:border-primary-800/50">
                <svg class="h-8 w-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">Hapus Pengguna</h3>
            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                Yakin ingin menghapus akun <strong>{{ selectedItem?.name }}</strong>? Aksi ini tidak dapat dibatalkan.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <button @click="closeConfirmModal"
                    class="w-full px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white border border-gray-200 dark:border-gray-600 dark:bg-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                    Batal
                </button>
                <button @click="destroyData"
                    class="w-full px-4 py-3 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-600/30 transition-all active:scale-95">
                    Ya, Hapus!
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PlusSquareIcon from "@/Components/icons/PlusSquareIcon.vue";
import SearchIcon from "@/Components/icons/SearchIcon.vue";
import UpIcon from "@/Components/icons/UpIcon.vue";
import DownIcon from "@/Components/icons/DownIcon.vue";
import Pagination from "@/Components/common/Pagination.vue";
import { useAuth } from "@/Composables/useAuth";
import { ref, watch, computed } from "vue";
import { useForm, router, Head } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    users: Object,
    roles: Object,
    search: String,
    role: String,
    sortBy: String,
    sortDirection: String,
});

const { can } = useAuth();


const formatTime = (date) => {
    if (!date) return "-";
    return new Intl.DateTimeFormat("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    }).format(new Date(date));
};

const isOnline = (lastSeen) => {
    if (!lastSeen) return false;
    const now = new Date();
    const seen = new Date(lastSeen);
    const diffMinutes = (now - seen) / (1000 * 60);
    return diffMinutes < 2; // kurang dari 2 menit
};

function fetchUsers({
    sortBy = props.sortBy,
    sortDirection = props.sortDirection,
} = {}) {
    router.get(
        route("users.index"),
        {
            search: search.value,
            role: roleFilter.value,
            sortBy,
            sortDirection,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }
    );
}

const search = ref(props.search || "");
const roleFilter = ref(props.role || "");

let timeout = null;
watch(search, () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        fetchUsers();
    }, 400);
});

watch(roleFilter, () => {
    fetchUsers();
});

function changeSort(column) {
    let direction = "asc";
    if (props.sortBy === column && props.sortDirection === "asc") {
        direction = "desc";
    }
    fetchUsers({ sortBy: column, sortDirection: direction });
}

const isModalOpen = ref(false);
const isEditMode = ref(false);

const form = useForm({
    id: null,
    name: "",
    username: "",
    email: "",
    role: "",
});

// Buka modal untuk tambah
function openAddModal() {
    if (!can("users.create")) {
        return;
    }
    form.reset();
    isEditMode.value = false;
    isModalOpen.value = true;
}

// Buka modal untuk edit
function openEditModal(user) {
    if (!can("users.edit")) {
        return;
    }
    form.id = user.id;
    form.name = user.name;
    form.username = user.username;
    form.email = user.email;
    form.role = user.roles[0].name;
    selectedItem.value = user;
    isEditMode.value = true;
    isModalOpen.value = true;
}

function closeModal() {
    isModalOpen.value = false;
    selectedItem.value = null;
    form.reset();
    form.clearErrors();
}

// Simpan (otomatis create/update)
function saveUser() {
    if (isEditMode.value) {
        if (!can("users.edit")) {
            return;
        }
        form.put(route("users.update", form.id), {
            onSuccess: closeModal,
        });
    } else {
        if (!can("users.create")) {
            return;
        }
        form.post(route("users.store"), {
            onSuccess: closeModal,
        });
    }
}

// destroy
const selectedItem = ref(null);

const isConfirmModalOpen = ref(false);
const openConfirmModal = (item) => {
    if (!can("users.delete")) {
        return;
    }
    selectedItem.value = item;
    isConfirmModalOpen.value = true;
};
const closeConfirmModal = () => {
    selectedItem.value = null;
    isConfirmModalOpen.value = false;
};
const destroyData = () => {
    if (!can("users.delete")) {
        return;
    }
    router.delete(route("users.destroy", selectedItem.value.id), {
        onSuccess: () => {
            closeConfirmModal();
        },
        preserveScroll: true,
    });
};
</script>
