<template>
    <aside :class="[
        'fixed flex flex-col top-0 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out border-r border-gray-100 dark:border-gray-800',
        {
            // Mobile: full width when open, hidden when closed, with padding top for header
            'w-[250px] translate-x-0 pt-[73px] z-[60]': isMobileOpen,
            '-translate-x-full z-50': !isMobileOpen,
            // Desktop: width based on expanded/hovered state, no padding top
            'lg:w-[260px] lg:pt-0': (isExpanded || isHovered) && !isMobileOpen,
            'lg:w-[80px] lg:pt-0': !isExpanded && !isHovered && !isMobileOpen,
            'lg:translate-x-0': true,
        },
    ]" @mouseenter="!isExpanded && !isMobileOpen && (isHovered = true)" @mouseleave="isHovered = false">

        <!-- Sidebar Header / Logo Area (Desktop only, since Navbar handles mobile) -->
        <div class="hidden lg:flex items-center justify-center h-[73px] border-b border-gray-100 dark:border-gray-800">
            <Link href="/" class="flex items-center gap-3">
                <!-- Icon Logo when collapsed -->
                <div v-if="!isExpanded && !isHovered"
                    class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white flex items-center justify-center rounded-lg font-bold text-lg">
                    K
                </div>
                <!-- Full Logo when expanded -->
                <div v-else class="flex items-center gap-2">
                    <img src="/KosMates/Asset 2.png" alt="KosMates Logo" class="h-8 object-contain" />
                </div>
            </Link>
        </div>

        <!-- User Profile Section -->
        <div
            class="relative flex flex-col items-center px-4 py-6 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-br from-primary-50/30 to-transparent dark:from-primary-900/10">
            <!-- Decorative background -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-primary-50/50 via-transparent to-transparent dark:from-primary-900/5 pointer-events-none">
            </div>

            <div class="relative z-10">
                <div
                    class="flex items-center justify-center w-14 h-14 overflow-hidden rounded-full ring-4 ring-primary-100/50 dark:ring-primary-900/20 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 shadow-lg">
                    <img v-if="user?.profile_photo_url || user?.profile_photo_path"
                        :src="user.profile_photo_url || user.profile_photo_path" alt="Profile"
                        class="object-cover w-full h-full" />
                    <span v-else
                        class="flex items-center justify-center w-full h-full text-lg font-semibold text-gray-500 dark:text-gray-400">
                        {{ (user?.name || user?.username || "U").charAt(0).toUpperCase() }}
                    </span>
                </div>
                <div
                    class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full dark:border-gray-900 shadow-sm animate-pulse">
                </div>
            </div>

            <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 translate-y-1"
                enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                <div class="relative text-center mt-3 z-10" v-if="isExpanded || isHovered || isMobileOpen">
                    <div class="font-bold text-sm text-gray-900 dark:text-gray-100 truncate max-w-[200px]">
                        {{ user?.name || user?.username || "User" }}
                    </div>
                    <div class="inline-flex items-center mt-1.5 px-2.5 py-1 bg-primary-50 dark:bg-primary-900/20 rounded-full">
                        <span class="text-[10px] font-semibold text-primary-600 dark:text-primary-400 uppercase tracking-wider">
                            {{ roles.length > 0 ? roles[0] : 'User' }}
                        </span>
                    </div>
                </div>
            </transition>
        </div>

        <!-- Cluster Selection Dropdown (for admin users only) -->
        <div v-if="showClusterDropdown"
            class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/30">
            <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="isExpanded || isHovered || isMobileOpen">
                    <div class="flex items-center gap-2 mb-2.5">
                        <OfficeIcon class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" />
                        <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Cluster saat ini
                        </span>
                    </div>
                    <div class="relative">
                        <button @click="isClusterDropdownOpen = !isClusterDropdownOpen" :class="[
                            'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-all duration-200 group shadow-sm border',
                            isClusterDropdownOpen
                                ? 'bg-primary-50 text-primary-600 border-primary-200 dark:bg-primary-900/20 dark:text-primary-400 dark:border-primary-900/40 shadow-primary-100 dark:shadow-none'
                                : 'bg-white text-gray-700 border-gray-200 hover:border-primary-200 hover:bg-primary-50/50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700/50 dark:hover:border-gray-600',
                        ]">
                            <OfficeIcon class="w-4 h-4 flex-shrink-0" />
                            <span class="text-sm font-medium truncate flex-1 text-left">
                                {{ selectedCluster?.name || 'Pilih Cluster' }}
                            </span>
                            <ChevronDownIcon :class="[
                                'w-4 h-4 transition-transform duration-200',
                                isClusterDropdownOpen ? 'transform rotate-180' : ''
                            ]" />
                        </button>

                        <!-- Dropdown Menu -->
                        <transition enter-active-class="transition duration-100 ease-out"
                            enter-from-class="transform scale-95 opacity-0"
                            enter-to-class="transform scale-100 opacity-100"
                            leave-active-class="transition duration-75 ease-in"
                            leave-from-class="transform scale-100 opacity-100"
                            leave-to-class="transform scale-95 opacity-0">
                            <div v-show="isClusterDropdownOpen" @click.stop
                                class="absolute z-50 mt-2 w-full bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 max-h-60 overflow-y-auto">
                                <div class="py-2">
                                    <!-- Semua Cluster Option -->
                                    <div class="flex items-center group relative px-2 py-0.5">
                                        <button
                                            @click="selectCluster('all')" :class="[
                                                'w-full text-left px-3 py-2 text-sm transition-all duration-150 flex items-center gap-2 rounded-lg',
                                                selectedClusterId === 'all'
                                                    ? 'bg-primary-50 text-primary-600 font-semibold dark:bg-primary-900/20 dark:text-primary-400'
                                                    : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700'
                                            ]">
                                            <div :class="[
                                                'w-1.5 h-1.5 rounded-full transition-all duration-150',
                                                selectedClusterId === 'all' ? 'bg-primary-500' : 'bg-gray-300 dark:bg-gray-600'
                                            ]"></div>
                                            <span class="truncate pr-16">Semua Cluster</span>
                                        </button>
                                    </div>

                                    <div v-for="cluster in clusters" :key="cluster.id" class="flex items-center group relative px-2 py-0.5">
                                        <button
                                            @click="selectCluster(cluster)" :class="[
                                                'w-full text-left px-3 py-2 text-sm transition-all duration-150 flex items-center gap-2 rounded-lg',
                                                selectedClusterId === cluster.id
                                                    ? 'bg-primary-50 text-primary-600 font-semibold dark:bg-primary-900/20 dark:text-primary-400'
                                                    : 'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700'
                                            ]">
                                            <div :class="[
                                                'w-1.5 h-1.5 rounded-full transition-all duration-150',
                                                selectedClusterId === cluster.id ? 'bg-primary-500' : 'bg-gray-300 dark:bg-gray-600'
                                            ]"></div>
                                            <span class="truncate pr-16">{{ cluster.name }}</span>
                                        </button>

                                        <div class="absolute right-3 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity" v-if="is('Superadmin') || is('Pengelola')">
                                            <Link :href="route('clusters.edit', cluster.id)" class="p-1.5 text-gray-500 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-500 rounded-md hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm" @click.stop title="Edit Cluster">
                                                <EditIcon class="w-4 h-4" />
                                            </Link>
                                            <button @click.stop="deleteCluster(cluster.id)" class="p-1.5 text-primary-500 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 rounded-md hover:bg-primary-50 dark:hover:bg-primary-900/40 transition-colors bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm shadow-sm" title="Hapus Cluster">
                                                <TrashIcon class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Tambah Cluster Button -->
                                    <div class="px-2 pt-2 border-t border-gray-100 dark:border-gray-700 mt-1" v-if="is('Superadmin') || is('Pengelola')">
                                        <Link :href="route('clusters.create')" @click="isClusterDropdownOpen = false" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors w-full text-left">
                                            <PlusSquareIcon class="w-4 h-4 flex-shrink-0" />
                                            Tambah Cluster
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>
            </transition>
        </div>

        <!-- Scrollable Navigation -->
        <div ref="sidebarScroll" data-simplebar
            class="flex-1 flex flex-col overflow-y-auto px-3 py-4 space-y-2 no-scrollbar hover:overflow-y-auto">
            <nav>
                <div v-for="(menuGroup, groupIndex) in menuGroups" :key="groupIndex" class="select-none mb-6">
                    <!-- Group Title with Divider -->
                    <div v-if="menuGroup.title" :class="!isExpanded && !isHovered && !isMobileOpen ? 'lg:hidden' : ''">
                        <div class="flex items-center gap-2 px-3 mb-3 mt-4">
                            <div
                                class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 to-transparent dark:via-gray-700">
                            </div>
                        </div>
                        <h3
                            class="px-3 mb-3 text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ menuGroup.title }}
                        </h3>
                    </div>

                    <!-- Menu Items -->
                    <ul class="flex flex-col gap-1">
                        <li v-for="(item, index) in menuGroup.items" :key="item.name">

                            <!-- Submenu Toggle Button -->
                            <button v-if="item.subItems && (!item.permission || can(item.permission))"
                                @click="toggleSubmenu(groupIndex, index)" :class="[
                                    'flex items-center w-full p-2.5 rounded-lg transition-all duration-200 group',
                                    isSubmenuOpen(groupIndex, index) || hasActiveSubmenuRoute(groupIndex, index)
                                        ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200',
                                    !isExpanded && !isHovered && !isMobileOpen ? 'lg:justify-center px-2' : '',
                                ]">

                                <component :is="item.icon" :class="[
                                    'flex-shrink-0 w-5 h-5 transition-colors duration-200',
                                    isSubmenuOpen(groupIndex, index) || hasActiveSubmenuRoute(groupIndex, index)
                                        ? 'text-primary-600 dark:text-primary-400'
                                        : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-400',
                                ]" />

                                <div v-if="isExpanded || isHovered || isMobileOpen"
                                    class="flex flex-1 items-center justify-between ml-3 overflow-hidden">
                                    <span class="text-sm font-medium truncate">{{ item.name }}</span>
                                    <span v-if="item.name === 'Daftar Pengajuan' && pendingCount > 0"
                                        class="px-1.5 py-0.5 text-[10px] font-bold text-white bg-primary-500 rounded-full min-w-[18px] text-center">
                                        {{ pendingCount }}
                                    </span>
                                    <ChevronDownIcon :class="[
                                        'w-4 h-4 transition-transform duration-200',
                                        isSubmenuOpen(groupIndex, index) ? 'transform rotate-180' : ''
                                    ]" />
                                </div>
                            </button>

                            <!-- Single Link Item -->
                            <Link v-else-if="(item.path || item.pathName) && (!item.permission || can(item.permission))"
                                :href="item.pathName ? route(item.pathName) : getMenuPath(item.path)"
                                @click="openSubmenu = null" :class="[
                                    'flex items-center w-full px-3 py-2.5 rounded-xl transition-all duration-200 group relative',
                                    isActive(item.pathName ? route(item.pathName) : item.path)
                                        ? 'bg-gradient-to-r from-primary-50 to-primary-50/50 text-primary-600 shadow-sm border border-primary-100 dark:from-primary-900/20 dark:to-primary-900/10 dark:text-primary-400 dark:border-primary-900/30'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200',
                                    !isExpanded && !isHovered && !isMobileOpen ? 'lg:justify-center px-2' : '',
                                ]">
                                <!-- Active indicator -->
                                <div v-if="isActive(item.pathName ? route(item.pathName) : item.path)"
                                    class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-primary-600 dark:bg-primary-400 rounded-r-full">
                                </div>

                                <component :is="item.icon" :class="[
                                    'flex-shrink-0 w-5 h-5 transition-colors duration-200',
                                    isActive(item.pathName ? route(item.pathName) : item.path)
                                        ? 'text-primary-600 dark:text-primary-400'
                                        : 'text-gray-400 group-hover:text-primary-500 dark:text-gray-500 dark:group-hover:text-primary-400',
                                ]" />

                                <span v-if="isExpanded || isHovered || isMobileOpen"
                                    class="ml-3 text-sm font-medium truncate overflow-hidden">
                                    {{ item.name }}
                                </span>

                                <!-- Pending Count Badge -->
                                <span v-if="getBadgeCount(item.name) > 0"
                                    class="ml-auto px-2 py-0.5 text-[10px] font-bold text-white bg-primary-500 rounded-full min-w-[20px] text-center shadow-sm">
                                    {{ getBadgeCount(item.name) }}
                                </span>
                            </Link>

                            <!-- Submenu Items -->
                            <transition @enter="startTransition" @after-enter="endTransition"
                                @before-leave="startTransition" @after-leave="endTransition">
                                <div v-show="isSubmenuOpen(groupIndex, index) && (isExpanded || isHovered || isMobileOpen)"
                                    class="overflow-hidden">
                                    <ul :class="[
                                        'mt-1 space-y-1',
                                        isExpanded || isHovered || isMobileOpen ? 'ml-9 border-l border-gray-100 dark:border-gray-700 pl-3' : ''
                                    ]">
                                        <template v-for="subItem in item.subItems || []" :key="subItem.name">
                                            <li v-if="!subItem?.permission || can(subItem.permission)">
                                                <Link
                                                    :href="subItem.pathName ? route(subItem.pathName) : getMenuPath(subItem.path)"
                                                    :class="[
                                                        'flex items-center justify-between w-full py-2 px-3 rounded-md text-sm transition-colors duration-200',
                                                        isActive(subItem.pathName ? route(subItem.pathName) : subItem.path)
                                                            ? 'text-primary-600 font-medium bg-primary-50/50 dark:text-primary-400 dark:bg-primary-900/10'
                                                            : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800'
                                                    ]">
                                                    <span>{{ subItem?.name }}</span>

                                                    <!-- Badges for Subitems -->
                                                    <span class="flex items-center">
                                                        <span
                                                            v-if="subItem.pathName === 'material-requests.index' && sidebarCounts.on_progress > 0"
                                                            class="ml-2 px-1.5 py-0.5 text-[10px] font-bold text-white bg-primary-500 rounded-full">
                                                            {{ sidebarCounts.on_progress }}
                                                        </span>
                                                    </span>
                                                </Link>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </transition>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Logout Button at Bottom -->
        <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/30">
            <Link href="#" @click.prevent="signOut"
                class="flex items-center gap-3 px-3.5 py-3 text-sm font-semibold text-gray-600 rounded-xl transition-all duration-200 hover:bg-gradient-to-r hover:from-primary-50 hover:to-primary-50/50 hover:text-primary-600 hover:shadow-sm hover:border hover:border-primary-100 dark:text-gray-400 dark:hover:from-primary-900/20 dark:hover:to-primary-900/10 dark:hover:text-primary-400 dark:hover:border-primary-900/30 group">
                <LogoutIcon
                    class="w-5 h-5 text-gray-400 transition-all duration-200 group-hover:translate-x-0.5 group-hover:text-primary-500 dark:group-hover:text-primary-400" />
                <span v-if="isExpanded || isHovered || isMobileOpen" class="truncate">Keluar</span>
            </Link>
        </div>
    </aside>
</template>

<script setup>
import PlusSquareIcon from "@/Components/icons/PlusSquareIcon.vue";
import {
    GridIcon,
    ChecklistIcon,
    NotificationStatusIcon,
    AddressBookIcon,
    ClockIcon,
    CalendarIcon,
    UserCard,
    FormChecklistIcon,
    VehicleIcon,
    FormIcon,
    MoneyIcon,
    QuestionnaireIcon,
    CartIcon,
    ArrowCircleIcon,
    ParcelIcon,
    ChartIcon,
    DeliveryIcon,
    EditIcon,
    HomeIcon,
    OfficeIcon,
    BriefCase,
    TrackingIcon,
    SettingIcon,
    CubeIcon,
    UserGroupIcon,
    RootIcon,
    GearIcon,
    UserCircleIcon,
    ChatIcon,
    MailIcon,
    TrashIcon,
    DocsIcon,
    PieChartIcon,
     ChevronDownIcon,
    PageIcon,
    TableIcon,
    ListIcon,
    PlugInIcon,
    ChevronRightIcon,
    WarningIcon,
    LogoutIcon,
} from "@/Components/icons";
import { useSidebar } from "@/Composables/useSidebar";
import { computed, ref, onMounted, onBeforeUnmount, nextTick } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";
import { useAuth } from "@/Composables/useAuth";
import { messaging } from "@/firebase";
import { onMessage } from "firebase/messaging";
import Swal from "sweetalert2";

const { user, is, can, roles } = useAuth();
const page = usePage();

// Cluster state management
const clusters = computed(() => page.props.clusters || []);
const selectedClusterId = ref(null);
const selectedCluster = computed(() => {
    if (selectedClusterId.value === 'all') return { id: 'all', name: 'Semua Cluster' };
    if (!selectedClusterId.value || !clusters.value.length) return null;
    return clusters.value.find(c => c.id === selectedClusterId.value);
});
const isClusterDropdownOpen = ref(false);
const showClusterDropdown = computed(() => {
    return clusters.value.length > 0 && (is('Superadmin') || is('Pengelola') || is('Pemilik'));
});

// Initialize selected cluster from URL or localStorage
onMounted(() => {
    // Try to get cluster_id from URL query params
    const urlParams = new URLSearchParams(window.location.search);
    const clusterIdFromUrl = urlParams.get('cluster_id');

    if (clusterIdFromUrl && (clusterIdFromUrl === 'all' || clusters.value.find(c => c.id == clusterIdFromUrl))) {
        selectedClusterId.value = clusterIdFromUrl === 'all' ? 'all' : parseInt(clusterIdFromUrl);
        localStorage.setItem('selectedClusterId', clusterIdFromUrl);
    } else {
        // Fallback to localStorage
        const storedClusterId = localStorage.getItem('selectedClusterId');
        if (storedClusterId === 'all' || (storedClusterId && clusters.value.find(c => c.id == storedClusterId))) {
            selectedClusterId.value = storedClusterId === 'all' ? 'all' : parseInt(storedClusterId);
        } else if (clusters.value.length > 0) {
            // Default to first cluster
            selectedClusterId.value = clusters.value[0].id;
            localStorage.setItem('selectedClusterId', clusters.value[0].id.toString());
        }
    }

    // Listen for foreground notifications
    onMessage(messaging, (payload) => {
        console.log('Message received. ', payload);
        const { title, body } = payload.notification;

        // Play notification sound
        // const audio = new Audio('/audio/notification.mp3');
        // audio.play().catch(e => console.log('Audio play failed', e));

        Swal.fire({
            title: title,
            text: body,
            icon: 'info',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    });
});

// Handle cluster selection
function selectCluster(cluster) {
    if (cluster === 'all') {
        selectedClusterId.value = 'all';
        localStorage.setItem('selectedClusterId', 'all');
        isClusterDropdownOpen.value = false;

        // Reload current page without cluster_id
        const url = new URL(window.location.href);
        url.searchParams.delete('cluster_id');
        router.visit(url.toString(), { preserveState: true, preserveScroll: true });
        return;
    }

    selectedClusterId.value = cluster.id;
    localStorage.setItem('selectedClusterId', cluster.id.toString());
    isClusterDropdownOpen.value = false;

    // Reload current page with new cluster_id
    const url = new URL(window.location.href);
    url.searchParams.set('cluster_id', cluster.id);
    router.visit(url.toString(), { preserveState: true, preserveScroll: true });
}

function deleteCluster(clusterId) {
    Swal.fire({
        title: 'Hapus Cluster?',
        text: 'Anda yakin ingin menghapus cluster ini? Semua data terkait mungkin akan terhapus.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('clusters.destroy', clusterId), {
                preserveScroll: true,
                onSuccess: () => {
                    // If currently selected cluster is deleted, reset the selection
                    if (selectedClusterId.value === clusterId) {
                        if (clusters.value.length > 0) {
                            selectCluster(clusters.value[0]);
                        } else {
                            selectedClusterId.value = null;
                            localStorage.removeItem('selectedClusterId');
                        }
                    }
                }
            });
        }
    });
}

// Helper function to append cluster_id to menu paths
function getMenuPath(path) {
    if (!selectedClusterId.value || selectedClusterId.value === 'all' || !showClusterDropdown.value) return path;

    try {
        const url = new URL(path, window.location.origin);
        url.searchParams.set('cluster_id', selectedClusterId.value);
        return url.pathname + url.search;
    } catch {
        // If path is relative
        const separator = path.includes('?') ? '&' : '?';
        return `${path}${separator}cluster_id=${selectedClusterId.value}`;
    }
}

// Close cluster dropdown when clicking outside
function handleClickOutside(event) {
    if (isClusterDropdownOpen.value && !event.target.closest('.relative')) {
        isClusterDropdownOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

// Website settings
const logoUrl = computed(() => page.props.settings?.logo_main_url || page.props.settings?.logo[1].value || '/images/logo/logo.png');
// const siteName = computed(() => page.props.settings?.site_name || page.props.settings?.general[1].value || 'Starter Project');
// const siteDescription = computed(() => page.props.settings?.site_description || page.props.settings?.general[0].value || 'Laravel Inertia Vue');

const sidebarCounts = computed(
    () => page.props.sidebarCounts || { total: 0, on_progress: 0 }
);

// Pending submissions count
const pendingSubmissions = computed(() => {
    const data = page.props.pendingSubmissionsCount || {
        total: 0,
        by_type: {},
    };
    return data;
});
const pendingCount = computed(() => pendingSubmissions.value.total || 0);

// Helper function to get pending count by submission type
function getPendingCountByType(type) {
    return pendingSubmissions.value.by_type?.[type] || 0;
}

const pendingCounts = computed(() => page.props.pendingCounts || {});

function getBadgeCount(itemName) {
    if (itemName === 'Permintaan Check-in') return pendingCounts.value.checkin_requests;
    if (itemName === 'Permintaan Pindah Kamar') return pendingCounts.value.room_transfers;
    if (itemName === 'Laporan Kerusakan') return pendingCounts.value.damage_reports;
    if (itemName === 'Pengembalian Dana') return pendingCounts.value.refunds;
    if (itemName === 'Tagihan') return pendingCounts.value.unpaid_bills;
    if (itemName === 'Akun Penyewa') return pendingCounts.value.unpaid_tenants;
    return 0;
}

// Auto-scroll to active menu
const sidebarScroll = ref(null);

function getScrollElement() {
    const root = sidebarScroll.value;
    if (!root) return null;
    const simplebar = root.querySelector?.(".simplebar-content-wrapper");
    return simplebar || root;
}

function scrollActiveIntoView() {
    const container = getScrollElement();
    if (!container) return;
    const activeEl = container.querySelector('[data-active="true"]');
    if (!activeEl) return;

    const margin = 12; // px threshold to avoid tiny jumps
    const cRect = container.getBoundingClientRect();
    const eRect = activeEl.getBoundingClientRect();

    const above = eRect.top < cRect.top + margin;
    const below = eRect.bottom > cRect.bottom - margin;

    if (!above && !below) return; // Already sufficiently visible

    let newTop = container.scrollTop;
    if (above) {
        newTop -= cRect.top + margin - eRect.top;
    } else if (below) {
        newTop += eRect.bottom - (cRect.bottom - margin);
    }

    if (typeof container.scrollTo === "function") {
        container.scrollTo({ top: newTop, behavior: "smooth" });
    } else {
        container.scrollTop = newTop;
    }
}

function onInertiaFinish() {
    nextTick(() => {
        scrollActiveIntoView();
        // Close submenu if current route is not a submenu route
        if (!isAnySubmenuRouteActive.value && openSubmenu.value !== null) {
            openSubmenu.value = null;
        }
    });
}

onMounted(() => {
    nextTick(() => scrollActiveIntoView());
    window.addEventListener("inertia:finish", onInertiaFinish);
});

onBeforeUnmount(() => {
    window.removeEventListener("inertia:finish", onInertiaFinish);
});

const { isExpanded, isMobileOpen, isHovered, openSubmenu } = useSidebar();

// Menu untuk Penyewa
const penyewaMenuGroups = computed(() => {
    const user = page.props.auth?.user;
    return [
    {
        title: "",
        items: [
            {
                icon: HomeIcon,
                name: "Dashboard",
                path: "/penyewa/rooms",
            },
        ],
    },
    {
        title: "Menu Penyewa",
        items: [
            {
                icon: MoneyIcon,
                name: "Tagihan",
                path: "/penyewa/transactions",
            },
            ...(user?.tenant?.is_moved ? [{
                icon: ArrowCircleIcon,
                name: "Pindah Kamar",
                path: "/penyewa/room-transfers",
            }] : []),
            {
                icon: MoneyIcon,
                name: "Pengembalian Dana",
                path: "/penyewa/refunds",
            },
            {
                icon: WarningIcon,
                name: "Laporan Kerusakan",
                path: "/penyewa/damage-reports",
            },
        ],
    },
    {
        title: "Akun",
        items: [
            {
                icon: UserCircleIcon,
                name: "Profil",
                path: "/profile",
            },
        ],
    },
]});

const sharedAdminItems = [
    {
        icon: GridIcon,
        name: "Dashboard",
        path: "/dashboard",
    },
    {
        icon: MoneyIcon,
        name: "Transaksi",
        path: "/admin/transactions",
    },
    {
        icon: HomeIcon,
        name: "Properti",
        path: "/boarding-houses",
    },
    {
        icon: ChecklistIcon,
        name: "Permintaan Check-in",
        path: "/admin/checkin-requests",
    },
    {
        icon: ArrowCircleIcon,
        name: "Permintaan Pindah Kamar",
        path: "/admin/room-transfers",
    },
    {
        icon: WarningIcon,
        name: "Laporan Kerusakan",
        path: "/admin/damage-reports",
    },
    {
        icon: UserGroupIcon,
        name: "Akun Penyewa",
        path: "/admin/tenants",
    },
    {
        icon: UserGroupIcon,
        name: "Akun Pemilik",
        path: "/admin/owners",
    },
    {
        icon: MoneyIcon,
        name: "Pengembalian Dana",
        path: "/admin/refunds",
    },
];

// Menu untuk Pengelola — tanpa akses Konfigurasi WA, Settings, Jabatan
const pengelolaMenuGroups = [
    {
        title: "",
        items: sharedAdminItems,
    },
    {
        title: "Laporan",
        items: [
            {
                icon: MoneyIcon,
                name: "Laporan Keuangan",
                path: "/admin/financial-reports",
            },
        ],
    },
];

// Menu untuk Superadmin — semua fitur + Manajemen Pengelola + Konfigurasi
const superadminMenuGroups = [
    {
        title: "",
        items: sharedAdminItems,
    },
    {
        title: "Laporan",
        items: [
            {
                icon: MoneyIcon,
                name: "Laporan Keuangan",
                path: "/admin/financial-reports",
            },
        ],
    },
    {
        title: "Konfigurasi",
        items: [
            {
                icon: UserGroupIcon,
                name: "Manajemen Pengelola",
                path: "/users?role=Pengelola",
            },
            {
                icon: ChatIcon,
                name: "Konfigurasi WA",
                path: "/whatsapp-config",
            },
            {
                icon: SettingIcon,
                name: "Pengaturan Website",
                path: "/settings",
            },
        ],
    },
];


const pemilikMenuGroups = [
    {
        title: "",
        items: [
            {
                icon: GridIcon,
                name: "Dashboard",
                path: "/dashboard",
            },
            {
                icon: MoneyIcon,
                name: "Transaksi",
                path: "/admin/transactions",
            },
            {
                icon: HomeIcon,
                name: "Properti",
                path: "/boarding-houses",
            },
            {
                icon: UserGroupIcon,
                name: "Akun Penyewa",
                path: "/admin/tenants",
            },
        ],
    },
    {
        title: "Laporan",
        items: [
            {
                icon: MoneyIcon,
                name: "Laporan Keuangan",
                path: "/admin/financial-reports",
            },
        ],
    },
];
// Computed property untuk menu berdasarkan role
const menuGroups = computed(() => {
    if (is('Penyewa')) {
        return penyewaMenuGroups.value;
    }
    if (is('Pemilik')) {
        return pemilikMenuGroups;
    }
    if (is('Superadmin')) {
        return superadminMenuGroups;
    }
    return pengelolaMenuGroups;
});

const isActive = (maybeUrl) => {
    if (!maybeUrl) return false;
    try {
        const url = new URL(maybeUrl, window.location.origin);
        const current = new URL(page.url, window.location.origin);
        return (
            current.pathname === url.pathname ||
            current.pathname.startsWith(url.pathname + "/")
        );
    } catch (e) {
        return page.url.startsWith(maybeUrl);
    }
};

const toggleSubmenu = (groupIndex, itemIndex) => {
    const key = `${groupIndex}-${itemIndex}`;
    openSubmenu.value = openSubmenu.value === key ? null : key;
};

const isAnySubmenuRouteActive = computed(() => {
    return menuGroups.value.some((group) =>
        group.items.some(
            (item) =>
                item.subItems &&
                item.subItems.some((subItem) => isActive(subItem.path))
        )
    );
});

const hasActiveSubmenuRoute = (groupIndex, itemIndex) => {
    const item = menuGroups.value[groupIndex].items[itemIndex];
    return item.subItems?.some((subItem) =>
        isActive(subItem.pathName ? route(subItem.pathName) : subItem.path)
    ) || false;
};

const isSubmenuOpen = (groupIndex, itemIndex) => {
    const key = `${groupIndex}-${itemIndex}`;
    // Open submenu if:
    // 1. Manually toggled (openSubmenu.value === key), OR
    // 2. Route matches a submenu of this item (auto-open)
    return openSubmenu.value === key || hasActiveSubmenuRoute(groupIndex, itemIndex);
};

const startTransition = (el) => {
    el.style.height = "auto";
    const height = el.scrollHeight;
    el.style.height = "0px";
    el.offsetHeight; // force reflow
    el.style.height = height + "px";
};

const endTransition = (el) => {
    el.style.height = "";
};

function signOut(e) {
    e.preventDefault();
    if (typeof route !== 'undefined') {
        router.post(route("logout"));
    } else {
        router.post("/logout");
    }
}
</script>
