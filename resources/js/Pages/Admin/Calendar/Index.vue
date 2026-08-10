<template>
    <Head title="Kalender Keluar-Masuk" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Kalender Keluar-Masuk</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Lihat jadwal check-in, check-out, dan rencana booking penyewa per cluster.
                </p>
            </div>
        </div>

        <!-- Cluster Selector -->
        <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 p-5">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                Pilih Cluster
            </label>
            <select v-model="selectedClusterId" @change="onClusterChange"
                class="w-full sm:w-80 px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500">
                <option :value="null" disabled>-- Pilih Cluster --</option>
                <option v-for="cluster in clusters" :key="cluster.id" :value="cluster.id">
                    {{ cluster.name }}
                </option>
            </select>
        </div>

        <!-- Empty State -->
        <div v-if="!selectedClusterId"
            class="flex-1 flex flex-col items-center justify-center rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 p-12 text-center">
            <CalendarIcon class="w-14 h-14 text-gray-300 dark:text-gray-600 mb-4" />
            <p class="text-gray-600 dark:text-gray-400 text-base font-medium">
                Pilih cluster terlebih dahulu untuk melihat kalender keluar-masuk penyewa.
            </p>
        </div>

        <!-- Calendar -->
        <div v-else class="rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 p-5">
            <!-- Legend -->
            <div class="flex flex-wrap items-center gap-5 mb-5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#22c55e]"></span>
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Check-in</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></span>
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Check-out</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#f59e0b]"></span>
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Rencana Booking</span>
                </div>
            </div>

            <!-- Desktop: month grid -->
            <div v-if="isDesktop" class="kos-calendar">
                <FullCalendar :key="selectedClusterId" :options="calendarOptions" />
            </div>

            <!-- Mobile: agenda table (grid is unreadable on narrow screens) -->
            <div v-else>
                <div class="flex items-center justify-between mb-4">
                    <button @click="prevListMonth" type="button"
                        class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                        ‹
                    </button>
                    <div class="text-center">
                        <div class="font-bold text-gray-900 dark:text-white">{{ listMonthLabel }}</div>
                        <button @click="goToListToday" type="button"
                            class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                            Hari ini
                        </button>
                    </div>
                    <button @click="nextListMonth" type="button"
                        class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                        ›
                    </button>
                </div>

                <div v-if="listLoading" class="text-center py-10 text-sm text-gray-400 dark:text-gray-500">
                    Memuat jadwal...
                </div>
                <div v-else-if="groupedListEvents.length === 0"
                    class="text-center py-10 text-sm text-gray-400 dark:text-gray-500">
                    Tidak ada jadwal keluar-masuk bulan ini.
                </div>
                <table v-else class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="text-left border-b border-gray-100 dark:border-gray-700">
                            <th class="py-2 pr-3 w-24 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="py-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kejadian
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="group in groupedListEvents" :key="group.date"
                            class="border-b border-gray-50 dark:border-gray-800 align-top">
                            <td class="py-3 pr-3 font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                {{ group.label }}
                            </td>
                            <td class="py-3">
                                <div v-for="(item, i) in group.items" :key="i"
                                    class="flex items-start gap-2 mb-1.5 last:mb-0 cursor-pointer"
                                    @click="showEventDetail(item.raw)">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0 mt-1.5"
                                        :style="{ background: item.color }"></span>
                                    <span class="text-gray-700 dark:text-gray-300">{{ item.text }}</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import axios from 'axios';
import Swal from 'sweetalert2';
import dayjs from 'dayjs';
import 'dayjs/locale/id';
import AppLayout from '@/Layouts/AppLayout.vue';
import CalendarIcon from '@/Components/icons/CalendarIcon.vue';

dayjs.locale('id');

defineOptions({
    layout: AppLayout,
});

const page = usePage();
const clusters = computed(() => page.props.clusters || []);

function initialClusterId() {
    const urlParams = new URLSearchParams(window.location.search);
    const fromUrl = urlParams.get('cluster_id');
    if (fromUrl && clusters.value.find((c) => c.id == fromUrl)) {
        return parseInt(fromUrl);
    }

    const stored = localStorage.getItem('selectedClusterId');
    if (stored && stored !== 'all' && clusters.value.find((c) => c.id == stored)) {
        return parseInt(stored);
    }

    return null;
}

const selectedClusterId = ref(initialClusterId());

function onClusterChange() {
    localStorage.setItem('selectedClusterId', String(selectedClusterId.value));

    const url = new URL(window.location.href);
    url.searchParams.set('cluster_id', selectedClusterId.value);
    window.history.replaceState({}, '', url);
}

function eventTypeLabel(type) {
    if (type === 'checkin') return 'Check-in';
    if (type === 'checkout') return 'Check-out';
    return 'Rencana Check-in';
}

function showEventDetail(props) {
    Swal.fire({
        title: eventTypeLabel(props.type),
        html: `
            <div class="text-left text-sm">
                <p><strong>Penyewa:</strong> ${props.tenant}</p>
                <p><strong>Kamar:</strong> ${props.room}</p>
                <p><strong>Boarding House:</strong> ${props.boarding_house}</p>
                <p><strong>Status:</strong> ${props.status}</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Tutup',
    });
}

// Switch between the desktop month grid and the mobile agenda table
const isDesktop = ref(true);
let desktopMql = null;

function handleDesktopChange(e) {
    isDesktop.value = e.matches;
}

onMounted(() => {
    desktopMql = window.matchMedia('(min-width: 640px)');
    isDesktop.value = desktopMql.matches;
    desktopMql.addEventListener('change', handleDesktopChange);
});

onBeforeUnmount(() => {
    desktopMql?.removeEventListener('change', handleDesktopChange);
});

// Mobile agenda list state
const listMonth = ref(dayjs());
const listEvents = ref([]);
const listLoading = ref(false);
const listMonthLabel = computed(() => listMonth.value.format('MMMM YYYY'));

function prevListMonth() {
    listMonth.value = listMonth.value.subtract(1, 'month');
}

function nextListMonth() {
    listMonth.value = listMonth.value.add(1, 'month');
}

function goToListToday() {
    listMonth.value = dayjs();
}

async function fetchListEvents() {
    if (!selectedClusterId.value) {
        listEvents.value = [];
        return;
    }

    listLoading.value = true;
    try {
        const res = await axios.get(route('admin.calendar.events'), {
            params: {
                cluster_id: selectedClusterId.value,
                start: listMonth.value.startOf('month').format('YYYY-MM-DD'),
                end: listMonth.value.endOf('month').add(1, 'day').format('YYYY-MM-DD'),
            },
        });
        listEvents.value = res.data;
    } finally {
        listLoading.value = false;
    }
}

function listItemText(props) {
    if (props.type === 'checkin') return `${props.tenant} masuk (${props.room})`;
    if (props.type === 'checkout') return `${props.tenant} keluar (${props.room})`;
    return `${props.tenant} rencana masuk (${props.room})`;
}

const groupedListEvents = computed(() => {
    const groups = {};
    for (const ev of listEvents.value) {
        if (!groups[ev.start]) groups[ev.start] = [];
        groups[ev.start].push(ev);
    }

    return Object.keys(groups)
        .sort()
        .map((date) => ({
            date,
            label: dayjs(date).format('DD MMM YYYY'),
            items: groups[date].map((ev) => ({
                color: ev.color,
                text: listItemText(ev.extendedProps),
                raw: ev.extendedProps,
            })),
        }));
});

watch([selectedClusterId, listMonth, isDesktop], () => {
    if (!isDesktop.value) {
        fetchListEvents();
    }
}, { immediate: true });

const calendarOptions = computed(() => ({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    height: 'auto',
    dayMaxEvents: 2,
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: '',
    },
    events: (fetchInfo, successCallback, failureCallback) => {
        if (!selectedClusterId.value) {
            successCallback([]);
            return;
        }

        axios
            .get(route('admin.calendar.events'), {
                params: {
                    cluster_id: selectedClusterId.value,
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr,
                },
            })
            .then((res) => successCallback(res.data))
            .catch((err) => failureCallback(err));
    },
    eventClick: (info) => showEventDetail(info.event.extendedProps),
}));
</script>

<style scoped>
/* Match FullCalendar chrome to the app's rounded / primary-blue button language */
.kos-calendar :deep(.fc) {
    --fc-border-color: theme('colors.gray.100');
    --fc-page-bg-color: transparent;
    --fc-neutral-bg-color: theme('colors.gray.50');
    --fc-today-bg-color: theme('colors.primary.50');
    font-family: inherit;
}

.kos-calendar :deep(.fc .fc-toolbar-title) {
    font-size: 1.125rem;
    font-weight: 700;
    color: theme('colors.gray.900');
}

.kos-calendar :deep(.fc .fc-button) {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.5rem 1rem;
    font-size: 0.813rem;
    font-weight: 600;
    text-transform: none;
    border: 1px solid theme('colors.gray.200');
    border-radius: 0.75rem;
    background: white;
    color: theme('colors.gray.700');
    box-shadow: none;
    transition: all 0.2s ease;
}

.kos-calendar :deep(.fc .fc-button:hover) {
    background: theme('colors.gray.50');
    border-color: theme('colors.primary.200');
    color: theme('colors.primary.600');
}

.kos-calendar :deep(.fc .fc-button:focus),
.kos-calendar :deep(.fc .fc-button:focus-visible) {
    box-shadow: 0 0 0 4px theme('colors.primary.500 / 10%');
    outline: none;
}

.kos-calendar :deep(.fc .fc-button-primary:not(:disabled).fc-button-active),
.kos-calendar :deep(.fc .fc-button-primary:not(:disabled):active) {
    background: theme('colors.primary.600');
    border-color: theme('colors.primary.600');
    color: white;
    box-shadow: 0 4px 12px -2px theme('colors.primary.600 / 30%');
}

.kos-calendar :deep(.fc .fc-today-button) {
    background: theme('colors.primary.600');
    border-color: theme('colors.primary.600');
    color: white;
    box-shadow: 0 4px 12px -2px theme('colors.primary.600 / 30%');
}

.kos-calendar :deep(.fc .fc-today-button:hover:not(:disabled)) {
    background: theme('colors.primary.700');
    border-color: theme('colors.primary.700');
    color: white;
}

.kos-calendar :deep(.fc .fc-button:disabled) {
    opacity: 0.5;
}

.kos-calendar :deep(.fc .fc-prev-button),
.kos-calendar :deep(.fc .fc-next-button) {
    padding: 0.5rem 0.65rem;
    border-radius: 0.75rem;
}

.kos-calendar :deep(.fc .fc-col-header-cell) {
    background: theme('colors.gray.50');
    padding: 0.75rem 0;
}

.kos-calendar :deep(.fc .fc-col-header-cell-cushion) {
    font-size: 0.688rem;
    font-weight: 700;
    color: theme('colors.gray.500');
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-decoration: none;
}

.kos-calendar :deep(.fc .fc-daygrid-day-number) {
    font-size: 0.813rem;
    font-weight: 500;
    color: theme('colors.gray.700');
    padding: 0.5rem;
    text-decoration: none;
}

.kos-calendar :deep(.fc .fc-day-today) {
    background: theme('colors.primary.50 / 60%');
}

.kos-calendar :deep(.fc .fc-daygrid-day-frame) {
    border-radius: 0.5rem;
}

.kos-calendar :deep(.fc .fc-event) {
    border: none;
    border-radius: 0.375rem;
    padding: 1px 6px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
}

.kos-calendar :deep(.fc .fc-event-title),
.kos-calendar :deep(.fc .fc-event-main) {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}

.kos-calendar :deep(.fc .fc-scrollgrid) {
    border-radius: 0.75rem;
    overflow: hidden;
}

.kos-calendar :deep(.fc .fc-more-link) {
    font-size: 0.688rem;
    font-weight: 700;
    color: theme('colors.primary.600');
}

.kos-calendar :deep(.fc .fc-popover) {
    border-radius: 0.75rem;
    border-color: theme('colors.gray.200');
    box-shadow: 0 10px 30px -5px rgb(0 0 0 / 15%);
    overflow: hidden;
}

.kos-calendar :deep(.fc .fc-popover-header) {
    background: theme('colors.gray.50');
    padding: 0.5rem 0.75rem;
    font-weight: 700;
}

/* Dark mode */
:global(.dark) .kos-calendar :deep(.fc) {
    --fc-border-color: theme('colors.gray.700');
    --fc-neutral-bg-color: theme('colors.gray.800');
    --fc-today-bg-color: theme('colors.primary.900 / 20%');
}

:global(.dark) .kos-calendar :deep(.fc .fc-toolbar-title) {
    color: theme('colors.white');
}

:global(.dark) .kos-calendar :deep(.fc .fc-button) {
    background: theme('colors.gray.800');
    border-color: theme('colors.gray.600');
    color: theme('colors.gray.300');
}

:global(.dark) .kos-calendar :deep(.fc .fc-button:hover) {
    background: theme('colors.gray.700');
    color: theme('colors.primary.400');
}

:global(.dark) .kos-calendar :deep(.fc .fc-col-header-cell) {
    background: theme('colors.gray.800 / 80%');
}

:global(.dark) .kos-calendar :deep(.fc .fc-col-header-cell-cushion) {
    color: theme('colors.gray.400');
}

:global(.dark) .kos-calendar :deep(.fc .fc-daygrid-day-number) {
    color: theme('colors.gray.300');
}
</style>
