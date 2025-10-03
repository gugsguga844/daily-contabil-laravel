<script setup>
defineProps({
    icon: [Object, Function],
});
</script>

<template>
    <button class="flex gap-2 items-center border bg-white shadow-sm border-surface-muted px-4 py-2 rounded-lg hover:bg-surface-muted">
        <component :is="icon" class="w-5 h-5 text-text-secondary" />
        <span class="text-text-secondary"><slot /></span>
    </button>
</template>