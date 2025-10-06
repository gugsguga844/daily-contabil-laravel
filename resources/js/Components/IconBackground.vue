<script setup>
defineProps({
    icon: [Object, Function, String],
});
</script>

<template>
    <div class="mr-4 w-12 h-12 rounded-xl flex items-center justify-center bg-brand-dark text-surface-accent">
        <component :is="icon" class="w-6 h-6" />
    </div>
</template>
