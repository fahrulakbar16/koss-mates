<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineOptions({
    layout: PublicLayout,
});

const props = defineProps({
    status: {
        type: Number,
        required: true,
    },
});

const title = computed(() => {
    return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
    }[props.status] || 'Error';
});

const description = computed(() => {
    return {
        503: 'Maaf, kami sedang melakukan pemeliharaan rutin. Silakan kembali lagi nanti.',
        500: 'Ups, terjadi kesalahan pada server kami. Tim kami sedang menanganinya.',
        404: 'Halaman yang Anda cari tidak ditemukan atau telah dipindahkan.',
        403: 'Maaf, Anda tidak memiliki akses untuk melihat halaman ini.',
    }[props.status] || 'Terjadi kesalahan yang tidak terduga.';
});

const statusText = computed(() => {
    return props.status.toString();
});
</script>

<template>

    <Head :title="title" />

    <div class="relative min-h-[70vh] flex items-center justify-center overflow-hidden py-20">
        <!-- Background Decorations -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary-50/50 dark:bg-primary-900/10 rounded-full blur-3xl opacity-50">
            </div>
        </div>

        <div class="max-w-xl mx-auto px-4 text-center relative z-10">
            <div class="animate-fade-in">
                <!-- Status Code -->
                <h1
                    class="text-[120px] md:text-[180px] font-black leading-none mb-4 tracking-tighter text-transparent bg-clip-text bg-gradient-to-br from-primary-600 to-primary-400 dark:from-primary-500 dark:to-primary-300 opacity-20 select-none">
                    {{ statusText }}
                </h1>

                <!-- Message -->
                <div class="-mt-12 md:-mt-20">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ title }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-10 leading-relaxed max-w-md mx-auto">
                        {{ description }}
                    </p>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link href="/"
                            class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-xl shadow-primary-600/30 hover:shadow-primary-600/50 hover:scale-[1.02] active:scale-[0.98]">
                            Kembali ke Beranda
                        </Link>
                        <button @click="() => window.history.back()"
                            class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-white font-bold rounded-2xl border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition-all duration-300">
                            Halaman Sebelumnya
                        </button>
                    </div>
                </div>

                <!-- Helpful Links -->
                <div class="mt-20 pt-10 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 font-medium uppercase tracking-widest">
                        Butuh bantuan?
                    </p>
                    <div class="flex flex-wrap justify-center gap-x-8 gap-y-4">
                        <a href="/#faq"
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors">Pusat
                            Bantuan</a>
                        <a href="https://wa.me/your-number" target="_blank"
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors">WhatsApp
                            Media</a>
                        <a href="mailto:support@Tharahub.com"
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary-600 transition-colors">Email
                            Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
