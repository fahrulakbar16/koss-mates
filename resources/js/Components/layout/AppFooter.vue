<!-- Footer.vue -->
<template>
    <footer class="p-4">
        <div
            class="container flex flex-col gap-2 justify-between items-center mx-auto text-sm md:flex-row"
        >
            <div
                class="flex order-2 gap-1 items-center text-gray-700 md:order-1 dark:text-gray-300"
            >
                <span class="text-gray-500"
                    >{{ new Date().getFullYear() }} &copy;</span
                >
                <Link
                    href="/"
                    class="font-bold text-blue-600 hover:text-blue-700"
                >
                    {{ siteName }}
                    <span v-if="siteDescription" class="italic text-sky-600 hover:text-sky-700">
                        {{ siteDescription }}
                    </span>
                </Link>
                <span class="text-gray-500">- Backoffice Website</span>
            </div>

            <ul
                class="flex order-1 items-center space-x-5 font-semibold text-gray-600 md:order-2 dark:text-gray-400"
            >
                <li>Versi 1.0.0</li>
            </ul>
        </div>
    </footer>
</template>

<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

// Website settings
const siteName = computed(() => page.props.settings?.site_name || 'Starter Project');
const siteDescription = computed(() => page.props.settings?.site_description || 'Laravel Inertia Vue');
</script>
