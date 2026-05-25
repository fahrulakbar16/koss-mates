<template>
    <Head title="Edit Cluster" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                        Edit Cluster
                    </h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Ubah data cluster yang sudah ada
                </p>
            </div>

            <div class="flex items-center gap-3">
                <Link :href="route('clusters.index')"
                    class="inline-flex gap-2 items-center px-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
                    <BackIcon class="w-5 h-5" />
                    <span class="font-medium">Kembali</span>
                </Link>
            </div>
        </div>

        <div
            class="flex-1 overflow-hidden rounded-3xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 flex flex-col">
            <!-- Decorative gradient line -->
            <div class="h-1 bg-gradient-to-r from-primary-500 via-blue-500 to-purple-500 w-full"></div>

            <div class="overflow-auto px-8 pb-8" data-simplebar>
                <form @submit.prevent="updateCluster" class="max-w-4xl mx-auto py-6 space-y-6">
                    <div class="space-y-6 border-b border-gray-100 dark:border-gray-700 pb-8 last:border-0 last:pb-0">
                        <div class="space-y-2">
                            <label for="name" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Nama Cluster <span class="text-primary-500">*</span>
                            </label>
                            <input id="name"
                                class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                                type="text" v-model="form.name" required placeholder="Contoh: Cluster Melati" />
                            <div v-if="form.errors.name" class="text-xs text-primary-500 mt-1 font-medium">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="address" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Alamat Lengkap
                            </label>
                            <input id="address"
                                class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                                type="text" v-model="form.address" placeholder="Masukkan alamat lengkap cluster" />
                            <div v-if="form.errors.address" class="text-xs text-primary-500 mt-1 font-medium">
                                {{ form.errors.address }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Deskripsi
                            </label>
                            <textarea id="description"
                                class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400 resize-none"
                                v-model="form.description" rows="3"
                                placeholder="Tuliskan deskripsi singkat mengenai cluster ini..."></textarea>
                            <div v-if="form.errors.description" class="text-xs text-primary-500 mt-1 font-medium">
                                {{ form.errors.description }}
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 flex items-center justify-end gap-3 mt-8">
                        <Link :href="route('clusters.index')"
                            class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-100 transition-all dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white">
                            Batal
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-primary-600 to-primary-500 rounded-xl hover:from-primary-700 hover:to-primary-600 focus:ring-4 focus:ring-primary-500/30 transition-all shadow-lg shadow-primary-500/30 disabled:opacity-50 disabled:cursor-not-allowed transform hover:-translate-y-0.5">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2.5 h-4 w-4 text-white" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import BackIcon from "@/Components/icons/BackIcon.vue";
import { useForm, Head, Link } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    cluster: Object,
});

const form = useForm({
    name: props.cluster.name,
    address: props.cluster.address || "",
    description: props.cluster.description || "",
});

function updateCluster() {
    form.put(route("clusters.update", props.cluster.id), {
        onSuccess: () => {
            // Success is handled by Laravel redirect to back with 'success' property
        },
    });
}
</script>
