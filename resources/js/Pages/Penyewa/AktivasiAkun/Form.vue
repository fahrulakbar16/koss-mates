<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <Head title="Aktivasi Akun" />
        <div class="max-w-2xl w-full bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-primary-600 px-6 py-8 text-center">
                <h1 class="text-2xl font-bold text-white mb-2">Aktivasi Akun Penyewa</h1>
                <p class="text-primary-100 text-sm">Silakan lengkapi data diri Anda untuk mengaktifkan akun dan mengakses layanan KosMates.</p>
            </div>
            
            <form @submit.prevent="submit" class="p-6 md:p-8 space-y-6">
                <!-- Data Diri -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informasi Profil</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                            <input v-model="form.id_card_number" type="text" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-shadow" placeholder="Masukkan 16 digit NIK" required />
                            <div v-if="form.errors.id_card_number" class="text-red-500 text-xs mt-1">{{ form.errors.id_card_number }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                            <input v-model="form.phone" type="text" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-shadow" placeholder="Contoh: 08123456789" required />
                            <div v-if="form.errors.phone" class="text-red-500 text-xs mt-1">{{ form.errors.phone }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select v-model="form.gender" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-shadow" required>
                                <option value="" disabled>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            <div v-if="form.errors.gender" class="text-red-500 text-xs mt-1">{{ form.errors.gender }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input v-model="form.birth_date" type="date" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-shadow" required />
                            <div v-if="form.errors.birth_date" class="text-red-500 text-xs mt-1">{{ form.errors.birth_date }}</div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Asal Lengkap</label>
                        <textarea v-model="form.address" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-shadow" placeholder="Masukkan alamat lengkap sesuai KTP" required></textarea>
                        <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</div>
                    </div>
                </div>

                <!-- Paket Sewa -->
                <div class="space-y-4 pt-4 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informasi Sewa</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paket Pembayaran</label>
                            <select v-model="form.payment_package" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-shadow" required>
                                <option value="" disabled>Pilih Paket</option>
                                <option value="Bulanan">Bulanan</option>
                                <option value="3 Bulan">3 Bulan</option>
                                <option value="6 Bulan">6 Bulan</option>
                                <option value="Tahunan">Tahunan</option>
                            </select>
                            <div v-if="form.errors.payment_package" class="text-red-500 text-xs mt-1">{{ form.errors.payment_package }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Masuk</label>
                            <input v-model="form.entry_date" type="date" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 transition-shadow" required />
                            <div v-if="form.errors.entry_date" class="text-red-500 text-xs mt-1">{{ form.errors.entry_date }}</div>
                        </div>
                    </div>
                </div>

                <!-- Submit Action -->
                <div class="pt-6 flex justify-between items-center border-t border-gray-100">
                    <Link :href="route('logout')" method="post" as="button" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                        Logout
                    </Link>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center min-w-[150px] disabled:opacity-70">
                        <span v-if="form.processing">Memproses...</span>
                        <span v-else>Kirim Pengajuan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';

const form = useForm({
    id_card_number: '',
    phone: '',
    gender: '',
    birth_date: '',
    address: '',
    payment_package: '',
    entry_date: ''
});

const submit = () => {
    form.post(route('aktivasi-akun.store'));
};
</script>
