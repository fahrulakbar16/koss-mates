<template>
    <Head title="Status Pembayaran" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden">
        <!-- Subtle Theme Accents -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary-500/5 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl"></div>

        <div class="max-w-md w-full relative z-10 animate-slideUp">
            <!-- Main Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden relative">

                <!-- Success Celebration (Confetti) -->
                <div v-if="isSuccess" class="absolute inset-0 pointer-events-none overflow-hidden">
                    <div v-for="i in 12" :key="i"
                        class="confetti absolute w-2 h-2 rounded-full"
                        :style="generateConfettiStyle(i)">
                    </div>
                </div>

                <div class="p-8 sm:p-10 text-center relative z-10">
                    <!-- Status Header Section -->
                    <div class="mb-8 space-y-4">
                        <!-- Icon with Advanced Glow -->
                        <div class="flex justify-center">
                            <div class="relative group">
                                <div class="absolute inset-0 rounded-full blur-2xl opacity-20 bg-primary-500 scale-150 animate-pulse"></div>
                                <div class="w-20 h-20 rounded-full flex items-center justify-center relative z-10 bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/30">
                                    <Check v-if="isSuccess" class="w-10 h-10 stroke-[3.5px]" />
                                    <Clock v-else-if="isPending" class="w-10 h-10 stroke-[3.5px]" />
                                    <X v-else class="w-10 h-10 stroke-[3.5px]" />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                                {{ title }}
                            </h1>
                            <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed max-w-[280px] mx-auto">
                                {{ message }}
                            </p>
                        </div>
                    </div>

                    <!-- Receipt Style Info Card -->
                    <div v-if="payment" class="bg-gray-50 dark:bg-gray-900/40 rounded-2xl p-6 mb-8 border border-gray-100 dark:border-gray-700/50 space-y-4">
                        <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                            <span>Order ID</span>
                            <span class="text-gray-900 dark:text-white font-mono select-all">#{{ orderId }}</span>
                        </div>

                        <div class="h-px bg-dashed-border opacity-50"></div>

                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-widest">Total Pembayaran</span>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">
                                {{ formatPrice(payment.amount) }}
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <Link :href="payment ? route('penyewa.transactions.show', payment.transaction_id) : route('penyewa.transactions.index')"
                            class="w-full py-4 px-6 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 transition-all duration-300 flex items-center justify-center gap-3 group">
                            <LayoutDashboard class="w-5 h-5 group-hover:scale-110 transition-transform" />
                            <span>Lihat Detail Tagihan</span>
                        </Link>

                        <Link :href="route('dashboard')"
                            class="w-full py-4 px-6 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl transition-all duration-300 flex items-center justify-center gap-3">
                            <Home class="w-5 h-5" />
                            <span>Ke Dashboard</span>
                        </Link>
                    </div>
                </div>

                <!-- Footer Support Information -->
                <div class="bg-gray-50/50 dark:bg-gray-900/50 p-6 text-center border-t border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Butuh bantuan? <a href="#" class="text-primary-600 dark:text-primary-400 font-bold hover:underline">Hubungi Admin</a>
                    </p>
                </div>
            </div>

            <!-- Bottom Helper Text -->
            <p class="text-center mt-8 text-gray-400 dark:text-gray-500 text-xs font-medium animate-fadeInUp" style="animation-delay: 0.8s">
                Email konfirmasi telah dikirimkan ke alamat Anda.
            </p>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Check, Clock, X, LayoutDashboard, Copy, Home } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    payment: Object,
    status: String,
    orderId: String,
    message: String,
});

const isSuccess = computed(() => {
    return ['settlement', 'capture', 'success'].includes(props.status) || (props.payment && props.payment.payment_status === 'success');
});

const isPending = computed(() => {
    return props.status === 'pending' || (props.payment && props.payment.payment_status === 'pending');
});

const title = computed(() => {
    if (isSuccess.value) return 'Great, Payment Success!';
    if (isPending.value) return 'Almost Done...';
    return 'Wait, Something Happened';
});

const formatPrice = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(value);
};

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
    // You could add a toast here
};

const generateConfettiStyle = (i) => {
    const colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'];
    const left = Math.random() * 100;
    const size = Math.random() * 10 + 5;
    const delay = Math.random() * 3;
    const color = colors[Math.floor(Math.random() * colors.length)];

    return {
        left: `${left}%`,
        width: `${size}px`,
        height: `${size}px`,
        backgroundColor: color,
        top: '-10px',
        animation: `confetti-fall 3s ease-out forwards ${delay}s`,
        opacity: 0
    };
};
</script>

<style scoped>
.bg-dashed-border {
    background-image: linear-gradient(to right, #e2e8f0 50%, transparent 50%);
    background-size: 10px 1px;
    background-repeat: repeat-x;
}

.dark .bg-dashed-border {
    background-image: linear-gradient(to right, #334155 50%, transparent 50%);
}

@keyframes slideUp {
    from { transform: translateY(40px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes fadeInUp {
    from { transform: translateY(10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes confetti-fall {
    0% { transform: translateY(0) rotate(0); opacity: 1; }
    100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
}

.animate-slideUp {
    animation: slideUp 1s cubic-bezier(0.19, 1, 0.22, 1) forwards;
}

.animate-fadeInUp {
    animation: fadeInUp 0.8s ease-out forwards;
}

.confetti {
    clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
}
</style>
