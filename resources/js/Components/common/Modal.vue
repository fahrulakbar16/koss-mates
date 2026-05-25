<template>
    <dialog
        class="z-50 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent"
        ref="dialog"
    >
        <div
            v-if="showSlot"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4 py-6"
        >
            <!-- Background Overlay -->
            <transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-show="show"
                    class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                    @click="close"
                />
            </transition>

            <!-- Modal Content -->
            <transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div
                    v-show="show"
                    class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-xl transform transition-all w-full mx-auto flex flex-col max-h-[90vh]"
                    :class="maxWidthClass"
                >
                    <!-- Header -->
                    <div v-if="$slots.header || title"
                        class="px-8 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center shrink-0"
                    >
                        <slot name="header">
                            <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">
                                {{ title }}
                            </h3>
                        </slot>
                        <button
                            @click="close"
                            class="text-gray-400 hover:text-gray-600 transition-all text-xl font-light"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Body -->
                    <div
                        class="flex-1 overflow-y-auto px-8 py-5 custom-scrollbar"
                    >
                        <slot />
                    </div>

                    <!-- Footer (Only if slot or buttons provided) -->
                    <div v-if="$slots.footer" class="px-8 py-5 border-t border-gray-100 dark:border-gray-800 shrink-0">
                        <slot name="footer" />
                    </div>
                    <div v-else-if="confirmText || showDefaultFooter" class="px-8 py-5 border-t border-gray-100 dark:border-gray-800 flex justify-end gap-3 shrink-0">
                        <button
                            type="button"
                            class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all"
                            @click="close"
                        >
                            {{ closeText }}
                        </button>
                        <button
                            v-if="confirmText"
                            type="button"
                            class="px-6 py-2.5 bg-primary-500 text-white rounded-xl font-bold hover:bg-primary-600 shadow-lg shadow-primary-500/30 dark:shadow-none transition-all"
                            @click="confirm"
                        >
                            {{ confirmText }}
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </dialog>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #374151;
}
</style>

<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from "vue";

const props = defineProps({
    show: { type: Boolean, default: false },
    maxWidth: { type: String, default: "md" },
    closeable: { type: Boolean, default: true },
    title: { type: String, default: "" },
    closeText: { type: String, default: "Batalkan" },
    confirmText: { type: String, default: "" },
    showDefaultFooter: { type: Boolean, default: false },
});

const emit = defineEmits(["close", "confirm"]);
const dialog = ref();
const showSlot = ref(props.show);

watch(
    () => props.show,
    (val) => {
        if (val) {
            document.body.style.overflow = "hidden";
            showSlot.value = true;
            dialog.value?.showModal();
        } else {
            document.body.style.overflow = null;
            setTimeout(() => {
                dialog.value?.close();
                showSlot.value = false;
            }, 200);
        }
    }
);

const confirm = () => emit("confirm");

const close = () => {
    if (props.closeable) emit("close");
};

const closeOnEscape = (e) => {
    if (e.key === "Escape" && props.show) {
        e.preventDefault();
        close();
    }
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));

onUnmounted(() => {
    document.removeEventListener("keydown", closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
    }[props.maxWidth];
});
</script>
