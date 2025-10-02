<script setup>
defineProps({
    title: String,
    subtitle: String,
});
</script>

<template>
    <div class="flex flex-col">
        <h1 class="text-2xl font-semibold text-text-primary">{{ title }}</h1>
        <p class="text-text-secondary">{{ subtitle }}</p>
    </div>
</template>