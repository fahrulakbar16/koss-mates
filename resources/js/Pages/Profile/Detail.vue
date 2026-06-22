<script setup>
import { ref, onBeforeUnmount, computed } from "vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

// Terima user via props (opsional) agar kompatibel dengan pemanggilan dari Show.vue
const props = defineProps({
    user: { type: Object, default: null },
});

const page = usePage();
const user = computed(() => props.user ?? page.props.auth?.user ?? {});

const isEditing = ref(false);

// ------- Profile form (name, email, photo) -------
const photoPreview = ref(user.value?.profile_photo_url || null);
let objectUrl = null;

const profileForm = useForm({
    name: user.value?.name || "",
    email: user.value?.email || "",
    photo: null, // File
});

function onPhotoChange(e) {
    const file = e.target.files?.[0];
    profileForm.photo = file || null;

    if (objectUrl) URL.revokeObjectURL(objectUrl);
    if (file) {
        objectUrl = URL.createObjectURL(file);
        photoPreview.value = objectUrl;
    } else {
        photoPreview.value = user.value?.profile_photo_url || null;
    }
}

onBeforeUnmount(() => {
    if (objectUrl) URL.revokeObjectURL(objectUrl);
});

// ------- Password form (old, new, confirm) -------
const passwordForm = useForm({
    password: "",
    password_confirmation: "",
});

const submitting = ref(false);

function startEdit() {
    profileForm.name = user.value?.name || "";
    profileForm.email = user.value?.email || "";
    profileForm.photo = null;
    photoPreview.value = user.value?.profile_photo_url || null;

    passwordForm.password = "";
    passwordForm.password_confirmation = "";

    isEditing.value = true;
}

function cancelEdit() {
    isEditing.value = false;
    profileForm.reset("photo");
    passwordForm.reset();
    if (objectUrl) URL.revokeObjectURL(objectUrl);
    objectUrl = null;
    photoPreview.value = user.value?.profile_photo_url || null;
}

async function submitAll() {
    submitting.value = true;
    let hasError = false;

    try {
        await profileForm
            .transform((data) => ({ ...data, _method: "PUT" }))
            .post(route("user-profile-information.update"), {
                preserveScroll: true,
                forceFormData: true,
                onError: () => {
                    hasError = true;
                },
            });

        if (
            passwordForm.password ||
            passwordForm.password_confirmation
        ) {
            await passwordForm.put(route("user-password.update"), {
                preserveScroll: true,
                onError: () => {
                    hasError = true;
                },
            });
        }

        if (profileForm.hasErrors || passwordForm.hasErrors) {
            hasError = true;
        }

        if (!hasError) {
            profileForm.reset("photo");
            passwordForm.reset();
            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }

            await router.reload({ only: ["auth"] });
            photoPreview.value = user.value?.profile_photo_url || null;
            isEditing.value = false;
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Profil berhasil diperbarui.",
                timer: 1500,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "Profil tidak dapat diperbarui. Periksa kembali data yang Anda masukkan.",
            });
        }
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "Kesalahan",
            text: "Terjadi kesalahan pada server. Silakan coba lagi nanti.",
        });
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <!-- Header Section -->
        <div class="px-4 sm:px-8 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-900">
                    {{ isEditing ? "Ubah Data Profile" : "Informasi Profil" }}
                </h3>
                <p class="text-sm text-slate-500">Kelola informasi dasar akun dan kata sandi Anda.</p>
            </div>
            <button v-if="!isEditing" @click="startEdit"
                class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white transition rounded-lg shadow-sm bg-primary hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Ubah Profil
            </button>
        </div>

        <!-- ================== MODE VIEW ================== -->
        <div v-if="!isEditing" class="grid grid-cols-1 lg:grid-cols-[280px,1fr]">
            <!-- Avatar Section -->
            <div
                class="p-8 flex flex-col items-center justify-center bg-slate-50/30 border-b lg:border-b-0 lg:border-r border-slate-100">
                <div class="relative group">
                    <div
                        class="w-48 h-48 rounded-full ring-4 ring-white shadow-lg overflow-hidden bg-primary-50 flex items-center justify-center">
                        <img v-if="user?.profile_photo_url" :src="user.profile_photo_url"
                            class="w-full h-full object-cover transition duration-500 group-hover:scale-110" />
                        <span v-else class="text-6xl font-black text-primary select-none">
                            {{ (user?.name || "?").charAt(0).toUpperCase() }}
                        </span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-success-100 text-success-700 uppercase tracking-wider">
                        {{ user?.status || 'Active' }}
                    </span>
                </div>
            </div>

            <!-- Details Section -->
            <div class="px-6 py-10 sm:p-10">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-8">
                    <div class="space-y-1">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</p>
                        <p class="text-base font-semibold text-slate-800">{{ user?.name || "-" }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat Email</p>
                        <p class="text-base font-semibold text-slate-800">{{ user?.email || "-" }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Username</p>
                        <p class="text-base font-semibold text-slate-800">@{{ user?.username || "-" }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Hak Akses</p>
                        <p class="text-base font-semibold text-slate-800 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-primary mr-2"></span>
                            {{ user?.role || user?.roles?.[0]?.name || "-" }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================== MODE EDIT ================== -->
        <form v-else @submit.prevent="submitAll" class="grid grid-cols-1 lg:grid-cols-[280px,1fr]">
            <!-- Avatar Upload Area -->
            <div
                class="p-8 flex flex-col items-center justify-center bg-slate-50/30 border-b lg:border-b-0 lg:border-r border-slate-100 text-center">
                <div class="relative group mb-6">
                    <div
                        class="w-48 h-48 rounded-full ring-4 ring-white shadow-lg overflow-hidden bg-primary-50 flex items-center justify-center">
                        <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover" />
                        <span v-else class="text-6xl font-black text-primary select-none">
                            {{ (user?.name || "?").charAt(0).toUpperCase() }}
                        </span>
                    </div>
                    <label
                        class="absolute bottom-1 right-1 bg-white p-2 rounded-full shadow-md border border-slate-200 cursor-pointer hover:bg-slate-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                                clip-rule="evenodd" />
                        </svg>
                        <input type="file" class="hidden" @change="onPhotoChange" accept="image/*" />
                    </label>
                </div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Ganti Foto Profil</p>
                <p class="text-[10px] text-slate-500 px-4">Disarankan file JPG, PNG dengan max ukuran 2MB.</p>
            </div>

            <!-- Form Fields Area -->
            <div class="px-6 py-10 sm:p-10 space-y-8">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</label>
                        <input v-model="profileForm.name" type="text"
                            class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition" />
                        <p v-if="profileForm.errors.name" class="text-xs text-primary-500 font-medium mt-1">{{
                            profileForm.errors.name }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat Email</label>
                        <input v-model="profileForm.email" type="email"
                            class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition" />
                        <p v-if="profileForm.errors.email" class="text-xs text-primary-500 font-medium mt-1">{{
                            profileForm.errors.email }}</p>
                    </div>
                </div>

                <!-- Password Info -->
                <div class="pt-8 border-t border-slate-100">
                    <h4
                        class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-widest text-center lg:text-left">
                        Ubah Kata Sandi <span
                            class="block lg:inline text-[10px] font-normal text-slate-400 normal-case tracking-normal">(Kosongkan jika
                            tidak ingin diubah)</span></h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Sandi Baru</label>
                            <input v-model="passwordForm.password" type="password"
                                class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition text-sm" />
                            <p v-if="passwordForm.errors.password" class="text-xs text-primary-500 font-medium mt-1">{{
                                passwordForm.errors.password }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Ulangi
                                Sandi</label>
                            <input v-model="passwordForm.password_confirmation" type="password"
                                class="w-full px-4 py-2 border-slate-200 rounded-lg text-slate-800 font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition text-sm" />
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-6 flex flex-col sm:flex-row items-center gap-4 border-t border-slate-100">
                    <button type="submit" :disabled="submitting"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition rounded-lg shadow-md bg-primary hover:bg-primary-600 disabled:opacity-50">
                        {{ submitting ? "Menyimpan..." : "Simpan Perubahan" }}
                    </button>
                    <button type="button" @click="cancelEdit"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-slate-600 transition bg-slate-100 rounded-lg hover:bg-slate-200">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
