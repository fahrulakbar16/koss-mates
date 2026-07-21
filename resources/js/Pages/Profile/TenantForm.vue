<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Swal from "sweetalert2";

const page = usePage();
const user = computed(() => page.props.auth.user);

const ktpPreview = ref(user.value?.file_ktp ? '/storage/' + user.value.file_ktp : null);

const form = useForm({
    _method: 'put',
    phone: user.value?.phone || "",
    address: user.value?.address || "",
    id_card_number: user.value?.id_card_number || "",
    file_ktp: null,
    birth_date: user.value?.birth_date || "",
    gender: user.value?.gender || "",
    emergency_contact: user.value?.emergency_contact || "",
});

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.file_ktp = file;
        ktpPreview.value = URL.createObjectURL(file);
    } else {
        form.file_ktp = null;
        ktpPreview.value = user.value?.file_ktp ? '/storage/' + user.value.file_ktp : null;
    }
};

const submit = () => {
    form.post(route("profile.tenant.update"), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Biodata penyewa berhasil diperbarui.",
                timer: 1500,
                showConfirmButton: false,
            });
        },
        onError: () => {
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "Terjadi kesalahan saat memperbarui biodata.",
            });
        },
    });
};
</script>

<template>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden mt-8">
        <!-- Header Section -->
        <div class="px-4 sm:px-8 py-5 flex items-center justify-between border-b border-slate-100 bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Biodata Penyewa</h3>
                <p class="text-sm text-slate-500">Lengkapi informasi identitas dan kontak darurat Anda.</p>
            </div>
        </div>

        <form @submit.prevent="submit" class="px-6 py-10 sm:p-10 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-8">
                <!-- Phone -->
                <div class="space-y-1">
                    <label for="phone" class="text-xs font-bold uppercase tracking-wider text-slate-400">Nomor
                        Telepon</label>
                    <input v-model="form.phone" type="text" id="phone"
                        class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition" />
                    <p v-if="form.errors.phone" class="text-xs text-primary-500 font-medium mt-1">{{ form.errors.phone[0] }}
                    </p>
                </div>

                <!-- Gender -->
                <div class="space-y-1">
                    <label for="gender" class="text-xs font-bold uppercase tracking-wider text-slate-400">Jenis
                        Kelamin</label>
                    <select v-model="form.gender" id="gender"
                        class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition bg-white">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                    <p v-if="form.errors.gender" class="text-xs text-primary-500 font-medium mt-1">{{ form.errors.gender[0] }}
                    </p>
                </div>

                <!-- ID Card Number -->
                <div class="space-y-1">
                    <label for="id_card_number" class="text-xs font-bold uppercase tracking-wider text-slate-400">Nomor
                        KTP</label>
                    <input v-model="form.id_card_number" type="text" id="id_card_number"
                        class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition" />
                    <p v-if="form.errors.id_card_number" class="text-xs text-primary-500 font-medium mt-1">{{
                        form.errors.id_card_number[0] }}</p>
                </div>

                <!-- File KTP -->
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Foto KTP</label>
                    <div class="relative w-full sm:max-w-sm">
                        <input type="file" id="file_ktp" @change="handleFileChange" accept="image/*" class="sr-only" />
                        <label for="file_ktp"
                               class="flex flex-col items-center justify-center w-full aspect-[16/10] border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200 overflow-hidden group"
                               :class="ktpPreview ? 'border-primary-500 bg-primary-500/5' : 'border-slate-300 bg-slate-50 hover:bg-slate-100 hover:border-slate-400'">

                            <!-- If there is preview -->
                            <img v-if="ktpPreview" :src="ktpPreview" alt="Preview KTP" class="w-full h-full object-cover" />

                            <!-- If no preview -->
                            <div v-else class="flex flex-col items-center justify-center p-4 text-center">
                                <div class="p-3 bg-white rounded-full shadow-sm mb-3 text-slate-400 group-hover:text-primary-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-700">Klik untuk upload KTP</p>
                                <p class="text-xs text-slate-500 mt-1">PNG, JPG, JPEG (Maks. 2MB)</p>
                            </div>

                            <!-- Overlay when hovering over image -->
                            <div v-if="ktpPreview" class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <span class="text-white text-sm font-medium">Ubah Foto</span>
                            </div>
                        </label>
                    </div>

                    <p v-if="form.errors.file_ktp" class="text-xs text-primary-500 font-medium mt-1">{{ form.errors.file_ktp[0] }}</p>
                </div>

                <!-- Birth Date -->
                <div class="space-y-1">
                    <label for="birth_date" class="text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal
                        Lahir</label>
                    <input v-model="form.birth_date" type="date" id="birth_date"
                        class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition" />
                    <p v-if="form.errors.birth_date" class="text-xs text-primary-500 font-medium mt-1">{{
                        form.errors.birth_date[0] }}</p>
                </div>

                <!-- Address (Span Full) -->
                <div class="sm:col-span-2 space-y-1">
                    <label for="address" class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat
                        Lengkap</label>
                    <textarea v-model="form.address" id="address" rows="3"
                        class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition"
                        placeholder="Alamat sesuai KTP atau tempat tinggal saat ini"></textarea>
                    <p v-if="form.errors.address" class="text-xs text-primary-500 font-medium mt-1">{{ form.errors.address[0]
                    }}</p>
                </div>

                <!-- Emergency Contact (Span Full) -->
                <div class="sm:col-span-2 space-y-1">
                    <label for="emergency_contact"
                        class="text-xs font-bold uppercase tracking-wider text-slate-400">Kontak Darurat (Nama &
                        Telp)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input v-model="form.emergency_contact" type="text" id="emergency_contact"
                            class="w-full pl-10 pr-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition"
                            placeholder="Contoh: Ayah - 08123456789" />
                    </div>
                    <p v-if="form.errors.emergency_contact" class="text-xs text-primary-500 font-medium mt-1">{{
                        form.errors.emergency_contact[0] }}</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" :disabled="form.processing"
                    class="inline-flex items-center px-8 py-3 text-sm font-bold text-white transition rounded-lg shadow-md bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50">
                    <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ form.processing ? "Menyimpan..." : "Simpan Biodata" }}
                </button>
            </div>
        </form>
    </div>
</template>
