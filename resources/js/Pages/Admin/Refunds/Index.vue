<template>
    <Head title="Pengembalian Dana" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Pengembalian Dana</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Kelola pengembalian dana kepada penyewa
                </p>
            </div>
        </div>

        <div class="h-full flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <!-- Content Area -->
            <div class="overflow-x-auto pb-8 flex-1" data-simplebar>
                <div v-if="refunds.data && refunds.data.length > 0">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-24">
                                    ID / Tanggal
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Penyewa
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Jumlah
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Bukti
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                            <tr v-for="refund in refunds.data" :key="refund.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ refund.id }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(refund.created_at) }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-sm font-bold text-primary-600 dark:text-primary-400 shrink-0 border border-primary-200 dark:border-primary-800/50">
                                            {{ refund.user?.name?.charAt(0) || 'U' }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ refund.user?.name || 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ refund.user?.email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white font-bold">Rp {{ formatCurrency(refund.amount) }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span :class="statusClass(refund.status)"
                                        class="px-3 py-1.5 inline-flex text-xs font-bold rounded-full border">
                                        {{ refund.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <a v-if="refund.proof" :href="'/storage/' + refund.proof" target="_blank"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition-colors">Lihat Bukti</a>
                                    <span v-else class="text-gray-400 italic text-sm">Tidak ada bukti</span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button v-if="refund.status === 'pending'" @click="processRefund(refund)"
                                            class="p-2.5 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-xl transition-all shadow-sm" title="Proses">
                                            Proses
                                        </button>

                                        <button v-if="refund.status === 'process'" @click="openUploadModal(refund)"
                                            class="p-2.5 text-green-600 bg-green-50 hover:bg-green-100 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 rounded-xl transition-all shadow-sm" title="Upload Bukti">
                                            Upload Bukti
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-else
                    class="text-center py-20 bg-gray-50/50 dark:bg-gray-800/50 rounded-2xl border-dashed border border-gray-200 dark:border-gray-700 mx-4 my-6">
                    <div
                        class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tidak ada data</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                        Tidak ada data pengembalian dana saat ini.
                    </p>
                </div>
            </div>

            <!-- Pagination Placeholder -->
            <div v-if="refunds.links && refunds.links.length > 3" class="border-t border-gray-100 dark:border-gray-700 p-4">
                <div class="flex justify-between">
                    <div v-for="(link, k) in refunds.links" :key="k">
                        <Link v-if="link.url" :href="link.url" v-html="link.label"
                            class="px-2 py-1 border rounded text-gray-700 dark:text-gray-300 dark:border-gray-600" :class="{ 'bg-gray-200 dark:bg-gray-700': link.active }">
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Modal -->
        <DialogModal :show="showUploadModal" @close="closeUploadModal">
            <template #title>
                Upload Bukti Transfer
            </template>
            <template #content>
                <div class="mt-4">
                    <p class="text-sm text-gray-500 mb-4">
                        Upload bukti transfer untuk pengembalian dana kepada <strong>{{ selectedRefund?.user?.name }}</strong> sebesar <strong>Rp {{ formatCurrency(selectedRefund?.amount) }}</strong>.
                    </p>
                    <input type="file" @change="handleFileChange" class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-violet-50 file:text-violet-700
                        hover:file:bg-violet-100
                    " />
                    <InputError :message="form.errors.proof" class="mt-2" />
                </div>
            </template>
            <template #footer>
                <SecondaryButton @click="closeUploadModal">
                    Batal
                </SecondaryButton>
                <PrimaryButton class="ml-3" @click="submitProof" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Upload & Konfirmasi
                </PrimaryButton>
            </template>
        </DialogModal>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link, router, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    refunds: Object,
});

const showUploadModal = ref(false);
const selectedRefund = ref(null);

const form = useForm({
    _method: 'PUT',
    proof: null,
    status: 'menunggu konfirmasi', // Will be set by controller logic potentially, but we send it mostly for clarity or fallback
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID').format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });
};

const statusClass = (status) => {
    switch (status) {
        case 'pending': return 'bg-yellow-50 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800';
        case 'process': return 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800';
        case 'menunggu konfirmasi': return 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-800';
        case 'selesai': return 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800';
        case 'ditolak': return 'bg-primary-50 text-primary-700 border-primary-200 dark:bg-primary-900/30 dark:text-primary-400 dark:border-primary-800';
        default: return 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700';
    }
};

const processRefund = (refund) => {
    if (confirm('Proses pengembalian dana ini? Status akan berubah menjadi "process".')) {
        router.put(route('admin.refunds.update', refund.id), {
            status: 'process'
        });
    }
};

const openUploadModal = (refund) => {
    selectedRefund.value = refund;
    form.reset();
    form.clearErrors();
    showUploadModal.value = true;
    form._method = 'PUT'; // Ensure method spoofing
    form.status = 'menunggu konfirmasi'; // Logic in controller: if proof exists, status -> menunggu konfirmasi
};

const closeUploadModal = () => {
    showUploadModal.value = false;
    selectedRefund.value = null;
    form.reset();
};

const handleFileChange = (e) => {
    form.proof = e.target.files[0];
};

const submitProof = () => {
    if (!form.proof) {
        alert('Pilih file bukti transfer terlebih dahulu.');
        return;
    }

    form.post(route('admin.refunds.update', selectedRefund.value.id), {
        onSuccess: () => {
            closeUploadModal();
        }
    });
};
</script>
