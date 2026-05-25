<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Detail from "@/Pages/Profile/Detail.vue";
import TenantForm from "@/Pages/Profile/TenantForm.vue";

const props = defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});

const isPenyewa = computed(() => {
    // Check from roles array or single role property
    const roles = usePage().props.auth.roles || [];
    const userRole = usePage().props.auth.user?.role || '';
    return roles.includes('Penyewa') || userRole === 'Penyewa';
});
</script>

<template>
    <AppLayout title="Profil Saya">
        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Header -->
                <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-1.5 h-10 bg-gradient-to-b from-primary-600 to-primary-400 rounded-full"></div>
                            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Pengaturan Profil</h1>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 text-base ml-5">
                            Kelola informasi profil dan pengaturan akun Anda.
                        </p>
                    </div>
                </div>

                <!-- Main Profile Section -->
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <Detail :user="$page.props.auth.user" />
                </div>

                <!-- Tenant Specific Section -->
                <div v-if="isPenyewa">
                    <TenantForm />
                </div>

                <!-- Extra padding for mobile -->
                <div class="h-10 lg:hidden"></div>
            </div>
        </div>
    </AppLayout>
</template>
