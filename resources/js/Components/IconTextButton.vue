<script setup>
defineProps({
    icon: [Object, Function],
    type: {
        type: String,
        default: 'button',
    },
    buttonClass: String,
    iconClass: String,
});
</script>

<template>
    <button :type="type" :class="buttonClass" class="flex gap-2 items-center border shadow-sm border-surface-muted px-4 py-2 rounded-lg hover:bg-surface-muted" v-bind="$attrs">
        <component :is="icon" :class="iconClass" class="w-5 h-5" />
        <span class="text-text-secondary"><slot /></span>
    </button>
</template>