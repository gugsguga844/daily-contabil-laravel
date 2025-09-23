<script setup>
defineProps({
    title: String,
    description: String,
    icon: Object,
})
</script>

<template>
    <div class="flex items-center">
        <div class="mr-4 w-12 h-12 rounded-xl flex items-center justify-center bg-brand-dark text-surface-accent">
            <component :is="icon" class="w-6 h-6" />
        </div>
        <div class="texts">
            <h2 class="xl:text-xl text-lg font-semibold text-text mb-1">{{ title }}</h2>
            <p class="xl:text-lg text-sm text-text-gray">{{ description }}</p>
        </div>
    </div>    
</template>
