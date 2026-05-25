<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineOptions({
    layout: PublicLayout,
});

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    featuredBoardingHouse: {
        type: Object,
        default: null,
    },
    recommendedBoardingHouses: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = ref('');
let scrollObserver = null;

onMounted(() => {
    // Setup Intersection Observer for scroll animations
    setupScrollAnimations();
});

onUnmounted(() => {
    if (scrollObserver) {
        scrollObserver.disconnect();
    }
});

const setupScrollAnimations = () => {
    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    };

    scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                // Use requestAnimationFrame for smoother class addition
                requestAnimationFrame(() => {
                    entry.target.classList.add('animate-reveal');
                });
                scrollObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Initial check for elements already in view
    document.querySelectorAll('.scroll-reveal').forEach((el) => {
        scrollObserver.observe(el);
    });
};

const scrollToSection = (sectionId) => {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

const formatPrice = (price) => {
    if (!price || price === 0) return 'Harga belum tersedia';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);
};

const getImageUrl = (boardingHouse) => {
    return boardingHouse.first_image || boardingHouse.thumbnail || '/images/placeholder.png';
};

// SEO Meta Data
const siteUrl = computed(() => {
    if (typeof window !== 'undefined') {
        return window.location.origin;
    }
    return 'https://Tharahub.com'; // Fallback URL
});

const seoTitle = 'Tharahub - Temukan Kos Ideal Anda dengan Mudah';
const seoDescription = 'Platform modern untuk mencari, mengelola, dan menyewa kos di seluruh Indonesia. Temukan kos impian Anda dengan fitur lengkap, foto detail, lokasi strategis, dan harga transparan. Terpercaya & Aman.';
const seoKeywords = 'kos, kost, boarding house, sewa kos, cari kos, kos murah, kos dekat kampus, kos strategis, platform kos, kos indonesia, sewa kamar, kos terpercaya';
const seoImage = computed(() => {
    if (props.featuredBoardingHouse?.hero_image) {
        return props.featuredBoardingHouse.hero_image;
    }
    return `${siteUrl.value}/images/placeholder.png`;
});

// Structured Data for SEO
const websiteStructuredData = computed(() => {
    return JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'WebSite',
        name: 'Tharahub',
        url: siteUrl.value,
        description: seoDescription,
        potentialAction: {
            '@type': 'SearchAction',
            target: {
                '@type': 'EntryPoint',
                urlTemplate: `${siteUrl.value}/search?q={search_term_string}`
            },
            'query-input': 'required name=search_term_string'
        }
    });
});

const organizationStructuredData = computed(() => {
    return JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'Organization',
        name: 'Tharahub',
        url: siteUrl.value,
        logo: `${siteUrl.value}/images/logo/Tharahub-logo.png`,
        description: seoDescription,
        sameAs: [
            'https://www.facebook.com/Tharahub',
            'https://www.instagram.com/Tharahub',
            'https://twitter.com/Tharahub'
        ],
        contactPoint: {
            '@type': 'ContactPoint',
            contactType: 'Customer Service',
            availableLanguage: 'Indonesian'
        }
    });
});
</script>

<template>

    <Head :title="seoTitle">
        <!-- Primary Meta Tags -->
        <meta name="title" :content="seoTitle" />
        <meta name="description" :content="seoDescription" />
        <meta name="keywords" :content="seoKeywords" />
        <meta name="author" content="Tharahub" />
        <meta name="robots" content="index, follow" />
        <meta name="language" content="Indonesian" />
        <meta name="revisit-after" content="7 days" />
        <link rel="canonical" :href="siteUrl" />

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="siteUrl" />
        <meta property="og:title" :content="seoTitle" />
        <meta property="og:description" :content="seoDescription" />
        <meta property="og:image" :content="seoImage" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Tharahub - Platform Pencarian Kos Terpercaya" />
        <meta property="og:site_name" content="Tharahub" />
        <meta property="og:locale" content="id_ID" />

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:url" :content="siteUrl" />
        <meta name="twitter:title" :content="seoTitle" />
        <meta name="twitter:description" :content="seoDescription" />
        <meta name="twitter:image" :content="seoImage" />
        <meta name="twitter:image:alt" content="Tharahub - Platform Pencarian Kos Terpercaya" />

        <!-- Additional Meta Tags -->
        <meta name="theme-color" content="#fa5252" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <meta name="format-detection" content="telephone=no" />

        <!-- Structured Data (JSON-LD) -->
        <component :is="'script'" type="application/ld+json" v-html="websiteStructuredData"></component>
        <component :is="'script'" type="application/ld+json" v-html="organizationStructuredData"></component>
    </Head>

    <div class="scroll-smooth">

        <!-- Hero Section -->
        <section class="relative pt-12 pb-20 lg:pt-24 lg:pb-32 overflow-hidden">
            <!-- Background Decorations -->
            <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary-50/50 dark:bg-primary-900/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 animate-parallax">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-50/50 dark:bg-blue-900/10 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4 animate-parallax" style="animation-delay: -5s">
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                    <!-- Left: Content -->
                    <div class="animate-reveal stagger-reveal max-w-2xl">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-sm font-medium mb-6 border border-primary-100 dark:border-primary-800">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                            </span>
                            Platform Pencarian Kos #1 di Indonesia
                        </div>

                        <h1
                            class="text-4xl sm:text-5xl lg:text-7xl font-bold text-gray-900 dark:text-white leading-[1.1] mb-6 tracking-tight">
                            Temukan Kos <br />
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-500">Impianmu</span>
                            Disini
                        </h1>

                        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed max-w-lg">
                            Dapatkan kenyamanan tempat tinggal terbaik dengan harga transparan dan lokasi strategis di
                            seluruh Indonesia.
                        </p>

                        <!-- Enhanced Search Bar -->
                        <div class="relative max-w-lg group z-20">
                            <div
                                class="absolute -inset-1 bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200">
                            </div>
                            <div
                                class="relative flex items-center bg-white dark:bg-gray-800 rounded-2xl shadow-xl dark:shadow-none p-2 border border-gray-100 dark:border-gray-700">
                                <div class="flex-1 flex items-center px-4">
                                    <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input v-model="searchQuery" type="text"
                                        placeholder="Cari lokasi, kampus, atau area..."
                                        class="w-full bg-transparent border-none p-2 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-0 text-base" />
                                </div>
                                <button
                                    class="btn-premium px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl shadow-lg shadow-primary-600/30">
                                    Cari
                                </button>
                            </div>
                        </div>

                        <!-- Stats/Trusted -->
                        <div class="mt-10 flex items-center gap-6 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex -space-x-3">
                                <img src="https://ui-avatars.com/api/?name=A+B&background=random"
                                    class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900"
                                    alt="User" />
                                <img src="https://ui-avatars.com/api/?name=C+D&background=random"
                                    class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900"
                                    alt="User" />
                                <img src="https://ui-avatars.com/api/?name=E+F&background=random"
                                    class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900"
                                    alt="User" />
                                <div
                                    class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-900 bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs font-bold">
                                    +2k</div>
                            </div>
                            <p>Telah dipercaya oleh <span class="font-bold text-gray-900 dark:text-white">2,000+</span>
                                pencari kos</p>
                        </div>
                    </div>

                    <!-- Right: Hero Image - Creative Layout -->
                    <div class="hidden lg:block relative animate-slide-in-right">
                        <div
                            class="relative w-full aspect-[4/5] rounded-[32px] overflow-hidden shadow-2xl border-[8px] border-white dark:border-gray-800 transform rotate-2 hover:rotate-0 transition-transform duration-700">
                            <template v-if="featuredBoardingHouse">
                                <img :src="featuredBoardingHouse.hero_image" :alt="featuredBoardingHouse.name"
                                    class="w-full h-full object-cover" />
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-8">
                                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                                        <h3 class="text-white text-xl font-bold mb-1">{{ featuredBoardingHouse.name }}
                                        </h3>
                                        <p class="text-white/80 text-sm flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ featuredBoardingHouse.cluster || featuredBoardingHouse.address }}
                                        </p>
                                    </div>
                                </div>
                            </template>
                            <div v-else
                                class="w-full h-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <p class="text-gray-400">Featured Image</p>
                            </div>
                        </div>

                        <!-- Floating Card Decoration -->
                        <div
                            class="absolute -bottom-10 -left-10 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 animate-bounce-slow max-w-[200px]">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-medium">Status</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">Terverifikasi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recommendations Section -->
        <section class="py-20 bg-gray-50 dark:bg-gray-900/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12 scroll-reveal stagger-reveal">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Rekomendasi Pilihan</h2>
                        <p class="text-gray-500 dark:text-gray-400">Kos terbaik yang paling banyak dicari minggu ini</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Link to all kos page -->
                        <Link :href="route('boarding-houses.public.index')"
                            class="btn-premium px-5 py-2.5 bg-primary-600 text-white font-semibold rounded-xl flex items-center gap-2">
                            <span>Lihat Semua</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>

                <!-- Kos Grid -->
                <div v-if="recommendedBoardingHouses.length > 0"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <TransitionGroup name="kos-list" tag="div" class="contents">
                        <div v-for="(kos, index) in recommendedBoardingHouses" :key="kos.id"
                            :style="{ '--delay': (index * 0.1) + 's' }"
                            class="scroll-reveal card-premium group relative bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800">
                            <!-- Image -->
                            <div class="relative h-64 overflow-hidden">
                                <img :src="getImageUrl(kos)" :alt="kos.name"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    @error="$event.target.src = '/images/placeholder.png'" />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60">
                                </div>

                                <!-- Price Badge -->
                                <div
                                    class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg">
                                    <span class="text-primary-600 font-bold text-sm">{{ formatPrice(kos.min_price)
                                        }}</span>
                                    <span class="text-xs text-gray-500">/bulan</span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h3
                                        class="text-lg font-bold text-gray-900 dark:text-white line-clamp-1 flex-1 pr-2 group-hover:text-primary-600 transition-colors">
                                        {{ kos.name }}
                                    </h3>
                                </div>

                                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 text-sm mb-4">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ kos.cluster || kos.address }}</span>
                                </div>

                                <div
                                    class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 bg-green-100 text-green-700 rounded-md dark:bg-green-900/30 dark:text-green-400">Tersedia</span>
                                    <Link :href="route('boarding-houses.public.show', kos.id)"
                                        class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1 group/btn">
                                        Detail
                                        <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>
                </div>

                <!-- Empty State -->
                <div v-else
                    class="text-center py-20 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-300 dark:border-gray-700">
                    <div
                        class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">Tidak ada kos yang ditemukan untuk kriteria ini.
                    </p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-gradient-to-br from-primary-600 to-primary-800 text-white relative overflow-hidden">
            <div
                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150">
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12 text-center stagger-reveal">
                    <div class="scroll-reveal bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10 transform transition-transform hover:scale-105"
                        style="--delay: 0s">
                        <div class="text-4xl md:text-5xl font-bold mb-2">{{ recommendedBoardingHouses.length }}+</div>
                        <div class="text-primary-100 text-sm font-medium uppercase tracking-wider">Kos
                            Tersedia
                        </div>
                    </div>
                    <div class="scroll-reveal bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10 transform transition-transform hover:scale-105"
                        style="--delay: 0.1s">
                        <div class="text-4xl md:text-5xl font-bold mb-2">50+</div>
                        <div class="text-primary-100 text-sm font-medium uppercase tracking-wider">Lokasi
                            Strategis
                        </div>
                    </div>
                    <div class="scroll-reveal bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10 transform transition-transform hover:scale-105"
                        style="--delay: 0.2s">
                        <div class="text-4xl md:text-5xl font-bold mb-2">2.5k+</div>
                        <div class="text-primary-100 text-sm font-medium uppercase tracking-wider">Penyewa
                            Puas
                        </div>
                    </div>
                    <div class="scroll-reveal bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10 transform transition-transform hover:scale-105"
                        style="--delay: 0.3s">
                        <div class="text-4xl md:text-5xl font-bold mb-2">98%</div>
                        <div class="text-primary-100 text-sm font-medium uppercase tracking-wider">Rating
                            Bintang 5
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-white dark:bg-gray-900 scroll-mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 scroll-reveal stagger-reveal">
                    <span class="text-primary-600 font-semibold tracking-wide uppercase text-sm">Keunggulan
                        Kami</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mt-2 mb-4">
                        Kenapa Harus <span class="text-primary-600">Tharahub?</span>
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed">
                        Kami menyediakan fitur terbaik untuk memudahkan pengalaman pencarian tempat tinggal
                        Anda
                        yang aman dan nyaman.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-reveal">
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 card-premium border border-gray-100 dark:border-gray-700 scroll-reveal"
                        style="--delay: 0s">
                        <div
                            class="w-14 h-14 bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Pencarian Cepat
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Filter canggih membantu Anda menemukan kos impian berdasarkan lokasi, harga, dan
                            fasilitas dalam hitungan detik.
                        </p>
                    </div>

                    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 card-premium border border-gray-100 dark:border-gray-700 scroll-reveal"
                        style="--delay: 0.1s">
                        <div
                            class="w-14 h-14 bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Informasi Lengkap
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Setiap listing dilengkapi foto berkualitas tinggi, detail fasilitas, dan
                            deskripsi
                            lengkap yang transparan.
                        </p>
                    </div>

                    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 card-premium border border-gray-100 dark:border-gray-700 scroll-reveal"
                        style="--delay: 0.2s">
                        <div
                            class="w-14 h-14 bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Lokasi Strategis
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Kami mengkurasi kos di area-area strategis, dekat dengan kampus, perkantoran,
                            dan akses
                            transportasi umum.
                        </p>
                    </div>

                    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 card-premium border border-gray-100 dark:border-gray-700 scroll-reveal"
                        style="--delay: 0.3s">
                        <div
                            class="w-14 h-14 bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Harga Transparan
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Tidak ada biaya tersembunyi. Harga yang Anda lihat adalah harga yang Anda bayar,
                            lengkap
                            dengan rinciannya.
                        </p>
                    </div>

                    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 card-premium border border-gray-100 dark:border-gray-700 scroll-reveal"
                        style="--delay: 0.4s">
                        <div
                            class="w-14 h-14 bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Terverifikasi</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Setiap mitra kos telah melewati proses verifikasi ketat untuk menjamin keamanan
                            dan
                            kenyamanan Anda.
                        </p>
                    </div>

                    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 card-premium border border-gray-100 dark:border-gray-700 scroll-reveal"
                        style="--delay: 0.5s">
                        <div
                            class="w-14 h-14 bg-primary-50 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Support 24/7</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Tim CS kami siap membantu keluhan dan pertanyaan Anda kapanpun dibutuhkan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-24 bg-gray-50 dark:bg-gray-900/50 scroll-mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20 scroll-reveal stagger-reveal">
                    <span class="text-primary-600 font-semibold tracking-wide uppercase text-sm">Langkah
                        Mudah</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mt-2 mb-4">
                        Cara Kerja di <span class="text-primary-600">Tharahub</span>
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                        Cukup ikuti 3 langkah sederhana ini untuk mendapatkan tempat tinggal yang Anda
                        inginkan.
                    </p>
                </div>

                <div class="relative grid md:grid-cols-3 gap-12 stagger-reveal">
                    <!-- Connecting Line (Desktop) -->
                    <div
                        class="hidden md:block absolute top-12 left-[16%] right-[16%] h-0.5 bg-gray-200 dark:bg-gray-700 -z-10">
                    </div>

                    <div class="relative text-center scroll-reveal group" style="--delay: 0s">
                        <div
                            class="w-24 h-24 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-8 shadow-theme-sm border-[6px] border-gray-50 dark:border-gray-900 group-hover:scale-110 transition-transform duration-500">
                            <span class="text-4xl font-bold text-primary-600">1</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Cari & Filter</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed px-4">
                            Gunakan pencarian pintar kami untuk menemukan kos yang sesuai dengan budget dan
                            preferensi lokasi Anda.
                        </p>
                    </div>

                    <div class="relative text-center scroll-reveal group" style="--delay: 0.2s">
                        <div
                            class="w-24 h-24 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-8 shadow-theme-sm border-[6px] border-gray-50 dark:border-gray-900 group-hover:scale-110 transition-transform duration-500">
                            <span class="text-4xl font-bold text-primary-600">2</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Survey & Booking
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed px-4">
                            Lihat detail kos, foto, dan video. Jika cocok, langsung ajukan survey atau
                            booking
                            secara online.
                        </p>
                    </div>

                    <div class="relative text-center scroll-reveal group" style="--delay: 0.4s">
                        <div
                            class="w-24 h-24 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-8 shadow-theme-sm border-[6px] border-gray-50 dark:border-gray-900 group-hover:scale-110 transition-transform duration-500">
                            <span class="text-4xl font-bold text-primary-600">3</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Deal & Huni</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed px-4">
                            Lakukan pembayaran aman lewat platform, dapatkan konfirmasi, dan kos siap untuk
                            Anda
                            huni.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-24 bg-white dark:bg-gray-900 scroll-mt-20">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 scroll-reveal stagger-reveal">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Pertanyaan Umum
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Kami rangkum beberapa hal yang sering ditanyakan pengguna.
                    </p>
                </div>

                <div class="space-y-6 stagger-reveal">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-8 hover:bg-gray-100 dark:hover:bg-gray-750 transition-colors scroll-reveal"
                        style="--delay: 0s">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">
                            Apakah ada biaya admin saat booking?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Tidak, Tharahub 100% bebas biaya admin untuk penyewa. Harga yang tertera adalah
                            harga
                            murni dari pemilik kos tanpa markup tersembunyi.
                        </p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-8 hover:bg-gray-100 dark:hover:bg-gray-750 transition-colors scroll-reveal"
                        style="--delay: 0.1s">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">
                            Bagaimana sistem pembayarannya?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Kami mendukung berbagai metode pembayaran mulai dari Transfer Bank, E-Wallet
                            (OVO,
                            GoPay, Dana), hingga kartu kredit untuk memudahkan transaksi Anda.
                        </p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-8 hover:bg-gray-100 dark:hover:bg-gray-750 transition-colors scroll-reveal"
                        style="--delay: 0.2s">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">
                            Apakah uang bisa kembali jika batal?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Kebijakan refund bergantung pada aturan masing-masing pemilik kos yang tertera
                            di
                            halaman detail. Namun, kami akan membantu mediasi jika terjadi kendala.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section with Modern Gradient -->
        <section class="py-24 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-600 to-primary-800 z-0"></div>
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-white/10 rounded-full blur-3xl">
            </div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-black/10 rounded-full blur-3xl">
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 scroll-reveal stagger-reveal">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">
                        Siap Menemukan Kos Impianmu?
                    </h2>
                    <p class="text-xl text-primary-100 mb-10 max-w-2xl mx-auto leading-relaxed">
                        Jangan tunda lagi. Ribuan pilihan kos nyaman menunggu. Gabung sekarang dan rasakan
                        kemudahannya.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 justify-center stagger-reveal">
                    <Link v-if="canRegister" :href="route('register')"
                        class="btn-premium px-10 py-4 text-lg font-bold bg-white text-primary-700 rounded-xl shadow-xl scroll-reveal" style="--delay: 0.1s">
                        Mulai Cari Gratis
                    </Link>
                    <Link v-if="canLogin" :href="route('login')"
                        class="btn-premium px-10 py-4 text-lg font-bold bg-white/10 backdrop-blur-md hover:bg-white/20 text-white rounded-xl border border-white/30 scroll-reveal" style="--delay: 0.2s">
                        Masuk Akun
                    </Link>
                </div>
            </div>
        </section>

    </div>
</template>

<style scoped>
/* High-performance Smoothing */
* {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Custom Timing for Modern Motion */
:root {
    --ease-premium: cubic-bezier(0.23, 1, 0.32, 1);
    --ease-in-out-soft: cubic-bezier(0.4, 0, 0.2, 1);
    --ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1);
}

/* Base Scroll Reveal State - Modernized */
.scroll-reveal {
    opacity: 0;
    filter: blur(8px);
    transform: translateY(20px) scale(0.98);
    transition:
        opacity 1.2s var(--ease-premium),
        transform 1.2s var(--ease-premium),
        filter 1.2s var(--ease-premium);
    transition-delay: var(--delay, 0s);
    will-change: transform, opacity, filter;
}

.scroll-reveal.animate-reveal {
    opacity: 1;
    filter: blur(0);
    transform: translateY(0) scale(1);
}

/* Staggered Children Logic */
.stagger-reveal > * {
    opacity: 0;
    transform: translateY(15px);
    transition: opacity 1s var(--ease-premium), transform 1s var(--ease-premium);
}

.animate-reveal.stagger-reveal > *:nth-child(1) { transition-delay: 0.1s; opacity: 1; transform: translateY(0); }
.animate-reveal.stagger-reveal > *:nth-child(2) { transition-delay: 0.2s; opacity: 1; transform: translateY(0); }
.animate-reveal.stagger-reveal > *:nth-child(3) { transition-delay: 0.3s; opacity: 1; transform: translateY(0); }
.animate-reveal.stagger-reveal > *:nth-child(4) { transition-delay: 0.4s; opacity: 1; transform: translateY(0); }

/* Hero Section Entrance - Premium */
@keyframes heroPremiumEntrance {
    from {
        opacity: 0;
        filter: blur(12px);
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        filter: blur(0);
        transform: translateY(0) scale(1);
    }
}

.animate-fade-in,
.animate-slide-in-left,
.animate-slide-in-right {
    animation: heroPremiumEntrance 1.4s var(--ease-premium) forwards;
    animation-delay: var(--delay, 0.5s);
}

/* Interactive Elements - Magnetic Feel */
.btn-premium {
    transition: all 0.4s var(--ease-premium);
}

.btn-premium:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 20px 40px -10px rgba(var(--primary-rgb), 0.3);
}

.btn-premium:active {
    transform: scale(0.98);
}

/* Card Modern Elevation */
.card-premium {
    transition: all 0.5s var(--ease-premium);
}

.card-premium:hover {
    transform: translateY(-8px);
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.1), 0 18px 36px -18px rgba(0, 0, 0, 0.15);
}

/* Float Animations for Background */
@keyframes float-parallax {
    0%, 100% { transform: translate(0, 0); }
    33% { transform: translate(10px, -15px); }
    66% { transform: translate(-15px, 10px); }
}

.animate-parallax {
    animation: float-parallax 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0); }
    50% { transform: translateY(-15px) rotate(2deg); }
}

.animate-bounce-slow {
    animation: float 6s ease-in-out infinite;
}

/* Transition Group for Kos List */
.kos-list-enter-active,
.kos-list-leave-active {
    transition: all 0.6s var(--ease-out-expo);
}

.kos-list-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.kos-list-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

.kos-list-move {
    transition: transform 0.6s var(--ease-out-expo);
}

/* Hover Effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}
</style>
