<template>
    <Head title="Edit Aktivasi Akun" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <div class="flex items-center gap-3 mb-3">
            <Link :href="route('admin.aktivasi-akun.index')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </Link>
            <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Aktivasi Akun</h1>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input v-model="form.id_card_number" type="text" class="w-full rounded-lg border-gray-300 focus:border-primary-500" required />
                        <div v-if="form.errors.id_card_number" class="text-red-500 text-xs mt-1">{{ form.errors.id_card_number }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No WhatsApp</label>
                        <input v-model="form.phone" type="text" class="w-full rounded-lg border-gray-300 focus:border-primary-500" required />
                        <div v-if="form.errors.phone" class="text-red-500 text-xs mt-1">{{ form.errors.phone }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select v-model="form.gender" class="w-full rounded-lg border-gray-300 focus:border-primary-500" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        <div v-if="form.errors.gender" class="text-red-500 text-xs mt-1">{{ form.errors.gender }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input v-model="form.birth_date" type="date" class="w-full rounded-lg border-gray-300 focus:border-primary-500" required />
                        <div v-if="form.errors.birth_date" class="text-red-500 text-xs mt-1">{{ form.errors.birth_date }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paket Sewa</label>
                        <select v-model="form.payment_package" class="w-full rounded-lg border-gray-300 focus:border-primary-500" required>
                            <option value="Bulanan">Bulanan</option>
                            <option value="3 Bulan">3 Bulan</option>
                            <option value="6 Bulan">6 Bulan</option>
                            <option value="Tahunan">Tahunan</option>
                        </select>
                        <div v-if="form.errors.payment_package" class="text-red-500 text-xs mt-1">{{ form.errors.payment_package }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk</label>
                        <input v-model="form.entry_date" type="date" class="w-full rounded-lg border-gray-300 focus:border-primary-500" required />
                        <div v-if="form.errors.entry_date" class="text-red-500 text-xs mt-1">{{ form.errors.entry_date }}</div>
                    </div>
                    <div class="col-span-full">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Asal</label>
                        <textarea v-model="form.address" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary-500" required></textarea>
                        <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</div>
                    </div>
                </div>
                
                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" :disabled="form.processing" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center min-w-[150px] disabled:opacity-70">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    aktivasi: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    id_card_number: props.aktivasi.id_card_number,
    phone: props.aktivasi.phone,
    gender: props.aktivasi.gender,
    birth_date: props.aktivasi.birth_date,
    address: props.aktivasi.address,
    payment_package: props.aktivasi.payment_package,
    entry_date: props.aktivasi.entry_date
});

const submit = () => {
    form.put(route('admin.aktivasi-akun.update', props.aktivasi.id));
};
</script>
