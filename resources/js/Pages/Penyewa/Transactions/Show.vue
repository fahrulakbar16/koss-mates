<template>

    <Head title="Detail Tagihan" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <!-- Back Button -->
            <Link :href="route('penyewa.transactions.index')"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 mb-8 transition-colors group">
                <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
                <span>Kembali ke Riwayat Tagihan</span>
            </Link>

            <!-- Two Column Checkout Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Left Column - Booking Summary (2/3 width) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Boarding House Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-transparent dark:from-primary-900/20">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <div class="w-1.5 h-6 bg-primary-600 rounded-full"></div>
                                Detail Pemesanan
                            </h3>
                        </div>

                        <div class="p-6 sm:p-8 space-y-6">
                            <!-- Boarding House Info -->
                            <div class="flex flex-col sm:flex-row items-start gap-6">
                                <img :src="transaction.room.boarding_house.thumbnail_url || '/images/placeholder.png'"
                                    :alt="transaction.room.boarding_house.name"
                                    class="w-full sm:w-48 h-48 rounded-2xl object-cover bg-gray-100 shadow-lg ring-2 ring-gray-100 dark:ring-gray-700"
                                    @error="$event.target.src = '/images/placeholder.png'">
                                <div class="flex-1 min-w-0 space-y-4">
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white text-2xl mb-2">
                                            {{ transaction.room.boarding_house.name }}
                                        </h4>
                                        <p class="text-gray-600 dark:text-gray-400 text-base flex items-center gap-2">
                                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Kamar {{ transaction.room.name }}
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        <div
                                            class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2.5 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/30">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ transaction.room_price.duration }} Bulan
                                        </div>
                                        <div
                                            class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            {{ transaction.payment_scheme === 'full' ? 'Bayar Penuh' : 'Cicilan' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="h-px bg-gradient-to-r from-transparent via-gray-200 dark:via-gray-700 to-transparent" />

                            <!-- Transaction Info -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 mb-1.5 font-medium uppercase tracking-wide">
                                        ID Tagihan</p>
                                    <p class="font-bold text-gray-900 dark:text-white text-base break-all">#{{
                                        transaction.id }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 mb-1.5 font-medium uppercase tracking-wide">
                                        Tanggal</p>
                                    <p class="font-bold text-gray-900 dark:text-white text-sm">{{
                                        formatDate(transaction.created_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History (Collapsible) -->
                    <div v-if="transaction.payments && transaction.payments.length > 0"
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <button @click="showHistory = !showHistory"
                            class="w-full p-6 sm:p-8 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <div class="w-1.5 h-5 bg-gray-400 rounded-full"></div>
                                Riwayat Pembayaran ({{ transaction.payments.length }})
                            </h3>
                            <svg :class="['w-5 h-5 text-gray-500 transition-transform', showHistory ? 'rotate-180' : '']"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-show="showHistory" class="p-6 sm:p-8 pt-0 space-y-3">
                            <div v-for="payment in transaction.payments" :key="payment.id"
                                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">
                                        Pembayaran #{{ payment.payment_sequence || payment.id }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ formatDate(payment.payment_date || payment.created_at) }}
                                    </p>
                                </div>
                                <div class="flex flex-col sm:items-end gap-2">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ formatPrice(payment.amount) }}
                                    </p>
                                    <span
                                        :class="['inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold', getStatusClass(payment.payment_status).class]">
                                        {{ getStatusClass(payment.payment_status).label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Payment Details (1/3 width) -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">
                        <!-- Payment Summary Card -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div
                                class="p-6 sm:p-8 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-transparent dark:from-primary-900/20">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <div class="w-1.5 h-6 bg-primary-600 rounded-full"></div>
                                    Ringkasan Pembayaran
                                </h3>
                            </div>

                            <div class="p-6 sm:p-8 space-y-4">
                                <!-- Total Price -->
                                <div
                                    class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Total Tagihan</span>
                                    <span class="font-bold text-gray-900 dark:text-white text-lg">{{
                                        formatPrice(transaction.total_price) }}</span>
                                </div>

                                <!-- Paid Amount -->
                                <div
                                    class="bg-gradient-to-br from-green-50 to-green-100/50 dark:from-green-900/20 dark:to-green-900/10 rounded-xl p-4 border border-green-200 dark:border-green-800">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-green-700 dark:text-green-300 font-bold text-sm">Sudah
                                                Dibayar</span>
                                        </div>
                                        <span class="text-green-600 dark:text-green-400 font-bold">{{
                                            formatPrice(paidAmount) }}</span>
                                    </div>
                                </div>

                                <!-- Remaining Amount -->
                                <div v-if="remainingAmount > 0"
                                    class="bg-gradient-to-br from-yellow-50 to-yellow-100/50 dark:from-yellow-900/20 dark:to-yellow-900/10 rounded-xl p-4 border-2 border-yellow-300 dark:border-yellow-700">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-yellow-700 dark:text-yellow-300 font-bold text-sm">Sisa
                                                Tagihan</span>
                                        </div>
                                        <span class="text-yellow-600 dark:text-yellow-400 font-bold text-lg">{{
                                            formatPrice(remainingAmount) }}</span>
                                    </div>
                                </div>

                                <!-- Active Payment Details -->
                                <div v-if="transaction.payment_active"
                                    class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <h4
                                        class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-4">
                                        Pembayaran Aktif</h4>

                                    <div
                                        class="bg-primary-50 dark:bg-primary-900/20 rounded-xl p-4 border border-primary-200 dark:border-primary-800 mb-4">
                                        <div class="flex justify-between items-start mb-3">
                                            <span
                                                class="text-primary-700 dark:text-primary-300 text-sm font-medium">Jumlah</span>
                                            <span class="text-primary-600 dark:text-primary-400 font-bold text-xl">{{
                                                formatPrice(transaction.payment_active.amount) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="text-primary-600 dark:text-primary-400">Status</span>
                                            <span
                                                :class="['px-2 py-1 rounded font-bold', getStatusClass(transaction.payment_active.payment_status).class]">
                                                {{ getStatusClass(transaction.payment_active.payment_status).label }}
                                            </span>
                                        </div>
                                    </div>

                                    <button @click="pay"
                                        class="w-full py-4 px-6 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all duration-300 flex items-center justify-center gap-3 group">
                                        <CreditCard class="w-5 h-5 group-hover:scale-110 transition-transform" />
                                        <span>Bayar Sekarang</span>
                                    </button>
                                </div>

                                <!-- No Active Payment - Create Payment Button -->
                                <div v-else-if="remainingAmount > 0"
                                    class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button @click="pay"
                                        class="w-full py-4 px-6 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all duration-300 flex items-center justify-center gap-3 group">
                                        <CreditCard class="w-5 h-5 group-hover:scale-110 transition-transform" />
                                        <span>Lanjutkan Pembayaran</span>
                                    </button>
                                </div>

                                <!-- Fully Paid Message -->
                                <div v-else class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <div
                                        class="bg-gradient-to-br from-green-50 to-green-100/50 dark:from-green-900/20 dark:to-green-900/10 rounded-xl border-2 border-green-200 dark:border-green-900/30 p-5 text-center">
                                        <div class="flex items-center justify-center gap-2 mb-2">
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-base text-green-800 dark:text-green-200 font-bold">Lunas</p>
                                        </div>
                                        <p class="text-sm text-green-700 dark:text-green-300">Semua pembayaran telah
                                            diselesaikan</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Banner -->
                        <div
                            :class="['rounded-2xl p-5 flex items-start gap-3 shadow-lg border-2', getStatusStyle(transaction.status).bg, getStatusStyle(transaction.status).text, getStatusStyle(transaction.status).border]">
                            <div :class="['p-2 rounded-xl shrink-0', getStatusStyle(transaction.status).iconBg]">
                                <component :is="getStatusStyle(transaction.status).icon" class="w-5 h-5" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-sm mb-1">{{ getStatusStyle(transaction.status).title }}</h4>
                                <p class="text-xs opacity-90">{{ getStatusStyle(transaction.status).desc }}</p>
                            </div>
                        </div>

                        <!-- Cancel Transaction Button -->
                        <div v-if="canCancelTransaction"
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                            <button @click="showCancelModal = true"
                                class="w-full py-3.5 px-6 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-700 dark:text-red-400 font-bold rounded-xl border-2 border-red-200 dark:border-red-800 hover:border-red-300 dark:hover:border-red-700 transition-all duration-300 flex items-center justify-center gap-2 group">
                                <XCircle class="w-5 h-5 group-hover:scale-110 transition-transform" />
                                <span>Batalkan Transaksi</span>
                            </button>
                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-3">
                                Transaksi yang dibatalkan tidak dapat dikembalikan
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Installment Amount Modal -->
            <div v-if="showInstallmentModal"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fadeIn">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-md w-full p-8 animate-slideUp">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                        <div class="w-1.5 h-8 bg-primary-600 rounded-full"></div>
                        Pilih Jumlah Pembayaran
                    </h3>

                    <div class="space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-5">
                            <label class="block text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Jumlah yang
                                sudah dibayar</label>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatPrice(paidAmount) }}
                            </p>
                        </div>

                        <div
                            class="bg-primary-50 dark:bg-primary-900/20 rounded-2xl p-5 border-2 border-primary-200 dark:border-primary-800">
                            <label class="block text-sm font-semibold text-primary-700 dark:text-primary-300 mb-2">Sisa
                                tagihan</label>
                            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{
                                formatPrice(remainingAmount) }}</p>
                        </div>

                        <div>
                            <label for="payment-amount"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Jumlah yang
                                ingin dibayar</label>
                            <input id="payment-amount" v-model.number="paymentAmount" type="number"
                                :max="remainingAmount" :min="1"
                                class="w-full px-5 py-4 text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-primary-500/20 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all"
                                placeholder="Masukkan jumlah" />
                            <p v-if="paymentAmount > remainingAmount"
                                class="text-sm text-red-600 dark:text-red-400 mt-2 font-medium">⚠️ Jumlah melebihi sisa
                                tagihan</p>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <button @click="showInstallmentModal = false"
                            class="flex-1 px-6 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-700 dark:text-gray-200 font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">Batal</button>
                        <button @click="processPayment"
                            :disabled="!paymentAmount || paymentAmount > remainingAmount || paymentAmount < 1"
                            class="flex-1 px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-lg">Lanjutkan</button>
                    </div>
                </div>
            </div>

            <!-- Cancel Transaction Modal -->
            <div v-if="showCancelModal"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fadeIn">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-md w-full p-8 animate-slideUp">
                    <div class="text-center mb-6">
                        <div
                            class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <XCircle class="w-8 h-8 text-red-600 dark:text-red-400" />
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Batalkan Transaksi?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Apakah Anda yakin ingin membatalkan transaksi ini? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>

                    <div
                        class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-4 mb-6 border border-red-200 dark:border-red-800">
                        <p class="text-sm text-red-800 dark:text-red-300 font-medium">
                            ⚠️ Setelah dibatalkan, Anda perlu membuat booking baru jika ingin menyewa kamar ini.
                        </p>
                    </div>

                    <div class="flex gap-4">
                        <button @click="showCancelModal = false" :disabled="isCanceling"
                            class="flex-1 px-6 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl text-gray-700 dark:text-gray-200 font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            Tidak
                        </button>
                        <button @click="cancelTransaction" :disabled="isCanceling"
                            class="flex-1 px-6 py-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold rounded-2xl shadow-lg shadow-red-500/30 hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <svg v-if="isCanceling" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>{{ isCanceling ? 'Membatalkan...' : 'Ya, Batalkan' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { ArrowLeft, CheckCircle, Clock, XCircle, AlertCircle, CreditCard, Download } from "lucide-vue-next";
import { ref, computed, onMounted } from "vue";
import axios from "axios";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    transaction: Object,
});

const showInstallmentModal = ref(false);
const paymentAmount = ref(0);
const isProcessing = ref(false);
const showHistory = ref(false);
const showCancelModal = ref(false);
const isCanceling = ref(false);

const canCancelTransaction = computed(() => {
    const status = props.transaction.status;
    const type = props.transaction.type;
    return (status === 'pending' || status === 'incomplete') && type === 'booked';
});

const paidAmount = computed(() => {
    if (!props.transaction.payments) return 0;
    return props.transaction.payments
        .filter(p => p.payment_status === 'success')
        .reduce((sum, p) => sum + p.amount, 0);
});

const remainingAmount = computed(() => {
    return props.transaction.total_price - paidAmount.value;
});

const getStatusClass = (status) => {
    switch (status) {
        case 'paid':
        case 'success':
        case 'completed':
        case 'settlement':
            return {
                class: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                label: 'Lunas'
            };
        case 'pending':
        case 'incomplete':
            return {
                class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                label: 'Belum Dibayar'
            };
        case 'failed':
        case 'cancel':
        case 'canceled':
        case 'expire':
            return {
                class: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                label: 'Gagal'
            };
        default:
            return {
                class: 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
                label: status || 'Belum Lunas'
            };
    }
};

const getStatusStyle = (status) => {
    switch (status) {
        case 'paid':
        case 'success':
        case 'completed':
        case 'settlement':
            return {
                bg: 'bg-green-50 dark:bg-green-900/20',
                text: 'text-green-800 dark:text-green-200',
                border: 'border-green-200 dark:border-green-800',
                iconBg: 'bg-green-100 dark:bg-green-800/50',
                icon: CheckCircle,
                title: 'Lunas',
                desc: 'Terima kasih, pembayaran Anda telah kami terima.',
                label: 'Lunas'
            };
        case 'pending':
        case 'incomplete':
            return {
                bg: 'bg-yellow-50 dark:bg-yellow-900/20',
                text: 'text-yellow-800 dark:text-yellow-200',
                border: 'border-yellow-200 dark:border-yellow-800',
                iconBg: 'bg-yellow-100 dark:bg-yellow-800/50',
                icon: Clock,
                title: status === 'incomplete' ? 'Belum Lunas' : 'Belum Dibayar',
                desc: status === 'incomplete' ? 'Pembayaran belum selesai. Mohon lanjutkan pembayaran.' : 'Mohon segera selesaikan pembayaran Anda.',
                label: status === 'incomplete' ? 'Belum Lunas' : 'Belum Dibayar'
            };
        case 'failed':
        case 'cancel':
        case 'canceled':
        case 'expire':
            return {
                bg: 'bg-red-50 dark:bg-red-900/20',
                text: 'text-red-800 dark:text-red-200',
                border: 'border-red-200 dark:border-red-800',
                iconBg: 'bg-red-100 dark:bg-red-800/50',
                icon: XCircle,
                title: 'Gagal',
                desc: 'Maaf, pembayaran Anda gagal atau dibatalkan.',
                label: 'Gagal'
            };
        default:
            return {
                bg: 'bg-gray-50 dark:bg-gray-900/20',
                text: 'text-gray-800 dark:text-gray-200',
                border: 'border-gray-200 dark:border-gray-700',
                iconBg: 'bg-gray-100 dark:bg-gray-700/50',
                icon: AlertCircle,
                title: 'Belum Lunas',
                desc: 'Silakan hubungi admin untuk info lebih lanjut.',
                label: 'Belum Lunas'
            };
    }
};

const formatPrice = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(value);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const pay = () => {
    // If there's an active payment with snap_token, use it directly
    if (props.transaction.payment_active && props.transaction.payment_active.snap_token) {
        openMidtransSnap(props.transaction.payment_active.snap_token);
        return;
    }

    // Otherwise, create a new payment
    if (props.transaction.payment_scheme === 'installment') {
        // Show modal for installment
        paymentAmount.value = remainingAmount.value;
        showInstallmentModal.value = true;
    } else {
        // Full payment
        processPayment();
    }
};

const openMidtransSnap = (snapToken) => {
    if (!window.snap) {
        alert('Midtrans belum siap. Silakan refresh halaman dan coba lagi.');
        return;
    }

    window.snap.pay(snapToken, {
        onSuccess: function (result) {
            router.visit(route('penyewa.payment.finish', { order_id: result.order_id, transaction_status: result.transaction_status }));
        },
        onPending: function (result) {
            router.visit(route('penyewa.payment.finish', { order_id: result.order_id, transaction_status: result.transaction_status }));
        },
        onError: function (result) {
            router.visit(route('penyewa.payment.finish', { order_id: result.order_id, transaction_status: result.transaction_status }));
        },
        onClose: function () {
            // User closed the popup
        }
    });
};

const processPayment = async () => {
    if (isProcessing.value) return;

    isProcessing.value = true;
    showInstallmentModal.value = false;

    try {
        const amount = props.transaction.payment_scheme === 'installment'
            ? paymentAmount.value
            : props.transaction.total_price;

        const response = await axios.post(
            route('penyewa.transactions.payment.create', props.transaction.id),
            { amount }
        );

        if (response.data.success && response.data.snap_token) {
            openMidtransSnap(response.data.snap_token);
        }
    } catch (error) {
        console.error('Payment creation failed:', error);
        alert(error.response?.data?.message || 'Gagal membuat pembayaran');
        isProcessing.value = false;
    }
};

const cancelTransaction = async () => {
    if (isCanceling.value) return;

    isCanceling.value = true;

    try {
        await router.delete(route('penyewa.transactions.cancel', props.transaction.id), {
            onSuccess: () => {
                // Redirect will be handled by Inertia
            },
            onError: (errors) => {
                console.error('Cancel failed:', errors);
                alert('Gagal membatalkan transaksi. Silakan coba lagi.');
                isCanceling.value = false;
                showCancelModal.value = false;
            }
        });
    } catch (error) {
        console.error('Cancel transaction failed:', error);
        alert('Gagal membatalkan transaksi. Silakan coba lagi.');
        isCanceling.value = false;
        showCancelModal.value = false;
    }
};

onMounted(() => {
    // Load Midtrans Snap script if not already loaded
    if (!document.getElementById('midtrans-script')) {
        const script = document.createElement('script');
        script.id = 'midtrans-script';
        script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
        script.setAttribute('data-client-key', import.meta.env.VITE_MIDTRANS_CLIENT_KEY || '');
        document.head.appendChild(script);
    }
});
</script>
