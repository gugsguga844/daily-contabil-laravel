<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {type: String, required: true },
    active: {type: Boolean, default: false},
})
</script>

<template>
    <Link
        :href="href"
        :class="{ 'bg-brand-accent text-white': active, 'text-text-gray hover:bg-base-50 hover:text-white': !active }" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg w-full">
        <slot name="icon"></slot>
        <slot name="label"></slot>
    </Link>
</template>
