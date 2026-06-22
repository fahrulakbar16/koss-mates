<template>
    <FullScreenLayout>
        <div
            class="flex relative w-full h-[100dvh] overflow-hidden font-sans antialiased text-gray-900 transition-colors duration-300 bg-white dark:bg-gray-950">
            <!-- Left Panel - Branding Section -->
            <div class="hidden relative lg:flex lg:w-[55%] bg-cover bg-center bg-no-repeat overflow-hidden group"
                :style="{ backgroundImage: 'url(/images/login.webp)' }">
                <!-- Advanced Multi-layered Gradient Overlay -->
                <div
                    class="absolute inset-0 z-0 bg-gradient-to-tr from-primary-950/90 via-primary-900/40 to-transparent">
                </div>
                <div
                    class="absolute inset-0 z-0 bg-black/20 opacity-60 group-hover:opacity-40 transition-opacity duration-700">
                </div>

                <div class="relative z-10 flex flex-col justify-between p-12 h-full text-white w-full">
                    <!-- Logo Section -->
                    <Link href="/" class="flex gap-3 items-center group/logo w-fit">
                        <img src="/KosMates/White Asset 2.png" alt="Kostmates Logo" class="h-12 w-auto object-contain transform transition-all duration-500 group-hover/logo:scale-110" />
                    </Link>

                    <!-- Main Branding Content -->
                    <div class="flex flex-col gap-8 max-w-xl animate-in fade-in slide-in-from-left-8 duration-1000">
                        <div class="space-y-4">
                            <h1
                                class="text-5xl xl:text-6xl font-extrabold leading-[1.1] tracking-tight text-white drop-shadow-md">
                                Solusi <span class="text-primary-400">Hunian Kos</span> Modern & Nyaman.
                            </h1>
                            <p class="text-xl leading-relaxed text-white/80 font-medium">
                                Kelola hunian Anda dengan sistem digital terbaik. Cepat, transparan, dan terintegrasi
                                dalam satu platform.
                            </p>
                        </div>

                        <!-- Enhanced Feature Badges with Glassmorphism -->
                        <div class="flex flex-wrap gap-4">
                            <div
                                class="flex gap-3 items-center px-5 py-2.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-lg transition-transform hover:-translate-y-1">
                                <div class="w-8 h-8 rounded-full bg-primary-500/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="text-sm font-bold tracking-wide uppercase">Terpercaya</span>
                            </div>
                            <div
                                class="flex gap-3 items-center px-5 py-2.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-lg transition-transform hover:-translate-y-1">
                                <div class="w-8 h-8 rounded-full bg-primary-500/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="text-sm font-bold tracking-wide uppercase">Digital Payment</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Info -->
                    <div class="text-sm text-white/50 font-medium">
                        © {{ new Date().getFullYear() }} Kostmates Platform. All rights reserved.
                    </div>
                </div>
            </div>

            <!-- Right Panel - Auth Forms -->
            <div class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950 relative isolate">
                <div
                    class="min-h-full flex flex-col items-center justify-start lg:justify-center p-6 sm:p-6 lg:p-12 pt-10 lg:pt-12">
                    <!-- Mobile Background Image with Overlay -->
                    <div class="absolute inset-0 z-[-1] lg:hidden pointer-events-none">
                        <div class="absolute inset-0 bg-cover bg-center"
                            :style="{ backgroundImage: 'url(https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&q=80&w=2000)' }">
                        </div>
                        <div class="absolute inset-0 bg-white/95 dark:bg-gray-950/90"></div>
                    </div>

                    <div class="w-full max-w-md lg:max-w-[440px] animate-in fade-in zoom-in-95 duration-500 relative">
                        <!-- Mobile Logo (Visible only on small screens) -->
                        <div class="flex lg:hidden justify-center mb-8">
                            <Link href="/" class="flex gap-3 items-center">
                                <img src="/KosMates/Asset 2.png" alt="Kostmates Logo" class="h-10 w-auto object-contain" />
                                <span class="text-2xl font-bold dark:text-white">Kostmates</span>
                            </Link>
                        </div>

                        <!-- Enhanced Tab Switcher (Segmented Control style) -->
                        <div
                            class="relative p-1.5 mb-10 bg-gray-200/50 dark:bg-gray-900/50 backdrop-blur-sm rounded-2xl flex items-center shadow-inner">
                            <div class="absolute top-1.5 bottom-1.5 transition-all duration-300 ease-out bg-white dark:bg-gray-800 rounded-xl shadow-md"
                                :style="{ width: 'calc(50% - 0.375rem)', left: activeTab === 'login' ? '0.375rem' : '50%' }">
                            </div>
                            <button @click="activeTab = 'login'"
                                class="relative flex-1 py-2.5 text-sm font-bold transition-colors duration-200 z-10"
                                :class="activeTab === 'login' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'">
                                Masuk
                            </button>
                            <button @click="activeTab = 'register'"
                                class="relative flex-1 py-2.5 text-sm font-bold transition-colors duration-200 z-10"
                                :class="activeTab === 'register' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'">
                                Daftar Baru
                            </button>
                        </div>

                        <div class="relative min-h-[620px]">
                            <!-- Login Form with Transition -->
                            <Transition enter-active-class="transition duration-300 ease-out"
                                enter-from-class="opacity-0 translate-y-4" enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition duration-200 ease-in absolute"
                                leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-4">
                                <div v-if="activeTab === 'login'" class="w-full">
                                    <div class="mb-8">
                                        <h2
                                            class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2 tracking-tight">
                                            Selamat Datang!</h2>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Silakan masuk untuk
                                            melanjutkan akses ke platform.</p>
                                    </div>

                                    <form @submit.prevent="loginForm.post('/login')" class="space-y-6">
                                        <!-- Alert for Flash Errors -->
                                        <div v-if="flashError"
                                            class="flex items-center gap-3 p-4 bg-primary-50 dark:bg-primary-950/30 border border-primary-100 dark:border-primary-900/50 rounded-2xl text-primary-600 dark:text-primary-400 text-sm font-medium animate-in fade-in zoom-in-95">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ flashError }}
                                        </div>

                                        <!-- Email Field -->
                                        <div class="space-y-2">
                                            <label
                                                class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Email</label>
                                            <div class="relative group">
                                                <div
                                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                                    </svg>
                                                </div>
                                                <input v-model="loginForm.user" type="text"
                                                    placeholder="email@example.com"
                                                    class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm transition-all focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white"
                                                    :class="{ 'border-primary-500 ring-primary-500/10': loginForm.errors.user }" />
                                            </div>
                                            <p v-if="loginForm.errors.user"
                                                class="text-xs font-bold text-primary-500 ml-1 mt-1">{{
                                                    getErrorMessage(loginForm.errors.user) }}</p>
                                        </div>

                                        <!-- Password Field -->
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center ml-1">
                                                <label
                                                    class="text-sm font-bold text-gray-700 dark:text-gray-300">Password</label>
                                                <Link href="/forgot-password"
                                                    class="text-xs font-bold text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                                    Lupa Password?</Link>
                                            </div>
                                            <div class="relative group">
                                                <div
                                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                </div>
                                                <input v-model="loginForm.password"
                                                    :type="showPassword ? 'text' : 'password'" placeholder="••••••••"
                                                    class="w-full pl-12 pr-12 py-3.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm transition-all focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white"
                                                    :class="{ 'border-primary-500 ring-primary-500/10': loginForm.errors.password }" />
                                                <button type="button" @click="togglePasswordVisibility"
                                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                                                    <svg v-if="!showPassword" class="w-5 h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p v-if="loginForm.errors.password"
                                                class="text-xs font-bold text-primary-500 ml-1 mt-1">{{
                                                    getErrorMessage(loginForm.errors.password) }}</p>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" :disabled="loginForm.processing"
                                            class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-xl shadow-primary-600/20 transform transition-all active:scale-[0.98] disabled:opacity-70 disabled:grayscale cursor-pointer">
                                            <div class="flex items-center justify-center gap-2">
                                                <span v-if="loginForm.processing"
                                                    class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                                <span>{{ loginForm.processing ? 'Memproses...' : 'Masuk Sekarang'
                                                    }}</span>
                                            </div>
                                        </button>

                                        <!-- Social Provider Divider -->
                                        <div class="relative flex py-2 items-center">
                                            <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
                                            <span
                                                class="flex-shrink mx-4 text-gray-400 text-xs font-bold uppercase tracking-widest">Atau</span>
                                            <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
                                        </div>

                                        <!-- Google Login -->
                                        <button type="button" @click="loginWithGoogle" :disabled="isGoogleLoading"
                                            class="w-full flex items-center justify-center gap-3 py-3.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl font-bold text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-all hover:shadow-md cursor-pointer disabled:opacity-50">
                                            <div v-if="isGoogleLoading"
                                                class="w-5 h-5 border-2 border-primary-600/30 border-t-primary-600 rounded-full animate-spin">
                                            </div>
                                            <svg v-else class="w-5 h-5" viewBox="0 0 24 24">
                                                <path fill="#4285F4"
                                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                                <path fill="#34A853"
                                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                                <path fill="#FBBC05"
                                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                                <path fill="#EA4335"
                                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                            </svg>
                                            {{ isGoogleLoading ? 'Menghubungkan...' : 'Masuk dengan Google' }}
                                        </button>
                                    </form>
                                </div>
                            </Transition>

                            <!-- Register Form with Transition -->
                            <Transition enter-active-class="transition duration-300 ease-out"
                                enter-from-class="opacity-0 translate-y-4" enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition duration-200 ease-in absolute"
                                leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-4">
                                <div v-if="activeTab === 'register'" class="w-full">
                                    <div class="mb-8">
                                        <h2
                                            class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2 tracking-tight">
                                            Buat Akun</h2>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Bergabunglah bersama
                                            kami
                                            untuk pengalaman hunian yang lebih baik.</p>
                                    </div>

                                    <form @submit.prevent="registerForm.post('/register')" class="space-y-5">
                                        <!-- Full Name -->
                                        <div class="space-y-2">
                                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Nama
                                                Lengkap</label>
                                            <div class="relative group">
                                                <div
                                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <input v-model="registerForm.name" type="text"
                                                    placeholder="Nama lengkap Anda"
                                                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm transition-all focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white"
                                                    :class="{ 'border-primary-500': registerForm.errors.name }" />
                                            </div>
                                            <p v-if="registerForm.errors.name"
                                                class="text-xs font-bold text-primary-500 ml-1 mt-1">{{
                                                    getErrorMessage(registerForm.errors.name) }}</p>
                                        </div>

                                        <!-- Email -->
                                        <div class="space-y-2">
                                            <label
                                                class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Email</label>
                                            <div class="relative group">
                                                <div
                                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <input v-model="registerForm.email" type="email"
                                                    placeholder="email@example.com"
                                                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm transition-all focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white"
                                                    :class="{ 'border-primary-500': registerForm.errors.email }" />
                                            </div>
                                            <p v-if="registerForm.errors.email"
                                                class="text-xs font-bold text-primary-500 ml-1 mt-1">{{
                                                    getErrorMessage(registerForm.errors.email) }}</p>
                                        </div>

                                        <!-- Password Row -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label
                                                    class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Password</label>
                                                <div class="relative">
                                                    <input v-model="registerForm.password"
                                                        :type="showRegisterPassword ? 'text' : 'password'"
                                                        placeholder="••••••••"
                                                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm transition-all focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white" />
                                                    <button type="button" @click="toggleRegisterPasswordVisibility"
                                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path v-if="!showRegisterPassword"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path v-else
                                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <label
                                                    class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Konfirmasi</label>
                                                <div class="relative">
                                                    <input v-model="registerForm.password_confirmation"
                                                        :type="showRegisterConfirmPassword ? 'text' : 'password'"
                                                        placeholder="••••••••"
                                                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm transition-all focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 dark:text-white" />
                                                </div>
                                            </div>
                                        </div>
                                        <p v-if="registerForm.errors.password"
                                            class="text-xs font-bold text-primary-500 ml-1">
                                            {{ getErrorMessage(registerForm.errors.password) }}</p>

                                        <!-- Submit Button -->
                                        <button type="submit" :disabled="registerForm.processing"
                                            class="w-full py-4 mt-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-xl shadow-primary-600/20 transform transition-all active:scale-[0.98] disabled:opacity-70 disabled:grayscale cursor-pointer">
                                            {{ registerForm.processing ? 'Memproses...' : 'Daftar Sekarang' }}
                                        </button>

                                        <!-- Divider -->
                                        <div class="relative flex py-2 items-center">
                                            <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
                                            <span
                                                class="flex-shrink mx-4 text-gray-400 text-xs font-bold uppercase tracking-widest">Atau</span>
                                            <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
                                        </div>

                                        <!-- Google Login Alternative -->
                                        <button type="button" @click="loginWithGoogle" :disabled="isGoogleLoading"
                                            class="w-full flex items-center justify-center gap-3 py-3.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl font-bold text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-all hover:shadow-md cursor-pointer disabled:opacity-50">
                                            <div v-if="isGoogleLoading"
                                                class="w-5 h-5 border-2 border-primary-600/30 border-t-primary-600 rounded-full animate-spin">
                                            </div>
                                            <svg v-else class="w-5 h-5" viewBox="0 0 24 24">
                                                <path fill="#4285F4"
                                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                                <path fill="#34A853"
                                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                                <path fill="#FBBC05"
                                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                                <path fill="#EA4335"
                                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                            </svg>
                                            {{ isGoogleLoading ? 'Menghubungkan...' : 'Daftar dengan Google' }}
                                        </button>
                                    </form>
                                </div>
                            </Transition>
                        </div>

                        <!-- Additional Links -->
                        <div class="mt-12 text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                Butuh bantuan lebih lanjut?
                                <a href="mailto:support@Kostmates.com"
                                    class="text-primary-600 dark:text-primary-400 font-bold ml-1 hover:underline">Hubungi
                                    Support</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FullScreenLayout>
</template>

<script setup>
import FullScreenLayout from "@/Components/layout/FullScreenLayout.vue";
import { Link, useForm, usePage, router } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import { auth, googleProvider, messaging } from "@/firebase";
import { signInWithPopup } from "firebase/auth";
import { getToken } from "firebase/messaging";
import Swal from "sweetalert2";

const props = defineProps({
    googleLoginUrl: String,
});

const page = usePage();
const activeTab = ref('login');

// Get flash error message
const flashError = computed(() => page.props.flash?.error || null);

const loginForm = useForm({
    user: "",
    password: "",
    remember: false,
    device_token: "",
    platform: "web",
});

const registerForm = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    device_token: "",
    platform: "web",
});

const showPassword = ref(false);
const showRegisterPassword = ref(false);
const showRegisterConfirmPassword = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const toggleRegisterPasswordVisibility = () => {
    showRegisterPassword.value = !showRegisterPassword.value;
};

const toggleRegisterConfirmPasswordVisibility = () => {
    showRegisterConfirmPassword.value = !showRegisterConfirmPassword.value;
};

const isGoogleLoading = ref(false);

// Get FCM Token
const getFcmToken = async () => {
    try {
        const permission = await Notification.requestPermission();
        if (permission === "granted") {
            const token = await getToken(messaging, {
                vapidKey: "BPlJkPF-cqoA4lAyjxoYfjgG_QAx_pwkadTSu1yVLReZcW8YYmkEo0MS4dndsPjtT6YVSCBCivplF_se9_VqkhA" // Ganti dengan VAPIDn Key dari Firebase Console -> Project Settings -> Cloud Messaging -> Web Configuration
            });
            if (token) {
                console.log("FCM Token:", token);
                loginForm.device_token = token;
                registerForm.device_token = token;
            } else {
                console.log("No registration token available. Request permission to generate one.");
            }
        } else {
            console.log("Unable to get permission to notify.");
        }
    } catch (error) {
        console.log("An error occurred while retrieving token. ", error);
    }
};

onMounted(() => {
    getFcmToken();
});

const loginWithGoogle = async () => {
    isGoogleLoading.value = true;
    try {
        const result = await signInWithPopup(auth, googleProvider);
        const token = await result.user.getIdToken();

        // Ensure token is fetched if not already
        let deviceToken = loginForm.device_token;
        if (!deviceToken) {
            await getFcmToken();
            deviceToken = loginForm.device_token;
        }

        router.post(route('login.google'), {
            token: token,
            device_token: deviceToken,
            platform: "web",
        }, {
            onFinish: () => {
                isGoogleLoading.value = false;
            },
            onError: (errors) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Masuk',
                    text: errors.error || 'Terjadi kesalahan saat masuk dengan Google.',
                });
            }
        });
    } catch (error) {
        isGoogleLoading.value = false;
        console.error(error);

        if (error.code !== 'auth/popup-closed-by-user') {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Masuk',
                text: 'Terjadi kesalahan saat menghubungkan ke Google.',
            });
        }
    }
};

// Helper function to extract error message
const getErrorMessage = (error) => {
    if (!error) return null;
    if (typeof error === 'string') return error;
    if (Array.isArray(error)) return error[0] || null;
    if (typeof error === 'object') {
        const values = Object.values(error);
        return values.length > 0 ? (Array.isArray(values[0]) ? values[0][0] : values[0]) : null;
    }
    return String(error);
};
</script>

<style scoped>
/* Advanced custom focus and typography styles */
input:focus {
    outline: none !important;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Custom background pattern for the forms panel */
.dark .dark\:bg-gray-950 {
    background-image:
        radial-gradient(circle at 2px 2px, rgba(255, 255, 255, 0.05) 1px, transparent 0);
    background-size: 32px 32px;
}

.bg-gray-50 {
    background-image:
        radial-gradient(circle at 2px 2px, rgba(0, 0, 0, 0.02) 1px, transparent 0);
    background-size: 32px 32px;
}
</style>
