<template>
    <Head title="Pengaturan Website" />

    <div class="p-6 space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold">Pengaturan Website</h1>
            <p class="text-gray-500">
                Kelola logo dan pengaturan website
            </p>
        </div>

        <!-- Settings Form -->
        <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Logo Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Logo Website</h2>

                <div class="space-y-4">
                    <!-- Logo Utama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Logo Utama
                        </label>
                        <div class="flex items-center gap-4">
                            <div v-if="formData.logo_main" class="flex-shrink-0">
                                <img
                                    :src="getImageUrl(formData.logo_main)"
                                    alt="Logo Utama"
                                    class="w-32 h-32 object-contain border border-gray-200 rounded-lg"
                                />
                            </div>
                            <div v-else class="flex-shrink-0 w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-sm">No Logo</span>
                            </div>
                            <div class="flex-1">
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleImageUpload($event, 'logo_main')"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                />
                                <p class="mt-1 text-xs text-gray-500">
                                    Format: JPG, PNG. Maksimal 2MB
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Favicon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Favicon
                        </label>
                        <div class="flex items-center gap-4">
                            <div v-if="formData.logo_favicon" class="flex-shrink-0">
                                <img
                                    :src="getImageUrl(formData.logo_favicon)"
                                    alt="Favicon"
                                    class="w-16 h-16 object-contain border border-gray-200 rounded-lg"
                                />
                            </div>
                            <div v-else class="flex-shrink-0 w-16 h-16 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 text-xs">No Icon</span>
                            </div>
                            <div class="flex-1">
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleImageUpload($event, 'logo_favicon')"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                />
                                <p class="mt-1 text-xs text-gray-500">
                                    Format: ICO, PNG. Ukuran disarankan 32x32 atau 16x16
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Pengaturan Umum</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Website
                        </label>
                        <input
                            v-model="formData.site_name"
                            type="text"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Nama Website"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Website
                        </label>
                        <textarea
                            v-model="formData.site_description"
                            rows="3"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Deskripsi Website"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email Kontak
                        </label>
                        <input
                            v-model="formData.contact_email"
                            type="email"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="email@example.com"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon
                        </label>
                        <input
                            v-model="formData.contact_phone"
                            type="text"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="+62 123 456 7890"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat
                        </label>
                        <textarea
                            v-model="formData.contact_address"
                            rows="2"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Alamat Lengkap"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="resetForm"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                >
                    Reset
                </button>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md disabled:opacity-50 transition-colors"
                >
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Pengaturan' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    settings: Object,
});

// Initialize form data from settings
const formData = ref({
    logo_main: null,
    logo_favicon: null,
    site_name: '',
    site_description: '',
    contact_email: '',
    contact_phone: '',
    contact_address: '',
});

// Track uploaded files
const uploadedFiles = ref({});

// Initialize form
const form = useForm({
    settings: [],
});

// Function to load settings into form data
const loadSettings = () => {
    if (props.settings) {
        Object.keys(props.settings).forEach(group => {
            props.settings[group].forEach(setting => {
                if (setting.key === 'logo_main') {
                    formData.value.logo_main = setting.value;
                } else if (setting.key === 'logo_favicon') {
                    formData.value.logo_favicon = setting.value;
                } else if (setting.key === 'site_name') {
                    formData.value.site_name = setting.value || '';
                } else if (setting.key === 'site_description') {
                    formData.value.site_description = setting.value || '';
                } else if (setting.key === 'contact_email') {
                    formData.value.contact_email = setting.value || '';
                } else if (setting.key === 'contact_phone') {
                    formData.value.contact_phone = setting.value || '';
                } else if (setting.key === 'contact_address') {
                    formData.value.contact_address = setting.value || '';
                }
            });
        });
    }
};

// Load settings on mount
onMounted(() => {
    loadSettings();
});

// Watch for settings changes (when props update after reload)
watch(() => props.settings, () => {
    loadSettings();
}, { deep: true });

// Get image URL
const getImageUrl = (path) => {
    if (!path) return null;
    // If it's a base64 preview (from file upload)
    if (path.startsWith('data:image')) return path;
    // If it's a full URL
    if (path.startsWith('http')) return path;
    // If it's a storage path (from database)
    if (path.startsWith('settings/')) return `/storage/${path}`;
    // If it already has /storage/ prefix
    if (path.startsWith('/storage/')) return path;
    // Default: assume it's a storage path
    return `/storage/${path}`;
};

// Handle image upload
const handleImageUpload = (event, key) => {
    const file = event.target.files[0];
    if (file) {
        // Preview image
        const reader = new FileReader();
        reader.onload = (e) => {
            formData.value[key] = e.target.result;
        };
        reader.readAsDataURL(file);

        // Store file for upload
        uploadedFiles.value[key] = file;
    }
};

// Submit form
const submitForm = () => {
    // Prepare settings data
    const settingsArray = [];

    // Handle text fields
    const textFields = [
        'site_name',
        'site_description',
        'contact_email',
        'contact_phone',
        'contact_address',
    ];
    textFields.forEach(key => {
        settingsArray.push({
            key: key,
            value: formData.value[key] || '',
        });
    });

    // Handle image fields - always include to ensure they're processed
    const imageFields = ['logo_main', 'logo_favicon'];
    imageFields.forEach(key => {
        if (uploadedFiles.value[key]) {
            // New file uploaded
            settingsArray.push({
                key: key,
                value: uploadedFiles.value[key],
            });
        } else {
            // Include existing value so controller knows to keep it
            const existingValue = formData.value[key];
            if (existingValue && (existingValue.startsWith('settings/') || existingValue.startsWith('/storage/settings/'))) {
                settingsArray.push({
                    key: key,
                    value: existingValue.replace('/storage/', ''),
                });
            } else {
                // Include empty to ensure setting exists
                settingsArray.push({
                    key: key,
                    value: existingValue || '',
                });
            }
        }
    });

    // Convert to form data format
    const formDataObj = new FormData();
    settingsArray.forEach((setting, index) => {
        formDataObj.append(`settings[${index}][key]`, setting.key);
        if (setting.value instanceof File) {
            // File upload
            formDataObj.append(`settings[${index}][value]`, setting.value);
        } else {
            // Text value
            formDataObj.append(`settings[${index}][value]`, setting.value || '');
        }
    });

    form.transform(() => formDataObj).post(route('settings.update-multiple'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            // Clear uploaded files and preview
            uploadedFiles.value = {};
            // Reset formData to clear base64 previews
            formData.value.logo_main = null;
            formData.value.logo_favicon = null;
            // Reload the entire page to get updated settings from shared data
            router.reload();
        },
    });
};

// Reset form
const resetForm = () => {
    form.reset();
    if (props.settings) {
        Object.keys(props.settings).forEach(group => {
            props.settings[group].forEach(setting => {
                if (setting.key === 'logo_main') {
                    formData.value.logo_main = setting.value;
                } else if (setting.key === 'logo_favicon') {
                    formData.value.logo_favicon = setting.value;
                } else if (setting.key === 'site_name') {
                    formData.value.site_name = setting.value || '';
                } else if (setting.key === 'site_description') {
                    formData.value.site_description = setting.value || '';
                } else if (setting.key === 'contact_email') {
                    formData.value.contact_email = setting.value || '';
                } else if (setting.key === 'contact_phone') {
                    formData.value.contact_phone = setting.value || '';
                } else if (setting.key === 'contact_address') {
                    formData.value.contact_address = setting.value || '';
                }
            });
        });
    }
};
</script>

