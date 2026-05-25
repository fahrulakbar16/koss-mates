<template>
    <Head title="Ajukan Pindah Kamar" />

    <div class="min-h-screen bg-[#F8FAFC] dark:bg-[#0F172A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
            <!-- Header -->
            <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Formulir Pindah Kamar</h1>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-base ml-5">
                        Silakan lengkapi data untuk mengajukan perpindahan kamar
                    </p>
                </div>
            </div>

            <!-- Form Content -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-8 sm:p-10 relative z-10">
                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Current Room -->
                        <div class="space-y-2 group">
                            <InputLabel value="Kamar Saat Ini"
                                class="text-base font-bold text-slate-700 dark:text-slate-200 ml-1" />
                            <div class="relative transition-all duration-300">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors z-10">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <SelectInput v-model="form.user_room_id" :items="userRoomOptions"
                                    label="Pilih Kamar Anda saat ini" class="w-full"
                                    inputClass="pl-11 !rounded-2xl !bg-slate-50 dark:!bg-slate-900/50 !border-slate-200 dark:!border-slate-700 focus:!border-primary-500 focus:!ring-primary-500/20"
                                    :disabled="userRooms.length === 1" />
                            </div>
                            <InputError :message="form.errors.user_room_id" class="ml-1" />
                        </div>

                        <!-- Target Boarding House Selection -->
                        <div class="space-y-2 group">
                            <InputLabel value="Pilih Kos Tujuan"
                                class="text-base font-bold text-slate-700 dark:text-slate-200 ml-1" />
                            <div class="relative transition-all duration-300">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors z-10">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <SelectInput v-model="form.boarding_house_id" :items="boardingHouseOptions"
                                    label="Pilih Kos Tujuan" class="w-full"
                                    inputClass="pl-11 !rounded-2xl !bg-slate-50 dark:!bg-slate-900/50 !border-slate-200 dark:!border-slate-700 focus:!border-primary-500 focus:!ring-primary-500/20" />
                            </div>
                            <InputError :message="form.errors.boarding_house_id" class="ml-1" />
                        </div>

                        <!-- Target Room Section -->
                        <transition enter-active-class="transition ease-out duration-300"
                            enter-from-class="transform opacity-0 translate-y-4"
                            enter-to-class="transform opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-200"
                            leave-from-class="transform opacity-100 translate-y-0"
                            leave-to-class="transform opacity-0 translate-y-4">
                            <div v-if="form.boarding_house_id"
                                class="space-y-6 p-6 bg-slate-50 dark:bg-slate-800/50 rounded-[1.5rem] border border-slate-100 dark:border-slate-700/50">
                                <div class="space-y-2 group">
                                    <InputLabel value="Kamar Tujuan"
                                        class="text-base font-bold text-slate-700 dark:text-slate-200 ml-1" />

                                    <div
                                        class="relative transition-all duration-300">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors z-10">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <SelectInput v-model="form.room_id" :items="availableRoomsDropdown"
                                            label="Pilih kamar tujuan"
                                            class="w-full"
                                            :disabled="availableRoomsDropdown.length === 0"
                                            inputClass="pl-11 !rounded-2xl !bg-white dark:!bg-slate-900 !border-slate-200 dark:!border-slate-700 focus:!border-primary-500 focus:!ring-primary-500/20 shadow-sm" />
                                    </div>

                                    <div v-if="availableRoomsDropdown.length === 0 && form.boarding_house_id"
                                        class="mt-3 flex items-center gap-3 text-sm text-amber-700 bg-amber-50 dark:bg-amber-900/20 dark:text-amber-400 p-4 rounded-2xl border border-amber-100 dark:border-amber-900/30">
                                        <svg class="w-5 h-5 flex-shrink-0 text-amber-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="font-medium">Tidak ada kamar lain yang tersedia di area kos ini saat ini.</span>
                                    </div>
                                    <InputError :message="form.errors.room_id" class="ml-1" />
                                </div>

                                <!-- Selected Room Preview Card -->
                                <transition enter-active-class="transition ease-out duration-300 delay-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-200"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95">
                                    <div v-if="selectedRoomDetails"
                                        class="bg-white dark:bg-slate-800 rounded-2xl border border-primary-200 dark:border-primary-700/50 shadow-sm p-5 relative overflow-hidden group">
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 relative z-10 w-full justify-between">
                                            <div class="flex items-center gap-4">
                                                <div class="w-14 h-14 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 flex-shrink-0">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ selectedRoomDetails.name }}</h4>
                                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                                            {{ selectedRoomDetails.type || 'Standard' }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-baseline gap-1">
                                                        <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">Mulai:</span>
                                                        <span class="text-base font-bold text-primary-600 dark:text-primary-400">Rp {{ Number(selectedRoomDetails.min_price).toLocaleString('id-ID') }}</span>
                                                        <span class="text-xs text-slate-400">/ bln</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </transition>

                                <!-- Package Selection -->
                                <div v-if="selectedRoomDetails && selectedRoomDetails.prices" class="space-y-2 group">
                                    <InputLabel value="Pilih Paket Harga"
                                        class="text-base font-bold text-slate-700 dark:text-slate-200 ml-1" />
                                    <div
                                        class="relative transition-all duration-300">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors z-10">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <SelectInput v-model="form.room_price_id" :items="roomPackageOptions"
                                            label="Pilih Paket Harga" class="w-full"
                                            inputClass="pl-11 !rounded-2xl !bg-white dark:!bg-slate-900 !border-slate-200 dark:!border-slate-700 focus:!border-primary-500 focus:!ring-primary-500/20 shadow-sm" />
                                    </div>
                                    <InputError :message="form.errors.room_price_id" class="ml-1" />
                                </div>


                            </div>
                        </transition>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Plan Date -->
                            <div class="space-y-2 group">
                                <InputLabel value="Rencana Tanggal Pindah"
                                    class="text-base font-bold text-slate-700 dark:text-slate-200 ml-1" />
                                <div class="relative transition-all duration-300">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="date" v-model="form.plan_date"
                                        class="w-full pl-11 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-700 dark:text-white focus:border-primary-500 focus:ring-primary-500/20 py-3 transition-all duration-200 shadow-sm outline-none" />
                                </div>
                                <InputError :message="form.errors.plan_date" class="ml-1" />
                            </div>

                            <!-- Reason -->
                            <div class="space-y-2 group md:col-span-2">
                                <InputLabel value="Alasan Pindah"
                                    class="text-base font-bold text-slate-700 dark:text-slate-200 ml-1" />
                                <div class="relative transition-all duration-300">
                                    <div
                                        class="absolute top-3 left-0 flex items-start pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                    </div>
                                    <textarea v-model="form.reason"
                                        class="w-full pl-11 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-700 dark:text-white focus:border-primary-500 focus:ring-primary-500/20 shadow-sm transition-all duration-200 outline-none resize-none"
                                        rows="4"
                                        placeholder="Jelaskan detail alasan mengapa Anda ingin pindah kamar..."></textarea>
                                </div>
                                <InputError :message="form.errors.reason" class="ml-1" />
                            </div>
                        </div>

                        <!-- Cost Calculation -->
                        <transition enter-active-class="transition ease-out duration-300"
                            enter-from-class="transform opacity-0 -translate-y-2"
                            enter-to-class="transform opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-200"
                            leave-from-class="transform opacity-100 translate-y-0"
                            leave-to-class="transform opacity-0 -translate-y-2">
                            <div v-if="form.room_price_id"
                                class="p-5 bg-gradient-to-br from-primary-50 to-white dark:from-primary-900/20 dark:to-slate-800 rounded-[1.5rem] border border-primary-200 dark:border-primary-800/50 shadow-sm space-y-3">
                                <h4 class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Rincian Pembayaran
                                </h4>
                                <div class="flex justify-between text-sm py-1">
                                    <span class="text-slate-600 dark:text-slate-400 font-medium">Harga Paket Baru</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">Rp {{ Number(selectedPackagePrice).toLocaleString('id-ID') }}</span>
                                </div>
                                <div class="flex justify-between text-sm py-1">
                                    <span class="text-slate-600 dark:text-slate-400 font-medium">Sisa Pembayaran (Kamar Lama)</span>
                                    <span class="font-bold text-green-600 dark:text-green-400">- Rp {{ Number(form.sisa_pembayaran).toLocaleString('id-ID') }}</span>
                                </div>
                                <div v-if="form.kekurangan_pembayaran > 0"
                                    class="pt-4 mt-2 border-t border-primary-200/60 dark:border-primary-800/60 flex justify-between items-center">
                                    <span class="font-black text-slate-800 dark:text-slate-200">Kekurangan Pembayaran</span>
                                    <span class="text-xl font-black text-primary-600 dark:text-primary-400">Rp {{ Number(form.kekurangan_pembayaran).toLocaleString('id-ID') }}</span>
                                </div>
                                <div v-if="form.pengembalian_dana > 0"
                                    class="pt-4 mt-2 border-t border-primary-200/60 dark:border-primary-800/60 flex justify-between items-center">
                                    <span class="font-black text-slate-800 dark:text-slate-200">Pengembalian Dana</span>
                                    <span class="text-xl font-black text-green-600 dark:text-green-400">Rp {{ Number(form.pengembalian_dana).toLocaleString('id-ID') }}</span>
                                </div>
                            </div>
                        </transition>

                        <div
                            class="pt-6 flex items-center justify-end gap-4 border-t border-slate-100 dark:border-slate-800/50 mt-10">
                            <Link :href="route('penyewa.transfers.index')"
                                class="px-6 py-3 rounded-xl font-bold text-sm text-slate-500 hover:text-slate-700 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800 transition-all duration-200">
                                Batal
                            </Link>

                            <PrimaryButton
                                class="px-8 py-3 !rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 transform hover:-translate-y-0.5 transition-all duration-200"
                                :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                                :disabled="form.processing">
                                <div class="flex items-center">
                                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    {{ form.processing ? 'Sedang Mengirim...' : 'Kirim Pengajuan' }}
                                </div>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head, useForm } from "@inertiajs/vue3";
import { ref, watch, computed, onMounted } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectInput from "@/Components/form/Select.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import axios from "axios";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    userRooms: {
        type: Array,
        default: () => [],
    },
    boardingHouses: {
        type: Array,
        default: () => [],
    }
});

const rawAvailableRooms = ref({});
const availableRoomsDropdown = ref([]);

const form = useForm({
    user_room_id: "",
    boarding_house_id: "", // New field for target boarding house
    room_id: "",
    room_price_id: "",
    sisa_pembayaran: 0,
    kekurangan_pembayaran: 0,
    pengembalian_dana: 0,
    reason: "",
    plan_date: "",
});

const userRoomOptions = props.userRooms.map((ur) => ({
    id: ur.id,
    name: `${ur.room?.name} - ${ur.boarding_house?.name}`,
}));

const boardingHouseOptions = props.boardingHouses.map((bh) => ({
    id: bh.id,
    name: bh.name,
}));

const selectedRoomDetails = computed(() => {
    return rawAvailableRooms.value[form.room_id];
});

// Load rooms from selected Boarding House prop
const updateAvailableRooms = (boardingHouseId) => {
    if (!boardingHouseId) {
        availableRoomsDropdown.value = [];
        rawAvailableRooms.value = {};
        return;
    }

    const selectedBoardingHouse = props.boardingHouses.find(bh => bh.id === boardingHouseId);

    if (selectedBoardingHouse && selectedBoardingHouse.rooms) {
        // Filter available rooms (assuming 'available' status is 'available')
        const rooms = selectedBoardingHouse.rooms.filter(room => room.status === 'available');

        // Map for detailed lookup
        const roomMap = {};
        rooms.forEach(r => {
            roomMap[r.id] = r;
        });
        rawAvailableRooms.value = roomMap;

        // Map for dropdown options
        availableRoomsDropdown.value = rooms.map(room => ({
            id: room.id,
            name: `${room.name} (${room.type || 'Standard'})`
        }));
    } else {
        availableRoomsDropdown.value = [];
        rawAvailableRooms.value = {};
    }
};

const roomPackageOptions = computed(() => {
    if (selectedRoomDetails.value && selectedRoomDetails.value.prices) {
        return selectedRoomDetails.value.prices.map(p => ({
            id: p.id,
            name: `${p.duration} Bulan - Rp ${Number(p.price).toLocaleString('id-ID')}`
        }));
    }
    return [];
});

const selectedPackagePrice = computed(() => {
    if (form.room_price_id && selectedRoomDetails.value && selectedRoomDetails.value.prices) {
        const price = selectedRoomDetails.value.prices.find(p => p.id === form.room_price_id);
        return price ? Number(price.price) : 0;
    }
    return 0;
});

// Calculate cost via API
const calculateCost = async () => {
    if (form.user_room_id && form.room_price_id && form.plan_date) {
        try {
            const response = await axios.post(route('penyewa.transfers.calculate-cost'), {
                user_room_id: form.user_room_id,
                room_price_id: form.room_price_id,
                plan_date: form.plan_date
            });
            form.sisa_pembayaran = response.data.sisa_pembayaran;
            form.kekurangan_pembayaran = response.data.kekurangan_pembayaran;
            form.pengembalian_dana = response.data.pengembalian_dana;
        } catch (error) {
            console.error(error);
        }
    } else {
        // Reset if selection incomplete
        form.kekurangan_pembayaran = 0;
        form.pengembalian_dana = 0;
        // Don't reset sisa_pembayaran to zero as it might confuse users, but strictly speaking without plan date we don't know the exact amount.
        // However, let's reset it to 0 or initial state because it depends on date now.
        form.sisa_pembayaran = 0;
    }
};

watch([() => form.user_room_id, () => form.room_price_id, () => form.plan_date], () => {
    calculateCost();
});

// Watch for changes in Boarding House selection
watch(() => form.boarding_house_id, (newVal) => {
    if (newVal) {
        updateAvailableRooms(newVal);
        form.room_id = ""; // Reset selected room
        form.room_price_id = "";
    } else {
        availableRoomsDropdown.value = [];
        rawAvailableRooms.value = {};
        form.room_id = "";
        form.room_price_id = "";
    }
});

watch(() => form.room_id, () => {
    form.room_price_id = "";
});

// Auto-select current room if only one exists
onMounted(() => {
    if (props.userRooms.length === 1) {
        form.user_room_id = props.userRooms[0].id;
        // Trigger manual update of sisa_pembayaran since watcher might not fire if initial value is set directly?
        // actually useForm uses reactive object, so setting it should trigger watcher if we were using ref,
        // but for useForm it's reactive. However onMounted runs after initial render.
        // Let's manually set it to be safe.
        const selectedUserRoom = props.userRooms[0];
        form.sisa_pembayaran = selectedUserRoom.sisa_pembayaran || 0;
    }
});

const submit = () => {
    form.post(route('penyewa.transfers.store'), {
        preserveScroll: true,
    });
};
</script>
