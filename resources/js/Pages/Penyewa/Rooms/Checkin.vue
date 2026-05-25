<template>

    <Head title="Check-in Kamar" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                    Check-in Kamar
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Upload foto kamar untuk melanjutkan proses check-in
                </p>
            </div>

            <!-- Check-in Form Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <!-- Room Info -->
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ activeRoom.name }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ activeRoom.address || '-' }}
                        </p>
                    </div>

                    <!-- Upload Form -->
                    <form @submit.prevent="submitForm">
                        <div class="space-y-6">
                            <!-- Instructions -->
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-5 border border-blue-200 dark:border-blue-800">
                                <div class="flex gap-3">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-blue-900 dark:text-blue-100 mb-1">
                                            Petunjuk Upload Foto
                                        </h3>
                                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                                            <li>• Pastikan foto kamar jelas dan terang</li>
                                            <li>• Format file: JPEG, PNG, atau JPG</li>
                                            <li>• Ukuran maksimal: 2MB</li>
                                            <li>• Foto akan diverifikasi oleh admin sebelum dapat mengakses kamar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- File Upload Area -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    Foto Kamar <span class="text-primary-500">*</span>
                                </label>

                                <!-- Upload Button (when no image selected) -->
                                <div v-if="!preview" @click="$refs.fileInput.click()"
                                    class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-8 text-center hover:border-primary-500 dark:hover:border-primary-500 transition-all cursor-pointer group">
                                    <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/jpg"
                                        @change="handleFileChange" class="hidden" />

                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/30 transition-colors">
                                            <svg class="w-8 h-8 text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300 font-medium mb-1">
                                            Klik untuk upload foto
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            JPEG, PNG, JPG (Max. 2MB)
                                        </p>
                                    </div>
                                </div>

                                <!-- Image Preview (when image selected) -->
                                <div v-else class="relative">
                                    <div
                                        class="rounded-2xl overflow-hidden border-2 border-gray-200 dark:border-gray-700">
                                        <img :src="preview" alt="Preview" class="w-full h-64 object-cover" />
                                    </div>
                                    <button type="button" @click="removeImage"
                                        class="absolute top-3 right-3 bg-primary-500 hover:bg-primary-600 text-white rounded-full p-2 shadow-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <button type="button" @click="$refs.fileInput.click()"
                                        class="absolute bottom-3 right-3 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl px-4 py-2 shadow-lg transition-colors font-medium">
                                        Ganti Foto
                                    </button>
                                    <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/jpg"
                                        @change="handleFileChange" class="hidden" />
                                </div>

                                <!-- Error Message -->
                                <p v-if="form.errors.foto_kamar" class="mt-2 text-sm text-primary-600 dark:text-primary-400">
                                    {{ form.errors.foto_kamar }}
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex gap-4 pt-4">
                                <button type="submit" :disabled="!preview || form.processing"
                                    class="flex-1 py-4 px-6 bg-primary-600 hover:bg-primary-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                                    <svg v-if="form.processing" class="animate-spin w-5 h-5" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span v-if="form.processing">Mengupload...</span>
                                    <span v-else>Upload & Submit</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    activeRoom: Object,
});

const activeRoom = props.activeRoom.data;

const form = useForm({
    foto_kamar: null,
});

const preview = ref(null);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2048 * 1024) {
            form.errors.foto_kamar = "Ukuran file maksimal 2MB";
            event.target.value = null;
            return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!validTypes.includes(file.type)) {
            form.errors.foto_kamar = "Format file harus JPEG, PNG, atau JPG";
            event.target.value = null;
            return;
        }

        form.foto_kamar = file;
        form.errors.foto_kamar = null;

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeImage = () => {
    form.foto_kamar = null;
    preview.value = null;
    form.errors.foto_kamar = null;
};

const submitForm = () => {
    form.post(route('penyewa.rooms.checkin.submit'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            preview.value = null;
        },
    });
};
</script>
