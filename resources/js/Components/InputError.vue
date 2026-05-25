<script setup>
defineProps({
    message: [String, Array],
});
</script>

<template>
    <div v-if="message">
        <p class="text-sm text-red-600 dark:text-red-400">
            {{ Array.isArray(message) ? message[0] : message }}
        </p>
    </div>
</template>
