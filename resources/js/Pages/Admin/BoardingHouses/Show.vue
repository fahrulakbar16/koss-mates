<template>

    <Head :title="`Detail ${boardingHouse.name}`" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-2">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                        Detail Kos
                    </h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Kelola informasi kos dan daftar kamar dengan mudah
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
            <div>
                <!-- Boarding House Detail Section -->
                <div
                    class="p-8 lg:p-10 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-br from-gray-50/30 to-transparent dark:from-gray-900/20">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <!-- Thumbnail -->
                        <div class="flex-shrink-0">
                            <div
                                class="relative w-full lg:w-80 h-52 lg:h-64 rounded-2xl overflow-hidden shadow-2xl shadow-gray-900/10 dark:shadow-none border border-gray-200/80 dark:border-gray-700 group ring-1 ring-gray-900/5">
                                <img v-if="boardingHouse.thumbnail" :src="`/storage/${boardingHouse.thumbnail}`"
                                    :alt="boardingHouse.name"
                                    class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-500" />
                                <div v-else
                                    class="flex items-center justify-center w-full h-full bg-gray-50 dark:bg-gray-800 text-gray-400 dark:text-gray-600">
                                    <HomeIcon class="w-20 h-20 opacity-50" />
                                </div>

                                <!-- Status Badge removed as per new schema -->
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span
                                                class="px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                                                {{ boardingHouse.cluster?.name || 'Tanpa Cluster' }}
                                            </span>
                                        </div>
                                            <h1
                                            class="text-3xl font-bold text-gray-900 dark:text-white mb-2 tracking-tight">
                                            {{ boardingHouse.name }}
                                        </h1>
                                        <div
                                            class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
                                            <!-- <span class="font-medium">Pemilik:</span> -->
                                            <span
                                                class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-gray-700 dark:text-gray-300">
                                                {{ boardingHouse.owner?.name || '-' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <button v-if="can('boarding_houses.edit')" @click="openEditBoardingHouseModal"
                                            class="group p-3 text-gray-600 bg-white border border-gray-200 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-primary-400 dark:hover:bg-gray-700 dark:hover:border-primary-800 rounded-xl transition-all shadow-sm hover:shadow-lg hover:-translate-y-1"
                                            title="Edit Data Nomor Kos">
                                            <EditIcon class="w-5 h-5 transition-transform group-hover:scale-110" />
                                        </button>
                                        <button v-if="can('boarding_houses.delete')"
                                            @click="openConfirmBoardingHouseModal"
                                            class="group p-3 text-primary-600 bg-white border border-gray-200 hover:bg-primary-50 hover:text-primary-700 hover:border-primary-200 dark:bg-gray-800 dark:border-gray-700 dark:text-primary-400 dark:hover:text-primary-300 dark:hover:bg-gray-700 dark:hover:border-primary-800 rounded-xl transition-all shadow-sm hover:shadow-lg hover:-translate-y-1"
                                            title="Hapus Kos">
                                            <TrashIcon class="w-5 h-5 transition-transform group-hover:scale-110" />
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                                    <div class="group">
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                            Alamat</p>
                                        <p
                                            class="text-sm font-medium text-gray-900 dark:text-gray-200 group-hover:text-primary-600 transition-colors">
                                            {{ boardingHouse.address || '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                            Tipe Kos</p>
                                        <div class="flex">
                                            <div v-if="boardingHouse.gender === 'L'"
                                                class="flex items-center gap-1.5 bg-primary-600/90 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg border border-white/20">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="4" r="2.5" />
                                                    <path d="M17,11c0-1.657-1.343-3-3-3H10c-1.657,0-3,1.343-3,3v5h2v6h2v-4h2v4h2v-6h2V11z" />
                                                </svg>
                                                <span>PUTRA</span>
                                            </div>
                                            <div v-else-if="boardingHouse.gender === 'P'"
                                                class="flex items-center gap-1.5 bg-primary-600/90 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg border border-white/20">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="3.5" r="2" />
                                                    <path
                                                        d="M17.5,13.5L15,8c-0.2-0.5-0.7-1-1.3-1h-3.4C9.7,7,9.2,7.5,9,8l-2.5,5.5C6.3,13.8,6.5,14.3,7,14.3h2V18c0,0.6,0.4,1,1,1h1v3h2v-3h1c0.6,0,1-0.4,1-1v-3.7h2C17.5,14.3,17.7,13.8,17.5,13.5z" />
                                                </svg>
                                                <span>PUTRI</span>
                                            </div>
                                            <div v-else-if="boardingHouse.gender === 'C'"
                                                class="flex items-center gap-1.5 bg-primary-600/90 backdrop-blur-md text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg border border-white/20">
                                                <div class="flex items-center -space-x-1">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="4" r="2.5" />
                                                        <path d="M17,11c0-1.657-1.343-3-3-3H10c-1.657,0-3,1.343-3,3v5h2v6h2v-4h2v4h2v-6h2V11z" />
                                                    </svg>
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="3.5" r="2" />
                                                        <path
                                                            d="M17.5,13.5L15,8c-0.2-0.5-0.7-1-1.3-1h-3.4C9.7,7,9.2,7.5,9,8l-2.5,5.5C6.3,13.8,6.5,14.3,7,14.3h2V18c0,0.6,0.4,1,1,1h1v3h2v-3h1c0.6,0,1-0.4,1-1v-3.7h2C17.5,14.3,17.7,13.8,17.5,13.5z" />
                                                    </svg>
                                                </div>
                                                <span>CAMPUR</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="group">
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                            Bagi Hasil Pemilik</p>
                                        <p
                                            class="text-sm font-medium text-gray-900 dark:text-gray-200 group-hover:text-primary-600 transition-colors">
                                            {{ boardingHouse.persentasi_pemilik || 0 }}%
                                        </p>
                                    </div>
                                </div>

                                <div v-if="boardingHouse.description"
                                    class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                        {{ boardingHouse.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery & Rooms Combined Section -->
                <div class="p-8 lg:p-10 pt-8">
                    <!-- Header with Tabs -->
                    <div
                        class="sticky top-0 z-10 bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl py-6 -mx-2 px-2 border-b border-gray-200 dark:border-gray-700 mb-8 shadow-sm">
                        <div class="flex flex-col gap-4">
                            <!-- Tab Switcher -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:overflow-x-auto sm:pb-2 sm:scrollbar-thin">
                                <button @click="activeContentTab = 'gallery'" :class="[
                                    'flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl transition-all whitespace-nowrap',
                                    activeContentTab === 'gallery'
                                        ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 shadow-sm'
                                        : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700'
                                ]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Galeri Foto</span>
                                    <span v-if="boardingHouse.images && boardingHouse.images.length > 0"
                                        class="px-2 py-0.5 rounded-full text-xs font-bold"
                                        :class="activeContentTab === 'gallery' ? 'bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'">
                                        {{ boardingHouse.images.length }}
                                    </span>
                                </button>
                                <button @click="activeContentTab = 'rooms'" :class="[
                                    'flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl transition-all whitespace-nowrap',
                                    activeContentTab === 'rooms'
                                        ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 shadow-sm'
                                        : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700'
                                ]">
                                    <BedIcon class="w-5 h-5" />
                                    <span>Daftar Kamar</span>
                                    <span v-if="rooms.total" class="px-2 py-0.5 rounded-full text-xs font-bold"
                                        :class="activeContentTab === 'rooms' ? 'bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'">
                                        {{ rooms.total }}
                                    </span>
                                </button>
                                <button @click="activeContentTab = 'expenses'" :class="[
                                    'flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl transition-all whitespace-nowrap',
                                    activeContentTab === 'expenses'
                                        ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 shadow-sm'
                                        : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700'
                                ]">
                                    <MoneyIcon class="w-5 h-5" />
                                    <span>Pengeluaran</span>
                                </button>

                                <!-- Action Buttons (dalam baris yang sama, scroll bersama) -->
                                <div class="sm:ml-auto flex-shrink-0">
                                    <button v-if="activeContentTab === 'gallery' && can('boarding_houses.edit')" @click="openGalleryModal" type="button"
                                        class="flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transform hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap">
                                        <PlusSquareIcon class="w-5 h-5" />
                                        <span>Tambah Foto</span>
                                    </button>
                                    <button v-if="activeContentTab === 'expenses' && (is('Superadmin') || is('Pengelola'))" @click="openExpenseModal" type="button"
                                        class="flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transform hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap">
                                        <PlusSquareIcon class="w-5 h-5" />
                                        <span>Tambah Pengeluaran</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Room Filters (only show when rooms tab is active) -->
                            <div v-if="activeContentTab === 'rooms'" class="flex flex-col gap-3">
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <div class="relative group flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <SearchIcon
                                                class="h-4 w-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" />
                                        </div>
                                        <input v-model="search" type="text" placeholder="Cari nomor/nama kamar..."
                                            class="pl-10 pr-4 py-2.5 w-full bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all shadow-sm" />
                                    </div>

                                    <select v-model="statusFilter"
                                        class="w-full sm:w-auto py-2.5 pl-4 pr-10 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all shadow-sm cursor-pointer">
                                        <option value="">Semua Status</option>
                                        <option value="available">Tersedia</option>
                                        <option value="occupied">Terisi</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                </div>

                                <div class="flex gap-2">
                                    <button v-if="can('rooms.create')" @click="openAddModal" type="button"
                                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transform hover:-translate-y-0.5 transition-all duration-200">
                                        <PlusSquareIcon class="w-5 h-5" />
                                        <span>Tambah Kamar</span>
                                    </button>
                                    <button v-if="can('rooms.create')" @click="openBatchModal" type="button"
                                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-primary-600 bg-primary-50 hover:bg-primary-100 border border-primary-200 rounded-xl shadow-sm transform hover:-translate-y-0.5 transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <span>Tambah Batch</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <!-- Gallery Tab -->
                    <div v-if="activeContentTab === 'gallery'">
                        <div v-if="boardingHouse.images && boardingHouse.images.length > 0"
                            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                            <div v-for="image in boardingHouse.images" :key="image.id"
                                class="group relative aspect-square rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-500 shadow-sm hover:shadow-xl hover:shadow-primary-500/10 transition-all duration-300 hover:-translate-y-1">
                                <img :src="`/storage/${image.image}`" :alt="`Gambar ${boardingHouse.name}`"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" />
                                <div
                                    class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-2">
                                    <button v-if="can('boarding_houses.edit')" @click="deleteImage(image)"
                                        class="p-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors shadow-lg"
                                        title="Hapus gambar">
                                        <TrashIcon class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-else
                            class="flex flex-col items-center justify-center py-12 px-4 text-center border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl">
                            <div
                                class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-300 dark:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum Ada Foto</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-6">
                                Tambahkan foto-foto kos untuk memberikan gambaran yang lebih jelas kepada calon penyewa.
                            </p>
                            <button v-if="can('boarding_houses.edit')" @click="openGalleryModal"
                                class="px-6 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-0.5">
                                Tambah Foto Sekarang
                            </button>
                        </div>
                    </div>

                    <!-- Rooms Tab -->
                    <div v-if="activeContentTab === 'rooms'">
                        <div v-if="rooms.data && rooms.data.length > 0">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                <div v-for="room in rooms.data" :key="room.id"
                                    class="group relative bg-white dark:bg-gray-700/30 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary-400 dark:hover:border-primary-600 p-6 shadow-md hover:shadow-2xl hover:shadow-primary-500/20 dark:hover:shadow-primary-500/10 transition-all duration-300 hover:-translate-y-2 ring-1 ring-gray-900/5 dark:ring-0">
                                    <!-- Status Dot -->
                                    <div class="absolute top-5 right-5 flex items-center gap-2">
                                        <span :class="[
                                            'w-2 h-2 rounded-full animate-pulse',
                                            room.status === 'occupied' ? 'bg-blue-500' : (room.status === 'maintenance' ? 'bg-primary-500' : (room.status === 'booked' ? 'bg-amber-500' : 'bg-emerald-500'))
                                        ]"></span>
                                        <span :class="[
                                            'text-xs font-bold uppercase tracking-wider',
                                            room.status === 'occupied' ? 'text-blue-600 dark:text-blue-400' : (room.status === 'maintenance' ? 'text-primary-600 dark:text-primary-400' : (room.status === 'booked' ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400'))
                                        ]">
                                            {{ room.status === 'occupied' ? 'Terisi' : (room.status === 'maintenance' ? 'Maintenance' : (room.status === 'booked' ? 'Booked' : 'Tersedia')) }}
                                        </span>
                                    </div>

                                    <div class="mb-4">
                                        <span
                                            class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kamar</span>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate pr-20">
                                            {{ room.name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 font-mono mt-0.5">
                                            No. {{ room.number || '-' }}
                                        </p>
                                    </div>

                                    <div class="py-4 border-t border-gray-100 dark:border-gray-600/50">
                                        <!-- Show tenant name when room is occupied -->
                                        <div v-if="room.active_tenant" class="space-y-2">
                                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">
                                                Ditempati Oleh</p>
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-900/10 flex items-center justify-center border-2 border-blue-200 dark:border-blue-800">
                                                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                                        {{ room.active_tenant.charAt(0).toUpperCase() }}
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                        {{ room.active_tenant }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Penyewa Aktif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Empty state for available rooms -->
                                        <div v-else class="text-center py-3">
                                            <p class="text-sm text-gray-400 dark:text-gray-500">Kamar Tersedia</p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div
                                        class="pt-4 border-t border-gray-100 dark:border-gray-600/50 flex flex-wrap items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <Link :href="route('boarding-houses.rooms.show', [boardingHouse.id, room.id])"
                                            class="flex-1 min-w-[90px] flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-white bg-primary-600 dark:bg-primary-500 rounded-lg hover:bg-primary-700 dark:hover:bg-primary-600 transition-colors shadow-lg">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail
                                        </Link>
                                        <button v-if="room.status === 'available' && can('rooms.edit')" @click="openAssignTenantModal(room)"
                                            class="flex-1 min-w-[90px] flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-white bg-emerald-600 dark:bg-emerald-500 rounded-lg hover:bg-emerald-700 dark:hover:bg-emerald-600 transition-colors shadow-lg"
                                            title="Tambah Penyewa">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                                            Tambah
                                        </button>
                                        <button v-if="room.status === 'booked' && can('rooms.edit')" @click="openCancelBookingModal(room)"
                                            class="flex-1 min-w-[90px] flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-white bg-primary-600 dark:bg-primary-500 rounded-lg hover:bg-primary-700 dark:hover:bg-primary-600 transition-colors shadow-lg"
                                            title="Batalkan Booking">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Batal
                                        </button>
                                        <div class="flex items-center gap-1 flex-shrink-0 ml-auto">
                                            <Link v-if="can('rooms.edit')" :href="route('boarding-houses.rooms.prices.edit', [boardingHouse.id, room.id])"
                                                class="flex items-center justify-center p-2 text-white bg-gray-900 dark:bg-gray-600 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-500 transition-colors shadow-lg"
                                                title="Atur Harga">
                                                <MoneyIcon class="w-4 h-4" />
                                            </Link>
                                            <button v-if="can('rooms.edit')" @click="openEditModal(room)"
                                                class="p-2 text-gray-600 bg-gray-100 dark:bg-gray-700/50 dark:text-gray-300 rounded-lg hover:bg-primary-50 hover:text-primary-600 dark:hover:bg-primary-900/30 dark:hover:text-primary-400 transition-colors"
                                                title="Edit Kamar">
                                                <EditIcon class="w-4 h-4" />
                                            </button>
                                            <button v-if="can('rooms.delete')" @click="openConfirmModal(room)"
                                                class="p-2 text-primary-600 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors"
                                                title="Hapus Kamar">
                                                <TrashIcon class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8">
                                <Pagination :pagination="rooms" />
                            </div>
                        </div>
                    </div>



                    <!-- Expenses Tab -->
                    <div v-if="activeContentTab === 'expenses'">
                        <div v-if="props.expenses && props.expenses.length > 0" class="space-y-4">
                            <div v-for="expense in props.expenses" :key="expense.id"
                                class="group bg-white dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-400 dark:hover:border-primary-600 p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                                                <MoneyIcon class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                                    {{ expense.description }}
                                                </h3>
                                                <div
                                                    class="flex flex-wrap items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                    <span class="flex items-center gap-1">
                                                        <BedIcon class="w-4 h-4" />
                                                        {{ expense.room_name || 'Umum' }}
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ formatDate(expense.date) }}
                                                    </span>
                                                </div>
                                                <div class="mt-3 flex items-center gap-3">
                                                    <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                                        {{ formatCurrency(expense.amount) }}
                                                    </p>
                                                    <a v-if="expense.receipt_path"
                                                        :href="`/storage/${expense.receipt_path}`" target="_blank"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Lihat Bukti
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="flex gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                        <button @click="editExpense(expense)"
                                            class="p-2 text-gray-600 bg-gray-100 dark:bg-gray-700/50 dark:text-gray-300 rounded-lg hover:bg-primary-50 hover:text-primary-600 dark:hover:bg-primary-900/30 dark:hover:text-primary-400 transition-colors"
                                            title="Edit Pengeluaran">
                                            <EditIcon class="w-4 h-4" />
                                        </button>
                                        <button @click="deleteExpense(expense)"
                                            class="p-2 text-primary-600 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors"
                                            title="Hapus Pengeluaran">
                                            <TrashIcon class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="flex flex-col items-center justify-center py-20 px-4 text-center">
                            <div
                                class="w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6">
                                <MoneyIcon class="w-12 h-12 text-gray-300 dark:text-gray-600" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum Ada Pengeluaran</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-8">
                                Catat semua pengeluaran untuk kos ini agar keuangan lebih teratur.
                            </p>
                            <button v-if="is('Superadmin') || is('Pengelola')" @click="openExpenseModal"
                                class="px-6 py-2.5 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-0.5">
                                Tambah Pengeluaran Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Room Modal -->
            <Modal :show="isModalOpen" :title="isEditMode ? `Edit ${selectedItem?.name}` : 'Tambah Kamar Baru'"
                confirmText="Simpan" maxWidth="lg" @close="closeModal" @confirm="saveRoom">
                <div class="space-y-5">
                    <div class="space-y-2">
                        <label for="room_number" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Nomor Kamar <span class="text-primary-500">*</span>
                        </label>
                        <input id="room_number"
                            class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all placeholder:text-gray-400"
                            type="number" v-model="form.number" required placeholder="Contoh: 01" />
                        <div v-if="form.errors.number" class="text-xs text-primary-500 font-medium mt-1">
                            {{ form.errors.number }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="room_capacity"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Kapasitas
                            </label>
                            <div class="relative">
                                <input id="room_capacity"
                                    class="w-full pl-4 pr-12 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all placeholder:text-gray-400"
                                    type="number" min="1" v-model.number="form.capacity" placeholder="1" />
                                <span
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Orang</span>
                            </div>
                            <div v-if="form.errors.capacity" class="text-xs text-primary-500 font-medium mt-1">
                                {{ form.errors.capacity }}
                            </div>
                        </div>
                    </div>

                        <div class="space-y-2">
                            <label for="room_description"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Deskripsi
                            </label>
                            <textarea id="room_description"
                                class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all placeholder:text-gray-400"
                                v-model="form.description" rows="3"
                                placeholder="Tambahkan catatan tentang kamar ini..."></textarea>
                            <div v-if="form.errors.description" class="text-xs text-primary-500 font-medium mt-1">
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Fasilitas
                            </label>
                            <div class="flex flex-wrap gap-2.5">
                                <button type="button" v-for="facility in availableFacilities" :key="facility"
                                    @click="toggleFacility(facility)"
                                    :class="[
                                        'px-4 py-1.5 text-sm font-medium rounded-full transition-all border',
                                        form.facilities && form.facilities.includes(facility)
                                            ? 'bg-primary-50 text-primary-700 border-primary-200 dark:bg-primary-900/30 dark:text-primary-400 dark:border-primary-800'
                                            : 'bg-white text-gray-700 border-gray-300 hover:border-gray-400 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:border-gray-500'
                                    ]">
                                    {{ facility }}
                                </button>
                            </div>
                            <div v-if="form.errors.facilities" class="text-xs text-primary-500 font-medium mt-1">
                                {{ form.errors.facilities }}
                            </div>
                        </div>
                    </div>
            </Modal>

            <!-- Batch Room Modal -->
            <Modal :show="isBatchModalOpen" title="Tambah Kamar Secara Batch" confirmText="Tambah Batch" maxWidth="2xl"
                @close="closeBatchModal" @confirm="saveBatchRoom">
                <div class="space-y-6">
                    <div
                        class="bg-primary-50 dark:bg-primary-900/20 p-4 rounded-xl border border-primary-100 dark:border-primary-800/50">
                        <p class="text-sm text-primary-700 dark:text-primary-300 flex gap-2">
                            <span class="font-bold">Info:</span>
                            Kamar akan dibuat secara batch dengan nomor yang berurutan. Anda juga dapat menentukan harga
                            sekaligus (opsional).
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label for="batch_count" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Jumlah Kamar <span class="text-primary-500">*</span>
                            </label>
                            <input id="batch_count"
                                class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all"
                                type="number" min="1" max="50" v-model.number="batchForm.count" required
                                placeholder="Contoh: 10" />
                            <div v-if="batchForm.errors.count" class="text-xs text-primary-500 font-medium mt-1">
                                {{ batchForm.errors.count }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="batch_start_number"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Nomor Mulai (Kosongi untuk lanjut terakhir)
                            </label>
                            <input id="batch_start_number"
                                class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all"
                                type="number" min="1" v-model.number="batchForm.start_number" placeholder="Otomatis" />
                            <div v-if="batchForm.errors.start_number" class="text-xs text-primary-500 font-medium mt-1">
                                {{ batchForm.errors.start_number }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="batch_capacity" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Kapasitas Default
                            </label>
                            <div class="relative">
                                <input id="batch_capacity"
                                    class="w-full pl-4 pr-12 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all"
                                    type="number" min="1" v-model.number="batchForm.capacity" placeholder="1" />
                                <span
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Orang</span>
                            </div>
                        </div>

                        <div class="space-y-3 md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Fasilitas
                            </label>
                            <div class="flex flex-wrap gap-2.5">
                                <button type="button" v-for="facility in availableFacilities" :key="facility"
                                    @click="toggleBatchFacility(facility)"
                                    :class="[
                                        'px-4 py-1.5 text-sm font-medium rounded-full transition-all border',
                                        batchForm.facilities && batchForm.facilities.includes(facility)
                                            ? 'bg-primary-50 text-primary-700 border-primary-200 dark:bg-primary-900/30 dark:text-primary-400 dark:border-primary-800'
                                            : 'bg-white text-gray-700 border-gray-300 hover:border-gray-400 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:border-gray-500'
                                    ]">
                                    {{ facility }}
                                </button>
                            </div>
                            <div v-if="batchForm.errors.facilities" class="text-xs text-primary-500 font-medium mt-1">
                                {{ batchForm.errors.facilities }}
                            </div>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="batch_description"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Deskripsi Default
                            </label>
                            <textarea id="batch_description"
                                class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all"
                                v-model="batchForm.description" rows="2"
                                placeholder="Tambahkan catatan untuk semua kamar ini..."></textarea>
                        </div>
                    </div>

                    <!-- Batch Price Section -->
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Pengaturan Harga (Opsional)</h4>
                            <button type="button" @click="addBatchPriceItem"
                                class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                                <PlusSquareIcon class="w-4 h-4" /> Tambah Durasi
                            </button>
                        </div>

                        <div class="space-y-3">
                            <div v-for="(priceItem, index) in batchForm.prices" :key="index"
                                class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="flex-1 grid grid-cols-2 gap-3">
                                    <div class="relative">
                                        <input type="number" min="1" v-model.number="priceItem.duration"
                                            class="w-full pl-3 pr-12 py-2 text-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                                            placeholder="Durasi" />
                                        <span
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400">BLN</span>
                                    </div>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400">RP</span>
                                        <input type="text" :value="formatInputCurrency(priceItem.price)" @input="handlePriceInput(priceItem, $event)"
                                            class="w-full pl-8 pr-3 py-2 text-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                                            placeholder="Harga" />
                                    </div>
                                </div>
                                <button type="button" @click="removeBatchPriceItem(index)"
                                    class="p-2 text-gray-400 hover:text-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-all">
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Modal>

            <!-- Confirm Modals -->
            <ConfirmModal :show="isConfirmModalOpen" :question="`Yakin ingin menghapus`"
                :selected="`${selectedItem?.name}`" title="Hapus Kamar" confirmText="Ya, Hapus!" maxWidth="md"
                @close="closeConfirmModal" @confirm="destroyData" />

            <ConfirmModal :show="isConfirmBoardingHouseModalOpen" :question="`Yakin ingin menghapus`"
                :selected="`${boardingHouse?.name}`" title="Hapus Boarding House" confirmText="Ya, Hapus!" maxWidth="md"
                @close="closeConfirmBoardingHouseModal" @confirm="destroyBoardingHouse" />

            <ConfirmModal :show="isCancelBookingModalOpen" :question="`Yakin ingin membatalkan booking`"
                :selected="`${selectedRoomForCancel?.name}`" title="Batalkan Booking" confirmText="Ya, Batalkan!" maxWidth="md"
                @close="closeCancelBookingModal" @confirm="confirmCancelBooking" />

            <!-- Gallery Modal -->
            <Modal :show="isGalleryModalOpen" title="Tambah Foto ke Galeri" maxWidth="2xl" @close="closeGalleryModal"
                @confirm="uploadImages" confirmText="Upload Foto">
                <div class="space-y-6">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800/50">
                        <p class="text-sm text-blue-700 dark:text-blue-300 flex gap-2">
                            <span class="font-bold">Info:</span>
                            Anda dapat mengupload maksimal 10 foto sekaligus. Format yang didukung: JPG, PNG, GIF, WEBP
                            (maks. 2MB per foto).
                        </p>
                    </div>

                    <div class="space-y-4">
                        <label for="gallery-images"
                            class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl cursor-pointer bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, WEBP (MAX. 2MB per
                                    foto)</p>
                            </div>
                            <input id="gallery-images" type="file" class="hidden" accept="image/*" multiple
                                @change="handleGalleryImagesChange" />
                        </label>

                        <!-- Preview Images -->
                        <div v-if="galleryForm.images && galleryForm.images.length > 0"
                            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div v-for="(preview, index) in galleryPreviews" :key="index"
                                class="group relative aspect-square rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                                <img :src="preview" :alt="`Preview ${index + 1}`" class="w-full h-full object-cover" />
                                <button type="button" @click="removeGalleryPreview(index)"
                                    class="absolute top-2 right-2 p-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors opacity-0 group-hover:opacity-100 shadow-lg">
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-xs px-2 py-1 text-center">
                                    {{ galleryForm.images[index]?.name || `Foto ${index + 1}` }}
                                </div>
                            </div>
                        </div>

                        <div v-if="galleryForm.errors.images"
                            class="flex items-center gap-2 px-4 py-3 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-xl text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ galleryForm.errors.images }}
                        </div>
                    </div>
                </div>
            </Modal>


            <!-- Expense Modal -->
            <Modal :show="isExpenseModalOpen" :title="isExpenseEditMode ? 'Edit Pengeluaran' : 'Tambah Pengeluaran'"
                maxWidth="lg" @close="closeExpenseModal" @confirm="saveExpense" confirmText="Simpan">
                <div class="space-y-5">
                    <div class="space-y-2">
                        <label for="expense_description"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Deskripsi Pengeluaran <span class="text-primary-500">*</span>
                        </label>
                        <input id="expense_description"
                            class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all placeholder:text-gray-400"
                            type="text" v-model="expenseForm.description" required
                            placeholder="Contoh: Perbaikan AC Kamar 101" />
                        <div v-if="expenseForm.errors.description" class="text-xs text-primary-500 font-medium mt-1">
                            {{ expenseForm.errors.description }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="expense_amount"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Jumlah <span class="text-primary-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">Rp</span>
                                <input id="expense_amount"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all placeholder:text-gray-400"
                                    type="number" step="0.01" min="0" v-model.number="expenseForm.amount" required
                                    placeholder="0" />
                            </div>
                            <div v-if="expenseForm.errors.amount" class="text-xs text-primary-500 font-medium mt-1">
                                {{ expenseForm.errors.amount }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="expense_date"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Tanggal <span class="text-primary-500">*</span>
                            </label>
                            <input id="expense_date"
                                class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all"
                                type="date" v-model="expenseForm.date" required />
                            <div v-if="expenseForm.errors.date" class="text-xs text-primary-500 font-medium mt-1">
                                {{ expenseForm.errors.date }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="expense_room" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Kamar (Opsional)
                        </label>
                        <select id="expense_room" v-model="expenseForm.room_id"
                            class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 focus:bg-white dark:focus:bg-gray-700 transition-all">
                            <option value="">Umum (Tidak terkait kamar tertentu)</option>
                            <option v-for="room in rooms.data" :key="room.id" :value="room.id">
                                {{ room.name }} {{ room.number ? `- No. ${room.number}` : '' }}
                            </option>
                        </select>
                        <div v-if="expenseForm.errors.room_id" class="text-xs text-primary-500 font-medium mt-1">
                            {{ expenseForm.errors.room_id }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="expense_receipt"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Bukti Pembayaran (Opsional)
                        </label>
                        <div class="space-y-3">
                            <label for="expense_receipt"
                                class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <div v-if="!receiptPreview" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF (MAX. 2MB)</p>
                                </div>
                                <div v-else class="relative w-full h-full p-4">
                                    <img v-if="receiptPreview && !receiptPreview.endsWith('.pdf')" :src="receiptPreview"
                                        alt="Receipt preview" class="w-full h-full object-contain rounded-lg" />
                                    <div v-else class="flex flex-col items-center justify-center h-full">
                                        <svg class="w-16 h-16 text-primary-500 mb-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ expenseForm.receipt?.name || 'File PDF' }}
                                        </p>
                                    </div>
                                    <button type="button" @click.prevent="removeReceipt"
                                        class="absolute top-2 right-2 p-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors shadow-lg">
                                        <TrashIcon class="w-4 h-4" />
                                    </button>
                                </div>
                                <input id="expense_receipt" type="file" class="hidden" accept="image/*,application/pdf"
                                    @change="handleReceiptChange" />
                            </label>
                        </div>
                        <div v-if="expenseForm.errors.receipt" class="text-xs text-primary-500 font-medium mt-1">
                            {{ expenseForm.errors.receipt }}
                        </div>
                    </div>
                </div>
            </Modal>
            <!-- Assign Tenant Modal -->
            <Modal :show="isAssignTenantModalOpen" title="Tambah Penyewa ke Kamar" confirmText="Tambahkan" maxWidth="2xl"
                @close="closeAssignTenantModal" @confirm="saveAssignTenant">
                <div class="space-y-5">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Kamar Terpilih: <span class="font-bold text-primary-600 dark:text-primary-400">{{ selectedRoomForTenant?.name }} (No. {{ selectedRoomForTenant?.number }})</span>
                        </p>
                    </div>

                    <!-- Toggle Penyewa Lama/Baru -->
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" v-model="assignTenantForm.is_new_tenant" :value="false" class="text-primary-600 focus:ring-primary-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Penyewa Terdaftar</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" v-model="assignTenantForm.is_new_tenant" :value="true" class="text-primary-600 focus:ring-primary-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Buat Akun Baru</span>
                        </label>
                    </div>

                    <!-- Penyewa Lama -->
                    <div v-if="!assignTenantForm.is_new_tenant" class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Pilih Penyewa <span class="text-primary-500">*</span></label>
                        <select v-model="assignTenantForm.tenant_id" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl">
                            <option value="">-- Pilih Penyewa --</option>
                            <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">{{ tenant.name }} ({{ tenant.email }})</option>
                        </select>
                        <div v-if="assignTenantForm.errors.tenant_id" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.tenant_id }}</div>
                    </div>

                    <!-- Penyewa Baru -->
                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-primary-500">*</span></label>
                            <input type="text" v-model="assignTenantForm.name" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl" required>
                            <div v-if="assignTenantForm.errors.name" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.name }}</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Username <span class="text-primary-500">*</span></label>
                            <input type="text" v-model="assignTenantForm.username" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl" required>
                            <div v-if="assignTenantForm.errors.username" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.username }}</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Email <span class="text-primary-500">*</span></label>
                            <input type="email" v-model="assignTenantForm.email" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl" required>
                            <div v-if="assignTenantForm.errors.email" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.email }}</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Password <span class="text-primary-500">*</span></label>
                            <input type="text" v-model="assignTenantForm.password" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl" required placeholder="Minimal 8 karakter">
                            <div v-if="assignTenantForm.errors.password" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.password }}</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">No. HP <span class="text-primary-500">*</span></label>
                            <input type="text" v-model="assignTenantForm.phone" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl" required>
                            <div v-if="assignTenantForm.errors.phone" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.phone }}</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Jenis Kelamin <span class="text-primary-500">*</span></label>
                            <select v-model="assignTenantForm.gender" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <div v-if="assignTenantForm.errors.gender" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.gender }}</div>
                        </div>

                        <!-- Detail Tenant Tambahan -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">No. KTP (NIK)</label>
                            <input type="text" v-model="assignTenantForm.id_card_number" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl">
                            <div v-if="assignTenantForm.errors.id_card_number" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.id_card_number }}</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal Lahir</label>
                            <input type="date" v-model="assignTenantForm.birth_date" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl">
                            <div v-if="assignTenantForm.errors.birth_date" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.birth_date }}</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Kontak Darurat</label>
                            <input type="text" v-model="assignTenantForm.emergency_contact" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl">
                            <div v-if="assignTenantForm.errors.emergency_contact" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.emergency_contact }}</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tempat Kuliah/Kerja</label>
                            <input type="text" v-model="assignTenantForm.tempat_kuliah_kerja" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl">
                            <div v-if="assignTenantForm.errors.tempat_kuliah_kerja" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.tempat_kuliah_kerja }}</div>
                        </div>
                        <div class="space-y-2 sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Alamat</label>
                            <textarea v-model="assignTenantForm.address" rows="2" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl"></textarea>
                            <div v-if="assignTenantForm.errors.address" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.address }}</div>
                        </div>
                    </div>

                    <!-- Room Booking Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Paket Harga <span class="text-primary-500">*</span></label>
                            <select v-model="assignTenantForm.room_price_id" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl" required>
                                <option value="">-- Pilih Paket Harga --</option>
                                <option v-for="price in selectedRoomForTenant?.prices" :key="price.id" :value="price.id">
                                    {{ price.duration }} Bulan - Rp {{ formatCurrency(price.price) }}
                                </option>
                            </select>
                            <div v-if="assignTenantForm.errors.room_price_id" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.room_price_id }}</div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal Check-in <span class="text-primary-500">*</span></label>
                            <input type="date" v-model="assignTenantForm.planned_checkin_date" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl" required>
                            <div v-if="assignTenantForm.errors.planned_checkin_date" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.planned_checkin_date }}</div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="space-y-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" v-model="assignTenantForm.has_payment" class="w-4 h-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Penyewa sudah melakukan pembayaran awal?</span>
                            </label>

                            <div v-if="assignTenantForm.has_payment" class="space-y-2 pl-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Jumlah yang Dibayarkan (Rp) <span class="text-primary-500">*</span></label>
                                <input type="number" v-model.number="assignTenantForm.payment_amount" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl" required>
                                <div v-if="assignTenantForm.errors.payment_amount" class="text-xs text-primary-500 font-medium">{{ assignTenantForm.errors.payment_amount }}</div>
                                <p class="text-xs text-gray-500 mt-1" v-if="assignTenantForm.room_price_id">
                                    Total Harga: Rp {{ formatCurrency(selectedRoomForTenant?.prices?.find(p => p.id === assignTenantForm.room_price_id)?.price || 0) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </Modal>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PlusSquareIcon from "@/Components/icons/PlusSquareIcon.vue";
import SearchIcon from "@/Components/icons/SearchIcon.vue";
import EditIcon from "@/Components/icons/EditIcon.vue";
import TrashIcon from "@/Components/icons/TrashIcon.vue";
import HomeIcon from "@/Components/icons/HomeIcon.vue";
import BackIcon from "@/Components/icons/BackIcon.vue";
import BedIcon from "@/Components/icons/BedIcon.vue";
import PageIcon from "@/Components/icons/PageIcon.vue";
import MoneyIcon from "@/Components/icons/MoneyIcon.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import Modal from "@/Components/common/Modal.vue";
import ConfirmModal from "@/Components/common/ConfirmModal.vue";
import Pagination from "@/Components/common/Pagination.vue";
import { useAuth } from "@/Composables/useAuth";
import { ref, watch } from "vue";
import { useForm, router, Head, Link } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    boardingHouse: Object,
    rooms: Object,
    expenses: Array,
    tenants: Array,
    search: String,
    status: String,
});

const { can, is } = useAuth();

const breadcrumbs = [
    { label: "Properti" },
    { label: "Cluster", path: route("clusters.index") },
    { label: props.boardingHouse?.name || "Detail" },
];

const search = ref(props.search || "");
const statusFilter = ref(props.status || "");
const activeContentTab = ref('rooms'); // Default to rooms tab

const isAssignTenantModalOpen = ref(false);
const selectedRoomForTenant = ref(null);
const assignTenantForm = useForm({
    is_new_tenant: true,
    tenant_id: "",
    name: "",
    username: "",
    email: "",
    password: "",
    phone: "",
    gender: "",
    address: "",
    id_card_number: "",
    birth_date: "",
    emergency_contact: "",
    tempat_kuliah_kerja: "",
    room_price_id: "",
    planned_checkin_date: "",
    has_payment: false,
    payment_amount: "",
});

function openAssignTenantModal(room) {
    selectedRoomForTenant.value = room;
    assignTenantForm.reset();
    isAssignTenantModalOpen.value = true;
}

function closeAssignTenantModal() {
    isAssignTenantModalOpen.value = false;
    selectedRoomForTenant.value = null;
    assignTenantForm.reset();
}

function saveAssignTenant() {
    assignTenantForm.post(route("boarding-houses.rooms.assign-tenant", [props.boardingHouse.id, selectedRoomForTenant.value.id]), {
        preserveScroll: true,
        onSuccess: () => {
            closeAssignTenantModal();
        },
    });
}


let timeout = null;
watch(search, () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        fetchRooms();
    }, 400);
});

watch(statusFilter, () => {
    fetchRooms();
});

function fetchRooms() {
    router.get(
        route("boarding-houses.show", props.boardingHouse.id),
        {
            search: search.value,
            status: statusFilter.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }
    );
}

function formatCurrency(value) {
    if (!value && value !== 0) return '-';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
}

function formatInputCurrency(value) {
    if (!value && value !== 0) return '';
    let numberString = value.toString().replace(/[^0-9]/g, '');
    if (!numberString) return '';
    return new Intl.NumberFormat('id-ID').format(parseInt(numberString, 10));
}

function handlePriceInput(item, event) {
    let rawValue = event.target.value.replace(/[^0-9]/g, '');
    let numValue = rawValue ? parseInt(rawValue, 10) : '';
    item.price = numValue;
    event.target.value = formatInputCurrency(rawValue);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(date);
}

// Room Modal
const availableFacilities = [
    'Wi-Fi', 'Air Conditioning', 'Private Bathroom', 'TV',
    'Furnished', 'Wardrobe', 'Desk', 'Window'
];

const isModalOpen = ref(false);
const isBatchModalOpen = ref(false);
const isEditMode = ref(false);
const form = useForm({
    id: null,
    number: "",
    description: "",
    capacity: 1,
    facilities: [],
});

const batchForm = useForm({
    count: 1,
    start_number: null,
    capacity: 1,
    description: "",
    facilities: [],
    prices: [],
});

function openAddModal() {
    if (!can("rooms.create")) {
        return;
    }
    form.reset();
    form.capacity = 1;
    form.facilities = [];
    isEditMode.value = false;
    isModalOpen.value = true;
}

function toggleFacility(facility) {
    if (!form.facilities) form.facilities = [];

    if (form.facilities.includes(facility)) {
        form.facilities = form.facilities.filter(f => f !== facility);
    } else {
        form.facilities.push(facility);
    }
}

function openBatchModal() {
    if (!can("rooms.create")) {
        return;
    }
    batchForm.reset();
    batchForm.count = 1;
    batchForm.capacity = 1;
    batchForm.facilities = [];
    batchForm.prices = [];
    isBatchModalOpen.value = true;
}

function toggleBatchFacility(facility) {
    if (!batchForm.facilities) batchForm.facilities = [];

    if (batchForm.facilities.includes(facility)) {
        batchForm.facilities = batchForm.facilities.filter(f => f !== facility);
    } else {
        batchForm.facilities.push(facility);
    }
}

function closeBatchModal() {
    isBatchModalOpen.value = false;
    batchForm.reset();
    batchForm.clearErrors();
}

function addBatchPriceItem() {
    const existingDurations = batchForm.prices.map(p => p.duration);
    let newDuration = 1;
    while (existingDurations.includes(newDuration)) {
        newDuration++;
    }

    batchForm.prices.push({
        duration: newDuration,
        price: 0,
    });

    batchForm.prices.sort((a, b) => a.duration - b.duration);
}

function removeBatchPriceItem(index) {
    batchForm.prices.splice(index, 1);
}

function saveBatchRoom() {
    if (!can("rooms.create")) {
        return;
    }

    let transformedForm = batchForm.transform((data) => ({
        ...data,
        facilities: data.facilities && data.facilities.length > 0 ? data.facilities : null,
    }));

    transformedForm.post(route("boarding-houses.rooms.batch-store", props.boardingHouse.id), {
        onSuccess: () => {
            closeBatchModal();
            router.reload({ only: ['rooms'] });
        },
    });
}

function openEditModal(room) {
    if (!can("rooms.edit")) {
        return;
    }
    form.id = room.id;
    form.number = room.number || "";
    form.description = room.description || "";
    form.capacity = room.capacity || 1;
    form.facilities = Array.isArray(room.facilities) ? room.facilities : (room.facilities ? [room.facilities] : []);
    selectedItem.value = room;
    isEditMode.value = true;
    isModalOpen.value = true;
}

function closeModal() {
    isModalOpen.value = false;
    selectedItem.value = null;
    form.reset();
    form.clearErrors();
}

function saveRoom() {
    if (isEditMode.value) {
        if (!can("rooms.edit")) {
            return;
        }
        form.put(route("boarding-houses.rooms.update", [props.boardingHouse.id, form.id]), {
            onSuccess: closeModal,
        });
    } else {
        if (!can("rooms.create")) {
            return;
        }
        form.post(route("boarding-houses.rooms.store", props.boardingHouse.id), {
            onSuccess: closeModal,
        });
    }
}

const selectedItem = ref(null);
const isConfirmModalOpen = ref(false);
const openConfirmModal = (item) => {
    if (!can("rooms.delete")) {
        return;
    }
    selectedItem.value = item;
    isConfirmModalOpen.value = true;
};
const closeConfirmModal = () => {
    selectedItem.value = null;
    isConfirmModalOpen.value = false;
};
const destroyData = () => {
    if (!can("rooms.delete")) {
        return;
    }
    router.delete(route("boarding-houses.rooms.destroy", [props.boardingHouse.id, selectedItem.value.id]), {
        onSuccess: () => {
            closeConfirmModal();
        },
        preserveScroll: true,
    });
};

// Boarding House Modal (Edit/Delete)
const isBoardingHouseModalOpen = ref(false);
const isBoardingHouseEditMode = ref(false);
const boardingHouseForm = useForm({
    id: null,
    owner_id: "",
    thumbnail: null,
    name: "",
    description: "",
    address: "",
    phone: "",
    latitude: "",
    longitude: "",
    status: "active",
});

// Cancel Booking
const isCancelBookingModalOpen = ref(false);
const selectedRoomForCancel = ref(null);

function openCancelBookingModal(room) {
    selectedRoomForCancel.value = room;
    isCancelBookingModalOpen.value = true;
}

function closeCancelBookingModal() {
    isCancelBookingModalOpen.value = false;
    selectedRoomForCancel.value = null;
}

function confirmCancelBooking() {
    if (!selectedRoomForCancel.value) return;

    router.post(route('boarding-houses.rooms.cancel-booking', [props.boardingHouse.id, selectedRoomForCancel.value.id]), {}, {
        onSuccess: () => {
            closeCancelBookingModal();
            router.reload({ only: ['rooms', 'boardingHouse', 'expenses'] });
        },
    });
}

function openEditBoardingHouseModal() {
    if (!can("boarding_houses.edit")) {
        return;
    }
    // Navigate to edit page
    router.visit(route("boarding-houses.edit", props.boardingHouse.id));
}

const isConfirmBoardingHouseModalOpen = ref(false);
const openConfirmBoardingHouseModal = () => {
    if (!can("boarding_houses.delete")) {
        return;
    }
    isConfirmBoardingHouseModalOpen.value = true;
};
const closeConfirmBoardingHouseModal = () => {
    isConfirmBoardingHouseModalOpen.value = false;
};
const destroyBoardingHouse = () => {
    if (!can("boarding_houses.delete")) {
        return;
    }
    router.delete(route("boarding-houses.destroy", props.boardingHouse.id), {
        onSuccess: () => {
            router.visit(route("clusters.index"));
        },
    });
};

// Gallery Modal
const isGalleryModalOpen = ref(false);
const galleryPreviews = ref([]);
const galleryForm = useForm({
    images: [],
});

function openGalleryModal() {
    if (!can("boarding_houses.edit")) {
        return;
    }
    galleryForm.images = [];
    galleryPreviews.value = [];
    galleryForm.clearErrors();
    isGalleryModalOpen.value = true;
}

function closeGalleryModal() {
    isGalleryModalOpen.value = false;
    galleryForm.images = [];
    galleryPreviews.value = [];
    galleryForm.clearErrors();
}

function handleGalleryImagesChange(event) {
    const files = Array.from(event.target.files || []);

    if (files.length === 0) return;

    // Validate max 10 images
    if (galleryForm.images.length + files.length > 10) {
        alert('Maksimal 10 foto dapat diupload sekaligus');
        return;
    }

    // Validate file size (2MB max)
    const invalidFiles = files.filter(file => file.size > 2 * 1024 * 1024);
    if (invalidFiles.length > 0) {
        alert('Beberapa foto melebihi ukuran maksimal 2MB');
        return;
    }

    // Add files to form
    galleryForm.images = [...galleryForm.images, ...files];

    // Create previews
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => {
            galleryPreviews.value.push(e.target.result);
        };
        reader.readAsDataURL(file);
    });
}

function removeGalleryPreview(index) {
    galleryForm.images.splice(index, 1);
    galleryPreviews.value.splice(index, 1);
}

function uploadImages() {
    if (!can("boarding_houses.edit") || galleryForm.images.length === 0) {
        return;
    }

    // Inertia form automatically handles FormData for file uploads
    galleryForm.post(route("boarding-houses.images.store", props.boardingHouse.id), {
        onSuccess: () => {
            closeGalleryModal();
            router.reload({ only: ['boardingHouse'] });
        },
        onError: () => {
            // Errors are automatically handled by form.errors
        },
    });
}

function deleteImage(image) {
    if (!can("boarding_houses.edit")) {
        return;
    }

    if (!confirm('Yakin ingin menghapus foto ini?')) {
        return;
    }

    router.delete(route("boarding-houses.images.destroy", [props.boardingHouse.id, image.id]), {
        onSuccess: () => {
            router.reload({ only: ['boardingHouse'] });
        },
        preserveScroll: true,
    });
}

// Expense Modal
const isExpenseModalOpen = ref(false);
const isExpenseEditMode = ref(false);
const receiptPreview = ref(null);
const expenseForm = useForm({
    id: null,
    description: '',
    amount: 0,
    date: new Date().toISOString().split('T')[0],
    room_id: '',
    receipt: null,
});

function openExpenseModal() {
    expenseForm.reset();
    expenseForm.description = '';
    expenseForm.amount = 0;
    expenseForm.date = new Date().toISOString().split('T')[0];
    expenseForm.room_id = '';
    expenseForm.receipt = null;
    receiptPreview.value = null;
    isExpenseEditMode.value = false;
    isExpenseModalOpen.value = true;
}

function editExpense(expense) {
    expenseForm.id = expense.id;
    expenseForm.description = expense.description;
    expenseForm.amount = expense.amount;
    expenseForm.date = expense.date;
    expenseForm.room_id = expense.room_id || '';
    expenseForm.receipt = null;
    receiptPreview.value = expense.receipt_path ? `/storage/${expense.receipt_path}` : null;
    isExpenseEditMode.value = true;
    isExpenseModalOpen.value = true;
}

function closeExpenseModal() {
    isExpenseModalOpen.value = false;
    expenseForm.reset();
    expenseForm.clearErrors();
    receiptPreview.value = null;
}

function saveExpense() {
    if (isExpenseEditMode.value) {
        // Update existing expense
        expenseForm.put(route('boarding-houses.expenses.update', [props.boardingHouse.id, expenseForm.id]), {
            onSuccess: () => {
                closeExpenseModal();
                router.reload({ only: ['expenses'] });
            },
        });
    } else {
        // Create new expense
        expenseForm.post(route('boarding-houses.expenses.store', props.boardingHouse.id), {
            onSuccess: () => {
                closeExpenseModal();
                router.reload({ only: ['expenses'] });
            },
        });
    }
}

function handleReceiptChange(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            event.target.value = '';
            return;
        }

        expenseForm.receipt = file;

        // Create preview for images, not for PDFs
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                receiptPreview.value = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            receiptPreview.value = 'file.pdf'; // Placeholder for PDF
        }
    }
}

function removeReceipt() {
    expenseForm.receipt = null;
    receiptPreview.value = null;
    // Reset file input
    const fileInput = document.getElementById('expense_receipt');
    if (fileInput) {
        fileInput.value = '';
    }
}

function deleteExpense(expense) {
    if (!confirm('Yakin ingin menghapus pengeluaran ini?')) {
        return;
    }

    router.delete(route('boarding-houses.expenses.destroy', [props.boardingHouse.id, expense.id]), {
        onSuccess: () => {
            router.reload({ only: ['expenses'] });
        },
        preserveScroll: true,
    });
}

</script>
