<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineOptions({
    layout: PublicLayout,
});

const props = defineProps({
    boardingHouse: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const user = computed(() => page.props.auth?.user);
const hasTenant = computed(() => page.props.auth?.has_tenant);

const selectedImage = ref(0);
const isPreviewOpen = ref(false);
const previewImageIndex = ref(0);

// Booking State
const isRequirementModalOpen = ref(false);
const isBookingModalOpen = ref(false);
const selectedRoom = ref(null);
const bookingForm = useForm({
    boarding_house_id: props.boardingHouse.id,
    room_id: null,
    room_price_id: null,
    payment_scheme: 'full',
    total_price: 0,
    planned_checkin_date: new Date().toISOString().split('T')[0],
});

const formatPrice = (price) => {
    if (!price || price === 0) return 'Harga belum tersedia';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);
};

// watch for room_price_id changes to update total_price
watch(() => bookingForm.room_price_id, (newVal) => {
    if (newVal && selectedRoom.value) {
        const selectedPrice = selectedRoom.value.prices.find(p => p.id === newVal);
        if (selectedPrice) {
            bookingForm.total_price = parseInt(selectedPrice.price);
        }
    }
});

function handleBooking(room) {
    if (!user.value || !hasTenant.value) {
        isRequirementModalOpen.value = true;
        document.body.style.overflow = 'hidden';
        return;
    }

    selectedRoom.value = room;
    bookingForm.room_id = room.id;
    // Pre-select first price option
    if (room.prices && room.prices.length > 0) {
        bookingForm.room_price_id = room.prices[0].id;
        bookingForm.total_price = parseInt(room.prices[0].price);
    }
    isBookingModalOpen.value = true;
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() {
    isBookingModalOpen.value = false;
    selectedRoom.value = null;
    bookingForm.reset();
    document.body.style.overflow = '';
}

function closeRequirementModal() {
    isRequirementModalOpen.value = false;
    document.body.style.overflow = '';
}

const submitBooking = () => {
    bookingForm.post(route('bookings.store'), {
        onSuccess: () => {
            const midtrans = usePage().props.midtrans;
            if (midtrans && midtrans.snap_token) {
                window.snap.pay(midtrans.snap_token, {
                    onSuccess: function (result) {
                        window.location.href = midtrans.redirect_url;
                    },
                    onPending: function (result) {
                        window.location.href = midtrans.redirect_url;
                    },
                    onError: function (result) {
                        console.error('Payment Error:', result);
                    },
                    onClose: function () {
                        console.log('Payment popup closed');
                    }
                });
            } else {
                closeBookingModal();
            }
        },
        onError: (errors) => {
            console.error('Booking Error:', errors);
        }
    });
};

// Alternative submitBooking using fetch if Inertia post behavior is tricky with JSON response
const handlePayment = async () => {
    try {
        const response = await axios.post(route('bookings.store'), bookingForm.data());

        if (response.data.success && response.data.snap_token) {
            window.snap.pay(response.data.snap_token, {
                onSuccess: function (result) {
                    window.location.href = response.data.redirect_url;
                },
                onPending: function (result) {
                    window.location.href = response.data.redirect_url;
                },
                onError: function (result) {
                    console.error('Payment Error:', result);
                },
                onClose: function () {
                    console.log('Payment popup closed');
                }
            });
        }
    } catch (error) {
        console.error('Payment Error:', error);
    }
};

const availableRooms = computed(() => {
    return props.boardingHouse.rooms.filter(room => room.status);
});

const images = computed(() => {
    if (props.boardingHouse.images && props.boardingHouse.images.length > 0) {
        return props.boardingHouse.images
            .filter(img => img)
            .map(img => {
                // Handle both object with image property and string
                if (typeof img === 'string') return img;
                return img.image ? `${img.image}` : null;
            })
            .filter(url => url);
    }
    return props.boardingHouse.thumbnail ? [`${props.boardingHouse.thumbnail}`] : [];
});

const getImageUrl = (img) => {
    if (!img) return '/images/placeholder.png';
    if (typeof img === 'string') return img;
    return img.image ? `/storage/${img.image}` : '/images/placeholder.png';
};

const getStatusLabel = (status) => {
    switch (status) {
        case 'available': return 'Tersedia';
        case 'occupied': return 'Terisi';
        case 'maintenance': return 'Maintenance';
        case 'booked': return 'Booked';
        default: return 'Tidak Tersedia';
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 'available': return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800';
        case 'occupied': return 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800';
        case 'maintenance': return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800';
        case 'booked': return 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800';
        default: return 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 border-gray-200 dark:border-gray-800';
    }
};

function openPreview(index) {
    if (images.value.length === 0) return;
    previewImageIndex.value = index;
    isPreviewOpen.value = true;
    document.body.style.overflow = 'hidden';
}

function closePreview() {
    isPreviewOpen.value = false;
    document.body.style.overflow = '';
}

function nextImage() {
    if (previewImageIndex.value < images.value.length - 1) {
        previewImageIndex.value++;
    } else {
        previewImageIndex.value = 0;
    }
}

function prevImage() {
    if (previewImageIndex.value > 0) {
        previewImageIndex.value--;
    } else {
        previewImageIndex.value = images.value.length - 1;
    }
}

function handleKeydown(event) {
    if (!isPreviewOpen.value) return;

    if (event.key === 'Escape') {
        closePreview();
    } else if (event.key === 'ArrowRight') {
        nextImage();
    } else if (event.key === 'ArrowLeft') {
        prevImage();
    }
}

// Add keyboard event listener
onMounted(() => {
    window.addEventListener('keydown', handleKeydown);

    // Load Midtrans Snap Script
    const midtransScriptUrl = page.props.midtrans_is_production
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
    const clientKey = page.props.midtrans_client_key;

    const script = document.createElement('script');
    script.src = midtransScriptUrl;
    script.setAttribute('data-client-key', clientKey);
    script.async = true;
    document.head.appendChild(script);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});
</script>

<template>

    <Head :title="`${boardingHouse.name} - Tharahub`" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Image Gallery -->
        <div class="mb-12">
            <div class="group relative w-full h-[400px] md:h-[600px] rounded-3xl overflow-hidden bg-gray-200 dark:bg-gray-800 mb-6 shadow-theme-lg cursor-pointer"
                @click="openPreview(selectedImage)">
                <img :src="getImageUrl(images[selectedImage]) || '/images/placeholder.png'" :alt="boardingHouse.name"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>

                <!-- Click hint -->
                <div
                    class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div
                        class="bg-black/50 backdrop-blur-sm px-6 py-3 rounded-full text-white font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                        </svg>
                        Klik untuk memperbesar
                    </div>
                </div>

                <div v-if="images.length > 1"
                    class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-2.5 bg-black/30 backdrop-blur-md px-4 py-2 rounded-full z-10"
                    @click.stop>
                    <button v-for="(img, index) in images" :key="index" @click="selectedImage = index" :class="[
                        'h-2.5 rounded-full transition-all duration-300 shadow-sm',
                        selectedImage === index ? 'bg-white w-8' : 'bg-white/50 w-2.5 hover:bg-white/80'
                    ]" />
                </div>
            </div>

            <div v-if="images.length > 1" class="grid grid-cols-4 md:grid-cols-8 gap-4">
                <button v-for="(img, index) in images.slice(0, 8)" :key="index" @click="selectedImage = index"
                    @dblclick="openPreview(index)" :class="[
                        'relative h-20 md:h-24 rounded-2xl overflow-hidden border-2 transition-all duration-300 cursor-pointer',
                        selectedImage === index
                            ? 'border-primary-600 ring-4 ring-primary-600/20 scale-105 z-10 shadow-lg'
                            : 'border-transparent opacity-70 hover:opacity-100 hover:scale-[1.02] grayscale hover:grayscale-0'
                    ]">
                    <img :src="getImageUrl(img)" :alt="`Image ${index + 1}`" class="w-full h-full object-cover" />
                </button>
            </div>
        </div>

        <!-- Image Preview Modal -->
        <Transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="isPreviewOpen"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-sm"
                @click="closePreview">
                <!-- Close Button -->
                <button @click="closePreview"
                    class="absolute top-4 right-4 z-50 p-3 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full text-white transition-all duration-200 hover:scale-110"
                    aria-label="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Navigation Buttons -->
                <button v-if="images.length > 1" @click.stop="prevImage"
                    class="absolute left-4 z-50 p-3 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full text-white transition-all duration-200 hover:scale-110"
                    aria-label="Gambar sebelumnya">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button v-if="images.length > 1" @click.stop="nextImage"
                    class="absolute right-4 z-50 p-3 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full text-white transition-all duration-200 hover:scale-110"
                    aria-label="Gambar berikutnya">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Image Container -->
                <div class="relative max-w-7xl w-full h-full flex items-center justify-center p-4 md:p-8" @click.stop>
                    <img :src="getImageUrl(images[previewImageIndex])"
                        :alt="`${boardingHouse.name} - Image ${previewImageIndex + 1}`"
                        class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" />

                    <!-- Image Counter -->
                    <div v-if="images.length > 1"
                        class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-black/50 backdrop-blur-md px-6 py-2 rounded-full text-white text-sm font-medium">
                        {{ previewImageIndex + 1 }} / {{ images.length }}
                    </div>
                </div>

                <!-- Thumbnail Strip (if more than 1 image) -->
                <div v-if="images.length > 1"
                    class="absolute bottom-0 left-0 right-0 bg-black/50 backdrop-blur-md p-4 overflow-x-auto"
                    @click.stop>
                    <div class="flex gap-3 justify-center max-w-7xl mx-auto">
                        <button v-for="(img, index) in images" :key="index" @click="previewImageIndex = index" :class="[
                            'relative flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition-all duration-200',
                            previewImageIndex === index
                                ? 'border-primary-500 ring-2 ring-primary-500/50 scale-110'
                                : 'border-transparent opacity-60 hover:opacity-100 hover:scale-105'
                        ]">
                            <img :src="getImageUrl(img)" :alt="`Thumbnail ${index + 1}`"
                                class="w-full h-full object-cover" />
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2">
                <!-- Title & Location -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700/50 mb-8">
                    <div class="flex flex-col gap-1 mb-6">
                        <span class="text-primary-600 font-semibold tracking-wide uppercase text-sm"
                            v-if="boardingHouse.cluster">
                            {{ boardingHouse.cluster.name }}
                        </span>
                        <h1
                            class="text-3xl md:text-5xl font-bold text-gray-900 dark:text-white tracking-tight leading-tight">
                            {{ boardingHouse.name }}
                        </h1>
                        <!-- Gender Badge -->
                        <div class="mt-4 flex">
                            <div v-if="boardingHouse.gender === 'L'"
                                class="flex items-center gap-1.5 bg-primary-600/90 backdrop-blur-md text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg border border-white/20">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="4" r="2.5" />
                                    <path d="M17,11c0-1.657-1.343-3-3-3H10c-1.657,0-3,1.343-3,3v5h2v6h2v-4h2v4h2v-6h2V11z" />
                                </svg>
                                <span>KOS PUTRA</span>
                            </div>
                            <div v-else-if="boardingHouse.gender === 'P'"
                                class="flex items-center gap-1.5 bg-primary-600/90 backdrop-blur-md text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg border border-white/20">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="3.5" r="2" />
                                    <path
                                        d="M17.5,13.5L15,8c-0.2-0.5-0.7-1-1.3-1h-3.4C9.7,7,9.2,7.5,9,8l-2.5,5.5C6.3,13.8,6.5,14.3,7,14.3h2V18c0,0.6,0.4,1,1,1h1v3h2v-3h1c0.6,0,1-0.4,1-1v-3.7h2C17.5,14.3,17.7,13.8,17.5,13.5z" />
                                </svg>
                                <span>KOS PUTRI</span>
                            </div>
                            <div v-else-if="boardingHouse.gender === 'C'"
                                class="flex items-center gap-1.5 bg-primary-600/90 backdrop-blur-md text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg border border-white/20">
                                <div class="flex items-center -space-x-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="4" r="2.5" />
                                        <path d="M17,11c0-1.657-1.343-3-3-3H10c-1.657,0-3,1.343-3,3v5h2v6h2v-4h2v4h2v-6h2V11z" />
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="3.5" r="2" />
                                        <path
                                            d="M17.5,13.5L15,8c-0.2-0.5-0.7-1-1.3-1h-3.4C9.7,7,9.2,7.5,9,8l-2.5,5.5C6.3,13.8,6.5,14.3,7,14.3h2V18c0,0.6,0.4,1,1,1h1v3h2v-3h1c0.6,0,1-0.4,1-1v-3.7h2C17.5,14.3,17.7,13.8,17.5,13.5z" />
                                    </svg>
                                </div>
                                <span>KOS CAMPUR</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 mb-6">
                        <div
                            class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 px-4 py-2 rounded-xl text-gray-600 dark:text-gray-300 border border-gray-100 dark:border-gray-600">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-medium">{{ boardingHouse.address }}</span>
                        </div>

                        <div
                            class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 px-4 py-2 rounded-xl text-gray-600 dark:text-gray-300 border border-gray-100 dark:border-gray-600">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="font-medium">{{ boardingHouse.rooms_count }} Kamar</span>
                        </div>

                        <a v-if="boardingHouse.phone" :href="`tel:${boardingHouse.phone}`"
                            class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 px-4 py-2 rounded-xl text-gray-600 dark:text-gray-300 border border-gray-100 dark:border-gray-600 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="font-medium">{{ boardingHouse.phone }}</span>
                        </a>
                    </div>
                </div>

                <!-- Description -->
                <div v-if="boardingHouse.description"
                    class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700/50 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Tentang Kos Ini
                    </h2>
                    <div
                        class="prose prose-lg max-w-none text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-line">
                        {{ boardingHouse.description }}
                    </div>
                </div>

                <!-- Rooms -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700/50">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Kamar Tersedia</h2>
                        <span
                            class="px-3 py-1 text-xs font-semibold bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 rounded-full border border-primary-100 dark:border-primary-800">
                            {{ availableRooms.length }} Kamar
                        </span>
                    </div>

                    <div v-if="availableRooms.length > 0" class="flex flex-col gap-6">
                        <div v-for="room in availableRooms" :key="room.id"
                            class="group relative rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 hover:shadow-theme-xl hover:border-primary-200 dark:hover:border-primary-800 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h3
                                            class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors">
                                            {{ room.name }}
                                        </h3>
                                        <span
                                            :class="['px-2.5 py-0.5 text-xs font-bold rounded-full border', getStatusClass(room.status)]">
                                            {{ getStatusLabel(room.status) }}
                                        </span>
                                    </div>
                                    <p v-if="room.number" class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        Kamar No. {{ room.number }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-primary-600">
                                        {{ formatPrice(room.min_price) }}
                                    </div>
                                    <span class="text-sm text-gray-500">/ bulan</span>
                                </div>
                            </div>

                            <div v-if="room.description"
                                class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                                {{ room.description }}
                            </div>

                            <div class="flex flex-col gap-4">
                                <div
                                    class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="font-semibold">Kapasitas {{ room.capacity }} Orang</span>
                                </div>

                                <div v-if="room.facilities && room.facilities.length > 0">
                                    <span
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Fasilitas</span>
                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="facility in room.facilities" :key="facility"
                                            class="px-3 py-1.5 text-xs font-medium bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 rounded-lg border border-gray-200 dark:border-gray-600">
                                            {{ facility }}
                                        </span>
                                    </div>
                                </div>

                                <div v-if="room.prices && room.prices.length > 0"
                                    class="pt-4 mt-2 border-t border-gray-100 dark:border-gray-700">
                                    <span
                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Opsi
                                        Harga Lain</span>
                                    <div class="grid grid-cols-1 gap-2 mb-6">
                                        <div v-for="price in room.prices" :key="price.duration"
                                            class="text-sm flex flex-col bg-gray-50 dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                            <div class="flex justify-between items-center mb-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-gray-900 dark:text-white">{{ price.name || `${price.duration} Bulan` }}</span>
                                                </div>
                                                <span class="font-bold text-primary-600">{{ formatPrice(price.price) }}</span>
                                            </div>
                                            <div v-if="price.addons && price.addons.length > 0" class="flex flex-wrap gap-x-2 gap-y-1 mt-1">
                                                <div v-for="(addon, idx) in price.addons" :key="idx" class="flex items-center gap-1 text-[10px] text-gray-500">
                                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    {{ addon.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button v-if="room.status === 'available'" @click="handleBooking(room)"
                                        class="w-full py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all duration-300 shadow-lg shadow-primary-600/20 flex items-center justify-center gap-2 group">
                                        <span>Pesan Sekarang</span>
                                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </button>
                                     <button v-else disabled
                                        class="w-full py-3.5 bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold rounded-xl flex items-center justify-center gap-2 cursor-not-allowed">
                                        <span>Tidak Tersedia</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else
                        class="text-center py-16 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                        <div
                            class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum Ada Kamar Tersedia
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400">Silakan cek kembali nanti atau hubungi pemilik
                            kos.</p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-28 space-y-6">
                    <!-- Price Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-theme-xl border border-gray-100 dark:border-gray-700/50 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-primary-50 dark:bg-primary-900/10 rounded-full blur-3xl z-0">
                        </div>

                        <div class="text-center mb-8 relative z-10">
                            <span
                                class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mulai
                                dari</span>
                            <div class="text-4xl md:text-5xl font-bold text-primary-600 mt-2 mb-1 tracking-tight">
                                {{ formatPrice(boardingHouse.min_price) }}
                            </div>
                            <div class="text-sm text-gray-400 dark:text-gray-500">per bulan</div>
                        </div>

                        <div v-if="boardingHouse.owner" class="mb-8 relative z-10">
                            <div
                                class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <div
                                    class="w-12 h-12 bg-primary-100 dark:bg-primary-900/50 rounded-xl flex items-center justify-center text-primary-600 font-bold text-xl">
                                    {{ boardingHouse.owner.name.charAt(0) }}
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Pemilik Kos
                                    </div>
                                    <div class="font-bold text-gray-900 dark:text-white">{{ boardingHouse.owner.name
                                    }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 relative z-10">
                            <a v-if="boardingHouse.phone" :href="`tel:${boardingHouse.phone}`"
                                class="w-full py-4 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg shadow-md shadow-primary-600/20 flex items-center justify-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Hubungi Pemilik
                            </a>

                            <a v-if="boardingHouse.latitude && boardingHouse.longitude"
                                :href="`https://www.google.com/maps?q=${boardingHouse.latitude},${boardingHouse.longitude}`"
                                target="_blank"
                                class="w-full py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-bold rounded-xl border-2 border-gray-100 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300 flex items-center justify-center gap-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lihat Peta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Booking Modal -->
        <Transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="isBookingModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="closeBookingModal"></div>

                <div
                    class="relative bg-white dark:bg-gray-900 w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
                    <!-- Modal Header -->
                    <div
                        class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Pemesanan</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan lengkapi detail penyewaan Anda
                            </p>
                        </div>
                        <button @click="closeBookingModal"
                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto">
                        <!-- Selected Room Info -->
                        <div
                            class="flex items-center gap-4 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-2xl border border-primary-100 dark:border-primary-800/50">
                            <div
                                class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ selectedRoom?.name }}</h4>
                                <p class="text-xs text-primary-700 dark:text-primary-400 font-medium">Kamar No. {{
                                    selectedRoom?.number }} • {{ boardingHouse.name }}</p>
                            </div>
                        </div>

                        <!-- Check-in Date -->
                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Rencana Check-in</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input
                                    type="date"
                                    v-model="bookingForm.planned_checkin_date"
                                    :min="new Date().toISOString().split('T')[0]"
                                    class="block w-full pl-11 pr-4 py-4 rounded-2xl border-2 border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 focus:border-primary-600 focus:ring-4 focus:ring-primary-600/10 transition-all duration-200 text-gray-900 dark:text-white font-medium"
                                />
                                <div v-if="bookingForm.errors.planned_checkin_date" class="mt-2 text-xs font-bold text-red-500">
                                    {{ bookingForm.errors.planned_checkin_date }}
                                </div>
                            </div>
                        </div>

                        <!-- Duration Selection -->
                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Durasi Sewa</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button v-for="price in selectedRoom?.prices" :key="price.id"
                                    @click="bookingForm.room_price_id = price.id" :class="[
                                        'px-4 py-4 rounded-2xl border-2 text-left transition-all duration-200 flex flex-col',
                                        bookingForm.room_price_id === price.id
                                            ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20 ring-4 ring-primary-600/10'
                                            : 'border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 hover:border-gray-200 dark:hover:border-gray-700'
                                    ]">
                                    <div class="flex justify-between items-start mb-1">
                                        <div class="text-xs font-bold uppercase tracking-wider"
                                            :class="bookingForm.room_price_id === price.id ? 'text-primary-600' : 'text-gray-500'">
                                            {{ price.duration }} Bulan</div>

                                    </div>
                                    <div v-if="price.name" class="text-sm font-bold text-gray-900 dark:text-white mb-1">{{ price.name }}</div>
                                    <div class="text-lg font-black text-gray-900 dark:text-white">{{ formatPrice(price.price) }}</div>

                                    <!-- Add-ons list in button -->
                                    <div v-if="price.addons && price.addons.length > 0" class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 space-y-1">
                                        <div v-for="(addon, idx) in price.addons" :key="idx" class="flex items-center gap-1.5 text-[10px] text-gray-600 dark:text-gray-400">
                                            <svg class="w-3 h-3 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ addon.name }}
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Payment Scheme Selection -->
                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Skema
                                Pembayaran</label>
                            <div class="flex flex-col gap-3">
                                <button @click="bookingForm.payment_scheme = 'full'" :class="[
                                    'group flex items-center gap-4 px-5 py-4 rounded-2xl border-2 transition-all duration-200',
                                    bookingForm.payment_scheme === 'full'
                                        ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20 shadow-sm'
                                        : 'border-gray-100 dark:border-gray-800 hover:border-gray-200'
                                ]">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                                        :class="bookingForm.payment_scheme === 'full' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <div class="font-bold text-gray-900 dark:text-white">Bayar Lunas</div>
                                        <div class="text-xs text-gray-500">Bayar penuh di muka</div>
                                    </div>
                                </button>

                                <button @click="bookingForm.payment_scheme = 'installment'" :class="[
                                    'group flex items-center gap-4 px-5 py-4 rounded-2xl border-2 transition-all duration-200',
                                    bookingForm.payment_scheme === 'installment'
                                        ? 'border-primary-600 bg-primary-50 dark:bg-primary-900/20 shadow-sm'
                                        : 'border-gray-100 dark:border-gray-800 hover:border-gray-200'
                                ]">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                                        :class="bookingForm.payment_scheme === 'installment' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <div class="font-bold text-gray-900 dark:text-white">Cicilan</div>
                                        <div class="text-xs text-gray-500">Bayar bertahap per bulan</div>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Total Summary -->
                        <div class="pt-6 border-t border-gray-100 dark:border-gray-800">
                            <div class="flex justify-between items-end">
                                <div>
                                    <div class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Total
                                        Pembayaran</div>
                                    <div class="text-3xl font-black text-primary-600">{{
                                        formatPrice(bookingForm.total_price) }}</div>
                                </div>
                                <div class="text-right text-xs text-gray-400 font-medium">
                                    Termasuk biaya admin & layanan
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-8 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800">
                        <button @click="submitBooking" :disabled="bookingForm.processing"
                            class="w-full py-4 bg-primary-600 hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-2xl transition-all duration-300 shadow-xl shadow-primary-600/30 flex items-center justify-center gap-3">
                            <svg v-if="bookingForm.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>{{ bookingForm.processing ? 'Memproses...' : 'Konfirmasi Pemesanan' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Requirement Modal (Auth/Tenant Check) -->
        <Transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="isRequirementModalOpen" class="fixed inset-0 z-[120] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="closeRequirementModal"></div>

                <div
                    class="relative bg-white dark:bg-gray-900 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
                    <div class="p-8 text-center">
                        <!-- Icon -->
                        <div
                            class="w-20 h-20 bg-primary-50 dark:bg-primary-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Pemesanan Terhenti</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                            Untuk melakukan pemesanan, Anda diwajibkan untuk:
                        </p>

                        <div class="space-y-4 mb-8 text-left">
                            <div
                                class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <div
                                    class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg v-if="user" class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span v-else class="text-xs font-bold text-green-600">1</span>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white">Masuk / Daftar</div>
                                    <div class="text-xs text-gray-500">Akses akun Tharahub Anda</div>
                                </div>
                            </div>

                            <div
                                class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                    :class="hasTenant ? 'bg-green-100 dark:bg-green-900/30' : 'bg-primary-100 dark:bg-primary-900/30'">
                                    <svg v-if="hasTenant" class="w-5 h-5 text-green-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span v-else class="text-xs font-bold text-primary-600">2</span>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white">Lengkapi Biodata</div>
                                    <div class="text-xs text-gray-500">Isi data diri penyewa (tenants)</div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <template v-if="!user">
                                <Link :href="route('login')"
                                    class="block w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-xl shadow-primary-600/30">
                                    Masuk Sekarang
                                </Link>
                                <Link :href="route('register')"
                                    class="block w-full py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold rounded-2xl border-2 border-gray-100 dark:border-gray-700 hover:border-gray-200 transition-all">
                                    Daftar Baru
                                </Link>
                            </template>
                            <template v-else-if="!hasTenant">
                                <Link :href="route('profile.show')"
                                    class="block w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-xl shadow-primary-600/30">
                                    Lengkapi Biodata
                                </Link>
                            </template>

                            <button @click="closeRequirementModal"
                                class="w-full py-3 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                                Nanti Saja
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>
