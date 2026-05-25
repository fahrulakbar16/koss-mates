<template>
    <Head :title="`Atur Harga - ${room.name}`" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-2">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Atur Harga Kamar
                    </h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Tentukan harga sewa berdasarkan durasi untuk kamar {{ room.name }}
                </p>
            </div>

            <Link :href="route('boarding-houses.show', boardingHouse.id)"
                class="inline-flex gap-2 items-center px-5 py-2.5 text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-300 transform hover:-translate-y-0.5">
                <BackIcon class="w-5 h-5" />
                <span class="font-medium">Kembali</span>
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Room Info Summary -->
            <div class="p-6 bg-primary-50/50 dark:bg-primary-900/10 border-b border-gray-100 dark:border-gray-700 flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-500/30">
                    <BedIcon class="w-6 h-6" />
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 dark:text-white">{{ room.name }} {{ room.number ? `- No. ${room.number}` : '' }}</h2>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">{{ boardingHouse.name }}</p>
                </div>
            </div>

            <div class="p-8 space-y-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800/50">
                    <p class="text-sm text-blue-700 dark:text-blue-300 flex gap-2">
                        <span class="font-bold">Info:</span>
                        Atur harga kamar berdasarkan durasi sewa (dalam bulan). Contoh: 1 bulan = Rp 1.000.000.
                    </p>
                </div>

                <div class="space-y-4">
                    <TransitionGroup enter-active-class="ease-out duration-300" enter-from-class="opacity-0 translate-y-4" enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="ease-in duration-200" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-4">
                        <div v-for="(priceItem, index) in priceForm.prices" :key="index"
                            class="group relative flex flex-col gap-5 p-6 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 transition-all shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="px-3 py-1 bg-primary-600 text-white text-xs font-bold rounded-lg shadow-sm">
                                        Paket {{ index + 1 }}
                                    </div>
                                    <input type="text" v-model="priceItem.name"
                                        class="bg-transparent border-none focus:ring-0 text-gray-900 dark:text-white font-bold p-0 placeholder:text-gray-400 placeholder:font-normal"
                                        placeholder="Tambahkan Nama Paket (Opsional)..." />
                                </div>
                                <button type="button" @click="removePriceItem(index)"
                                    class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-xl transition-all"
                                    title="Hapus Harga">
                                    <TrashIcon class="w-5 h-5" />
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Durasi (Bulan) <span class="text-primary-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" min="1" step="1" v-model.number="priceItem.duration"
                                            class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                            placeholder="Durasi" required />
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">Bulan</span>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Harga Jual (Rp) <span class="text-primary-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">Rp</span>
                                        <input type="text" :value="formatInputCurrency(priceItem.price)" @input="handlePriceInput(priceItem, 'price', $event)"
                                            class="w-full pl-10 pr-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                            placeholder="Masukkan harga" required />
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Target Modal/Pengeluaran (Rp)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">Rp</span>
                                        <input type="text" :value="formatInputCurrency(priceItem.monthly_expense)" @input="handlePriceInput(priceItem, 'monthly_expense', $event)"
                                            class="w-full pl-10 pr-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                            placeholder="Modal per bulan" />
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Add-ons Paket (Opsional)
                                    </label>
                                    <button type="button" @click="addNewAddon(priceItem)"
                                        class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1 transition-colors">
                                        <PlusSquareIcon class="w-4 h-4" />
                                        Tambah Add-on
                                    </button>
                                </div>

                                <div v-if="priceItem.addons && priceItem.addons.length > 0" class="space-y-3">
                                    <div v-for="(addon, aIndex) in priceItem.addons" :key="aIndex"
                                        class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm transition-all hover:border-gray-300 dark:hover:border-gray-600">
                                        <div class="flex-1 grid grid-cols-2 gap-3">
                                            <input type="text" v-model="addon.name"
                                                class="w-full px-3 py-1.5 text-xs text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500/10 focus:border-primary-500 transition-all"
                                                placeholder="Nama Add-on (misal: Laundry)" />
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400">Rp</span>
                                                <input type="text" :value="formatInputCurrency(addon.price)" @input="handlePriceInput(addon, 'price', $event)"
                                                    class="w-full pl-8 pr-3 py-1.5 text-xs text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                                    placeholder="Biaya" />
                                            </div>
                                        </div>
                                        <button type="button" @click="removeAddon(priceItem, aIndex)"
                                            class="p-1.5 text-gray-400 hover:text-primary-500 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all">
                                            <TrashIcon class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>

                                <div v-else class="text-center py-6 border border-dashed border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50/50 dark:bg-gray-900/10">
                                    <p class="text-xs text-gray-400">Belum ada add-on untuk paket ini</p>
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>
                </div>

                <button type="button" @click="addPriceItem"
                    class="w-full flex items-center justify-center gap-2 px-4 py-4 text-sm font-bold text-primary-600 bg-primary-50 dark:bg-primary-900/20 border border-dashed border-primary-200 dark:border-primary-800 rounded-2xl hover:bg-primary-100 dark:hover:bg-primary-900/40 hover:border-primary-300 transition-all group">
                    <PlusSquareIcon class="w-5 h-5 transition-transform group-hover:scale-110" />
                    Tambah Durasi Sewa
                </button>

                <div v-if="priceForm.errors.prices"
                    class="flex items-center gap-2 px-4 py-3 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-xl text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ priceForm.errors.prices }}
                </div>
            </div>

            <div class="p-8 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                <button @click="savePrices" :disabled="priceForm.processing"
                    class="w-full py-4 bg-primary-600 hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-2xl transition-all duration-300 shadow-xl shadow-primary-600/30 flex items-center justify-center gap-3">
                    <svg v-if="priceForm.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ priceForm.processing ? 'Menyimpan...' : 'Simpan Perubahan Harga' }}</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PlusSquareIcon from "@/Components/icons/PlusSquareIcon.vue";
import TrashIcon from "@/Components/icons/TrashIcon.vue";
import BedIcon from "@/Components/icons/BedIcon.vue";
import BackIcon from "@/Components/icons/BackIcon.vue";
import { useForm, Head, Link } from "@inertiajs/vue3";
import { onMounted } from "vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    boardingHouse: Object,
    room: Object,
});

const priceForm = useForm({
    prices: [],
});

const defaultDurations = [1, 3, 6, 12];

onMounted(() => {
    // Load existing prices or initialize with default durations
    if (props.room.prices && props.room.prices.length > 0) {
        priceForm.prices = props.room.prices.map(price => ({
            id: price.id,
            duration: price.duration,
            price: parseFloat(price.price) || 0,
            name: price.name || "",
            monthly_expense: parseFloat(price.monthly_expense) || 0,
            addons: price.addons || [],
        }));
    } else {
        // Initialize with default durations
        priceForm.prices = defaultDurations.map(duration => ({
            id: null,
            duration: duration,
            price: 0,
            name: "",
            monthly_expense: 0,
            addons: [],
        }));
    }

    // Sort by duration
    priceForm.prices.sort((a, b) => a.duration - b.duration);
});

function formatInputCurrency(value) {
    if (!value && value !== 0) return '';
    let numberString = value.toString().replace(/[^0-9]/g, '');
    if (!numberString) return '';
    return new Intl.NumberFormat('id-ID').format(parseInt(numberString, 10));
}

function handlePriceInput(item, field, event) {
    let rawValue = event.target.value.replace(/[^0-9]/g, '');
    let numValue = rawValue ? parseInt(rawValue, 10) : 0;
    item[field] = numValue;
    event.target.value = formatInputCurrency(rawValue);
}

function addNewAddon(priceItem) {
    if (!priceItem.addons) priceItem.addons = [];
    priceItem.addons.push({
        name: "",
        price: 0
    });
}

function removeAddon(priceItem, index) {
    priceItem.addons.splice(index, 1);
}

function addPriceItem() {
    const existingDurations = priceForm.prices.map(p => p.duration);
    let newDuration = 1;
    while (existingDurations.includes(newDuration)) {
        newDuration++;
    }

    priceForm.prices.push({
        id: null,
        duration: newDuration,
        price: 0,
        name: "",
        monthly_expense: 0,
        addons: [],
    });

    priceForm.prices.sort((a, b) => a.duration - b.duration);
}

function removePriceItem(index) {
    if (priceForm.prices.length <= 1) {
        alert('Minimal harus ada satu durasi harga.');
        return;
    }
    priceForm.prices.splice(index, 1);
}

function savePrices() {
    // Validate that all prices have duration and price > 0
    const validPrices = priceForm.prices.filter(p => p.duration > 0 && p.price > 0);

    if (validPrices.length === 0) {
        alert('Silakan isi setidaknya satu durasi dan harga yang valid.');
        return;
    }

    // Check for duplicate durations
    const durations = validPrices.map(p => p.duration);
    const uniqueDurations = [...new Set(durations)];
    if (durations.length !== uniqueDurations.length) {
        alert('Durasi tidak boleh duplikat.');
        return;
    }

    priceForm.transform((data) => ({
        prices: validPrices.map(p => ({
            id: p.id,
            duration: p.duration,
            price: p.price,
            name: p.name,
            monthly_expense: p.monthly_expense,
            addons: p.addons.filter(a => a.name && a.price > 0),
        })),
    })).post(route("boarding-houses.rooms.prices.store", [props.boardingHouse.id, props.room.id]), {
        onSuccess: () => {
            // Success notification is handled by the flash message
        },
    });
}
</script>
