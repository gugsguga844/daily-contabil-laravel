<script setup>
defineProps({
    title: String,
    subtitle: String,
    icon: Object,
});
</script>

<template>
    <div class="flex p-4 w-full rounded-lg bg-surface-white shadow-md">
        <div class="mr-4 w-12 h-12 rounded-xl flex items-center justify-center bg-brand-dark text-surface-accent">
            <component :is="icon" class="w-6 h-6" />
        </div>
        <div class="texts">
            <h2 class="text-sm font-semibold text-text-secondary mb-1">{{ title }}</h2>
            <p class="text-base font-semibold text-text-primary">{{ subtitle }}</p>
        </div>
    </div>
</template>