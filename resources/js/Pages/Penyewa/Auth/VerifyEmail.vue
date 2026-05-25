<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import FullScreenLayout from "@/Components/layout/FullScreenLayout.vue";

const props = defineProps({
    status: String,
    emailVerified: {
        type: Boolean,
        default: false
    },
    phoneVerified: {
        type: Boolean,
        default: false
    }
});

const form = useForm({});
const phoneForm = useForm({
    phone: '' // Only used if they need to input a phone number, but we can just use the OTP send endpoint that doesn't need phone if they already have it, or we allow them to input it if missing from profile. For simplicity, we'll hit the OTP endpoint that takes 'phone'.
});

const page = usePage();

// Current active active verification step
const currentStep = computed(() => {
    if (!props.emailVerified) return 'email';
    if (!props.phoneVerified) return 'phone';
    return 'done';
});

// Countdown state - Email
const countdownEmail = ref(0);
let timerEmail;

// Countdown state - Phone
const countdownPhone = ref(0);
let timerPhone;

const startCountdownEmail = () => {
    countdownEmail.value = 60;
    const endTime = new Date().getTime() + 60000;
    localStorage.setItem('verifyEmailResendDelay', endTime.toString());

    timerEmail = setInterval(() => {
        countdownEmail.value--;
        if (countdownEmail.value <= 0) {
            clearInterval(timerEmail);
        }
    }, 1000);
};

const startCountdownPhone = () => {
    countdownPhone.value = 300; // 5 minutes
    const endTime = new Date().getTime() + 300000;
    localStorage.setItem('verifyPhoneResendDelay', endTime.toString());

    timerPhone = setInterval(() => {
        countdownPhone.value--;
        if (countdownPhone.value <= 0) {
            clearInterval(timerPhone);
        }
    }, 1000);
};

onMounted(() => {
    // Check Email Countdown
    const savedEndTimeEmail = localStorage.getItem('verifyEmailResendDelay');
    if (savedEndTimeEmail) {
        const remaining = Math.ceil((parseInt(savedEndTimeEmail) - new Date().getTime()) / 1000);
        if (remaining > 0) {
            countdownEmail.value = remaining;
            timerEmail = setInterval(() => {
                countdownEmail.value--;
                if (countdownEmail.value <= 0) {
                    clearInterval(timerEmail);
                    localStorage.removeItem('verifyEmailResendDelay');
                }
            }, 1000);
        } else {
            localStorage.removeItem('verifyEmailResendDelay');
        }
    }

    // Check Phone Countdown
    const savedEndTimePhone = localStorage.getItem('verifyPhoneResendDelay');
    if (savedEndTimePhone) {
        const remaining = Math.ceil((parseInt(savedEndTimePhone) - new Date().getTime()) / 1000);
        if (remaining > 0) {
            countdownPhone.value = remaining;
            timerPhone = setInterval(() => {
                countdownPhone.value--;
                if (countdownPhone.value <= 0) {
                    clearInterval(timerPhone);
                    localStorage.removeItem('verifyPhoneResendDelay');
                }
            }, 1000);
        } else {
            localStorage.removeItem('verifyPhoneResendDelay');
        }
    }
});

onUnmounted(() => {
    if (timerEmail) clearInterval(timerEmail);
    if (timerPhone) clearInterval(timerPhone);
});

const submitEmail = () => {
    if (countdownEmail.value > 0) return;

    form.post(route('penyewa.verification.send'), {
        onSuccess: () => {
            startCountdownEmail();
        }
    });
};

const sendPhoneOtp = () => {
    if (countdownPhone.value > 0) return;

    // Hit the web route instead of the API route to receive a valid Inertia redirect
    phoneForm.post(route('profile.tenant.send-otp'), {
        onSuccess: () => {
            startCountdownPhone();
            // The server will automatically redirect the user to verify-otp.show
            // so we don't need window.location.href here anymore.
        }
    });
};
</script>

<template>
    <FullScreenLayout>
        <Head title="Verifikasi Email" />

        <div class="flex relative w-full min-h-[100dvh] overflow-hidden font-sans antialiased text-gray-900 transition-colors duration-300 bg-gray-50 dark:bg-gray-950">
            <!-- Background Elements -->
            <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>

                <!-- Custom background pattern -->
                <div class="absolute inset-0 bg-pattern opacity-50 dark:opacity-20"></div>
            </div>

            <div class="flex-1 flex flex-col items-center justify-center p-6 relative z-10 w-full">
                <!-- Main Card -->
                <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-2xl shadow-gray-200/50 dark:shadow-black/50 overflow-hidden border border-gray-100 dark:border-gray-800 animate-in fade-in zoom-in-95 duration-500">

                    <div class="p-8 sm:p-10">
                        <!-- Logo Header -->
                        <div class="flex justify-center mb-8">
                            <Link href="/" class="flex gap-3 items-center group/logo">
                                <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-600/30 transform transition-all duration-300 group-hover/logo:scale-105 group-hover/logo:rotate-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <span class="text-2xl font-black tracking-tight dark:text-white">Tharahub</span>
                            </Link>
                        </div>

                        <div v-if="currentStep === 'email'" class="text-center mb-8">
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">Verifikasi Email</h2>
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm leading-relaxed">
                                Sebelum dapat mengakses fitur penyewa, harap lengkapi verifikasi email Anda terlebih dahulu. Silahkan periksa kotak masuk email Anda atau klik tombol di bawah ini untuk mengirim ulang tautan verifikasi.
                            </p>
                        </div>

                        <div v-else-if="currentStep === 'phone'" class="text-center mb-8">
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">Verifikasi No WhatsApp</h2>
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm leading-relaxed">
                                Email Anda sudah terverifikasi! Langkah selanjutnya, mohon verifikasi Nomor Telepon/WhatsApp Anda untuk keamanan akun dan kemudahan komunikasi.
                            </p>
                        </div>

                        <div v-else class="text-center mb-8">
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">Terverifikasi</h2>
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm leading-relaxed">
                                Seluruh kontak Anda telah diverifikasi. Memuat halaman dashboard...
                            </p>
                        </div>

                        <!-- Alerts -->
                        <div v-if="$page.props.flash?.success" class="mb-6 flex items-center gap-3 p-4 bg-green-50 dark:bg-green-950/30 border border-green-100 dark:border-green-900/50 rounded-2xl text-green-600 dark:text-green-400 text-sm font-medium animate-in fade-in slide-in-from-top-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ $page.props.flash.success }}
                        </div>

                        <div v-if="$page.props.flash?.error" class="mb-6 flex items-center gap-3 p-4 bg-primary-50 dark:bg-primary-950/30 border border-primary-100 dark:border-primary-900/50 rounded-2xl text-primary-600 dark:text-primary-400 text-sm font-medium animate-in fade-in slide-in-from-top-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            {{ $page.props.flash.error }}
                        </div>

                        <!-- Email Verification Form -->
                        <form v-if="currentStep === 'email'" @submit.prevent="submitEmail" class="space-y-6">
                            <!-- Submit Button -->
                            <button type="submit" :disabled="form.processing || countdownEmail > 0" class="w-full py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-xl shadow-primary-600/20 transform transition-all active:scale-[0.98] disabled:opacity-70 disabled:grayscale disabled:cursor-not-allowed cursor-pointer flex justify-center items-center gap-2">
                                <span v-if="form.processing" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <svg v-else-if="countdownEmail === 0" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                                <span>
                                    <template v-if="form.processing">Mengirim...</template>
                                    <template v-else-if="countdownEmail > 0">Tunggu {{ countdownEmail }} detik</template>
                                    <template v-else>Kirim Ulang Email Verifikasi</template>
                                </span>
                            </button>
                        </form>

                        <!-- Phone Verification Form -->
                        <form v-if="currentStep === 'phone'" @submit.prevent="sendPhoneOtp" class="space-y-6">
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Masukkan Nomor WhatsApp
                                </label>
                                <input
                                    id="phone"
                                    type="text"
                                    v-model="phoneForm.phone"
                                    placeholder="Contoh: 081234567890"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    required
                                >
                                <p v-if="phoneForm.errors.phone" class="mt-2 text-sm text-primary-600 dark:text-primary-400">
                                    {{ phoneForm.errors.phone }}
                                </p>
                            </div>

                            <button type="submit" :disabled="phoneForm.processing || countdownPhone > 0" class="w-full py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-2xl shadow-xl shadow-green-600/20 transform transition-all active:scale-[0.98] disabled:opacity-70 disabled:grayscale disabled:cursor-not-allowed cursor-pointer flex justify-center items-center gap-2">
                                <span v-if="phoneForm.processing" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <svg v-else-if="countdownPhone === 0" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                                <span>
                                    <template v-if="phoneForm.processing">Mengirim OTP...</template>
                                    <template v-else-if="countdownPhone > 0">Tunggu {{ countdownPhone }} detik</template>
                                    <template v-else>Kirim WhatsApp OTP</template>
                                </span>
                            </button>
                        </form>

                            <!-- Navigation Links -->
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="flex items-center gap-1.5 text-sm font-bold text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                                </Link>
                            </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 text-sm text-gray-400 dark:text-gray-500 font-medium">
                    © {{ new Date().getFullYear() }} Tharahub Platform. All rights reserved.
                </div>
            </div>
        </div>
    </FullScreenLayout>
</template>

<style scoped>
@keyframes spin {
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

.bg-pattern {
    background-image: radial-gradient(circle at 2px 2px, rgba(0, 0, 0, 0.05) 1px, transparent 0);
    background-size: 32px 32px;
}

.dark .bg-pattern {
    background-image: radial-gradient(circle at 2px 2px, rgba(255, 255, 255, 0.05) 1px, transparent 0);
}
</style>
