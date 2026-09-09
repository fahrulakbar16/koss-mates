<template>
    <Head title="Manajemen Transaksi" />

    <div class="flex flex-col gap-6 px-4 sm:px-6 lg:px-8 py-8 h-full">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Transaksi</h1>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                    Kelola pemasukan dan pengeluaran operasional
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <button @click="openIncomeModal"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-200 dark:shadow-none transition-all duration-300 transform hover:-translate-y-0.5">
                    <PlusIcon class="w-5 h-5 text-white" />
                    Tambah Pemasukan
                </button>
                <button @click="openExpenseModal"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-200 dark:shadow-none transition-all duration-300 transform hover:-translate-y-0.5">
                    <PlusIcon class="w-5 h-5 text-white" />
                    Tambah Pengeluaran
                </button>
            </div>
        </div>

        <div class="h-full flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <!-- Filter & Search Bar -->
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col items-start lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full lg:w-auto">
                    <div class="relative w-full lg:w-64">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <SearchIcon class="w-5 h-5 text-gray-400" />
                        </div>
                        <input v-model="search" type="text" placeholder="Cari deskripsi atau ref..."
                            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 placeholder:text-gray-400 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500" />
                    </div>
                    <select v-model="typeFilter"
                        class="h-10 w-full sm:w-auto rounded-xl border-gray-200 bg-gray-50 text-sm text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300 dark:bg-gray-900/50 dark:border-gray-600 dark:text-white dark:focus:border-primary-500">
                        <option value="">Semua Tipe</option>
                        <option value="payment">Pembayaran Sewa</option>
                        <option value="income">Pemasukan Manual</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                </div>

                <!-- Date Filters -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full lg:w-auto">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
                        <div class="flex items-center gap-2">
                            <CalendarIcon class="w-4 h-4 text-gray-400 flex-shrink-0" />
                            <input v-model="startDateFilter" type="date" class="h-10 flex-1 sm:flex-none rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white text-sm focus:border-primary-500 focus:ring-primary-500 sm:w-40" />
                        </div>
                        <div class="flex items-center gap-2">
                            <CalendarIcon class="w-4 h-4 text-gray-400 flex-shrink-0" />
                            <input v-model="endDateFilter" type="date" class="h-10 flex-1 sm:flex-none rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-900/50 dark:text-white text-sm focus:border-primary-500 focus:ring-primary-500 sm:w-40" />
                        </div>
                    </div>
                    <button v-if="startDateFilter || endDateFilter || search || typeFilter" @click="resetFilters" class="text-xs text-primary-500 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-bold flex items-center gap-1 transition-colors">
                        Reset
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="overflow-x-auto pb-8 flex-1">
                <div v-if="transactions.data && transactions.data.length > 0">
                    <table class="w-full text-left border-collapse min-w-[1100px]">
                        <thead class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-center w-16">
                                    No
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Tanggal
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Deskripsi
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Properti / Kamar
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Tipe
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-right">
                                    Jumlah
                                </th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap text-center">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                            <tr v-for="(item, index) in transactions.data" :key="item.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-5 whitespace-nowrap text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ (transactions.current_page - 1) * transactions.per_page + index + 1 }}
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ formatDate(item.transaction_date) }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ item.description }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-mono">
                                        {{ item.reference_number }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ item.boarding_house?.name || '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ item.room?.room_number ? 'Kamar ' + item.room.room_number : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span v-if="item.type === 'payment'" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        Pembayaran Sewa
                                    </span>
                                    <span v-else-if="item.type === 'income'" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        Pemasukan Manual
                                    </span>
                                    <span v-else-if="item.type === 'expense'" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                                        Pengeluaran
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <span :class="[
                                        'text-sm font-bold',
                                        item.type === 'expense' ? 'text-primary-600 dark:text-primary-400' : 'text-emerald-600 dark:text-emerald-400'
                                    ]">
                                        {{ item.type === 'expense' ? '-' : '+' }} {{ formatCurrency(Math.abs(item.amount)) }}
                                    </span>
                                </td>
                                <!-- Action Buttons -->
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    <div v-if="item.type === 'income' || item.type === 'expense'" class="flex items-center justify-center gap-2">
                                        <button @click="openEditModal(item)"
                                            class="p-2 rounded-lg text-gray-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:text-primary-400 dark:hover:bg-primary-900/30 transition-all duration-200"
                                            title="Edit transaksi">
                                            <EditIcon class="w-4 h-4" />
                                        </button>
                                        <button @click="openDeleteModal(item)"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:text-red-400 dark:hover:bg-red-900/30 transition-all duration-200"
                                            title="Hapus transaksi">
                                            <TrashIcon class="w-4 h-4" />
                                        </button>
                                    </div>
                                    <span v-else class="text-xs text-gray-300 dark:text-gray-600">—</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-else
                    class="text-center py-20 bg-gray-50/50 dark:bg-gray-800/50 rounded-2xl border-dashed border border-gray-200 dark:border-gray-700 mx-4 my-6">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <SearchIcon class="h-10 w-10 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tidak ada transaksi ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                        Coba sesuaikan filter atau cari dengan kata kunci lain.
                    </p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="transactions.data && transactions.data.length > 0" class="border-t border-gray-100 dark:border-gray-700 p-4">
                <Pagination :pagination="transactions" />
            </div>
        </div>

        <!-- Income Modal (Add) -->
        <Modal :show="showIncomeModal" @close="closeIncomeModal" max-width="2xl">
            <template #header>
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-primary-50 dark:bg-primary-900/30 rounded-2xl">
                        <PlusIcon class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">Tambah Pemasukan Manual</h3>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Catat pemasukan tambahan di luar biaya sewa</p>
                    </div>
                </div>
            </template>

            <form id="incomeForm" @submit.prevent="submitIncome" class="space-y-4">
                <!-- Property & Room Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50/50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-800 transition-all hover:bg-white dark:hover:bg-gray-900 shadow-sm hover:shadow-md">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Target Properti</label>
                        <select v-model="incomeForm.boarding_house_id" required
                            class="w-full h-[58px] px-5 py-4 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 focus:border-primary-500 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none appearance-none">
                            <option value="" disabled>Pilih Properti</option>
                            <option v-for="bh in boardingHouses" :key="bh.id" :value="bh.id">{{ bh.name }}</option>
                        </select>
                        <div v-if="incomeForm.errors.boarding_house_id" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ incomeForm.errors.boarding_house_id }}</div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Kamar (Opsional)</label>
                        <select v-model="incomeForm.room_id"
                            class="w-full h-[58px] px-5 py-4 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 focus:border-primary-500 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none appearance-none"
                            :disabled="!incomeForm.boarding_house_id">
                            <option value="">Semua Kamar</option>
                            <option v-for="room in availableIncomeRooms" :key="room.id" :value="room.id">{{ room.name }}</option>
                        </select>
                        <p v-if="!incomeForm.boarding_house_id" class="text-xs text-gray-400 mt-2 px-1">Pilih properti terlebih dahulu</p>
                        <div v-if="incomeForm.errors.room_id" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ incomeForm.errors.room_id }}</div>
                    </div>
                </div>

                <!-- Amount & Date Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Jumlah Pemasukan</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-lg font-bold text-gray-400 group-focus-within:text-primary-500 transition-colors">Rp</span>
                            </div>
                            <input v-model="formattedIncomeAmount" type="text"
                                class="w-full h-[58px] pl-14 pr-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none"
                                placeholder="0" required />
                        </div>
                        <div v-if="incomeForm.errors.amount" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ incomeForm.errors.amount }}</div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Tanggal Transaksi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <CalendarIcon class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" />
                            </div>
                            <input v-model="incomeForm.transaction_date" type="date"
                                class="w-full h-[58px] pl-14 pr-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none"
                                required />
                        </div>
                        <div v-if="incomeForm.errors.transaction_date" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ incomeForm.errors.transaction_date }}</div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Metode Pembayaran</label>
                    <div class="grid grid-cols-3 gap-4">
                        <button v-for="method in [
                            { id: 'cash', name: 'Tunai', icon: MoneyIcon },
                            { id: 'transfer', name: 'Transfer', icon: HomeIcon },
                            { id: 'other', name: 'Lainnya', icon: GridIcon }
                        ]" :key="method.id" type="button" @click="incomeForm.payment_method = method.id"
                            :class="[
                                'px-4 py-4 rounded-2xl border-2 font-black transition-all flex flex-col items-center justify-center gap-2 transform active:scale-95',
                                incomeForm.payment_method === method.id
                                    ? 'bg-primary-50 border-primary-500 text-primary-700 dark:bg-primary-900/30 dark:border-primary-500 dark:text-primary-400 shadow-xl shadow-primary-500/10'
                                    : 'bg-white border-gray-100 text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400'
                            ]">
                            <component :is="method.icon" class="w-7 h-7" />
                            <span class="text-[10px] uppercase tracking-[0.15em] font-black">{{ method.name }}</span>
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Deskripsi / Catatan</label>
                    <textarea v-model="incomeForm.description"
                        class="w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none resize-none"
                        rows="3" placeholder="Contoh: Denda keterlambatan sewa, penjualan sampah, dll." required></textarea>
                    <div v-if="incomeForm.errors.description" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ incomeForm.errors.description }}</div>
                </div>
            </form>

            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button type="button" @click="closeIncomeModal"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        Batal
                    </button>
                    <button type="submit" form="incomeForm"
                        class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-500/30 dark:shadow-none transition-all flex items-center gap-2 transform active:scale-95 disabled:grayscale disabled:scale-100"
                        :disabled="incomeForm.processing">
                        <template v-if="incomeForm.processing">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </template>
                        <template v-else>
                            Simpan Pemasukan
                        </template>
                    </button>
                </div>
            </template>
        </Modal>

        <!-- Expense Modal (Add) -->
        <Modal :show="showExpenseModal" @close="closeExpenseModal" max-width="2xl">
            <template #header>
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-primary-50 dark:bg-primary-900/30 rounded-2xl">
                        <PlusIcon class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">Tambah Pengeluaran</h3>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Catat pengeluaran operasional properti</p>
                    </div>
                </div>
            </template>

            <form id="expenseForm" @submit.prevent="submitExpense" class="space-y-4">
                <!-- Property & Room Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50/50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-800 transition-all hover:bg-white dark:hover:bg-gray-900 shadow-sm hover:shadow-md">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Target Properti</label>
                        <select v-model="expenseForm.boarding_house_id" required
                            class="w-full h-[58px] px-5 py-4 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 focus:border-primary-500 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none appearance-none">
                            <option value="" disabled>Pilih Properti</option>
                            <option v-for="bh in boardingHouses" :key="bh.id" :value="bh.id">{{ bh.name }}</option>
                        </select>
                        <div v-if="expenseForm.errors.boarding_house_id" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ expenseForm.errors.boarding_house_id }}</div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Kamar (Opsional)</label>
                        <select v-model="expenseForm.room_id"
                            class="w-full h-[58px] px-5 py-4 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 focus:border-primary-500 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none appearance-none"
                            :disabled="!expenseForm.boarding_house_id">
                            <option value="">Semua Kamar</option>
                            <option v-for="room in availableExpenseRooms" :key="room.id" :value="room.id">Kamar {{ room.name }}</option>
                        </select>
                        <p v-if="!expenseForm.boarding_house_id" class="text-xs text-gray-400 mt-2 px-1">Pilih properti terlebih dahulu</p>
                        <div v-if="expenseForm.errors.room_id" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ expenseForm.errors.room_id }}</div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Kategori Pengeluaran</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <button v-for="cat in [
                            { id: 'utility', name: 'Listrik/Air', icon: GridIcon },
                            { id: 'maintenance', name: 'Perawatan', icon: SettingIcon },
                            { id: 'salary', name: 'Gaji', icon: UserGroupIcon },
                            { id: 'supplies', name: 'Alat Kantor', icon: BriefCase },
                            { id: 'legal', name: 'Pajak', icon: DocsIcon },
                            { id: 'other', name: 'Lainnya', icon: GridIcon }
                        ]" :key="cat.id" type="button" @click="expenseForm.category = cat.id"
                            :class="[
                                'px-4 py-4 rounded-2xl border-2 font-black transition-all flex items-center gap-3 transform active:scale-95',
                                expenseForm.category === cat.id
                                    ? 'bg-primary-50 border-primary-500 text-primary-700 dark:bg-primary-900/30 dark:border-primary-500 dark:text-primary-400 shadow-xl shadow-primary-500/10'
                                    : 'bg-white border-gray-100 text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400'
                            ]">
                            <component :is="cat.icon" class="w-5 h-5 transition-transform group-hover:scale-110" />
                            <span class="text-[10px] uppercase tracking-wider">{{ cat.name }}</span>
                        </button>
                    </div>
                    <div v-if="expenseForm.errors.category" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ expenseForm.errors.category }}</div>
                </div>

                <!-- Amount & Date Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Jumlah Pengeluaran</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-lg font-bold text-gray-400 group-focus-within:text-primary-500 transition-colors">Rp</span>
                            </div>
                            <input v-model="formattedExpenseAmount" type="text"
                                class="w-full h-[58px] pl-14 pr-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none"
                                placeholder="0" required />
                        </div>
                        <div v-if="expenseForm.errors.amount" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ expenseForm.errors.amount }}</div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Tanggal Pengeluaran</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <CalendarIcon class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" />
                            </div>
                            <input v-model="expenseForm.expense_date" type="date"
                                class="w-full h-[58px] pl-14 pr-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none"
                                required />
                        </div>
                        <div v-if="expenseForm.errors.expense_date" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ expenseForm.errors.expense_date }}</div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Deskripsi / Catatan</label>
                    <textarea v-model="expenseForm.description"
                        class="w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none resize-none"
                        rows="3" placeholder="Contoh: Perbaikan keran bocor, bayar internet, dll." required></textarea>
                    <div v-if="expenseForm.errors.description" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ expenseForm.errors.description }}</div>
                </div>
            </form>

            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button type="button" @click="closeExpenseModal"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        Batal
                    </button>
                    <button type="submit" form="expenseForm"
                        class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-500/30 dark:shadow-none transition-all flex items-center gap-2 transform active:scale-95 disabled:grayscale disabled:scale-100"
                        :disabled="expenseForm.processing">
                        <template v-if="expenseForm.processing">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </template>
                        <template v-else>
                            Simpan Pengeluaran
                        </template>
                    </button>
                </div>
            </template>
        </Modal>

        <!-- Edit Income Modal -->
        <Modal :show="showEditIncomeModal" @close="closeEditIncomeModal" max-width="2xl">
            <template #header>
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl">
                        <EditIcon class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">Edit Pemasukan</h3>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Perbarui data pemasukan manual</p>
                    </div>
                </div>
            </template>

            <form id="editIncomeForm" @submit.prevent="submitEditIncome" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50/50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Target Properti</label>
                        <select v-model="editIncomeForm.boarding_house_id" required
                            class="w-full h-[58px] px-5 py-4 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 focus:border-primary-500 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none appearance-none">
                            <option value="" disabled>Pilih Properti</option>
                            <option v-for="bh in boardingHouses" :key="bh.id" :value="bh.id">{{ bh.name }}</option>
                        </select>
                        <div v-if="editIncomeForm.errors.boarding_house_id" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editIncomeForm.errors.boarding_house_id }}</div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Kamar (Opsional)</label>
                        <select v-model="editIncomeForm.room_id"
                            class="w-full h-[58px] px-5 py-4 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 focus:border-primary-500 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none appearance-none"
                            :disabled="!editIncomeForm.boarding_house_id">
                            <option value="">Semua Kamar</option>
                            <option v-for="room in availableEditIncomeRooms" :key="room.id" :value="room.id">{{ room.name }}</option>
                        </select>
                        <div v-if="editIncomeForm.errors.room_id" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editIncomeForm.errors.room_id }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Jumlah Pemasukan</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-lg font-bold text-gray-400 group-focus-within:text-primary-500 transition-colors">Rp</span>
                            </div>
                            <input v-model="formattedEditIncomeAmount" type="text"
                                class="w-full h-[58px] pl-14 pr-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none"
                                placeholder="0" required />
                        </div>
                        <div v-if="editIncomeForm.errors.amount" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editIncomeForm.errors.amount }}</div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Tanggal Transaksi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <CalendarIcon class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" />
                            </div>
                            <input v-model="editIncomeForm.transaction_date" type="date"
                                class="w-full h-[58px] pl-14 pr-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none"
                                required />
                        </div>
                        <div v-if="editIncomeForm.errors.transaction_date" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editIncomeForm.errors.transaction_date }}</div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Metode Pembayaran</label>
                    <div class="grid grid-cols-3 gap-4">
                        <button v-for="method in [
                            { id: 'cash', name: 'Tunai', icon: MoneyIcon },
                            { id: 'transfer', name: 'Transfer', icon: HomeIcon },
                            { id: 'other', name: 'Lainnya', icon: GridIcon }
                        ]" :key="method.id" type="button" @click="editIncomeForm.payment_method = method.id"
                            :class="[
                                'px-4 py-4 rounded-2xl border-2 font-black transition-all flex flex-col items-center justify-center gap-2 transform active:scale-95',
                                editIncomeForm.payment_method === method.id
                                    ? 'bg-primary-50 border-primary-500 text-primary-700 dark:bg-primary-900/30 dark:border-primary-500 dark:text-primary-400 shadow-xl shadow-primary-500/10'
                                    : 'bg-white border-gray-100 text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400'
                            ]">
                            <component :is="method.icon" class="w-7 h-7" />
                            <span class="text-[10px] uppercase tracking-[0.15em] font-black">{{ method.name }}</span>
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Deskripsi / Catatan</label>
                    <textarea v-model="editIncomeForm.description"
                        class="w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none resize-none"
                        rows="3" required></textarea>
                    <div v-if="editIncomeForm.errors.description" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editIncomeForm.errors.description }}</div>
                </div>
            </form>

            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button type="button" @click="closeEditIncomeModal"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        Batal
                    </button>
                    <button type="submit" form="editIncomeForm"
                        class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-emerald-500/30 dark:shadow-none transition-all flex items-center gap-2 transform active:scale-95 disabled:grayscale disabled:scale-100"
                        :disabled="editIncomeForm.processing">
                        <template v-if="editIncomeForm.processing">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </template>
                        <template v-else>
                            Simpan Perubahan
                        </template>
                    </button>
                </div>
            </template>
        </Modal>

        <!-- Edit Expense Modal -->
        <Modal :show="showEditExpenseModal" @close="closeEditExpenseModal" max-width="2xl">
            <template #header>
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-primary-50 dark:bg-primary-900/30 rounded-2xl">
                        <EditIcon class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">Edit Pengeluaran</h3>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Perbarui data pengeluaran</p>
                    </div>
                </div>
            </template>

            <form id="editExpenseForm" @submit.prevent="submitEditExpense" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50/50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Target Properti</label>
                        <select v-model="editExpenseForm.boarding_house_id" required
                            class="w-full h-[58px] px-5 py-4 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 focus:border-primary-500 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none appearance-none">
                            <option value="" disabled>Pilih Properti</option>
                            <option v-for="bh in boardingHouses" :key="bh.id" :value="bh.id">{{ bh.name }}</option>
                        </select>
                        <div v-if="editExpenseForm.errors.boarding_house_id" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editExpenseForm.errors.boarding_house_id }}</div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Kamar (Opsional)</label>
                        <select v-model="editExpenseForm.room_id"
                            class="w-full h-[58px] px-5 py-4 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 focus:border-primary-500 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none appearance-none"
                            :disabled="!editExpenseForm.boarding_house_id">
                            <option value="">Semua Kamar</option>
                            <option v-for="room in availableEditExpenseRooms" :key="room.id" :value="room.id">Kamar {{ room.name }}</option>
                        </select>
                        <div v-if="editExpenseForm.errors.room_id" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editExpenseForm.errors.room_id }}</div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Kategori Pengeluaran</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <button v-for="cat in [
                            { id: 'utility', name: 'Listrik/Air', icon: GridIcon },
                            { id: 'maintenance', name: 'Perawatan', icon: SettingIcon },
                            { id: 'salary', name: 'Gaji', icon: UserGroupIcon },
                            { id: 'supplies', name: 'Alat Kantor', icon: BriefCase },
                            { id: 'legal', name: 'Pajak', icon: DocsIcon },
                            { id: 'other', name: 'Lainnya', icon: GridIcon }
                        ]" :key="cat.id" type="button" @click="editExpenseForm.category = cat.id"
                            :class="[
                                'px-4 py-4 rounded-2xl border-2 font-black transition-all flex items-center gap-3 transform active:scale-95',
                                editExpenseForm.category === cat.id
                                    ? 'bg-primary-50 border-primary-500 text-primary-700 dark:bg-primary-900/30 dark:border-primary-500 dark:text-primary-400 shadow-xl shadow-primary-500/10'
                                    : 'bg-white border-gray-100 text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400'
                            ]">
                            <component :is="cat.icon" class="w-5 h-5" />
                            <span class="text-[10px] uppercase tracking-wider">{{ cat.name }}</span>
                        </button>
                    </div>
                    <div v-if="editExpenseForm.errors.category" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editExpenseForm.errors.category }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Jumlah Pengeluaran</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-lg font-bold text-gray-400 group-focus-within:text-primary-500 transition-colors">Rp</span>
                            </div>
                            <input v-model="formattedEditExpenseAmount" type="text"
                                class="w-full h-[58px] pl-14 pr-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none"
                                placeholder="0" required />
                        </div>
                        <div v-if="editExpenseForm.errors.amount" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editExpenseForm.errors.amount }}</div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Tanggal Pengeluaran</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <CalendarIcon class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" />
                            </div>
                            <input v-model="editExpenseForm.expense_date" type="date"
                                class="w-full h-[58px] pl-14 pr-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none"
                                required />
                        </div>
                        <div v-if="editExpenseForm.errors.expense_date" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editExpenseForm.errors.expense_date }}</div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Deskripsi / Catatan</label>
                    <textarea v-model="editExpenseForm.description"
                        class="w-full px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-2 border-transparent focus:border-primary-500 focus:bg-white dark:focus:bg-gray-900 rounded-2xl text-lg font-bold text-gray-700 dark:text-white transition-all outline-none resize-none"
                        rows="3" required></textarea>
                    <div v-if="editExpenseForm.errors.description" class="text-primary-500 text-xs mt-1 font-medium ml-1">{{ editExpenseForm.errors.description }}</div>
                </div>
            </form>

            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button type="button" @click="closeEditExpenseModal"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        Batal
                    </button>
                    <button type="submit" form="editExpenseForm"
                        class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-500/30 dark:shadow-none transition-all flex items-center gap-2 transform active:scale-95 disabled:grayscale disabled:scale-100"
                        :disabled="editExpenseForm.processing">
                        <template v-if="editExpenseForm.processing">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </template>
                        <template v-else>
                            Simpan Perubahan
                        </template>
                    </button>
                </div>
            </template>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal" max-width="md">
            <template #header>
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-50 dark:bg-red-900/30 rounded-2xl">
                        <TrashIcon class="w-6 h-6 text-red-600 dark:text-red-400" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">Hapus Transaksi</h3>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </template>

            <div class="py-2">
                <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-2xl border border-red-100 dark:border-red-800 mb-4">
                    <p class="text-sm font-bold text-red-700 dark:text-red-400 mb-1">⚠️ Perhatian!</p>
                    <p class="text-sm text-red-600 dark:text-red-300">Data transaksi yang dihapus tidak dapat dipulihkan kembali.</p>
                </div>
                <div v-if="selectedItem" class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ selectedItem.description }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</span>
                        <span class="text-sm font-bold" :class="selectedItem.type === 'expense' ? 'text-primary-600' : 'text-emerald-600'">
                            {{ formatCurrency(Math.abs(selectedItem.amount)) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</span>
                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ formatDate(selectedItem.transaction_date) }}</span>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button type="button" @click="closeDeleteModal"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        Batal
                    </button>
                    <button type="button" @click="confirmDelete"
                        :disabled="isDeleting"
                        class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 dark:shadow-none transition-all flex items-center gap-2 transform active:scale-95 disabled:grayscale disabled:scale-100">
                        <template v-if="isDeleting">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menghapus...
                        </template>
                        <template v-else>
                            <TrashIcon class="w-4 h-4" />
                            Ya, Hapus
                        </template>
                    </button>
                </div>
            </template>
        </Modal>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/common/Pagination.vue";
import Modal from "@/Components/common/Modal.vue";
import SearchIcon from "@/Components/icons/SearchIcon.vue";
import CalendarIcon from "@/Components/icons/CalendarIcon.vue";
import PlusIcon from "@/Components/icons/PlusIcon.vue";
import EditIcon from "@/Components/icons/EditIcon.vue";
import TrashIcon from "@/Components/icons/TrashIcon.vue";
import MoneyIcon from "@/Components/icons/MoneyIcon.vue";
import HomeIcon from "@/Components/icons/HomeIcon.vue";
import GridIcon from "@/Components/icons/GridIcon.vue";
import SettingIcon from "@/Components/icons/SettingIcon.vue";
import UserGroupIcon from "@/Components/icons/UserGroupIcon.vue";
import BriefCase from "@/Components/icons/BriefCase.vue";
import DocsIcon from "@/Components/icons/DocsIcon.vue";
import { ref, watch, computed } from "vue";
import { router, Head, useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import 'dayjs/locale/id';

dayjs.locale('id');

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    transactions: Object,
    boardingHouses: Array,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const typeFilter = ref(props.filters?.type || "");
const startDateFilter = ref(props.filters?.start_date || "");
const endDateFilter = ref(props.filters?.end_date || "");

// ─── Modal State ──────────────────────────────────────────────────────────────
const showIncomeModal = ref(false);
const showExpenseModal = ref(false);
const showEditIncomeModal = ref(false);
const showEditExpenseModal = ref(false);
const showDeleteModal = ref(false);

const selectedItem = ref(null);
const isDeleting = ref(false);

// ─── Add Income Form ──────────────────────────────────────────────────────────
const incomeForm = useForm({
    boarding_house_id: '',
    room_id: '',
    amount: '',
    transaction_date: dayjs().format('YYYY-MM-DD'),
    description: '',
    payment_method: 'cash',
});

// ─── Add Expense Form ─────────────────────────────────────────────────────────
const expenseForm = useForm({
    boarding_house_id: '',
    room_id: '',
    amount: '',
    expense_date: dayjs().format('YYYY-MM-DD'),
    description: '',
    category: 'maintenance',
});

// ─── Edit Income Form ─────────────────────────────────────────────────────────
const editIncomeForm = useForm({
    boarding_house_id: '',
    room_id: '',
    amount: '',
    transaction_date: '',
    description: '',
    payment_method: 'cash',
});

// ─── Edit Expense Form ────────────────────────────────────────────────────────
const editExpenseForm = useForm({
    boarding_house_id: '',
    room_id: '',
    amount: '',
    expense_date: '',
    description: '',
    category: 'maintenance',
});

// ─── Computed: Available Rooms ────────────────────────────────────────────────
const availableIncomeRooms = computed(() => {
    const bh = props.boardingHouses.find(b => b.id === incomeForm.boarding_house_id);
    return bh ? bh.rooms : [];
});

const availableExpenseRooms = computed(() => {
    const bh = props.boardingHouses.find(b => b.id === expenseForm.boarding_house_id);
    return bh ? bh.rooms : [];
});

const availableEditIncomeRooms = computed(() => {
    const bh = props.boardingHouses.find(b => b.id === editIncomeForm.boarding_house_id);
    return bh ? bh.rooms : [];
});

const availableEditExpenseRooms = computed(() => {
    const bh = props.boardingHouses.find(b => b.id === editExpenseForm.boarding_house_id);
    return bh ? bh.rooms : [];
});

// ─── Watch boarding_house_id changes to reset room_id ─────────────────────────
watch(() => incomeForm.boarding_house_id, () => { incomeForm.room_id = ''; });
watch(() => expenseForm.boarding_house_id, () => { expenseForm.room_id = ''; });
watch(() => editIncomeForm.boarding_house_id, () => { editIncomeForm.room_id = ''; });
watch(() => editExpenseForm.boarding_house_id, () => { editExpenseForm.room_id = ''; });

// ─── Formatted Amount Computed ─────────────────────────────────────────────────
const formattedIncomeAmount = computed({
    get: () => incomeForm.amount ? new Intl.NumberFormat('id-ID').format(incomeForm.amount) : '',
    set: (val) => { incomeForm.amount = val.replace(/[^0-9]/g, ''); }
});

const formattedExpenseAmount = computed({
    get: () => expenseForm.amount ? new Intl.NumberFormat('id-ID').format(expenseForm.amount) : '',
    set: (val) => { expenseForm.amount = val.replace(/[^0-9]/g, ''); }
});

const formattedEditIncomeAmount = computed({
    get: () => editIncomeForm.amount ? new Intl.NumberFormat('id-ID').format(editIncomeForm.amount) : '',
    set: (val) => { editIncomeForm.amount = val.replace(/[^0-9]/g, ''); }
});

const formattedEditExpenseAmount = computed({
    get: () => editExpenseForm.amount ? new Intl.NumberFormat('id-ID').format(editExpenseForm.amount) : '',
    set: (val) => { editExpenseForm.amount = val.replace(/[^0-9]/g, ''); }
});

// ─── Modal Open/Close ──────────────────────────────────────────────────────────
const openIncomeModal = () => { showIncomeModal.value = true; };
const closeIncomeModal = () => { showIncomeModal.value = false; incomeForm.reset(); };

const openExpenseModal = () => { showExpenseModal.value = true; };
const closeExpenseModal = () => { showExpenseModal.value = false; expenseForm.reset(); };

const closeEditIncomeModal = () => { showEditIncomeModal.value = false; editIncomeForm.reset(); selectedItem.value = null; };
const closeEditExpenseModal = () => { showEditExpenseModal.value = false; editExpenseForm.reset(); selectedItem.value = null; };

const openDeleteModal = (item) => { selectedItem.value = item; showDeleteModal.value = true; };
const closeDeleteModal = () => { showDeleteModal.value = false; selectedItem.value = null; };

// Helper: extract expense ID from reference_number (format: EXP-{id})
const getExpenseId = (item) => {
    if (item.reference_number && item.reference_number.startsWith('EXP-')) {
        return parseInt(item.reference_number.replace('EXP-', ''));
    }
    return item.id;
};

// Helper: extract category from description (format: 'category - description')
const parseCategoryFromDescription = (description) => {
    if (!description) return 'maintenance';
    const knownCategories = ['utility', 'maintenance', 'salary', 'supplies', 'legal', 'other'];
    const parts = description.split(' - ');
    if (parts.length > 1 && knownCategories.includes(parts[0].trim().toLowerCase())) {
        return parts[0].trim().toLowerCase();
    }
    return 'other';
};

// Helper: extract pure description (strip category prefix if present)
const parseDescriptionOnly = (description) => {
    if (!description) return '';
    const knownCategories = ['utility', 'maintenance', 'salary', 'supplies', 'legal', 'other'];
    const parts = description.split(' - ');
    if (parts.length > 1 && knownCategories.includes(parts[0].trim().toLowerCase())) {
        return parts.slice(1).join(' - ').trim();
    }
    return description;
};

// ─── Open Edit Modal ──────────────────────────────────────────────────────────
const openEditModal = (item) => {
    selectedItem.value = item;
    if (item.type === 'income') {
        editIncomeForm.boarding_house_id = item.boarding_house_id;
        editIncomeForm.room_id = item.room_id || '';
        editIncomeForm.amount = String(Math.abs(item.amount));
        editIncomeForm.transaction_date = dayjs(item.transaction_date).format('YYYY-MM-DD');
        editIncomeForm.description = item.description;
        editIncomeForm.payment_method = item.payment_method || 'cash';
        showEditIncomeModal.value = true;
    } else if (item.type === 'expense') {
        editExpenseForm.boarding_house_id = item.boarding_house_id;
        editExpenseForm.room_id = item.room_id || '';
        editExpenseForm.amount = String(Math.abs(item.amount));
        editExpenseForm.expense_date = dayjs(item.transaction_date).format('YYYY-MM-DD');
        editExpenseForm.description = parseDescriptionOnly(item.description);
        editExpenseForm.category = parseCategoryFromDescription(item.description);
        showEditExpenseModal.value = true;
    }
};

// ─── Submit Add ───────────────────────────────────────────────────────────────
const submitIncome = () => {
    incomeForm.post(route('admin.transactions.store-income'), {
        onSuccess: () => closeIncomeModal(),
    });
};

const submitExpense = () => {
    expenseForm.post(route('admin.transactions.store-expense'), {
        onSuccess: () => closeExpenseModal(),
    });
};

// ─── Submit Edit ──────────────────────────────────────────────────────────────
const submitEditIncome = () => {
    editIncomeForm.put(route('admin.transactions.update-income', selectedItem.value.id), {
        onSuccess: () => closeEditIncomeModal(),
    });
};

const submitEditExpense = () => {
    const expenseId = getExpenseId(selectedItem.value);
    editExpenseForm.put(route('admin.transactions.update-expense', expenseId), {
        onSuccess: () => closeEditExpenseModal(),
    });
};

// ─── Delete ───────────────────────────────────────────────────────────────────
const confirmDelete = () => {
    if (!selectedItem.value || isDeleting.value) return;
    isDeleting.value = true;

    const item = selectedItem.value;
    const routeName = item.type === 'income'
        ? 'admin.transactions.destroy-income'
        : 'admin.transactions.destroy-expense';

    const resourceId = item.type === 'expense'
        ? getExpenseId(item)
        : item.id;

    router.delete(route(routeName, resourceId), {
        onSuccess: () => { closeDeleteModal(); },
        onFinish: () => { isDeleting.value = false; },
    });
};

// ─── Utilities ────────────────────────────────────────────────────────────────
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value || 0);
};

const formatDate = (date) => {
    return dayjs(date).format('DD MMM YYYY');
};

let timeout = null;
const fetchData = () => {
    router.get(
        route("admin.transactions.index"),
        {
            search: search.value,
            type: typeFilter.value,
            start_date: startDateFilter.value,
            end_date: endDateFilter.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }
    );
};

watch([search, typeFilter, startDateFilter, endDateFilter], () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        fetchData();
    }, 400);
});

const resetFilters = () => {
    search.value = "";
    typeFilter.value = "";
    startDateFilter.value = "";
    endDateFilter.value = "";
};
</script>
