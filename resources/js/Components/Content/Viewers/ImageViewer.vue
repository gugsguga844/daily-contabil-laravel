<script setup>
defineProps({
    content: Object,
})
</script>

<template>
    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center p-4">
        <img
            :src="content.full_url"
            alt="content.title"
            class="max-w-full max-h-full object-contain rounded-md shadow-lg"
        >
    </div>
</template>