<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <Head title="Aktivasi Akun Ditinjau" />
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-100 p-8 text-center">
            
            <div class="w-20 h-20 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Akun Sedang Ditinjau</h1>
            <p class="text-gray-500 mb-8 leading-relaxed">
                Terima kasih telah melengkapi data diri Anda. Pengajuan aktivasi akun Anda saat ini sedang kami proses. Silakan tunggu konfirmasi dari pengelola.
            </p>
            
            <div class="pt-6 border-t border-gray-100">
                <Link :href="route('logout')" method="post" as="button" class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar Aplikasi
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>
