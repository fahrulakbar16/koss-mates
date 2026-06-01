<template>
    <div class="fixed top-10 right-5 z-50 flex flex-col gap-3 min-w-[320px] max-w-sm">
        <TransitionGroup enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <!-- Success Toast -->
            <div v-if="successMessage" key="success"
                class="flex w-full overflow-hidden bg-white rounded-lg shadow-lg border-l-4 border-success-500 ring-1 ring-black/5 dark:bg-gray-800">
                <div class="p-4 w-full">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-success-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                Berhasil!
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ successMessage }}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="successMessage = ''"
                                class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-500 rounded-md">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Toast -->
            <div v-if="errorMessage" key="error"
                class="flex w-full overflow-hidden bg-white rounded-lg shadow-lg border-l-4 border-error-500 ring-1 ring-black/5 dark:bg-gray-800">
                <div class="p-4 w-full">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-error-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                Terjadi Kesalahan
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ errorMessage }}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="errorMessage = ''"
                                class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-error-500 rounded-md">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const successMessage = ref("");
const errorMessage = ref("");
const page = usePage();

// Watch flash messages
watch(
    () => page.props.flash?.success,
    (message) => {
        if (message) {
            successMessage.value = message;
            setTimeout(() => (successMessage.value = ""), 5000);
        }
    },
    { immediate: true }
);

watch(
    () => page.props.flash?.error,
    (message) => {
        if (message) {
            errorMessage.value = message;
            setTimeout(() => (errorMessage.value = ""), 5000);
        }
    },
    { immediate: true }
);

window.flash = {
    success: (msg) => {
        successMessage.value = msg;
        setTimeout(() => (successMessage.value = ""), 5000);
    },
    error: (msg) => {
        errorMessage.value = msg;
        setTimeout(() => (errorMessage.value = ""), 5000);
    },
};
</script>
