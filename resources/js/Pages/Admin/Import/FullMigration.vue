<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    CloudArrowUpIcon,
    DocumentTextIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
    ArrowPathIcon,
    BanknotesIcon
} from '@heroicons/vue/24/outline';

defineOptions({
    layout: AppLayout,
});

const importType = ref('migration'); // 'migration' or 'expense'

const form = useForm({
    file: null,
    type: 'migration', // default
});

const processing = ref(false);
const results = ref(null);

const handleFileUpload = (event) => {
    form.file = event.target.files[0];
};

const submit = () => {
    processing.value = true;
    form.type = importType.value;
    form.post(route('admin.import.process'), {
        onSuccess: (page) => {
            results.value = page.props.flash.import_results || page.props.import_results;
            processing.value = false;
        },
        onError: () => {
            processing.value = false;
        },
    });
};

const downloadMigrationTemplate = () => {
    const csvContent = "cluster,kos,no kamar,nama,kuliah,asal,no hp,plan,total harga,start kos,finis kost\n" +
        "Cluster Athara,AT1 Kav 2,1,Jane Doe,Teknik Informatika,Malang,08123456789,3 bulan,2850000,2024-01-01,2024-04-01";

    downloadFile(csvContent, "template_import_migration.csv");
};

const downloadExpenseTemplate = () => {
    const csvContent = "cluster,kos,kamar,kategori,deskripsi,nominal,tanggal\n" +
        "Cluster Athara,AT1 Kav 2,1,Listrik,Bayar token listrik kamar 1,200000,2024-05-10\n" +
        "Cluster Athara,AT1 Kav 2,,Kebersihan,Iuran sampah bulanan,50000,2024-05-11";

    downloadFile(csvContent, "template_import_pengeluaran.csv");
};

const downloadFile = (content, filename) => {
    const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", filename);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<template>
    <Head title="Import Data" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left: Instructions -->
                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <DocumentTextIcon class="w-6 h-6 mr-2 text-red-500" />
                            Panduan Import
                        </h3>

                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Umum:</h4>
                                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-5 h-5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-xs font-bold mt-0.5 mr-3">1</span>
                                        Gunakan format CSV dengan delimiter koma (,).
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-5 h-5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-xs font-bold mt-0.5 mr-3">2</span>
                                        Format tanggal: YYYY-MM-DD atau DD/MM/YYYY.
                                    </li>
                                </ul>
                            </div>

                            <div v-if="importType === 'migration'">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Migrasi Data:</h4>
                                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-5 h-5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center text-xs font-bold mt-0.5 mr-3">3</span>
                                        Membuat User, Kamar, dan Transaksi sewa otomatis.
                                    </li>
                                </ul>
                                <button @click="downloadMigrationTemplate" class="mt-4 w-full flex items-center justify-center px-4 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl transition-all duration-200 font-medium group">
                                    <CloudArrowUpIcon class="w-5 h-5 mr-2 group-hover:-translate-y-1 transition-transform" />
                                    Template Migrasi
                                </button>
                            </div>

                            <div v-else>
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pengeluaran:</h4>
                                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-5 h-5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-xs font-bold mt-0.5 mr-3">3</span>
                                        Mencatat biaya operasional atau pemeliharaan unit.
                                    </li>
                                </ul>
                                <button @click="downloadExpenseTemplate" class="mt-4 w-full flex items-center justify-center px-4 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl transition-all duration-200 font-medium group">
                                    <CloudArrowUpIcon class="w-5 h-5 mr-2 group-hover:-translate-y-1 transition-transform" />
                                    Template Pengeluaran
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Upload Form -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Type Selector -->
                    <div class="bg-white dark:bg-gray-800 p-2 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex gap-2">
                        <button
                            @click="importType = 'migration'"
                            :class="[
                                'flex-1 py-3 px-4 rounded-xl font-bold transition-all duration-200 flex items-center justify-center gap-2',
                                importType === 'migration'
                                    ? 'bg-red-600 text-white shadow-lg shadow-red-100 dark:shadow-none'
                                    : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50'
                            ]"
                        >
                            <DocumentTextIcon class="w-5 h-5" />
                            Migrasi Data
                        </button>
                        <button
                            @click="importType = 'expense'"
                            :class="[
                                'flex-1 py-3 px-4 rounded-xl font-bold transition-all duration-200 flex items-center justify-center gap-2',
                                importType === 'expense'
                                    ? 'bg-red-600 text-white shadow-lg shadow-red-100 dark:shadow-none'
                                    : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50'
                            ]"
                        >
                            <BanknotesIcon class="w-5 h-5" />
                            Pengeluaran
                        </button>
                    </div>

                    <!-- Upload Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl p-8 border border-gray-100 dark:border-gray-700">
                        <form @submit.prevent="submit">
                            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-3xl p-12 transition-colors hover:border-red-400 group relative">
                                <input
                                    type="file"
                                    @change="handleFileUpload"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    accept=".csv"
                                />

                                <div class="text-center">
                                    <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-2xl inline-block mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <CloudArrowUpIcon class="text-red-500 w-12 h-12" />
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white mb-1">
                                        {{ form.file ? form.file.name : 'Pilih File CSV' }}
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        Klik atau drag file ke area ini
                                    </p>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button
                                    type="submit"
                                    :disabled="!form.file || processing"
                                    class="px-8 py-4 text-white rounded-2xl font-bold flex items-center shadow-lg transition-all hover:-translate-y-1 active:translate-y-0 bg-red-600 hover:bg-red-700 shadow-red-200 disabled:bg-gray-400 disabled:shadow-none disabled:cursor-not-allowed"
                                >
                                    <ArrowPathIcon v-if="processing" class="w-5 h-5 mr-2 animate-spin" />
                                    Mulai Import {{ importType === 'migration' ? 'Data' : 'Pengeluaran' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Results Card -->
                    <div v-if="results" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl p-8 border border-gray-100 dark:border-gray-700 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Hasil Import</h3>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-2xl border border-emerald-100 dark:border-emerald-800/30">
                                <div class="flex items-center text-emerald-600 dark:text-emerald-400 mb-1">
                                    <CheckCircleIcon class="w-5 h-5 mr-2" />
                                    <span class="font-bold">Berhasil</span>
                                </div>
                                <p class="text-2xl font-black text-emerald-700 dark:text-emerald-300">{{ results.success }}</p>
                            </div>
                            <div class="bg-rose-50 dark:bg-rose-900/20 p-4 rounded-2xl border border-rose-100 dark:border-rose-800/30">
                                <div class="flex items-center text-rose-600 dark:text-rose-400 mb-1">
                                    <ExclamationCircleIcon class="w-5 h-5 mr-2" />
                                    <span class="font-bold">Gagal</span>
                                </div>
                                <p class="text-2xl font-black text-rose-700 dark:text-rose-300">{{ results.error }}</p>
                            </div>
                        </div>

                        <div v-if="results.messages.length > 0" class="space-y-3">
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Detail Error:</p>
                            <div class="max-h-60 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                                <div v-for="(msg, i) in results.messages" :key="i" class="text-xs p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg text-rose-600 dark:text-rose-400 border-l-4 border-rose-500">
                                    {{ msg }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
}
</style>
