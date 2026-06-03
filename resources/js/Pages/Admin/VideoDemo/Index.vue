<template>
    <Head title="Video Demo" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                    Video Demo
                </h1>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm ml-5">
                Panduan penggunaan aplikasi KosMates
            </p>
        </div>

        <!-- Video list -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Each video -->
            <div
                v-for="video in videos"
                :key="video.filename"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden"
            >
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white capitalize">{{ video.label }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Video panduan penggunaan fitur-fitur KosMates</p>
                </div>

                <div class="p-6">
                    <!-- Protected video player -->
                    <div class="aspect-video w-full rounded-xl overflow-hidden bg-black">
                        <video
                            class="w-full h-full"
                            controls
                            :src="video.url"
                        ></video>
                    </div>
                </div>
            </div>

            <!-- Placeholder saat belum ada video -->
            <div
                v-if="videos.length === 0"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden"
            >
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Demo Aplikasi</h2>
                </div>
                <div class="p-6">
                    <div class="aspect-video w-full rounded-xl flex items-center justify-center bg-gray-50 dark:bg-gray-900 border-2 border-dashed border-gray-200 dark:border-gray-700">
                        <div class="text-center space-y-3">
                            <div class="w-16 h-16 mx-auto rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                <svg class="w-8 h-8 text-primary-500" fill="none" viewBox="0 0 24 24">
                                    <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M10 8.5L16 12L10 15.5V8.5Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum ada video tersedia</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Upload file video ke <code class="bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded text-xs">storage/app/videos/</code></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

defineProps({
    videos: {
        type: Array,
        default: () => [],
    },
});
</script>

<style scoped>
video {
    -webkit-user-select: none;
    user-select: none;
}
</style>
