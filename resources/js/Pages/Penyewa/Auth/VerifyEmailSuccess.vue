<script setup>
import { Head, Link } from '@inertiajs/vue3';
import FullScreenLayout from "@/Components/layout/FullScreenLayout.vue";

const props = defineProps({
    message: String,
});

</script>

<template>
    <FullScreenLayout>
        <Head title="Verifikasi Email Berhasil" />

        <div class="flex relative w-full min-h-[100dvh] overflow-hidden font-sans antialiased text-gray-900 transition-colors duration-300 bg-gray-50 dark:bg-gray-950">
            <!-- Background Elements -->
            <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-80 h-80 bg-green-500/10 rounded-full blur-3xl"></div>

                <!-- Custom background pattern -->
                <div class="absolute inset-0 bg-pattern opacity-50 dark:opacity-20"></div>
            </div>

            <div class="flex-1 flex flex-col items-center justify-center p-6 relative z-10 w-full">
                <!-- Main Card -->
                <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-2xl shadow-gray-200/50 dark:shadow-black/50 overflow-hidden border border-gray-100 dark:border-gray-800 animate-in fade-in zoom-in-95 duration-500">

                    <div class="p-8 sm:p-10">
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

                        <div class="flex justify-center mb-6">
                            <div class="w-20 h-20 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center text-green-600 dark:text-green-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">Verifikasi Berhasil!</h2>
                            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm leading-relaxed">
                                {{ message || 'Email Anda telah berhasil diverifikasi. Anda sekarang dapat mengakses semua fitur yang tersedia.' }}
                            </p>
                        </div>

                        <div class="space-y-6">
                            <!-- Link to Dashboard/Home -->
                            <Link :href="route('dashboard')" class="w-full flex justify-center py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-xl shadow-primary-600/20 transform transition-all active:scale-[0.98] cursor-pointer items-center gap-2">
                                <span>Lanjut ke Dashboard</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 text-sm text-gray-400 dark:text-gray-500 font-medium">
                    © {{ new Date().getFullYear() }} KosMates Platform. All rights reserved.
                </div>
            </div>
        </div>
    </FullScreenLayout>
</template>

<style scoped>
.bg-pattern {
    background-image: radial-gradient(circle at 2px 2px, rgba(0, 0, 0, 0.05) 1px, transparent 0);
    background-size: 32px 32px;
}

.dark .bg-pattern {
    background-image: radial-gradient(circle at 2px 2px, rgba(255, 255, 255, 0.05) 1px, transparent 0);
}
</style>
