<template>
    <Head title="Edit Akun Penyewa" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-2">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                        Edit Akun Penyewa
                    </h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Perbarui informasi akun penyewa
                </p>
            </div>
<!--
            <Link :href="route('admin.tenants.index')"
                class="inline-flex gap-2 items-center px-5 py-2.5 text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-300 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="font-medium">Kembali</span>
            </Link> -->
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <div class="p-6 sm:p-8 md:p-10">
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Account Information -->
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-8 last:border-0 last:pb-0">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            Informasi Akun
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Nama Lengkap <span class="text-primary-500">*</span>
                                </label>
                                <input v-model="form.name" type="text"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                                    :class="{ 'border-primary-500': errors.name }" />
                                <div v-if="errors.name" class="text-xs text-primary-500 mt-1 font-medium">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Username <span class="text-primary-500">*</span>
                                </label>
                                <input v-model="form.username" type="text"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                                    :class="{ 'border-primary-500': errors.username }" />
                                <div v-if="errors.username" class="text-xs text-primary-500 mt-1 font-medium">
                                    {{ errors.username }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Email <span class="text-primary-500">*</span>
                                </label>
                                <input v-model="form.email" type="email"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                                    :class="{ 'border-primary-500': errors.email }" />
                                <div v-if="errors.email" class="text-xs text-primary-500 mt-1 font-medium">
                                    {{ errors.email }}
                                </div>
                            </div>

                            <div class="md:col-span-2 pt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Password (Kosongkan jika tidak ingin mengubah)</p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Password Baru
                                </label>
                                <input v-model="form.password" type="password"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                                    :class="{ 'border-primary-500': errors.password }" />
                                <div v-if="errors.password" class="text-xs text-primary-500 mt-1 font-medium">
                                    {{ errors.password }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Konfirmasi Password Baru
                                </label>
                                <input v-model="form.password_confirmation" type="password"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400" />
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-8 last:border-0 last:pb-0">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Informasi Pribadi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    No. Telepon <span class="text-primary-500">*</span>
                                </label>
                                <input v-model="form.phone" type="text"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                                    :class="{ 'border-primary-500': errors.phone }" />
                                <div v-if="errors.phone" class="text-xs text-primary-500 mt-1 font-medium">
                                    {{ errors.phone }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Jenis Kelamin
                                </label>
                                <select v-model="form.gender"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400">
                                    <option value="" disabled hidden>Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <div v-if="errors.gender" class="text-xs text-primary-500 mt-1 font-medium">
                                    {{ errors.gender }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    No. KTP
                                </label>
                                <input v-model="form.id_card_number" type="text"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Tanggal Lahir
                                </label>
                                <input v-model="form.birth_date" type="date"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400" />
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Alamat
                                </label>
                                <textarea v-model="form.address" rows="3"
                                    class="w-full px-4 py-3 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400 resize-none"></textarea>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Kontak Darurat
                                </label>
                                <input v-model="form.emergency_contact" type="text"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400" />
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-gray-100 dark:border-gray-700">
                        <Link :href="route('admin.tenants.index')"
                            class="px-6 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-750 transition-all">
                            Batal
                        </Link>
                        <button type="submit" :disabled="processing"
                            class="px-8 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <span v-if="processing" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menyimpan...
                            </span>
                            <span v-else>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    tenant: {
        type: Object,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    name: props.tenant.name,
    username: props.tenant.username,
    email: props.tenant.email,
    password: '',
    password_confirmation: '',
    phone: props.tenant.phone || '',
    gender: props.tenant.gender || '',
    id_card_number: props.tenant.id_card_number || '',
    birth_date: props.tenant.birth_date || '',
    address: props.tenant.address || '',
    emergency_contact: props.tenant.emergency_contact || '',
});

const processing = computed(() => form.processing);

function submit() {
    form.put(route('admin.tenants.update', props.tenant.id));
}
</script>
