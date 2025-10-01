<script setup>
const props = defineProps({
    icon: Object,
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="props.type"
        class="flex gap-2 items-center border border-surface-muted p-2 rounded-lg hover:bg-surface-muted"
        v-bind="$attrs"
    >
        <component :is="props.icon" class="w-5 h-5 text-text-secondary" />
    </button>
</template>