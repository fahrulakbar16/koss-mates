<template>
    <Head :title="`Detail Kamar - ${room.name}`" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Detail Kamar
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola informasi kamar dan pengeluaran
                </p>
            </div>

            <Link :href="route('boarding-houses.show', boardingHouse.id)"
                class="inline-flex gap-2 items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700 shadow-sm transition-all duration-200">
                <BackIcon class="w-4 h-4" />
                Kembali ke Detail Kos
            </Link>
        </div>

        <div
            class="flex-1 overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 flex flex-col">
            <!-- Decorative gradient line -->
            <div class="h-1 bg-gradient-to-r from-primary-500 via-blue-500 to-purple-500 w-full"></div>

            <div class="overflow-auto" data-simplebar>
                <!-- Room Info Section -->
                <div class="p-8 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <!-- Room Info Card -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-6">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                            {{ room.name }}
                                        </h1>
                                        <span :class="[
                                            'px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-lg border',
                                            room.status === 'available' ? 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800' :
                                                room.status === 'occupied' ? 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800' :
                                                    room.status === 'booked' ? 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800' :
                                                        'bg-primary-100 text-primary-700 border-primary-200 dark:bg-primary-900/30 dark:text-primary-400 dark:border-primary-800'
                                        ]">
                                            {{ room.status === 'available' ? 'Tersedia' : room.status === 'occupied' ? 'Terisi' : room.status === 'booked' ? 'Booked' : 'Maintenance' }}
                                        </span>
                                    </div>
                                    <p v-if="room.number" class="text-sm text-gray-500 dark:text-gray-400 font-mono">
                                        No. {{ room.number }}
                                    </p>
                                </div>

                                <div class="flex gap-2">
                                    <button v-if="room.status === 'booked'" @click="cancelBooking"
                                        class="flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transform hover:-translate-y-0.5 transition-all duration-200"
                                        title="Batalkan Booking">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Batalkan Book
                                    </button>
                                    <button v-if="room.status === 'occupied'" @click="checkout"
                                        class="flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 transform hover:-translate-y-0.5 transition-all duration-200"
                                        title="Check Out Penyewa">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Check Out
                                    </button>
                                    <button v-if="can('rooms.edit')" @click="openEditModal"
                                        class="p-2.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:text-primary-600 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-primary-400 dark:hover:bg-gray-700 rounded-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5"
                                        title="Edit Kamar">
                                        <EditIcon class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kapasitas</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ room.capacity }} Orang
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Harga Mulai</p>
                                    <p class="text-sm font-medium text-primary-600 dark:text-primary-400">
                                        {{ room.min_price ? formatCurrency(room.min_price) : 'Belum diatur' }}
                                    </p>
                                </div>
                                <div v-if="room.description" class="md:col-span-2">
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Deskripsi</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                        {{ room.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Tenant Section -->
                <div v-if="room.status === 'occupied' && room.penyewa_aktif && room.penyewa_aktif.length > 0"
                    class="p-8 border-b border-gray-100 dark:border-gray-700 bg-blue-50/20 dark:bg-blue-900/10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Informasi Penyewa Aktif
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Detail penyewa yang sedang menempati kamar saat ini
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <p class="text-xs font-semibold text-gray-400 lg:text-gray-500 uppercase tracking-wider mb-2">Nama
                                Penyewa</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">{{ room.penyewa_aktif[0].name }}
                            </p>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <p class="text-xs font-semibold text-gray-400 lg:text-gray-500 uppercase tracking-wider mb-2">
                                WhatsApp</p>
                            <a :href="`https://wa.me/${room.penyewa_aktif[0].tenant?.phone}`" target="_blank"
                                class="text-base font-bold text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                                {{ room.penyewa_aktif[0].tenant?.phone || '-' }}
                            </a>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <p class="text-xs font-semibold text-gray-400 lg:text-gray-500 uppercase tracking-wider mb-2">
                                Tanggal Mulai</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">{{
                                formatDate(room.penyewa_aktif[0].pivot.start_date) }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                Daftar Pengeluaran
                                <span v-if="room.expenses && room.expenses.length > 0"
                                    class="px-2.5 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-600 dark:text-gray-300">
                                    {{ room.expenses.length }}
                                </span>
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Kelola pengeluaran untuk kamar ini
                            </p>
                        </div>
                        <button v-if="can('rooms.edit')" @click="openExpenseModal(null)" type="button"
                            class="flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transform hover:-translate-y-0.5 transition-all duration-200">
                            <PlusSquareIcon class="w-5 h-5" />
                            <span class="hidden md:inline">Tambah Pengeluaran</span>
                            <span class="md:hidden">Tambah</span>
                        </button>
                    </div>

                    <div v-if="room.expenses && room.expenses.length > 0" class="space-y-4">
                        <div v-for="expense in room.expenses" :key="expense.id"
                            class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-500 transition-all shadow-sm hover:shadow-lg">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="px-3 py-1 text-xs font-bold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 rounded-lg">
                                            {{ expense.category }}
                                        </span>
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ formatCurrency(expense.amount) }}
                                        </span>
                                    </div>
                                    <p v-if="expense.description" class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        {{ expense.description }}
                                    </p>
                                    <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-500">
                                        <span>{{ formatDate(expense.expense_date) }}</span>
                                        <a v-if="expense.receipt_path" :href="`/storage/${expense.receipt_path}`" target="_blank"
                                            class="text-primary-600 hover:text-primary-700 dark:text-primary-400 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Bukti
                                        </a>
                                    </div>
                                </div>
                                <div v-if="can('rooms.edit')" class="flex gap-2">
                                    <button @click="openExpenseModal(expense)"
                                        class="p-2 text-gray-600 bg-gray-100 dark:bg-gray-700/50 rounded-lg hover:bg-primary-50 hover:text-primary-600 dark:hover:bg-primary-900/30 transition-colors"
                                        title="Edit">
                                        <EditIcon class="w-4 h-4" />
                                    </button>
                                    <button @click="deleteExpense(expense)"
                                        class="p-2 text-primary-600 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors"
                                        title="Hapus">
                                        <TrashIcon class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-16 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                        <div
                            class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                            Belum Ada Pengeluaran
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                            Tambahkan pengeluaran untuk kamar ini untuk melacak biaya operasional.
                        </p>
                        <button v-if="can('rooms.edit')" @click="openExpenseModal(null)"
                            class="px-6 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-0.5">
                            Tambah Pengeluaran Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense Modal -->
        <Modal :show="isExpenseModalOpen" :title="isExpenseEditMode ? 'Edit Pengeluaran' : 'Tambah Pengeluaran'" maxWidth="lg"
            @close="closeExpenseModal" @confirm="saveExpense" confirmText="Simpan">
            <div class="space-y-5">
                <div class="space-y-2">
                    <label for="expense_category" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Kategori <span class="text-primary-500">*</span>
                    </label>
                    <input id="expense_category"
                        class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all placeholder:text-gray-400"
                        type="text" v-model="expenseForm.category" required placeholder="Contoh: Perbaikan, Listrik, Air, dll" />
                    <div v-if="expenseForm.errors.category" class="text-xs text-primary-500 font-medium mt-1">
                        {{ expenseForm.errors.category }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="expense_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Jumlah (Rp) <span class="text-primary-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                            <input id="expense_amount"
                                class="w-full pl-10 pr-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all placeholder:text-gray-400"
                                type="number" step="0.01" min="0" v-model.number="expenseForm.amount" required placeholder="0" />
                        </div>
                        <div v-if="expenseForm.errors.amount" class="text-xs text-primary-500 font-medium mt-1">
                            {{ expenseForm.errors.amount }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="expense_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Tanggal <span class="text-primary-500">*</span>
                        </label>
                        <input id="expense_date"
                            class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all"
                            type="date" v-model="expenseForm.expense_date" required />
                        <div v-if="expenseForm.errors.expense_date" class="text-xs text-primary-500 font-medium mt-1">
                            {{ expenseForm.errors.expense_date }}
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="expense_description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Deskripsi
                    </label>
                    <textarea id="expense_description"
                        class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all placeholder:text-gray-400"
                        v-model="expenseForm.description" rows="3" placeholder="Tambahkan keterangan pengeluaran..."></textarea>
                    <div v-if="expenseForm.errors.description" class="text-xs text-primary-500 font-medium mt-1">
                        {{ expenseForm.errors.description }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="expense_receipt" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Bukti Pengeluaran
                    </label>
                    <div v-if="expenseForm.receipt_preview || currentExpenseReceipt" class="mb-4">
                        <div class="relative inline-block">
                            <img :src="expenseForm.receipt_preview || (currentExpenseReceipt ? `/storage/${currentExpenseReceipt}` : '')"
                                alt="Receipt preview"
                                class="w-full max-w-xs h-48 object-cover rounded-lg border-2 border-gray-300 dark:border-gray-600" />
                            <button type="button" @click="removeExpenseReceipt"
                                class="absolute top-2 right-2 p-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                                <TrashIcon class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                    <input id="expense_receipt" type="file" accept="image/*" @change="handleExpenseReceiptChange"
                        class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/30 dark:file:text-primary-300 cursor-pointer bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg" />
                    <div v-if="expenseForm.errors.receipt" class="text-xs text-primary-500 font-medium mt-1">
                        {{ expenseForm.errors.receipt }}
                    </div>
                </div>
            </div>
        </Modal>

        <!-- Confirm Delete Modal -->
        <ConfirmModal :show="isConfirmExpenseModalOpen" :question="`Yakin ingin menghapus`"
            :selected="`pengeluaran ${selectedExpense?.category || ''}`" title="Hapus Pengeluaran" confirmText="Ya, Hapus!" maxWidth="md"
            @close="closeConfirmExpenseModal" @confirm="confirmDeleteExpense" />
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PlusSquareIcon from "@/Components/icons/PlusSquareIcon.vue";
import EditIcon from "@/Components/icons/EditIcon.vue";
import TrashIcon from "@/Components/icons/TrashIcon.vue";
import BackIcon from "@/Components/icons/BackIcon.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import Modal from "@/Components/common/Modal.vue";
import ConfirmModal from "@/Components/common/ConfirmModal.vue";
import { useAuth } from "@/Composables/useAuth";
import { ref } from "vue";
import { useForm, router, Head, Link } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    boardingHouse: Object,
    room: Object,
});

const { can } = useAuth();

const breadcrumbs = [
    { label: "Properti" },
    { label: "Cluster", path: route("clusters.index") },
    { label: props.boardingHouse?.name || "Kos", href: route("boarding-houses.show", props.boardingHouse?.id) },
    { label: props.room?.name || "Detail Kamar" },
];

function formatCurrency(value) {
    if (!value || value === 0) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(date);
}

function openEditModal() {
    if (!can("rooms.edit")) {
        return;
    }
    router.visit(route("boarding-houses.show", props.boardingHouse.id));
}

const cancelBooking = () => {
    if (confirm('Apakah Anda yakin ingin membatalkan booking ini? Jika ada pembayaran yang sudah lunas, dana tersebut akan dikembalikan secara otomatis dan dicatat sebagai pengeluaran.')) {
        router.post(route('boarding-houses.rooms.cancel-booking', [props.boardingHouse.id, props.room.id]), {}, {
            onSuccess: () => {
                // Page will reload with new room status
            }
        });
    }
};

const checkout = () => {
    if (confirm('Apakah Anda yakin ingin mengeluarkan penyewa ini? Status kamar akan berubah menjadi tersedia kembali.')) {
        router.post(route('boarding-houses.rooms.checkout', [props.boardingHouse.id, props.room.id]), {}, {
            onSuccess: () => {
                // Page will reload with new room status
            }
        });
    }
}

// Expense Modal
const isExpenseModalOpen = ref(false);
const isExpenseEditMode = ref(false);
const currentExpenseReceipt = ref(null);
const expenseForm = useForm({
    id: null,
    category: "",
    description: "",
    amount: 0,
    expense_date: new Date().toISOString().split('T')[0],
    receipt: null,
    receipt_preview: null,
    remove_receipt: false,
});

function openExpenseModal(expense) {
    if (!can("rooms.edit")) {
        return;
    }
    if (expense) {
        expenseForm.id = expense.id;
        expenseForm.category = expense.category || "";
        expenseForm.description = expense.description || "";
        expenseForm.amount = expense.amount || 0;
        expenseForm.expense_date = expense.expense_date || new Date().toISOString().split('T')[0];
        expenseForm.receipt = null;
        expenseForm.receipt_preview = null;
        currentExpenseReceipt.value = expense.receipt_path;
        isExpenseEditMode.value = true;
    } else {
        expenseForm.reset();
        expenseForm.expense_date = new Date().toISOString().split('T')[0];
        expenseForm.amount = 0;
        currentExpenseReceipt.value = null;
        isExpenseEditMode.value = false;
    }
    expenseForm.clearErrors();
    isExpenseModalOpen.value = true;
}

function closeExpenseModal() {
    isExpenseModalOpen.value = false;
    expenseForm.reset();
    expenseForm.receipt_preview = null;
    currentExpenseReceipt.value = null;
    expenseForm.clearErrors();
}

function handleExpenseReceiptChange(event) {
    const file = event.target.files[0];
    if (file) {
        expenseForm.receipt = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            expenseForm.receipt_preview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function removeExpenseReceipt() {
    expenseForm.receipt = null;
    expenseForm.receipt_preview = null;
    expenseForm.remove_receipt = true;
    currentExpenseReceipt.value = null;
    const input = document.getElementById("expense_receipt");
    if (input) {
        input.value = "";
    }
}

function saveExpense() {
    if (!can("rooms.edit")) {
        return;
    }

    if (isExpenseEditMode.value) {
        expenseForm.put(
            route("boarding-houses.rooms.expenses.update", [
                props.boardingHouse.id,
                props.room.id,
                expenseForm.id,
            ]),
            {
                onSuccess: () => {
                    closeExpenseModal();
                    router.reload({ only: ['room'] });
                },
            }
        );
    } else {
        expenseForm.post(
            route("boarding-houses.rooms.expenses.store", [props.boardingHouse.id, props.room.id]),
            {
                onSuccess: () => {
                    closeExpenseModal();
                    router.reload({ only: ['room'] });
                },
            }
        );
    }
}

// Confirm Delete Expense
const isConfirmExpenseModalOpen = ref(false);
const selectedExpense = ref(null);

function deleteExpense(expense) {
    if (!can("rooms.edit")) {
        return;
    }
    selectedExpense.value = expense;
    isConfirmExpenseModalOpen.value = true;
}

function closeConfirmExpenseModal() {
    isConfirmExpenseModalOpen.value = false;
    selectedExpense.value = null;
}

function confirmDeleteExpense() {
    if (!can("rooms.edit") || !selectedExpense.value) {
        return;
    }

    router.delete(
        route("boarding-houses.rooms.expenses.destroy", [
            props.boardingHouse.id,
            props.room.id,
            selectedExpense.value.id,
        ]),
        {
            onSuccess: () => {
                closeConfirmExpenseModal();
                router.reload({ only: ['room'] });
            },
        }
    );
}
</script>

