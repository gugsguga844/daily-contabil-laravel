<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    // Accept Vue component constructors (functions/objects) or tag names
    icon: [Object, Function, String],
});
</script>

<template>
    <button
        :type="type"
        class="flex items-center gap-2 justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 disabled:opacity-25"
    >
        <component :is="icon" class="w-4 h-4" />
        <slot />
    </button>
</template>
