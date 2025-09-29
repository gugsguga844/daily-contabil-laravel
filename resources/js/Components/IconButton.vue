<script setup>
defineProps({
    icon: Object,
});
</script>

<template>
    <button class="flex gap-2 items-center border border-surface-muted p-2 rounded-lg hover:bg-surface-muted">
        <component :is="icon" class="w-5 h-5 text-text-secondary" />
    </button>
</template>