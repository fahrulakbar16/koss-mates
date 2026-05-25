<template>
    <Head title="Daftar Pengguna" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
            <button
                v-if="can('users.create')"
                @click="openAddModal"
                type="button"
                class="flex gap-2 items-center px-3 py-2 text-white rounded btn-primary"
            >
                <PlusSquareIcon />
                <span class="hidden text-sm md:block">Tambah Pengguna</span>
            </button>
        </div>

        <div
            class="h-[90%] grid-cols-12 gap-4 md:gap-6 overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"
        >
            <div
                class="flex flex-col gap-2 px-8 py-1 sm:flex-row sm:items-center sm:justify-between"
            >
                <div
                    class="font-bold text-gray-700 md:text-xl dark:text-gray-300"
                >
                    Daftar Pengguna
                </div>

                <div class="flex gap-3 items-center">
                    <select
                        v-model="roleFilter"
                        class="px-3 h-10 text-sm text-gray-800 bg-transparent rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 focus:border-blue-300 focus:outline-hidden focus:ring-2 focus:ring-blue-500/20"
                    >
                        <option value="">Semua Peran</option>
                        <option
                            v-for="role in props.roles"
                            :key="role.id"
                            :value="role.name"
                        >
                            {{ role.name }}
                        </option>
                    </select>
                    <div class="relative py-2">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2">
                            <SearchIcon class="text-gray-400" />
                        </div>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari pengguna"
                            class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-400 dark:focus:border-blue-800 xl:w-[200px]"
                        />
                    </div>
                </div>
            </div>

            <!-- Status Filter Tabs -->
            <div class="px-8 border-b border-gray-200 dark:border-gray-600">
                <nav class="flex gap-4 -mb-px">
                    <button
                        @click="statusFilter = 'all'"
                        :class="[
                            'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
                            statusFilter === 'all'
                                ? 'border-primary text-primary'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        ]"
                    >
                        Semua
                        <span
                            v-if="statusFilter === 'all'"
                            class="px-2 py-0.5 ml-2 text-xs text-primary bg-primary/10 rounded-full"
                        >
                            {{ getStatusCount('all') }}
                        </span>
                    </button>
                    <button
                        @click="statusFilter = 'active'"
                        :class="[
                            'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
                            statusFilter === 'active'
                                ? 'border-green-500 text-green-600 dark:text-green-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        ]"
                    >
                        Aktif
                        <span
                            v-if="statusFilter === 'active'"
                            class="px-2 py-0.5 ml-2 text-xs text-green-600 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300"
                        >
                            {{ getStatusCount('active') }}
                        </span>
                    </button>
                    <button
                        @click="statusFilter = 'pending'"
                        :class="[
                            'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
                            statusFilter === 'pending'
                                ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        ]"
                    >
                        Menunggu Verifikasi
                        <span
                            v-if="statusFilter === 'pending'"
                            class="px-2 py-0.5 ml-2 text-xs text-yellow-600 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-300"
                        >
                            {{ getStatusCount('pending') }}
                        </span>
                    </button>
                    <button
                        @click="statusFilter = 'inactive'"
                        :class="[
                            'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
                            statusFilter === 'inactive'
                                ? 'border-red-500 text-red-600 dark:text-red-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        ]"
                    >
                        Tidak Aktif
                        <span
                            v-if="statusFilter === 'inactive'"
                            class="px-2 py-0.5 ml-2 text-xs text-red-600 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300"
                        >
                            {{ getStatusCount('inactive') }}
                        </span>
                    </button>
                </nav>
            </div>

            <div class="overflow-auto" data-simplebar>
                <table class="min-w-full text-sm">
                    <thead>
                        <tr>
                            <th
                                class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div class="flex justify-center items-center">
                                    <p
                                        class="flex flex-col items-center font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        No.
                                    </p>
                                </div>
                            </th>
                            <th
                                @click="changeSort('name')"
                                class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div
                                    class="flex gap-2 justify-center items-center px-3"
                                >
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        Nama & Email Pengguna
                                    </p>
                                    <div class="flex flex-col items-center">
                                        <UpIcon
                                            :class="[
                                                '-mb-1',
                                                sortBy === 'name' &&
                                                sortDirection === 'asc'
                                                    ? 'text-gray-900 dark:text-gray-200'
                                                    : 'text-gray-400 dark:text-gray-500',
                                            ]"
                                        />
                                        <DownIcon
                                            :class="[
                                                '-mt-1',
                                                sortBy === 'name' &&
                                                sortDirection === 'desc'
                                                    ? 'text-gray-900 dark:text-gray-200'
                                                    : 'text-gray-400 dark:text-gray-500',
                                            ]"
                                        />
                                    </div>
                                </div>
                            </th>
                            <th
                                @click="changeSort('username')"
                                class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div
                                    class="flex gap-2 justify-center items-center px-3"
                                >
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        Username
                                    </p>
                                    <div class="flex flex-col items-center">
                                        <UpIcon
                                            :class="[
                                                '-mb-1',
                                                sortBy === 'username' &&
                                                sortDirection === 'asc'
                                                    ? 'text-gray-900 dark:text-gray-200'
                                                    : 'text-gray-400 dark:text-gray-500',
                                            ]"
                                        />
                                        <DownIcon
                                            :class="[
                                                '-mt-1',
                                                sortBy === 'username' &&
                                                sortDirection === 'desc'
                                                    ? 'text-gray-900 dark:text-gray-200'
                                                    : 'text-gray-400 dark:text-gray-500',
                                            ]"
                                        />
                                    </div>
                                </div>
                            </th>
                            <th
                                @click="changeSort('role')"
                                class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div
                                    class="flex gap-2 justify-center items-center px-3"
                                >
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        Peran & Akses
                                    </p>
                                    <div class="flex flex-col items-center">
                                        <UpIcon
                                            :class="[
                                                '-mb-1',
                                                sortBy === 'role' &&
                                                sortDirection === 'asc'
                                                    ? 'text-gray-900 dark:text-gray-200'
                                                    : 'text-gray-400 dark:text-gray-500',
                                            ]"
                                        />
                                        <DownIcon
                                            :class="[
                                                '-mt-1',
                                                sortBy === 'role' &&
                                                sortDirection === 'desc'
                                                    ? 'text-gray-900 dark:text-gray-200'
                                                    : 'text-gray-400 dark:text-gray-500',
                                            ]"
                                        />
                                    </div>
                                </div>
                            </th>
                            <th
                                class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div class="flex justify-center items-center">
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        Status
                                    </p>
                                </div>
                            </th>
                            <th
                                @click="changeSort('updated_at')"
                                class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div
                                    class="flex gap-2 justify-center items-center px-3"
                                >
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        Terakhir Dilihat
                                    </p>
                                    <div class="flex flex-col items-center">
                                        <UpIcon
                                            :class="[
                                                '-mb-1',
                                                sortBy === 'updated_at' &&
                                                sortDirection === 'asc'
                                                    ? 'text-gray-900 dark:text-gray-200'
                                                    : 'text-gray-400 dark:text-gray-500',
                                            ]"
                                        />
                                        <DownIcon
                                            :class="[
                                                '-mt-1',
                                                sortBy === 'updated_at' &&
                                                sortDirection === 'desc'
                                                    ? 'text-gray-900 dark:text-gray-200'
                                                    : 'text-gray-400 dark:text-gray-500',
                                            ]"
                                        />
                                    </div>
                                </div>
                            </th>
                            <th
                                v-if="can('users.edit') || can('users.delete')"
                                class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div class="flex justify-center items-center">
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        Aksi
                                    </p>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="users.data && users.data.length > 0">
                            <tr
                                v-for="(user, index) in users.data"
                                :key="user.id"
                                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                            >
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex justify-center items-center whitespace-nowrap"
                                    >
                                        <p
                                            class="px-3 text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                (users.current_page - 1) *
                                                    users.per_page +
                                                index +
                                                1
                                            }}.
                                        </p>
                                    </div>
                                </td>
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex gap-3 items-center whitespace-nowrap ps-5"
                                    >
                                        <img
                                            class="object-cover w-10 h-10 rounded-full"
                                            :src="
                                                user.profile_photo_path
                                                    ? `/storage/${user.profile_photo_path}`
                                                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(
                                                          user.name
                                                      )}&background=3b82f6&color=fff`
                                            "
                                            alt="User profile"
                                            loading="lazy"
                                        />
                                        <div
                                            class="flex flex-col leading-tight"
                                        >
                                            <p
                                                class="font-medium text-gray-800 dark:text-white/90"
                                            >
                                                {{ user.name }}
                                            </p>
                                            <span
                                                class="text-gray-500 dark:text-gray-400"
                                            >
                                                {{ user.email }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex justify-center items-center px-3 whitespace-nowrap"
                                    >
                                        <p
                                            class="text-gray-500 dark:text-gray-400"
                                        >
                                            {{ user.username }}
                                        </p>
                                    </div>
                                </td>
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex justify-center items-center px-3 whitespace-nowrap"
                                    >
                                        <p
                                            class="text-gray-500 dark:text-gray-400"
                                        >
                                            {{ user.roles[0]?.name || '-' }}
                                        </p>
                                    </div>
                                </td>
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex justify-center items-center px-3 whitespace-nowrap"
                                    >
                                        <span
                                            :class="[
                                                'px-3 py-1 rounded-full text-xs font-medium',
                                                user.status === 'active'
                                                    ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                                    : user.status ===
                                                      'pending'
                                                    ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300'
                                                    : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                            ]"
                                        >
                                            {{
                                                user.status === "active"
                                                    ? "Aktif"
                                                    : user.status ===
                                                      "pending"
                                                    ? "Menunggu Verifikasi"
                                                    : "Tidak Aktif"
                                            }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex justify-center items-center px-3 text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        <span
                                            v-if="
                                                user.last_seen &&
                                                isOnline(user.last_seen)
                                            "
                                            class="px-3 py-1 text-teal-500 bg-teal-200 rounded-full dark:bg-teal-400 dark:text-teal-100"
                                        >
                                            Online
                                        </span>

                                        <span v-else-if="user.last_seen">
                                            {{ formatTime(user.last_seen) }}
                                        </span>

                                        <span v-else class="text-gray-400">
                                            Tidak pernah terlihat
                                        </span>
                                    </div>
                                </td>
                                <td
                                    v-if="
                                        can('users.edit') ||
                                        can('users.delete')
                                    "
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex gap-3 justify-center px-4 whitespace-nowrap sm:px-0"
                                    >
                                        <button
                                            v-if="can('users.edit')"
                                            @click.stop="openEditModal(user)"
                                            class="text-yellow-500 hover:text-yellow-600"
                                            title="Edit"
                                        >
                                            <EditIcon />
                                        </button>
                                        <button
                                            v-if="can('users.delete')"
                                            @click.stop="openConfirmModal(user)"
                                            class="text-red-500 hover:text-red-600"
                                            title="Hapus"
                                        >
                                            <TrashIcon />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr v-else>
                            <td
                                :colspan="
                                    can('users.edit') || can('users.delete')
                                        ? 7
                                        : 6
                                "
                                class="py-6 font-medium text-center text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-600"
                            >
                                Tidak ada pengguna ditemukan
                            </td>
                        </tr>
                    </tbody>
                </table>

                <Modal
                    :show="isModalOpen"
                    :title="
                        isEditMode
                            ? `Edit ${selectedItem?.name}`
                            : 'Tambah Pengguna Baru'
                    "
                    confirmText="Simpan"
                    maxWidth="lg"
                    @close="closeModal"
                    @confirm="saveUser"
                >
                    <div class="space-y-3">
                        <div class="space-y-1 text-sm">
                            <label
                                for="name"
                                class="text-gray-900 dark:text-white"
                                >Nama Lengkap</label
                            >
                            <input
                                id="name"
                                class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                type="text"
                                v-model="form.name"
                                required
                                placeholder="Masukkan nama lengkap pengguna"
                            />
                            <div
                                v-if="form.errors.name"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>
                        <div class="space-y-1 text-sm">
                            <label
                                for="username"
                                class="text-gray-900 dark:text-white"
                                >Nama Pengguna</label
                            >
                            <input
                                id="username"
                                class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                type="text"
                                v-model="form.username"
                                required
                                placeholder="Masukkan username"
                            />
                            <div
                                v-if="form.errors.username"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.username }}
                            </div>
                        </div>
                        <div class="space-y-1 text-sm">
                            <label
                                for="email"
                                class="text-gray-900 dark:text-white"
                                >Email</label
                            >
                            <input
                                id="email"
                                v-model="form.email"
                                class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                type="email"
                                placeholder="Masukkan email pengguna"
                            />
                            <div
                                v-if="form.errors.email"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <div class="space-y-1 text-sm">
                            <label
                                for="role"
                                class="text-gray-900 dark:text-white"
                                >Peran</label
                            >
                            <select
                                id="role"
                                v-model="form.role"
                                class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option selected hidden value="">
                                    Pilih peran pengguna
                                </option>
                                <option
                                    v-for="role in props.roles"
                                    :key="role.name"
                                    :value="role.name"
                                >
                                    {{ role.name }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.role"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.role }}
                            </div>
                        </div>

                        <div class="space-y-1 text-sm">
                            <label
                                for="status"
                                class="text-gray-900 dark:text-white"
                                >Status</label
                            >
                            <select
                                id="status"
                                v-model="form.status"
                                class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option value="active">Aktif</option>
                                <option value="pending">
                                    Menunggu Verifikasi
                                </option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                            <div
                                v-if="form.errors.status"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.status }}
                            </div>
                        </div>
                    </div>
                </Modal>

                <ConfirmModal
                    :show="isConfirmModalOpen"
                    :question="`Yakin ingin menghapus`"
                    :selected="`${selectedItem?.name}`"
                    title="Hapus Pengguna"
                    confirmText="Ya, Hapus!"
                    maxWidth="md"
                    @close="closeConfirmModal"
                    @confirm="destroyData"
                />
            </div>

            <Pagination
                v-if="users.data && users.data.length > 0"
                :pagination="users"
            />
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PlusSquareIcon from "@/Components/icons/PlusSquareIcon.vue";
import SearchIcon from "@/Components/icons/SearchIcon.vue";
import EditIcon from "@/Components/icons/EditIcon.vue";
import UpIcon from "@/Components/icons/UpIcon.vue";
import DownIcon from "@/Components/icons/DownIcon.vue";
import TrashIcon from "@/Components/icons/TrashIcon.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import Modal from "@/Components/common/Modal.vue";
import ConfirmModal from "@/Components/common/ConfirmModal.vue";
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
    status: String,
    statusCounts: Object,
    sortBy: String,
    sortDirection: String,
});

const { can } = useAuth();

const breadcrumbs = [{ label: "Menu Utama" }, { label: "Pengguna" }];

// Status filter - initialize from props
const statusFilter = ref(props.status || "all");

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

// Get status count for badge display
const getStatusCount = (status) => {
    if (!props.statusCounts) return 0;
    return props.statusCounts[status] || 0;
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
            status: statusFilter.value,
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

watch(statusFilter, () => {
    // Reset to page 1 when status filter changes
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.delete('page');
    router.get(
        route("users.index"),
        {
            search: search.value,
            role: roleFilter.value,
            status: statusFilter.value,
            sortBy: props.sortBy,
            sortDirection: props.sortDirection,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }
    );
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
    status: "active",
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
    form.status = user.status || "active";
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
