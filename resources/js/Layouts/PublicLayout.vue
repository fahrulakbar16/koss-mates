<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 font-sans">
        <!-- Navigation Header -->
        <nav
            class="sticky top-0 z-[100] bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-theme-sm border-b border-gray-100 dark:border-gray-800 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <Link href="/" class="flex items-center gap-3 group">
                        <div
                            class="relative w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center transform transition-all duration-500 group-hover:rotate-12 group-hover:scale-110 shadow-lg shadow-primary-600/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <div
                                class="absolute inset-0 bg-white/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                        </div>
                        <span
                            class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300">Tharahub</span>
                    </Link>

                    <!-- Desktop Navigation Links -->
                    <div class="hidden md:flex items-center gap-10">
                        <Link href="/"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            Home</Link>
                        <a :href="route('boarding-houses.public.index')"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors cursor-pointer">Cari
                            Kos</a>
                        <a href="/#features" @click.prevent="handleAnchorClick('features')"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors cursor-pointer">Fitur</a>
                        <a href="/#how-it-works" @click.prevent="handleAnchorClick('how-it-works')"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors cursor-pointer">Cara
                            Kerja</a>
                        <a href="/#faq" @click.prevent="handleAnchorClick('faq')"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors cursor-pointer">FAQ</a>
                    </div>

                    <!-- Desktop Auth Buttons -->
                    <div class="hidden md:flex items-center gap-4">
                        <Link v-if="!$page.props.auth?.user" :href="route('login')"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all duration-300 shadow-lg shadow-primary-600/30 hover:shadow-primary-600/50 hover:-translate-y-0.5">
                            Masuk
                        </Link>
                        <Link v-if="$page.props.auth?.user" :href="route('dashboard')"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all duration-300 shadow-lg shadow-primary-600/30 hover:shadow-primary-600/50 hover:-translate-y-0.5">
                            Dashboard
                        </Link>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="toggleMobileMenu"
                        class="md:hidden p-2 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors focus:ring-2 focus:ring-primary-500">
                        <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <Transition enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2">
                <div v-if="mobileMenuOpen"
                    class="absolute top-20 left-0 w-full bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 shadow-xl md:hidden">
                    <div class="px-4 py-6 space-y-3">
                        <Link href="/" @click="closeMobileMenu"
                            class="block px-4 py-3 text-base font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-900 dark:text-white">
                            Home</Link>
                        <a :href="route('boarding-houses.public.index')"
                            class="block px-4 py-3 text-base font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 cursor-pointer">Cari
                            Kos</a>
                        <a href="/#features" @click.prevent="handleAnchorClick('features'); closeMobileMenu()"
                            class="block px-4 py-3 text-base font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 cursor-pointer">Fitur</a>
                        <a href="/#how-it-works" @click.prevent="handleAnchorClick('how-it-works'); closeMobileMenu()"
                            class="block px-4 py-3 text-base font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 cursor-pointer">Cara
                            Kerja</a>
                        <a href="/#faq" @click.prevent="handleAnchorClick('faq'); closeMobileMenu()"
                            class="block px-4 py-3 text-base font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 cursor-pointer">FAQ</a>

                        <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-800 grid grid-cols-2 gap-3">
                            <Link v-if="canLogin && !$page.props.auth?.user" :href="route('login')"
                                @click="closeMobileMenu"
                                class="flex justify-center items-center px-4 py-3 text-sm font-semibold rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                Login</Link>
                            <Link v-if="canRegister && !$page.props.auth?.user" :href="route('register')"
                                @click="closeMobileMenu"
                                class="flex justify-center items-center px-4 py-3 text-sm font-semibold text-white bg-primary-600 rounded-xl shadow-lg shadow-primary-600/20">
                                Daftar</Link>
                            <Link v-if="$page.props.auth?.user" :href="route('dashboard')" @click="closeMobileMenu"
                                class="col-span-2 flex justify-center items-center px-4 py-3 text-sm font-semibold text-white bg-primary-600 rounded-xl shadow-lg shadow-primary-600/20">
                                Dashboard</Link>
                        </div>
                    </div>
                </div>
            </Transition>
        </nav>

        <!-- Flash Messages -->
        <FlashMessage />

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-50 dark:bg-gray-900 pt-20 pb-12 border-t border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-16">
                    <!-- Brand -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-1">
                        <Link href="/" class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">Tharahub</span>
                        </Link>
                        <p class="text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                            Sahabat terbaik pencari kos di Indonesia. Aman, Mudah, dan Terpercaya sejak
                            2024.
                        </p>
                    </div>

                    <!-- Links -->
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-6">Layanan</h4>
                        <ul class="space-y-4 text-gray-500 dark:text-gray-400">
                            <li>
                                <Link href="/" class="hover:text-primary-600 transition-colors">Home</Link>
                            </li>
                            <li>
                                <Link :href="route('boarding-houses.public.index')"
                                    class="hover:text-primary-600 transition-colors">Cari Kos</Link>
                            </li>
                            <li>
                                <Link href="/#features" class="hover:text-primary-600 transition-colors">Fitur</Link>
                            </li>
                            <li>
                                <Link href="/#how-it-works" class="hover:text-primary-600 transition-colors">Cara Kerja
                                </Link>
                            </li>
                            <li>
                                <Link href="/#faq" class="hover:text-primary-600 transition-colors">FAQ</Link>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-6">Hubungi Kami</h4>
                        <div class="flex items-center gap-4">
                            <!-- Instagram icon -->
                            <a href="https://instagram.com" target="_blank"
                                class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </a>
                            <!-- Facebook icon -->
                            <a href="https://facebook.com" target="_blank"
                                class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            <!-- Linkedin icon -->
                            <a href="https://linkedin.com" target="_blank"
                                class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="border-t border-gray-200 dark:border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-500 text-center md:text-left">
                        &copy; {{ new Date().getFullYear() }} Tharahub. All rights reserved. Made with ❤️ in
                        Indonesia.
                    </p>
                    <div v-if="laravelVersion || phpVersion" class="flex gap-6 text-sm text-gray-500">
                        <span v-if="laravelVersion">Laravel v{{ laravelVersion }}</span>
                        <span v-if="phpVersion">PHP v{{ phpVersion }}</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import FlashMessage from '@/Components/layout/FlashMessage.vue';

const page = usePage();
const canLogin = page.props.canLogin ?? true;
const canRegister = page.props.canRegister ?? true;
const laravelVersion = computed(() => page.props.laravelVersion);
const phpVersion = computed(() => page.props.phpVersion);

const mobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
};

const handleAnchorClick = (sectionId) => {
    // Check if we're on the home page
    if (window.location.pathname === '/') {
        const element = document.getElementById(sectionId);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    } else {
        // If not on home page, navigate to home with anchor
        window.location.href = `/#${sectionId}`;
    }
};
</script>
