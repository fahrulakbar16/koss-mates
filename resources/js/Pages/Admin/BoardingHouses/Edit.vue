<template>

    <Head :title="`Edit ${boardingHouse.name}`" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-2">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                        Edit Kos: <span class="text-primary-600 dark:text-primary-400">{{ boardingHouse.name }}</span>
                    </h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Perbarui informasi, kontak, dan lokasi kos ini
                </p>
            </div>
            <!-- <Link :href="route('boarding-houses.index')"
                class="inline-flex gap-2 items-center px-5 py-2.5 text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-300 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="font-medium">Kembali</span>
            </Link> -->
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <div class="p-6 sm:p-8 md:p-10">
                <form @submit.prevent="saveBoardingHouse" class="space-y-8">
                    <!-- Section: Informasi Dasar -->
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-8 last:border-0 last:pb-0">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Nomor Kos <span class="text-red-500">*</span>
                                </label>
                                <input id="name"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                                    type="number" v-model="form.number" required placeholder="Contoh: 01" />
                                <div v-if="form.errors.number" class="text-xs text-red-500 mt-1 font-medium">
                                    {{ form.errors.number }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="persentasi_pemilik" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Persentasi Pemilik (%) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input id="persentasi_pemilik"
                                        class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400"
                                        type="number" v-model="form.persentasi_pemilik" required placeholder="Contoh: 100" min="0" max="100" />
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-500 text-sm font-bold">
                                        %
                                    </div>
                                </div>
                                <div v-if="form.errors.persentasi_pemilik" class="text-xs text-red-500 mt-1 font-medium">
                                    {{ form.errors.persentasi_pemilik }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="owner_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Pemilik <span class="text-red-500">*</span>
                                </label>
                                <select id="owner_id" v-model="form.owner_id"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500">
                                    <option selected hidden value="">Pilih pemilik</option>
                                    <option v-for="owner in props.owners" :key="owner.id" :value="owner.id">
                                        {{ owner.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.owner_id" class="text-xs text-red-500 mt-1 font-medium">
                                    {{ form.errors.owner_id }}
                                </div>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label for="cluster_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Cluster
                                </label>
                                <select id="cluster_id" v-model="form.cluster_id"
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500">
                                    <option value="">Tidak ada cluster</option>
                                    <option v-for="cluster in props.clusters" :key="cluster.id" :value="cluster.id">
                                        {{ cluster.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.cluster_id" class="text-xs text-red-500 mt-1 font-medium">
                                    {{ form.errors.cluster_id }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Media -->
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-8 last:border-0 last:pb-0">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Thumbnail
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambahkan foto thumbnail utama untuk kos ini agar lebih menarik.</p>
                        </div>

                        <div class="space-y-4">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Foto Utama (Thumbnail) <span class="text-red-500">*</span>
                            </label>

                            <div class="flex flex-col sm:flex-row items-stretch gap-6">
                                <!-- Input File (Hidden, placed outside so label works for both) -->
                                <input id="thumbnail" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp"
                                    @change="handleThumbnailChange" />

                                <!-- Preview Area -->
                                <div v-if="currentThumbnail || thumbnailPreview" class="relative group shrink-0 w-full sm:w-auto">
                                    <label for="thumbnail"
                                        class="cursor-pointer mx-auto sm:mx-0 w-48 h-48 sm:w-64 sm:h-64 block rounded-2xl overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg relative bg-gray-100 dark:bg-gray-900">
                                        <img :src="thumbnailPreview || currentThumbnail" alt="Preview" class="w-full h-full object-cover" />
                                        <div
                                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center backdrop-blur-sm gap-3">
                                            <div class="text-white font-medium text-sm flex items-center gap-2 mb-2">
                                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                                <span>Klik untuk ubah foto</span>
                                            </div>
                                            <button type="button" @click.prevent="removeThumbnail"
                                                class="flex items-center gap-2 px-4 py-2 bg-red-500 text-white font-medium text-sm rounded-xl hover:bg-red-600 transition-colors shadow-lg transform hover:scale-105"
                                                title="Hapus gambar">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </label>
                                </div>

                                <!-- Upload Area -->
                                <div v-else class="flex-1 w-full">
                                    <div class="flex items-center justify-center w-full h-full min-h-[12rem] sm:min-h-[16rem]">
                                        <label for="thumbnail"
                                            class="group flex flex-col items-center justify-center w-full h-full border-2 border-dashed border-primary-300 dark:border-primary-700 rounded-2xl cursor-pointer bg-primary-50/50 dark:bg-primary-900/10 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:border-primary-500 transition-all duration-300">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4 text-center">
                                                <div class="p-4 bg-white dark:bg-gray-800 rounded-full shadow-sm mb-4 group-hover:scale-110 group-hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                                                    <svg class="w-8 h-8 text-primary-500" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                </svg>
                                                </div>
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                                                    <span class="font-bold text-primary-600 dark:text-primary-400">Klik untuk upload</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">PNG, JPG, JPEG atau WEBP</p>
                                                <p class="text-xs text-gray-400 mt-2">Maksimal ukuran file: 2MB</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.errors.thumbnail" class="text-sm text-red-500 mt-2 font-medium bg-red-50 dark:bg-red-900/20 px-3 py-2 rounded-lg inline-block">
                                {{ form.errors.thumbnail }}
                            </div>
                        </div>
                    </div>

                    <!-- Section: Kontak & Lokasi -->
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-8 last:border-0 last:pb-0">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">
                            Kontak & Lokasi
                        </h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label for="address" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea id="address"
                                    class="w-full px-4 py-3 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400 resize-none"
                                    v-model="form.address" required rows="2" placeholder="Masukkan alamat lengkap"></textarea>
                                <div v-if="form.errors.address" class="text-xs text-red-500 mt-1 font-medium">
                                    {{ form.errors.address }}
                                </div>
                            </div>

                            <div class="space-y-2 md:w-1/2">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Tipe Kos (Gender) <span class="text-red-500">*</span>
                                </label>
                                <select id="gender" v-model="form.gender" required
                                    class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500">
                                    <option value="C">Campur (Laki-laki & Perempuan)</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <div v-if="form.errors.gender" class="text-xs text-red-500 mt-1 font-medium">
                                    {{ form.errors.gender }}
                                </div>
                            </div>

                            <div class="space-y-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Titik Lokasi
                                </label>
                                <div class="flex flex-col sm:flex-row gap-4 items-start">
                                    <div class="flex-1 w-full grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <input
                                                class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-100 border border-transparent rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all dark:bg-gray-800 dark:text-gray-300 cursor-not-allowed"
                                                type="text" v-model="form.latitude" placeholder="Latitude" readonly />
                                        </div>
                                        <div class="space-y-2">
                                            <input
                                                class="w-full px-4 py-2.5 text-sm font-medium text-gray-900 bg-gray-100 border border-transparent rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all dark:bg-gray-800 dark:text-gray-300 cursor-not-allowed"
                                                type="text" v-model="form.longitude" placeholder="Longitude" readonly />
                                        </div>
                                    </div>
                                    <button type="button" @click="openMapModal"
                                        class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gray-900 rounded-xl hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                        <LocationIcon class="w-4 h-4" />
                                        {{ form.latitude ? 'Ubah Lokasi' : 'Pilih Lokasi' }}
                                    </button>
                                </div>
                                <div v-if="form.errors.latitude || form.errors.longitude"
                                    class="text-xs text-red-500 mt-1 font-medium">
                                    {{ form.errors.latitude }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Deskripsi -->
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-8 last:border-0 last:pb-0">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">
                            Deskripsi
                        </h3>
                        <div class="space-y-2">
                            <label for="description" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Deskripsi Kos
                            </label>
                            <textarea id="description"
                                class="w-full px-4 py-3 text-sm font-medium text-gray-900 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:border-primary-500 placeholder:font-normal placeholder:text-gray-400 resize-none"
                                v-model="form.description" rows="6"
                                placeholder="Jelaskan fasilitas, lingkungan, dan keunggulan kos ini..."></textarea>
                            <div v-if="form.errors.description" class="text-xs text-red-500 mt-1 font-medium">
                                {{ form.errors.description }}
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-gray-100 dark:border-gray-700">
                        <Link :href="route('boarding-houses.show', boardingHouse.id)"
                            class="px-6 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-750 transition-all">
                            Batal
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="px-8 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <span v-if="form.processing" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Menyimpan...
                            </span>
                            <span v-else>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Map Modal -->
            <Modal :show="isMapModalOpen" title="Pilih Lokasi di Peta" maxWidth="4xl" @close="closeMapModal"
                @confirm="confirmLocation" confirmText="Gunakan Lokasi Ini">
                <div class="space-y-6">
                    <div
                        class="h-[500px] w-full rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-lg relative bg-gray-100 dark:bg-gray-800">
                        <div class="absolute inset-0 flex items-center justify-center text-gray-400" v-if="!mapCenter">
                            Loading Map...
                        </div>
                        <l-map v-if="isMapModalOpen" ref="map" :zoom="zoom" :center="mapCenter" @click="onMapClick"
                            style="height: 100%; width: 100%; z-index: 10;">
                            <l-tile-layer :url="tileLayerUrl" :attribution="attribution"></l-tile-layer>
                            <l-marker v-if="markerPosition" :lat-lng="markerPosition" :draggable="true"
                                @update:lat-lng="updateMarkerPosition"></l-marker>
                        </l-map>
                    </div>

                    <div v-if="selectedLocation"
                        class="flex flex-col sm:flex-row items-center justify-between p-4 bg-primary-50 dark:bg-primary-900/10 border border-primary-100 dark:border-primary-800 rounded-xl gap-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg text-primary-600 dark:text-primary-400">
                                <LocationIcon class="w-5 h-5" />
                            </div>
                            <div>
                                <p
                                    class="text-xs font-semibold uppercase tracking-wider text-primary-600 dark:text-primary-400 mb-0.5">
                                    Lokasi Terpilih
                                </p>
                                <div class="flex gap-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <span>Lat: {{ selectedLocation.lat.toFixed(6) }}</span>
                                    <span>Lng: {{ selectedLocation.lng.toFixed(6) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else
                        class="text-center p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-gray-500 text-sm">
                        Klik pada peta untuk memilih lokasi
                    </div>
                </div>
            </Modal>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import BackIcon from "@/Components/icons/BackIcon.vue";
import LocationIcon from "@/Components/icons/LocationIcon.vue";
import Modal from "@/Components/common/Modal.vue";
import { LMap, LTileLayer, LMarker } from "@vue-leaflet/vue-leaflet";
import "leaflet/dist/leaflet.css";
import { useAuth } from "@/Composables/useAuth";
import { ref, onMounted, nextTick, computed } from "vue";
import { useForm, Head, Link } from "@inertiajs/vue3";
import L from "leaflet";

// Fix for default marker icon in Leaflet
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png",
    iconUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png",
    shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png",
});

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    boardingHouse: Object,
    clusters: Array,
    owners: Array,
});

const { can } = useAuth();

const breadcrumbs = [
    { label: "Properti" },
    { label: "Kos", href: route("boarding-houses.index") },
    { label: props.boardingHouse.name, href: route("boarding-houses.show", props.boardingHouse.id) },
    { label: "Edit" },
];

const form = useForm({
    owner_id: props.boardingHouse.owner_id || "",
    cluster_id: props.boardingHouse.cluster_id || "",
    thumbnail: null,
    remove_thumbnail: false,
    number: props.boardingHouse.number || "",
    persentasi_pemilik: props.boardingHouse.persentasi_pemilik || 100,
    description: props.boardingHouse.description || "",
    address: props.boardingHouse.address || "",
    gender: props.boardingHouse.gender || "C",
    latitude: props.boardingHouse.latitude || "",
    longitude: props.boardingHouse.longitude || "",
});

// Thumbnail preview
const thumbnailPreview = ref(null);
const currentThumbnail = computed(() => {
    if (form.remove_thumbnail) {
        return null;
    }
    if (thumbnailPreview.value) {
        return thumbnailPreview.value;
    }
    if (props.boardingHouse.thumbnail) {
        return `/storage/${props.boardingHouse.thumbnail}`;
    }
    return null;
});

// Map related
const isMapModalOpen = ref(false);
const map = ref(null);
const zoom = ref(13);
const mapCenter = ref([-6.2088, 106.8456]); // Default to Jakarta
const markerPosition = ref(null);
const selectedLocation = ref(null);
const tileLayerUrl = "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
const attribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';

onMounted(() => {
    // Set map center to existing location if available
    if (form.latitude && form.longitude) {
        const lat = parseFloat(form.latitude);
        const lng = parseFloat(form.longitude);
        mapCenter.value = [lat, lng];
    } else if (navigator.geolocation) {
        // Try to get user's location
        navigator.geolocation.getCurrentPosition(
            (position) => {
                mapCenter.value = [position.coords.latitude, position.coords.longitude];
            },
            () => {
                // Use default location if geolocation fails
            }
        );
    }
});

function handleThumbnailChange(event) {
    const file = event.target.files[0];
    if (file) {
        form.thumbnail = file;
        form.remove_thumbnail = false;
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            thumbnailPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function removeThumbnail() {
    form.thumbnail = null;
    form.remove_thumbnail = true;
    thumbnailPreview.value = null;
    const input = document.getElementById("thumbnail");
    if (input) {
        input.value = "";
    }
}

function openMapModal() {
    isMapModalOpen.value = true;
    // If there's existing lat/long, set marker
    if (form.latitude && form.longitude) {
        nextTick(() => {
            const lat = parseFloat(form.latitude);
            const lng = parseFloat(form.longitude);
            markerPosition.value = [lat, lng];
            mapCenter.value = [lat, lng];
            selectedLocation.value = { lat, lng };
        });
    } else {
        // Reset marker when opening modal
        markerPosition.value = null;
        selectedLocation.value = null;
    }
}

function closeMapModal() {
    isMapModalOpen.value = false;
}

function onMapClick(event) {
    const { lat, lng } = event.latlng;
    markerPosition.value = [lat, lng];
    selectedLocation.value = { lat, lng };
}

function updateMarkerPosition(event) {
    const { lat, lng } = event.target.getLatLng();
    markerPosition.value = [lat, lng];
    selectedLocation.value = { lat, lng };
}

function confirmLocation() {
    if (selectedLocation.value) {
        form.latitude = selectedLocation.value.lat.toString();
        form.longitude = selectedLocation.value.lng.toString();
        closeMapModal();
    }
}

function saveBoardingHouse() {
    if (!can("boarding_houses.edit")) {
        return;
    }

    form.transform((data) => ({ ...data, _method: "PUT" }));

    form.post(route("boarding-houses.update", props.boardingHouse.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Success handling is automatic - Inertia will follow redirect from server
        },
        onError: () => {
            // Error handling is automatic - validation errors are set in form.errors
        },
    });
}
</script>
