<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import Swal from "sweetalert2";

const page = usePage();
const user = computed(() => page.props.auth.user);

const form = useForm({
    phone: user.value?.phone || "",
    address: user.value?.address || "",
    id_card_number: user.value?.id_card_number || "",
    birth_date: user.value?.birth_date || "",
    gender: user.value?.gender || "",
    emergency_contact: user.value?.emergency_contact || "",
});

const submit = () => {
    form.put(route("profile.tenant.update"), {
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
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden mt-8">
        <!-- Header Section -->
        <div class="px-8 py-5 flex items-center justify-between border-b border-slate-100 bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Biodata Penyewa</h3>
                <p class="text-sm text-slate-500">Lengkapi informasi identitas dan kontak darurat Anda.</p>
            </div>
            <div class="p-2 bg-primary-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        <form @submit.prevent="submit" class="p-10 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-8">
                <!-- Phone -->
                <div class="space-y-1">
                    <label for="phone" class="text-xs font-bold uppercase tracking-wider text-slate-400">Nomor
                        Telepon</label>
                    <input v-model="form.phone" type="text" id="phone"
                        class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition" />
                    <p v-if="form.errors.phone" class="text-xs text-rose-500 font-medium mt-1">{{ form.errors.phone[0] }}
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
                    <p v-if="form.errors.gender" class="text-xs text-rose-500 font-medium mt-1">{{ form.errors.gender[0] }}
                    </p>
                </div>

                <!-- ID Card Number -->
                <div class="space-y-1">
                    <label for="id_card_number" class="text-xs font-bold uppercase tracking-wider text-slate-400">Nomor
                        KTP</label>
                    <input v-model="form.id_card_number" type="text" id="id_card_number"
                        class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition" />
                    <p v-if="form.errors.id_card_number" class="text-xs text-rose-500 font-medium mt-1">{{
                        form.errors.id_card_number[0] }}</p>
                </div>

                <!-- Birth Date -->
                <div class="space-y-1">
                    <label for="birth_date" class="text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal
                        Lahir</label>
                    <input v-model="form.birth_date" type="date" id="birth_date"
                        class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition" />
                    <p v-if="form.errors.birth_date" class="text-xs text-rose-500 font-medium mt-1">{{
                        form.errors.birth_date[0] }}</p>
                </div>

                <!-- Address (Span Full) -->
                <div class="sm:col-span-2 space-y-1">
                    <label for="address" class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat
                        Lengkap</label>
                    <textarea v-model="form.address" id="address" rows="3"
                        class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition"
                        placeholder="Alamat sesuai KTP atau tempat tinggal saat ini"></textarea>
                    <p v-if="form.errors.address" class="text-xs text-rose-500 font-medium mt-1">{{ form.errors.address[0]
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
                    <p v-if="form.errors.emergency_contact" class="text-xs text-rose-500 font-medium mt-1">{{
                        form.errors.emergency_contact[0] }}</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" :disabled="form.processing"
                    class="inline-flex items-center px-8 py-3 text-sm font-bold text-white transition rounded-lg shadow-md bg-primary hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50">
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
