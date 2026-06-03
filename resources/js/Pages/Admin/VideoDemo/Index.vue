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
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden"
            >
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white capitalize">Demo Aplikasi</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Video panduan penggunaan fitur-fitur KosMates</p>
                </div>

                <div class="p-6">
                    <!-- Protected video player -->
                    <div class="aspect-video w-full rounded-xl overflow-hidden bg-black">
                        <video
                            class="w-full h-full"
                            controls
                            controlsList="nodownload"
                            @contextmenu.prevent
                            src="/copy_59F7FFD2-F3F0-4A7F-BEB4-FD834C1BFD2E.mov"
                        ></video>
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
