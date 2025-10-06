<script setup>
defineProps({
    title: String,
    subtitle: String,
});
</script>

<template>
    <div class="flex flex-col mt-1">
        <h1 class="text-lg font-semibold text-text-primary">{{ title }}</h1>
        <p class="text-sm text-text-secondary">{{ subtitle }}</p>
    </div>
</template>