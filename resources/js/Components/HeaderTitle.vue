<script setup>
    defineProps({
        title: String,
        subtitle: String
    })
</script>

<template>
    <div class="flex items-center">
        <h1 class="text-xl font-semibold text-primary">{{ title }}</h1>
        <p class="text-gray">{{ subtitle }}</p>
    </div>
</template>
