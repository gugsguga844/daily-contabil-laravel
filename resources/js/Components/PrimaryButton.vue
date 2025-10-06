<script setup>
defineProps({
    icon: [Object, Function, String],
});
</script>

<template>
    <button
        class="flex items-center gap-2 justify-center rounded-lg px-4 py-2 text-sm font-medium text-white bg-surface-accent"
    >
        <component :is="icon" class="w-4 h-4" />
        <slot />
    </button>
</template>
