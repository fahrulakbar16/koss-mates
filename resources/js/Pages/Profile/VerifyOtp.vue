<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';
import FullScreenLayout from '@/Components/layout/FullScreenLayout.vue';

const form = useForm({
    otp: '',
});

const submit = () => {
    form.post(route('profile.tenant.verify-otp'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Nomor WhatsApp berhasil diverifikasi.',
                timer: 1500,
                showConfirmButton: false,
            });
        },
    });
};
</script>

<template>
    <Head title="Verifikasi OTP" />

    <FullScreenLayout>
        <div class="min-h-screen flex flex-col justify-center items-center p-4 bg-gray-50/50 dark:bg-gray-950/50">
            <!-- Main Content Card -->
            <div class="w-full max-w-md animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl shadow-gray-200/50 dark:shadow-black/40 border border-gray-100 dark:border-gray-800 overflow-hidden relative">
                    <!-- Decorative Background element -->
                    <div class="absolute top-0 right-0 -mt-16 -mr-16 w-32 h-32 bg-primary-500/10 dark:bg-primary-500/5 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-32 h-32 bg-green-500/10 dark:bg-green-500/5 rounded-full blur-3xl"></div>

                    <div class="p-8 sm:p-10 relative z-10">
                        <!-- Logo Header -->
                        <div class="flex justify-center mb-8">
                            <Link href="/" class="flex gap-3 items-center group/logo">
                                <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-600/30 transform transition-all duration-300 group-hover/logo:scale-105 group-hover/logo:rotate-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <span class="text-2xl font-black tracking-tight dark:text-white">KosMates</span>
                            </Link>
                        </div>

                        <!-- Info Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">Verifikasi No WhatsApp</h2>
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm leading-relaxed">
                                Kami telah mengirimkan kode OTP ke nomor WhatsApp Anda. Silakan masukkan kode 6 digit tersebut di bawah ini untuk memverifikasi.
                            </p>
                        </div>

                        <!-- OTP Form -->
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="otp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-center">
                                    Kode Verifikasi (OTP)
                                </label>
                                <input
                                    id="otp"
                                    type="text"
                                    v-model="form.otp"
                                    class="w-full px-4 py-4 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-4 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-center tracking-[0.5em] text-3xl font-bold font-mono placeholder-gray-300 dark:placeholder-gray-600"
                                    required
                                    autofocus
                                    maxlength="6"
                                    placeholder="000000"
                                >
                                <p v-if="form.errors.otp" class="mt-2 text-sm text-primary-600 dark:text-primary-400 text-center font-medium">
                                    {{ form.errors.otp }}
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" :disabled="form.processing || form.otp.length < 6" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-xl shadow-primary-600/20 transform transition-all active:scale-[0.98] disabled:opacity-70 disabled:grayscale disabled:cursor-not-allowed cursor-pointer flex justify-center items-center gap-2">
                                <span v-if="form.processing" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>
                                    <template v-if="form.processing">Memverifikasi...</template>
                                    <template v-else>Verifikasi OTP</template>
                                </span>
                            </button>
                        </form>

                        <!-- Navigation Link -->
                        <div class="mt-8 text-center pt-6 border-t border-gray-100 dark:border-gray-800">
                            <Link
                                :href="route('profile.show')"
                                class="text-sm font-semibold text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-1"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Profil
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 text-center text-sm text-gray-400 dark:text-gray-500 font-medium">
                    © {{ new Date().getFullYear() }} KosMates Platform. All rights reserved.
                </div>
            </div>
        </div>
    </FullScreenLayout>
</template>
