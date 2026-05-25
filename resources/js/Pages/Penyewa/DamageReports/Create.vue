<template>
    <Head title="Buat Laporan Kerusakan" />

    <div class="min-h-screen bg-[#F8FAFC] dark:bg-[#0F172A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
            <!-- Header -->
            <div class="mb-12">

                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-3">
                        Lapor Kerusakan Baru
                    </h1>
                    <p class="text-lg text-slate-500 dark:text-slate-400 font-medium">
                        Laporkan masalah pada fasilitas kamar atau kos Anda
                    </p>
                </div>
            </div>

            <!-- Active Room Info -->
            <div class="bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-2xl p-6 mb-8 flex items-start gap-4">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-800 rounded-full flex items-center justify-center flex-shrink-0 text-primary-600 dark:text-primary-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg">
                        {{ activeRoom?.room?.name || 'Kamar' }} - {{ activeRoom?.name }}
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 mt-1">
                        Laporan ini akan terkait dengan kamar aktif Anda saat ini.
                    </p>
                </div>
            </div>

            <!-- Form Content -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-8 sm:p-10 relative z-10">
                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Title field -->
                        <div class="space-y-2 group">
                            <InputLabel value="Judul Laporan"
                                class="text-base font-bold text-slate-700 dark:text-slate-200 ml-1" />
                            <div class="relative transition-all duration-300 transform focus-within:-translate-y-1">
                                <div
                                    class="absolute top-3 left-0 flex items-start pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <input v-model="form.title" type="text"
                                    class="w-full pl-11 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-700 dark:text-white focus:border-primary-500 focus:ring-primary-500/20 py-3 transition-all duration-200 shadow-sm"
                                    placeholder="Contoh: AC Bocor, Lampu Mati, Atap Rembes" required />
                            </div>
                            <InputError :message="form.errors.title" class="ml-1" />
                        </div>

                        <!-- Description field -->
                        <div class="space-y-2 group">
                            <InputLabel value="Deskripsi Detail"
                                class="text-base font-bold text-slate-700 dark:text-slate-200 ml-1" />
                            <div class="relative transition-all duration-300 transform focus-within:-translate-y-1">
                                <div
                                    class="absolute top-3 left-0 flex items-start pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                </div>
                                <textarea v-model="form.description"
                                    class="w-full pl-11 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-700 dark:text-white focus:border-primary-500 focus:ring-primary-500/20 shadow-sm transition-all duration-200 resize-none"
                                    rows="5"
                                    placeholder="Jelaskan secara rinci kerusakan yang terjadi, lokasi spesifik, dan kapan mulai terjadi..." required></textarea>
                            </div>
                            <InputError :message="form.errors.description" class="ml-1" />
                        </div>

                        <!-- Photo upload -->
                        <div class="space-y-2 group">
                            <InputLabel value="Foto Bukti (Opsional)"
                                class="text-base font-bold text-slate-700 dark:text-slate-200 ml-1" />

                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 dark:border-slate-700 border-dashed rounded-[2rem] relative hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer"
                                    @click="$refs.fileInput.click()"
                                    @dragover.prevent @drop.prevent="handleDrop">

                                <div class="space-y-2 text-center">
                                    <template v-if="!previewUrl">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                                            <label for="file-upload" class="relative cursor-pointer bg-transparent rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                                <span>Upload a file</span>
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            PNG, JPG, GIF up to 2MB
                                        </p>
                                    </template>
                                    <template v-else>
                                        <img :src="previewUrl" class="mx-auto h-48 w-auto object-contain rounded-xl shadow-md" />
                                        <button type="button" @click.stop="removePhoto" class="mt-4 px-4 py-2 text-sm font-semibold text-primary-500 bg-primary-50 dark:bg-primary-500/10 rounded-full hover:bg-primary-100 dark:hover:bg-primary-500/20 transition-colors">
                                            Hapus Foto
                                        </button>
                                    </template>
                                </div>
                                <input ref="fileInput" id="file-upload" name="file-upload" type="file" class="sr-only" @change="handlePhotoChange" accept="image/*">
                            </div>
                            <InputError :message="form.errors.photo" class="ml-1" />
                        </div>

                        <div class="pt-6 flex items-center justify-end gap-4 border-t border-slate-100 dark:border-slate-800/50">
                            <Link :href="route('penyewa.damage-reports.index')"
                                class="px-6 py-3 rounded-xl font-bold text-sm text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800 transition-all duration-200">
                                Batal
                            </Link>

                            <PrimaryButton
                                class="px-8 py-3 !rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 transform hover:-translate-y-0.5 transition-all duration-200"
                                :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                                :disabled="form.processing">
                                <div class="flex items-center">
                                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    {{ form.processing ? 'Sedang Mengirim...' : 'Kirim Laporan' }}
                                </div>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head, useForm } from "@inertiajs/vue3";
import { ref } from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    activeRoom: Object
});

const form = useForm({
    title: '',
    description: '',
    photo: null,
});

const previewUrl = ref(null);
const fileInput = ref(null);

const handlePhotoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        processFile(file);
    }
};

const handleDrop = (event) => {
    const file = event.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        processFile(file);
    }
};

const processFile = (file) => {
    form.photo = file;
    const reader = new FileReader();
    reader.onload = (e) => {
        previewUrl.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const removePhoto = () => {
    form.photo = null;
    previewUrl.value = null;
    if (fileInput.value) fileInput.value.value = '';
};

const submit = () => {
    form.post(route('penyewa.damage-reports.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Redirect handled by controller/Inertia
        },
    });
};
</script>
